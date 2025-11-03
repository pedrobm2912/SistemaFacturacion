<?php

namespace App\Http\Controllers;

use App\Helpers\CodeGenerator;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Factura;
use App\Models\FacturaDetalle;
use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacturaController extends Controller
{
    public function index($cotizacion_id) {
        $cotizacion = Cotizacion::with(['cotizacion_detalles', 'cliente', 'user'])->findOrFail($cotizacion_id);

        // return $cotizacion;

        return view("facturacion.comprobantes.facturas.proceso", [
            "cotizacion" => $cotizacion
        ]);
    }

    public function cotiToFact($cotizacion_id) {
        try {

            $cotizacion = Cotizacion::findOrFail($cotizacion_id);

            $factura = Factura::create([
                'cotizacion_id' => $cotizacion->id,
                'cod_factura' => CodeGenerator::generate('FAC', Factura::class, 'cod_factura'),
                'igv' => $cotizacion->igv,
                'moneda' => 'soles',
                'total' => $cotizacion->total,
                'cliente_id' => $cotizacion->cliente_id,
                'user_id' => Auth::user()->id
            ]);

            $cotizacionProductos = CotizacionDetalle::where('cotizacion_id', $cotizacion->id)->get();
            foreach ($cotizacionProductos as $detalle) {
                FacturaDetalle::create([
                    'factura_id' => $factura->id,
                    'producto_id' => $detalle->producto_id,
                    'subtotal' => $detalle->subtotal,
                    'desc1' => 0,
                    'desc2' => 0,
                    'precio' => $detalle->precio
                ]);

                $producto = Producto::findOrFail($detalle->producto_id);
                if ($producto) {
                    $producto->update([
                        'stock' => $producto->stock - $detalle->cantidad
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Factura creada exitosamente');

        } catch (Exception $e) {
            dd($e->getMessage());
            // return redirect()->back()->with('error', $e->getMessage());
            // return redirect()->back()->with('error', 'Error interno del servidor');

        }
    }
}
