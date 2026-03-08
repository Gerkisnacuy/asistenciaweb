<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    $settings = [
        'site_name'       => 'U.E.C Timoteo Aguirre Pe',
        'site_rif'        => 'J-00133027-5',
        'site_address'    => 'Dirección de la institución aquí...',
        'site_phone'      => '0212-0000000',
        'site_email'      => 'contacto@feyalegria.edu.ve',
        'site_logo'       => null, 
        'primary_color'   => '#ef4444', // Para encabezados y bordes
        'secondary_color' => '#71717a', // Para botones y elementos secundarios (Gris)
        'theme_mode'      => 'dark',
    ];

    foreach ($settings as $key => $value) {
        \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
}