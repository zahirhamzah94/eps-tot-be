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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('course_categories')->cascadeOnDelete();
            $table->string('code', 50)->unique();
            $table->string('course_name');
            $table->text('course_note')->nullable();
            $table->json('open_to')->nullable();
            $table->json('special_to')->nullable();
            $table->json('application_requirement')->nullable();
            $table->unsignedTinyInteger('attendance_percentage')->nullable();
            $table->boolean('payment_closed_requirement')->default(false);
            $table->boolean('payment_internal_requirement')->default(false);
            $table->boolean('payment_government_requirement')->default(false);
            $table->boolean('payment_private_requirement')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
