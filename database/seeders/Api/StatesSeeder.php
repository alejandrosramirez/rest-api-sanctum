<?php

namespace Database\Seeders\Api;

use App\Models\State;
use Illuminate\Database\Seeder;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        State::create([
            'name' => 'Aguascalientes',
            'short_name' => 'AGS',
        ]);
        State::create([
            'name' => 'Baja California',
            'short_name' => 'BC',
        ]);
        State::create([
            'name' => 'Baja California Sur',
            'short_name' => 'BCS',
        ]);
        State::create([
            'name' => 'Campeche',
            'short_name' => 'CAMP',
        ]);
        State::create([
            'name' => 'Chiapas',
            'short_name' => 'CHIS',
        ]);
        State::create([
            'name' => 'Chihuahua',
            'short_name' => 'CHIH',
        ]);
        State::create([
            'name' => 'Coahuila',
            'short_name' => 'COAH',
        ]);
        State::create([
            'name' => 'Colima',
            'short_name' => 'COL',
        ]);
        State::create([
            'name' => 'Ciudad de México',
            'short_name' => 'DF',
        ]);
        State::create([
            'name' => 'Durango',
            'short_name' => 'DGO',
        ]);
        State::create([
            'name' => 'Estado de México',
            'short_name' => 'EDOMEX',
        ]);
        State::create([
            'name' => 'Guanajuato',
            'short_name' => 'GTO',
        ]);
        State::create([
            'name' => 'Guerrero',
            'short_name' => 'GRO',
        ]);
        State::create([
            'name' => 'Hidalgo',
            'short_name' => 'HGO',
        ]);
        State::create([
            'name' => 'Jalisco',
            'short_name' => 'JAL',
        ]);
        State::create([
            'name' => 'Michoacán',
            'short_name' => 'MICH',
        ]);
        State::create([
            'name' => 'Morelos',
            'short_name' => 'MOR',
        ]);
        State::create([
            'name' => 'Nayarit',
            'short_name' => 'NAY',
        ]);
        State::create([
            'name' => 'Nuevo León',
            'short_name' => 'NL',
        ]);
        State::create([
            'name' => 'Oaxaca',
            'short_name' => 'OAX',
        ]);
        State::create([
            'name' => 'Puebla',
            'short_name' => 'PUE',
        ]);
        State::create([
            'name' => 'Querétaro',
            'short_name' => 'QRO',
        ]);
        State::create([
            'name' => 'Quintana Roo',
            'short_name' => 'QROO',
        ]);
        State::create([
            'name' => 'San Luis Potosí',
            'short_name' => 'SLP',
        ]);
        State::create([
            'name' => 'Sinaloa',
            'short_name' => 'SIN',
        ]);
        State::create([
            'name' => 'Sonora',
            'short_name' => 'SON',
        ]);
        State::create([
            'name' => 'Tabasco',
            'short_name' => 'TAB',
        ]);
        State::create([
            'name' => 'Tamaulipas',
            'short_name' => 'TAMPS',
        ]);
        State::create([
            'name' => 'Tlaxcala',
            'short_name' => 'TLAX',
        ]);
        State::create([
            'name' => 'Veracruz',
            'short_name' => 'VER',
        ]);
        State::create([
            'name' => 'Yucatan',
            'short_name' => 'YUC',
        ]);
        State::create([
            'name' => 'Zacatecas',
            'short_name' => 'ZAC',
        ]);
    }
}
