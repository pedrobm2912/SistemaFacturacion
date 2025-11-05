<?php

namespace App\Http\Controllers;

use App\Helpers\CodeGenerator;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Factura;
use App\Models\FacturaDetalle;
use App\Models\Producto;
use App\Models\TipoCambio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    public function index($cotizacion_id) {
        $cotizacion = Cotizacion::with(['cotizacion_detalles', 'cliente', 'user'])->findOrFail($cotizacion_id);
        $tipoCambio = TipoCambio::orderBy('id', 'desc')->first();

        // return [$cotizacion, $tipoCambio];

        return view("facturacion.comprobantes.facturas.proceso", [
            "cotizacion" => $cotizacion,
            'tipoCambio' => $tipoCambio
        ]);
    }

    public function cotiToFact(Request $request) {
        try {

            $cotizacion = Cotizacion::findOrFail($request->cotizacion_id);

            DB::beginTransaction();
            $factura = Factura::create([
                'cotizacion_id' => $cotizacion->id,
                'cod_factura' => CodeGenerator::generate('FAC', Factura::class, 'cod_factura'),
                'igv' => $request->igv,
                'moneda' => 'soles',
                'total' => $request->total_con_igv,
                'cliente_id' => $cotizacion->cliente_id,
                'user_id' => Auth::user()->id,
                'observaciones' => $request->observaciones
            ]);

            foreach($request->detalles as $detalle) {
                FacturaDetalle::create([
                    'factura_id' => $factura->id,
                    'producto_id' => $detalle['producto_id'],
                    'precio' => $detalle['precio'],
                    'cantidad' => $detalle['cantidad'],
                    'subtotal' => $detalle['subtotal'],
                    'desc1' => 0,
                    'desc2' => 0
                ]);

                // actualizar el stock de cada producto
                if (isset($detalle['producto_id'])) {
                    $producto = Producto::findOrFail($detalle['producto_id']);
                    if ($producto) {
                        $producto->decrement('stock', $detalle['cantidad']);
                    }
                }
            }

            // actualizar el estado de la cotizacion a facturada
            $cotizacion->update([
                'estado' => 3
            ]);

            DB::commit();
            return redirect()->route('cotizaciones.index')->with('success', 'Factura creada exitosamente');

        } catch (Exception $e) {

            DB::rollBack();
            // return $e;
            return redirect()->back()->with('error', 'Error interno del servidor');

        }
    }
}
