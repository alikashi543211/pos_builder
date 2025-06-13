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
        Schema::create('resume_header_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('job_position_id')->nullable();
            $table->string('name')->nullable();
            $table->string('position_display_title')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('profile_picture')->nullable();
            $table->text('heading')->nullable();
            $table->text('short_summary')->nullable();
            $table->text('linked_in_link')->nullable();
            $table->text('github_link')->nullable();
            $table->text('portfolio_website')->nullable();
            $table->text('facebook_link')->nullable();
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_header_sections');
    }
};
