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
        Schema::create('application_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_version')->nullable(false)->default('1.0.0');
            $table->string('blog_name')->nullable(false);
            $table->string('navbar_color')->nullable(false);
            $table->string('navbar_text_color')->nullable(false);
            $table->string('footer_color')->nullable(false);
            $table->string('footer_text_color')->nullable(false);
            $table->string('logo_filename')->nullable(false);
            $table->string('email')->nullable(false);
            $table->string('phone_number')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_settings');
    }
};
