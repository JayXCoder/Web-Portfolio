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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45); // IPv6 support
            $table->string('user_agent', 500)->nullable();
            $table->string('referer', 500)->nullable();
            $table->string('page_url', 500);
            $table->string('page_title')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('device_type', 50)->nullable(); // desktop, mobile, tablet
            $table->string('browser', 100)->nullable();
            $table->string('os', 100)->nullable();
            $table->timestamp('last_visit')->nullable();
            $table->integer('visit_count')->default(1);
            $table->boolean('is_unique_visitor')->default(true);
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['ip_address', 'created_at']);
            $table->index('page_url');
            $table->index('created_at');
            $table->index('is_unique_visitor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
