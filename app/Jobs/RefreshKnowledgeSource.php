<?php

namespace App\Jobs;

use App\Services\KnowledgeSourceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RefreshKnowledgeSource implements ShouldQueue
{
    use Queueable;

    public int $timeout = 360;

    public int $tries = 2;

    public function __construct(public string $source = 'all', public bool $force = false)
    {
        $this->onQueue('rag');
    }

    public function handle(KnowledgeSourceService $sources): void
    {
        $sources->refresh($this->source, true, $this->force);
    }
}
