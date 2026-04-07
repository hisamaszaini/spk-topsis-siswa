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
        Schema::create('tabel_kriteria', function (Blueprint $table) {
            $table->id('id_kriteria');
            $table->string('kode_kriteria', 10);
            $table->string('nama_kriteria', 100);
            $table->float('bobot');
            $table->enum('jenis', ['benefit', 'cost']); // Lowercase as requested
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_kriteria');
    }
};
