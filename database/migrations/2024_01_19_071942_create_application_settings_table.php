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
            $table->string('app_version')->nullable()->default('1.0.0');
            $table->string('blog_name')->nullable()->default('Untitled');
            $table->string('navbar_color')->nullable()->default('var(--main-color)');
            $table->string('navbar_text_color')->nullable()->default('var(--text-color)');
            $table->string('footer_color')->nullable()->default('var(--main-color)');
            $table->string('footer_text_color')->nullable()->default('var(--text-color)');
            $table->string('logo_filename')->nullable()->default('untitled.jpg');
            $table->string('email')->nullable()->default('example@mail.com');
            $table->string('phone_number')->nullable();
            $table->boolean('show_blog_name')->nullable(false)->default(true);
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
