<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Importamos el modelo Employee para asegurar la relación
use App\Models\Employee;

class Attendance extends Model
{
    use HasFactory;

    // Estos son los campos que Laravel tiene permiso de escribir
    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'entry_status',
        'exit_status',
        'minutes_late',
        'minutes_early_exit',
        'minutes_extra',
        'total_work_minutes',
        'method'
    ];

    /**
     * AJUSTE CLAVE: Conversión de tipos (Casts)
     * Esto asegura que Laravel trate estos campos como objetos Carbon
     * y respete la zona horaria configurada en el .env
     */
    protected $casts = [
        'date'               => 'date',
        'check_in'           => 'datetime',
        'check_out'          => 'datetime',
        'minutes_late'       => 'integer',
        'minutes_extra'      => 'integer',
        'total_work_minutes' => 'integer',
    ];

    /**
     * Relación con el empleado
     */
    public function employee()
    {
        // Especificamos la llave foránea 'employee_id' para evitar errores de vinculación
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}