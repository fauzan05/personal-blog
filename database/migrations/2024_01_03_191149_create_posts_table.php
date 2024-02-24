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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->unsignedBigInteger('category_id')->nullable(false);
            $table->unsignedBigInteger('menu_id')->nullable();
            $table->string('title')->nullable(false)->unique();
            $table->text('content')->nullable(false);
            $table->string('location')->nullable(false);
            $table->string('slug')->nullable(false)->unique();
            $table->boolean('show_image')->nullable(false)->default(true);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('menu_id')->references('id')->on('menus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
