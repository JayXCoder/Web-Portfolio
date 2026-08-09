<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeChunk extends Model
{
    protected $fillable = [
        'knowledge_document_id', 'position', 'content', 'content_hash',
        'embedding', 'embedding_model', 'dimensions',
    ];

    protected function casts(): array
    {
        return ['dimensions' => 'integer', 'position' => 'integer'];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(KnowledgeDocument::class, 'knowledge_document_id');
    }

    /** @return list<float> */
    public function vector(): array
    {
        $decoded = json_decode($this->embedding, true);

        return is_array($decoded) ? array_map('floatval', $decoded) : [];
    }
}
