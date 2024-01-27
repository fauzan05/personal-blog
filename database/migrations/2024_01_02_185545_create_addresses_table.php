<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(false);
            // $table->string('street')->nullable(); // jalan
            // $table->string('village')->nullable(); // desa
            // $table->string('subdistrict')->nullable(); // kecamatan
            // $table->string('city')->nullable(); // kota/kabupaten
            $table->string('province')->nullable(false); // provinsi
            $table->string('country')->nullable(false); // negara
            $table->boolean('is_active')->nullable(false)->default(false);
            // $table->string('postal_code')->nullable(false); // kode pos
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
