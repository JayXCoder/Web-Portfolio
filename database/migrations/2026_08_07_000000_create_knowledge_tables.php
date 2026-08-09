<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_documents', function (Blueprint $table) {
            $table->id();
            $table->string('source_type', 40)->index();
            $table->string('source_key', 191);
            $table->string('title');
            $table->longText('content');
            $table->string('url', 1000)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->char('content_hash', 64);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('last_indexed_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->unique(['source_type', 'source_key']);
        });

        Schema::create('knowledge_chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('knowledge_document_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('position');
            $table->text('content');
            $table->char('content_hash', 64);
            $table->longText('embedding');
            $table->string('embedding_model');
            $table->unsignedSmallInteger('dimensions');
            $table->timestamps();

            $table->unique(['knowledge_document_id', 'position']);
        });

        Schema::create('knowledge_sync_runs', function (Blueprint $table) {
            $table->id();
            $table->string('source_type', 40)->index();
            $table->string('status', 30)->index();
            $table->unsignedInteger('documents_seen')->default(0);
            $table->unsignedInteger('documents_changed')->default(0);
            $table->unsignedInteger('documents_deactivated')->default(0);
            $table->text('error')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('linkedin_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('member_urn')->nullable();
            $table->longText('access_token');
            $table->longText('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->string('scope', 1000)->nullable();
            $table->string('status', 40)->default('connected')->index();
            $table->timestamp('last_synced_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('linkedin_connections');
        Schema::dropIfExists('knowledge_sync_runs');
        Schema::dropIfExists('knowledge_chunks');
        Schema::dropIfExists('knowledge_documents');
    }
};
