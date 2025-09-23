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
        Schema::create('documento_sistemas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sistema_id'); // Clave foránea
            $table->string('ruta_archivo');
            $table->string('nombre_original');
            $table->timestamps();

            // Clave foránea
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_sistemas');
    }
};
