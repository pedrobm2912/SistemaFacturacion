<?php

namespace App\Http\Controllers;

use App\Helpers\CodeGenerator;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Factura;
use App\Models\FacturaDetalle;
use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CotizacionController extends Controller
{
    public function getCotizaciones() {

        $cotizaciones = Cotizacion::with(['cotizacion_detalles', 'cliente'])->get();

        // return $cotizaciones;

        return view("facturacion.ventas.cotizacion.index", [
            "cotizaciones" => $cotizaciones
        ]);

    }

    public function index() {
        $clientes = Cliente::get();

        return view('facturacion.ventas.cotizacion.cotizacion',[
            'clientes' => $clientes
        ]);
    }

    public function store(Request $request) {
        try {

            // return $request;
            DB::beginTransaction();
            $cotizacion = Cotizacion::create([
                'cod_cotizacion' => CodeGenerator::generate('COT', Cotizacion::class, 'cod_cotizacion'),
                'igv' => $request->igv,
                'total' => $request->total_con_igv,
                'dias_valido' => $request->dias_valido,
                'cliente_id' => $request->cliente_id,
                'user_id' => Auth::user()->id
            ]);


            if ($request->has('productos_id')) {
                foreach ($request->productos_id as $index => $producto_id) {
                    $producto = Producto::findOrFail($producto_id);
                    if ($producto->stock < $request->cantidad[$index]) {
                        DB::rollBack();
                        return redirect()->back()->with('warning', 'No hay stock suficiente');
                    }

                    CotizacionDetalle::create([
                        'cotizacion_id' => $cotizacion->id,
                        'producto_id' => $producto_id,
                        'cantidad' => $request->cantidades[$index] ?? 0,
                        'precio' => $request->precios[$index] ?? 0,
                        'subtotal' => $request->subtotales[$index] ?? 0,
                        'desc1' => 0,
                        'desc2' => 0,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('proceso.cotizacion')->with('success', 'Cotización creada exitosamente');

        } catch (Exception $e) {

            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
            // return redirect()->back()->with('error', 'Error interno del servidor');

        }
    }

    public function changeStateCotizacion(Request $request, $cotizacion_id) {
        try {

            $cotizacion = Cotizacion::findOrFail($cotizacion_id);

            $cotizacion->update([
                'estado' => $request->estado
            ]);

            if ($cotizacion->estado == 0) {
                $mensaje = 'anulada';
            } else if ($cotizacion->estado == 1) {
                $mensaje = 'cotizada';
            } else if ($cotizacion->estado == 2) {
                $mensaje = 'aceptada';
            }

            return redirect()->back()->with('success', $cotizacion->cod_cotizacion . '' . $mensaje);

        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
            // return redirect()->back()->with('error', 'Error interno del servidor');

        }
    }
}
