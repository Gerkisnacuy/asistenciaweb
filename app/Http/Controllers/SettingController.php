<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Muestra el formulario de configuración institucional.
     */
    public function index()
    {
        // Convertimos todas las filas en un array asociativo ['key' => 'value']
        $settings = Setting::pluck('value', 'key')->all();
        
        return view('settings.index', compact('settings'));
    }

    /**
     * Actualiza la identidad visual y los datos de la institución.
     */
    public function update(Request $request)
    {
        // 1. Validación exhaustiva
        $request->validate([
            'site_name'       => 'required|string|max:255',
            'site_rif'        => 'required|string|max:20',
            'site_phone'      => 'nullable|string|max:20',
            'site_email'      => 'nullable|email|max:255',
            'site_address'    => 'nullable|string',
            'primary_color'   => 'required|string|size:7', 
            'secondary_color' => 'required|string|size:7', 
            'theme_mode'      => 'required|in:light,dark',
            'site_logo'       => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048', 
            
            // --- CAMPOS DE ASISTENCIA ---
            'p_hora_entrada'  => 'required|string', 
            'p_tolerancia'    => 'required|integer|min:0',
            'p_hora_salida'   => 'required|string', 
        ]);

        // 2. Procesar la subida del Logo (Si existe)
        if ($request->hasFile('site_logo')) {
            // Buscamos si ya existe un logo previo para borrar el archivo físico
            $currentLogo = Setting::where('key', 'site_logo')->value('value');
            
            if ($currentLogo && Storage::disk('public')->exists($currentLogo)) {
                Storage::disk('public')->delete($currentLogo);
            }

            // Guardamos el nuevo logo
            $path = $request->file('site_logo')->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $path]);
        }

        // 3. Actualizar los campos de texto, colores y horarios
        // IMPORTANTE: Excluimos _method porque tu formulario usa @method('PATCH')
        $inputs = $request->except(['_token', '_method', 'site_logo']);
        
        foreach ($inputs as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('settings.index')->with('success', 'La configuración institucional ha sido actualizada correctamente.');
    }
}