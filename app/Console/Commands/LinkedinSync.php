<?php

namespace App\Console\Commands;

use App\Jobs\SyncLinkedinKnowledge;
use App\Models\LinkedinConnection;
use App\Services\LinkedinKnowledgeService;
use Illuminate\Console\Command;

class LinkedinSync extends Command
{
    protected $signature = 'linkedin:sync {--queue : Queue instead of waiting}';

    protected $description = 'Synchronize LinkedIn posts into the knowledge index';

    public function handle(LinkedinKnowledgeService $service): int
    {
        $connections = LinkedinConnection::where('status', 'connected')->get();
        if ($connections->isEmpty()) {
            $this->warn('No connected LinkedIn account.');

            return self::SUCCESS;
        }
        foreach ($connections as $connection) {
            if ($this->option('queue')) {
                SyncLinkedinKnowledge::dispatch($connection->id, true);
            } else {
                $service->sync($connection, true, false);
            }
        }
        $this->info($this->option('queue') ? 'LinkedIn sync queued.' : 'LinkedIn sync complete.');

        return self::SUCCESS;
    }
}
