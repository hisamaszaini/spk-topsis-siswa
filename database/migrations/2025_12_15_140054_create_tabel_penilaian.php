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
        Schema::create('tabel_penilaian', function (Blueprint $table) {
            $table->id('id_penilaian');
            $table->unsignedBigInteger('id_alternatif');
            $table->unsignedBigInteger('id_kriteria');
            $table->float('nilai');
            $table->timestamps();

            $table->foreign('id_alternatif')->references('id_alternatif')->on('tabel_alternatif')->onDelete('cascade');
            $table->foreign('id_kriteria')->references('id_kriteria')->on('tabel_kriteria')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_penilaian');
    }
};
