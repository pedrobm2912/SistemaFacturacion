<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Producto::create([
            "codigo" => "EPS-000001",
            "descripcion" => "Manguera",
            "precio" => 24.00,
            "stock" => 10,
            "marca_id" => 1
        ]);

        Producto::create([
            "codigo" => "EPS-000002",
            "descripcion" => "Termómetro",
            "precio" => 30.00,
            "stock" => 100,
            "marca_id" => 1
        ]);

        Producto::create([
            "codigo" => "EPS-000003",
            "descripcion" => "Destornillado",
            "precio" => 14.00,
            "stock" => 50,
            "marca_id" => 1
        ]);

        Producto::create([
            "codigo" => "DLL-000001",
            "descripcion" => "Combustible",
            "precio" => 35.50,
            "stock" => 50,
            "marca_id" => 3
        ]);

        Producto::create([
            "codigo" => "DLL-000002",
            "descripcion" => "Manguera",
            "precio" => 29.90,
            "stock" => 35,
            "marca_id" => 3
        ]);

        Producto::create([
            "codigo" => "DLL-000003",
            "descripcion" => "Cafetera",
            "precio" => 35.50,
            "stock" => 15,
            "marca_id" => 3
        ]);
    }
}
