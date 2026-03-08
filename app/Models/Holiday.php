<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    // Esto permite que Laravel guarde los datos en la tabla
    protected $fillable = ['name', 'date'];
}