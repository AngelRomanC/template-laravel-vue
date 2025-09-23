<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sistemas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->integer('departamento_id');
            $table->string('url');
            $table->date('fecha_creacion');
            $table->date('fecha_produccion');
            $table->string('estatus');
            $table->string('numero_usuarios');
            $table->string('nombre_servidor');
            $table->string('ip_servidor');
            $table->string('sistema_operativo');
            $table->string('nombre_servidor_bd');
            $table->string('ip_servidor_bd');
            $table->string('lenguaje_desarrollo');
            $table->string('version_lenguaje');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sistemas');
    }
};
