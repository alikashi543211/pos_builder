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
        Schema::create('resume_interest_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('job_position_id')->nullable();
            $table->string('interest')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('interest_icon')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_interest_sections');
    }
};
