<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Buscamos si el empleado ya existe por cédula para no duplicarlo
        $existingEmployee = Employee::where('cedula', $row['cedula'])->first();

        if ($existingEmployee) {
            // Si existe, actualizamos sus datos en lugar de crear uno nuevo
            $existingEmployee->update([
                'nombres'   => $row['nombres'],
                'apellidos' => $row['apellidos'],
                'cargo'     => $row['cargo'],
                'telefono'  => $row['telefono'] ?? null,
                'email'     => $row['email'] ?? null,
            ]);
            return null; // No creamos un nuevo registro
        }

        // Si no existe, creamos el nuevo registro
        return new Employee([
            'cedula'    => $row['cedula'],
            'nombres'   => $row['nombres'],
            'apellidos' => $row['apellidos'],
            'cargo'     => $row['cargo'],
            'telefono'  => $row['telefono'] ?? null,
            'email'     => $row['email'] ?? null,
            // La foto se deja nula o con un valor por defecto ya que el Excel no suele traer imágenes
            'foto'      => null,
        ]);
    }

    /**
     * Reglas de validación para cada fila del Excel
     */
    public function rules(): array
    {
        return [
            'cedula'    => 'required',
            'nombres'   => 'required|string',
            'apellidos' => 'required|string',
            'cargo'     => 'required|string',
            'email'     => 'nullable|email',
        ];
    }

    /**
     * Mensajes personalizados de error (opcional)
     */
    public function customValidationMessages()
    {
        return [
            'cedula.required' => 'La columna "cedula" es obligatoria en el Excel.',
            'email.email'     => 'El formato de correo en el Excel no es válido.',
        ];
    }
}