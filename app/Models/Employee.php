<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden asignar masivamente.
     * Importante para la carga desde Excel que haremos más adelante.
     */
    protected $fillable = [
        'cedula',     // Clave única para el QR
        'nombres',
        'apellidos',
        'cargo',
        'telefono',
        'email',
        'foto',
        'status'
    ];

    /**
     * Accesor para obtener el nombre completo de forma sencilla.
     * Uso: $employee->full_name
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    /**
     * Accesor para la URL de la foto. 
     * Si no tiene foto, devuelve una imagen por defecto (placeholder).
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return asset('storage/' . $this->foto);
        }

        // Placeholder elegante basado en iniciales o imagen genérica
        return "https://ui-avatars.com/api/?name=" . urlencode($this->full_name) . "&color=FFFFFF&background=71717a";
    }

    /**
     * Scope para buscar por cédula o nombre (útil para filtros).
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('cedula', 'LIKE', "%{$term}%")
                     ->orWhere('nombres', 'LIKE', "%{$term}%")
                     ->orWhere('apellidos', 'LIKE', "%{$term}%");
    }
}