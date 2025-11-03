<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CotizacionController extends Controller
{
    public function index() {
        $clientes = Cliente::get();

        return view('facturacion.ventas.cotizacion.cotizacion',[
            'clientes' => $clientes
        ]);
    }

    public function store(Request $request) {
        $productos = Producto::get();

        try {

            // return $request;
            DB::beginTransaction();
            $cotizacion = Cotizacion::create([
                'cod_cotizacion' => $this->generateCodCotizacion(),
                'igv' => $request->igv,
                'total' => $request->total_con_igv,
                'dias_valido' => $request->dias_valido,
                'cliente_id' => $request->cliente_id,
                'user_id' => Auth::user()->id
            ]);

            if ($request->has('productos_id')) {
                foreach ($request->productos_id as $index => $producto_id) {
                    CotizacionDetalle::create([
                        'cotizacion_id' => $cotizacion->id,
                        'producto_id' => $producto_id,
                        'cantidad' => $request->cantidades[$index] ?? 0,
                        'precio' => $request->precios[$index] ?? 0,
                        'subtotal' => $request->subtotales[$index] ?? 0,
                        'desc1' => 0,
                        'desc2' => 0,
                    ]);

                    // $producto = Producto::findOrFail($producto_id);

                    // $producto->update([
                    //     'stock' => $producto->stock - ($request->cantidades[$index] ?? 0)
                    // ]);
                }
            }

            DB::commit();
            return redirect()->route('proceso.cotizacion')->with('success', 'Cotización creada exitosamente');

        } catch (Exception $e) {
            dd($e->getMessage());

            return redirect()->back()->with('error', $e->getMessage());
            // return redirect()->back()->with('error', 'Error interno del servidor');

        }
    }

    private function generateCodCotizacion() {
        try {

            $ultimaCot = Cotizacion::orderBy('id', 'desc')->first();
            if ($ultimaCot) {
                $ultimoNum = (int) substr($ultimaCot->cod_cotizacion, 4);
                $nuevoNum = $ultimoNum + 1;
            } else {
                $nuevoNum = 1;
            }

            $codCotizacion = 'COT-' . str_pad($nuevoNum, 6, '0', STR_PAD_LEFT);

            return $codCotizacion;

        } catch (Exception $e) {

            throw new Exception('Hubo un error al generar código');

        }
    }
}
