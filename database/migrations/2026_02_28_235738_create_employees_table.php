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
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('cedula')->unique(); // Clave para el QR
        $table->string('nombres');
        $table->string('apellidos');
        $table->string('cargo'); // Docente, Administrativo, Obrero
        $table->string('telefono')->nullable();
        $table->string('email')->nullable();
        $table->string('foto')->nullable(); // Ruta de la imagen de perfil
        $table->boolean('status')->default(true); // Para activar/desactivar personal
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
