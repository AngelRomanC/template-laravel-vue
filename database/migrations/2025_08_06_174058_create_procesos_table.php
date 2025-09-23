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
        Schema::create('procesos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->foreignId('departamento_id')->nullable()->constrained('departamentos')->onDelete('set null'); 
            $table->date('fecha_solicitud');
            $table->date('fecha_entrega');
            $table->date('fecha_inicio_vigencia');
            $table->date('fecha_fin_vigencia');
            $table->string('estatus');
            $table->string('numero_usuarios');
            $table->string('nombre_responsable');
            $table->string('nombre_autorizacion');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); 


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procesos');
    }
};
