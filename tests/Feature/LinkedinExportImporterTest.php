<?php

namespace Tests\Feature;

use App\Jobs\IndexKnowledgeDocument;
use App\Models\KnowledgeDocument;
use App\Services\LinkedinExportImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class LinkedinExportImporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_imports_and_idempotently_updates_linkedin_shares_csv(): void
    {
        Queue::fake();
        $csv = "Date,ShareLink,ShareCommentary,SharedUrl\n"
            ."2026-08-01,https://www.linkedin.com/feed/update/urn:li:activity:123/,Built an Ollama RAG portfolio,https://example.com/rag\n";
        $file = UploadedFile::fake()->createWithContent('Shares.csv', $csv);

        $first = app(LinkedinExportImporter::class)->import($file);
        $second = app(LinkedinExportImporter::class)->import($file);

        $this->assertSame(1, $first['seen']);
        $this->assertSame(1, $first['changed']);
        $this->assertSame(1, $second['changed']);
        $this->assertDatabaseCount('knowledge_documents', 1);
        $this->assertSame('urn:li:activity:123', KnowledgeDocument::first()->source_key);
        Queue::assertPushed(IndexKnowledgeDocument::class, 2);
    }
}
