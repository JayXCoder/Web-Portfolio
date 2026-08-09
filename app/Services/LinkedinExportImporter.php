<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use RuntimeException;
use ZipArchive;

class LinkedinExportImporter
{
    public function __construct(private KnowledgeSourceService $sources) {}

    /** @return array{seen: int, changed: int, deactivated: int} */
    public function import(UploadedFile $file, bool $queue = true): array
    {
        $csv = $this->readCsv($file);
        $stream = fopen('php://temp', 'r+');
        if (! $stream) {
            throw new RuntimeException('Could not open the LinkedIn export.');
        }
        fwrite($stream, $csv);
        rewind($stream);
        $headers = fgetcsv($stream);
        if (! is_array($headers)) {
            throw new RuntimeException('LinkedIn export has no header row.');
        }
        $headers = array_map(fn ($header) => $this->header((string) $header), $headers);
        $documents = [];
        while (($row = fgetcsv($stream)) !== false) {
            $record = array_combine($headers, array_pad($row, count($headers), ''));
            if (! is_array($record)) {
                continue;
            }
            $commentary = trim((string) ($record['sharecommentary'] ?? $record['commentary'] ?? $record['post'] ?? ''));
            $url = trim((string) ($record['sharelink'] ?? $record['link'] ?? $record['url'] ?? ''));
            if ($commentary === '' && $url === '') {
                continue;
            }
            $date = trim((string) ($record['date'] ?? $record['createdat'] ?? ''));
            $sourceKey = $this->sourceKey($url, $commentary, $date);
            $documents[] = [
                'source_key' => $sourceKey,
                'title' => $commentary === '' ? 'LinkedIn post' : mb_strimwidth(preg_replace('/\s+/', ' ', $commentary), 0, 100, '…'),
                'content' => implode("\n", array_filter([
                    'LinkedIn post by Jay.',
                    $commentary !== '' ? 'Post: '.$commentary : null,
                    ($record['sharedurl'] ?? '') !== '' ? 'Shared URL: '.$record['sharedurl'] : null,
                ])),
                'url' => filter_var($url, FILTER_VALIDATE_URL) ? $url : 'https://www.linkedin.com/in/jay71/recent-activity/all/',
                'published_at' => $date !== '' ? Carbon::parse($date) : null,
                'metadata' => ['origin' => 'export'],
            ];
        }
        fclose($stream);

        if ($documents === []) {
            throw new RuntimeException('No posts were found. Expected a LinkedIn Shares.csv export.');
        }

        return $this->sources->syncDocuments('linkedin_post', $documents, true, $queue);
    }

    private function readCsv(UploadedFile $file): string
    {
        if (strtolower($file->getClientOriginalExtension()) !== 'zip') {
            $contents = file_get_contents($file->getRealPath());
            if ($contents === false) {
                throw new RuntimeException('Could not read the uploaded CSV.');
            }

            return $contents;
        }

        $zip = new ZipArchive;
        if ($zip->open($file->getRealPath()) !== true) {
            throw new RuntimeException('Could not open the LinkedIn export ZIP.');
        }
        try {
            for ($index = 0; $index < $zip->numFiles; $index++) {
                $name = (string) $zip->getNameIndex($index);
                if (strtolower(basename($name)) === 'shares.csv') {
                    $contents = $zip->getFromIndex($index);
                    if (is_string($contents)) {
                        return $contents;
                    }
                }
            }
        } finally {
            $zip->close();
        }
        throw new RuntimeException('The ZIP does not contain Shares.csv.');
    }

    private function header(string $header): string
    {
        return strtolower((string) preg_replace('/[^a-z0-9]/i', '', preg_replace('/^\xEF\xBB\xBF/', '', $header)));
    }

    private function sourceKey(string $url, string $commentary, string $date): string
    {
        if (preg_match('~/feed/update/([^/?]+)~', rawurldecode($url), $matches)) {
            return $matches[1];
        }

        return 'export:'.hash('sha256', $url.'|'.$date.'|'.$commentary);
    }
}
