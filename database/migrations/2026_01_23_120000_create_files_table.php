<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();

            // File metadata
            $table->string('filename');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->string('path');

            // Storage info
            $table->string('disk')->default('local');
            $table->string('hash')->unique();

            // User tracking
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            // File categorization (polymorphic)
            $table->string('fileable_type')->nullable();
            $table->unsignedBigInteger('fileable_id')->nullable();

            // File tags/metadata
            $table->string('category')->nullable();
            $table->json('metadata')->nullable();
            $table->text('description')->nullable();

            // Access control
            $table->boolean('is_public')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->integer('download_count')->default(0);

            // Soft deletes & timestamps
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('fileable_type');
            $table->index('hash');
            $table->index('category');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
