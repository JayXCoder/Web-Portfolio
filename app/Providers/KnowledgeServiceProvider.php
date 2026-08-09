<?php

namespace App\Providers;

use App\Jobs\RefreshKnowledgeSource;
use App\Models\Achievement;
use App\Models\Portfolio;
use App\Models\WorkExperience;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class KnowledgeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->listen(Portfolio::class, 'portfolio');
        $this->listen(Achievement::class, 'achievement');
        $this->listen(WorkExperience::class, 'experience');
    }

    private function listen(string $model, string $source): void
    {
        $refresh = function () use ($source): void {
            if (config('rag.enabled') && Schema::hasTable('knowledge_documents')) {
                RefreshKnowledgeSource::dispatch($source);
            }
        };
        $model::saved($refresh);
        $model::deleted($refresh);
    }
}
