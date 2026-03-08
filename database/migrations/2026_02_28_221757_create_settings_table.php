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
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique(); // Ejemplo: 'site_name', 'primary_color'
        $table->text('value')->nullable(); // Ejemplo: 'Fe y Alegría', '#FF0000'
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
