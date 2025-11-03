<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Marca::create([
            'nombre' => 'Epson',
            'abreviatura' => 'EPS'
        ]);

        Marca::create([
            'nombre' => 'Xiaomi',
            'abreviatura' => 'XMI'
        ]);

        Marca::create([
            'nombre' => 'Dell',
            'abreviatura' => 'DLL'
        ]);

        Marca::create([
            'nombre' => 'Samsung',
            'abreviatura' => 'SSG'
        ]);

        Marca::create([
            'nombre' => 'Apple',
            'abreviatura' => 'APL'
        ]);
    }
}
