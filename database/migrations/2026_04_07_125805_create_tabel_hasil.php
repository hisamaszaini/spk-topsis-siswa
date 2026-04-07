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
        Schema::create('tabel_hasil', function (Blueprint $table) {
            $table->id('id_hasil');
            $table->unsignedBigInteger('id_alternatif');
            $table->double('nilai_preferensi');
            $table->integer('peringkat');
            $table->timestamps();

            $table->foreign('id_alternatif')
                  ->references('id_alternatif')
                  ->on('tabel_alternatif')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_hasil');
    }
};
