<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index() {
        $productos = Producto::with('marca')->get();
        $marcas = Marca::get();

        return view('facturacion.productos.index', [
            'productos' => $productos,
            'marcas' => $marcas
        ]);
    }

    public function store(Request $request) {
        try {

            $codigo = $this->generateCodeProducto($request->marca_id, $request->codigo_original);

            Producto::create([
                'codigo' => $codigo,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
                'stock' => $request->stock,
                'marca_id' => $request->marca_id
            ]);

            return redirect()->back()->with('success', 'Producto creado correctamente');

        } catch (Exception $e) {

            // return redirect()->back()->with('error', $e->getMessage());
            return redirect()->back()->with('error', 'Error interno del servidor');

        }
    }

    private function generateCodeProducto($marca_id, $codigo_original) {
        try {

            $marca = Marca::findOrFail($marca_id);

            $marcaCantidad = Producto::where('marca_id', $marca->id)->count() + 1;
            $codigoNum = str_pad($marcaCantidad, 6, '0', STR_PAD_LEFT);
            $codigo = "{$marca->abreviatura}-{$codigoNum}";

            $newCodigo = $codigo_original ?? $codigo;

            return $newCodigo;


        } catch (Exception $e) {

            throw $e;

        }
    }
}
