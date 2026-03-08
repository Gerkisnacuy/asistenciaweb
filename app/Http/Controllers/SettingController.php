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
        // Las configuraciones ya están disponibles en todas las vistas vía AppServiceProvider
        return view('settings.index');
    }

    /**
     * Actualiza la identidad visual y los datos de la institución.
     */
    public function update(Request $request)
    {
        // 1. Validación de todos los campos solicitados
        $request->validate([
            'site_name'       => 'required|string|max:255',
            'site_rif'        => 'required|string|max:20',
            'site_phone'      => 'nullable|string|max:20',
            'site_email'      => 'nullable|email|max:255',
            'site_address'    => 'nullable|string',
            'primary_color'   => 'required|string|size:7', // Formato #RRGGBB
            'secondary_color' => 'required|string|size:7', // Formato #RRGGBB
            'theme_mode'      => 'required|in:light,dark',
            'site_logo'       => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048', // Máximo 2MB
        ]);

        // 2. Procesar la subida del Logo
        if ($request->hasFile('site_logo')) {
            // Obtener el nombre del logo actual para eliminarlo
            $currentLogo = Setting::where('key', 'site_logo')->value('value');
            
            if ($currentLogo && Storage::disk('public')->exists($currentLogo)) {
                Storage::disk('public')->delete($currentLogo);
            }

            // Guardar el nuevo logo en storage/app/public/logos
            $path = $request->file('site_logo')->store('logos', 'public');
            Setting::where('key', 'site_logo')->update(['value' => $path]);
        }

        // 3. Actualizar los campos de texto y colores
        $inputs = $request->except(['_token', '_method', 'site_logo']);
        
        foreach ($inputs as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->route('settings.index')->with('success', 'La configuración institucional ha sido actualizada correctamente.');
    }
}