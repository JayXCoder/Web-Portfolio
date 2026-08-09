<?php

namespace App\Jobs;

use App\Models\LinkedinConnection;
use App\Services\LinkedinKnowledgeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncLinkedinKnowledge implements ShouldQueue
{
    use Queueable;

    public int $timeout = 360;

    public int $tries = 3;

    public function __construct(public int $connectionId, public bool $full = true)
    {
        $this->onQueue('rag');
    }

    public function handle(LinkedinKnowledgeService $linkedin): void
    {
        $connection = LinkedinConnection::find($this->connectionId);
        if ($connection) {
            $linkedin->sync($connection, $this->full, true);
        }
    }
}
