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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // User information
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_email')->nullable();
            $table->string('username')->nullable();

            // API request information
            $table->string('method')->nullable();
            $table->string('endpoint')->nullable();
            $table->string('action')->nullable();
            $table->text('description')->nullable();

            // Object being audited (polymorphic)
            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();

            // Data tracking
            $table->longText('old_values')->nullable();
            $table->longText('new_values')->nullable();

            // Request details
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->integer('status_code')->nullable();
            $table->text('response')->nullable();

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('method');
            $table->index('endpoint');
            $table->index('action');
            $table->index('auditable_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
