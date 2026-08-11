<?php

namespace App\Console\Commands;

use App\Jobs\RefreshKnowledgeSource;
use App\Services\KnowledgeSourceService;
use Illuminate\Console\Command;

class RagReindex extends Command
{
    protected $signature = 'rag:reindex
        {--source=all : all, profile, skills, portfolio, achievement, experience, blog, or linkedin_post}
        {--force : Re-embed unchanged documents}
        {--queue : Queue work instead of waiting for Ollama}';

    protected $description = 'Refresh and embed the portfolio knowledge index';

    public function handle(KnowledgeSourceService $sources): int
    {
        $source = (string) $this->option('source');
        if (! in_array($source, ['all', 'profile', 'skills', 'portfolio', 'achievement', 'experience', 'blog', 'linkedin_post'], true)) {
            $this->error('Invalid source.');

            return self::INVALID;
        }
        if ($this->option('queue')) {
            RefreshKnowledgeSource::dispatch($source, (bool) $this->option('force'));
            $this->info('Knowledge refresh queued.');

            return self::SUCCESS;
        }

        $result = $sources->refresh($source, false, (bool) $this->option('force'));
        $this->table(['Seen', 'Changed', 'Deactivated'], [array_values($result)]);

        return self::SUCCESS;
    }
}
