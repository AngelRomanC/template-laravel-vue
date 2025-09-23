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
        Schema::create('documento_certificacions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('certificacion_id'); // Clave foránea
            $table->string('ruta_archivo');
            $table->string('nombre_original');
            $table->timestamps();

            // Clave foránea
            $table->foreign('certificacion_id')->references('id')->on('certificacions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_certificacions');
    }
};
