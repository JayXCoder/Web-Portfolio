<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description');
            $table->json('technologies'); // Store as JSON array
            $table->string('category');
            $table->json('images'); // Store image paths as JSON array
            $table->json('features'); // Store features as JSON array
            $table->integer('duration_months')->nullable(); // Development duration
            $table->string('client')->nullable(); // Client name
            $table->text('challenges')->nullable(); // Project challenges
            $table->text('solutions')->nullable(); // Solutions implemented
            $table->boolean('is_featured')->default(false); // Featured on homepage
            $table->boolean('is_published')->default(true); // Published status
            $table->integer('sort_order')->default(0); // For ordering
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
