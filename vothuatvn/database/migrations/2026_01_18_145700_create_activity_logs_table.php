<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // 'created', 'updated', 'deleted', 'login', etc.
            $table->string('model')->nullable(); // Model class name
            $table->unsignedBigInteger('model_id')->nullable(); // ID of affected record
            $table->text('description'); // Human-readable description
            $table->string('ip_address', 45); // IPv4 or IPv6
            $table->text('user_agent')->nullable(); // Browser/device info
            $table->string('device')->nullable(); // Parsed device type (Desktop, Mobile, Tablet)
            $table->timestamps();

            // Indexes for faster queries
            $table->index(['user_id', 'created_at']);
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
