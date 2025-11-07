<?php

namespace App\Services;

use App\Helpers\CodeGenerator;
use App\Models\Cotizacion;
use App\Models\Factura;
use App\Models\FacturaDetalle;
use App\Models\Producto;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacturacionService {
    public function createFacturacion(array $data) {
        DB::beginTransaction();

        try {

            $cotizacion = Cotizacion::findOrFail($data['cotizacion_id']);

            $factura = Factura::create([
                'cod_factura' => CodeGenerator::generate('FACT', Factura::class, 'cod_factura'),
                'igv' => $data['igv'],
                'total' => $data['total_con_igv'],
                'observaciones' => $data['observaciones'],
                'moneda' => 'soles',
                'cliente_id' => $cotizacion->cliente_id,
                'user_id' => Auth::id(),
                'cotizacion_id' => $cotizacion->id,
            ]);

            foreach ($data['detalles'] as $detalle) {
                FacturaDetalle::create([
                    'factura_id' => $factura->id,
                    'producto_id' => $detalle['producto_id'],
                    'precio' => $detalle['precio'],
                    'cantidad' => $detalle['cantidad'],
                    'subtotal' => $detalle['subtotal'],
                    'desc1' => 0,
                    'desc2' => 0
                ]);

                $producto = Producto::findOrFail($detalle['producto_id']);
                $producto->decrement('stock', $detalle['cantidad']);
            }

            $cotizacion->update([
                'estado' => 3
            ]);

            DB::commit();
            return $factura;

        } catch (Exception $e) {

            DB::rollBack();
            throw $e;

        }
    }
}
