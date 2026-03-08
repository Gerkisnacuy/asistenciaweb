<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'type',
        'observation'
    ];

    // Relación con el empleado
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}