<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeSyncRun extends Model
{
    protected $fillable = [
        'source_type', 'status', 'documents_seen', 'documents_changed',
        'documents_deactivated', 'error', 'started_at', 'finished_at',
    ];

    protected function casts(): array
    {
        return ['started_at' => 'datetime', 'finished_at' => 'datetime'];
    }
}
