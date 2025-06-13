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
        Schema::create('resume_icon_paths', function (Blueprint $table) {
            $table->id();
            // icons
            $table->string('email_icon')->nullable();
            $table->string('phone_icon')->nullable();
            $table->string('website_icon')->nullable();
            $table->string('location_icon')->nullable();
            $table->string('linked_in_icon')->nullable();
            $table->string('github_icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_icon_paths');
    }
};
