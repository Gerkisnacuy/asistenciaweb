<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden llenar masivamente.
     * key: el nombre de la configuración (ej: p_hora_entrada)
     * value: el valor asignado (ej: 07:30)
     */
    protected $fillable = ['key', 'value'];
}