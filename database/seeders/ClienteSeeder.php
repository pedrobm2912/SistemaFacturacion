<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cliente::create([
            'nombres' => 'John',
            'apellidos'=> 'Doe',
            'ruc' => '123456789',
            'razon_social' => 'SLLLA SAC',
            'pais' => 'Nicaragua'
        ]);

        Cliente::create([
            'nombres' => 'Artemisa',
            'apellidos'=> 'Pavkov',
            'ruc' => '123416481',
            'razon_social' => 'ART SAC',
            'pais' => 'Peru'
        ]);

        Cliente::create([
            'nombres' => 'Yola',
            'apellidos'=> 'None',
            'ruc' => '123454711',
            'razon_social' => 'YOLN SAC',
            'pais' => 'Brasil'
        ]);

        Cliente::create([
            'nombres' => 'Luis',
            'apellidos'=> 'Carcin',
            'ruc' => '021126780',
            'razon_social' => 'CARLUI SAC',
            'pais' => 'USA'
        ]);
    }
}
