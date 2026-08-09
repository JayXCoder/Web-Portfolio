<?php

namespace App\Http\Controllers;

use App\Jobs\RefreshKnowledgeSource;
use App\Jobs\SyncLinkedinKnowledge;
use App\Models\KnowledgeChunk;
use App\Models\KnowledgeDocument;
use App\Models\KnowledgeSyncRun;
use App\Models\LinkedinConnection;
use App\Services\LinkedinExportImporter;
use App\Services\RagOllamaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class AdminKnowledgeController extends Controller
{
    public function index(RagOllamaService $ollama): View
    {
        $connection = LinkedinConnection::where('user_id', auth()->id())->first();
        $failedDocuments = KnowledgeDocument::query()
            ->whereNotNull('last_error')
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get(['id', 'title', 'source_type', 'last_error', 'updated_at']);

        $stats = [
            'documents' => KnowledgeDocument::where('is_active', true)->count(),
            'chunks' => KnowledgeChunk::count(),
            'failed_documents' => KnowledgeDocument::whereNotNull('last_error')->count(),
            'last_sync' => KnowledgeSyncRun::latest('id')->first(),
        ];

        return view('admin.knowledge.index', [
            'connection' => $connection,
            'stats' => $stats,
            'failedDocuments' => $failedDocuments,
            'health' => $ollama->health(),
            'chatModel' => $ollama->chatModel(),
            'embeddingModel' => $ollama->embeddingModel(),
            'linkedinConfigured' => filled(config('linkedin.client_id')) && filled(config('linkedin.client_secret')),
        ]);
    }

    public function reindex(Request $request): RedirectResponse
    {
        $source = $request->validate([
            'source' => 'required|in:all,profile,skills,portfolio,achievement,experience',
        ])['source'];
        RefreshKnowledgeSource::dispatch($source, true);

        return back()->with('success', 'Knowledge reindex queued.');
    }

    public function connect(Request $request): RedirectResponse
    {
        if (! filled(config('linkedin.client_id')) || ! filled(config('linkedin.client_secret'))) {
            return back()->withErrors(['linkedin' => 'Configure LinkedIn client credentials first.']);
        }
        $state = Str::random(64);
        $request->session()->put('linkedin_oauth_state', $state);
        $url = config('linkedin.authorize_url').'?'.http_build_query([
            'response_type' => 'code',
            'client_id' => config('linkedin.client_id'),
            'redirect_uri' => config('linkedin.redirect_uri'),
            'state' => $state,
            'scope' => implode(' ', config('linkedin.scopes')),
        ]);

        return redirect()->away($url);
    }

    public function callback(Request $request): RedirectResponse
    {
        $request->validate(['code' => 'required|string', 'state' => 'required|string']);
        $expected = (string) $request->session()->pull('linkedin_oauth_state');
        if ($expected === '' || ! hash_equals($expected, (string) $request->input('state'))) {
            return redirect()->route('admin.knowledge')->withErrors(['linkedin' => 'LinkedIn OAuth state validation failed.']);
        }

        try {
            $tokenResponse = Http::asForm()->timeout(30)->post(config('linkedin.token_url'), [
                'grant_type' => 'authorization_code',
                'code' => $request->input('code'),
                'client_id' => config('linkedin.client_id'),
                'client_secret' => config('linkedin.client_secret'),
                'redirect_uri' => config('linkedin.redirect_uri'),
            ]);
            if (! $tokenResponse->successful() || ! $tokenResponse->json('access_token')) {
                throw new RuntimeException('LinkedIn token exchange failed.');
            }
            $accessToken = (string) $tokenResponse->json('access_token');
            $profile = Http::withToken($accessToken)->timeout(20)->get(config('linkedin.userinfo_url'));
            if (! $profile->successful() || ! $profile->json('sub')) {
                throw new RuntimeException('LinkedIn profile lookup failed.');
            }

            $connection = LinkedinConnection::updateOrCreate(
                ['user_id' => auth()->id()],
                [
                    'member_urn' => 'urn:li:person:'.$profile->json('sub'),
                    'access_token' => $accessToken,
                    'refresh_token' => $tokenResponse->json('refresh_token'),
                    'token_expires_at' => now()->addSeconds((int) $tokenResponse->json('expires_in', 5184000)),
                    'scope' => (string) $tokenResponse->json('scope', implode(' ', config('linkedin.scopes'))),
                    'status' => 'connected',
                    'last_error' => null,
                ]
            );
            SyncLinkedinKnowledge::dispatch($connection->id, true);

            return redirect()->route('admin.knowledge')->with('success', 'LinkedIn connected. Initial post sync queued.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.knowledge')->withErrors(['linkedin' => $e->getMessage()]);
        }
    }

    public function sync(): RedirectResponse
    {
        $connection = LinkedinConnection::where('user_id', auth()->id())->firstOrFail();
        SyncLinkedinKnowledge::dispatch($connection->id, true);

        return back()->with('success', 'LinkedIn full sync queued.');
    }

    public function disconnect(): RedirectResponse
    {
        LinkedinConnection::where('user_id', auth()->id())->delete();

        return back()->with('success', 'LinkedIn disconnected. Existing indexed posts remain available until replaced or removed.');
    }

    public function import(Request $request, LinkedinExportImporter $importer): RedirectResponse
    {
        $validated = $request->validate([
            'linkedin_export' => 'required|file|mimes:csv,txt,zip|max:'.config('linkedin.import_max_kb'),
        ]);
        try {
            $result = $importer->import($validated['linkedin_export'], true);

            return back()->with('success', "Imported {$result['seen']} LinkedIn posts; {$result['changed']} require indexing.");
        } catch (\Throwable $e) {
            return back()->withErrors(['linkedin_export' => $e->getMessage()]);
        }
    }
}
