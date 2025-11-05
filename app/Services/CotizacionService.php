<?php

namespace App\Services;

use App\Helpers\CodeGenerator;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Producto;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CotizacionService {
    public function crearCotizacion(array $data) {
        DB::beginTransaction();
        try {
            $cotizacion = Cotizacion::create([
                'cod_cotizacion' => CodeGenerator::generate('COT', Cotizacion::class, 'cod_cotizacion'),
                'igv' => $data['igv'],
                'total' => $data['total_con_igv'],
                'dias_valido' => $data['dias_valido'],
                'cliente_id' => $data['cliente_id'],
                'user_id' => Auth::id()
            ]);

            if (!empty($data['productos_id'])) {
                foreach ($data['productos_id'] as $index => $producto_id) {
                    $this->crearDetalleCotizacion($cotizacion, $data, $index, $producto_id);
                }
            }

            DB::commit();
            return $cotizacion;

        } catch (Exception $e) {

            DB::rollBack();
            throw $e;

        }
    }

    protected function crearDetalleCotizacion(Cotizacion $cotizacion, array $data, $index, $producto_id) {
        $producto = Producto::findOrFail($producto_id);
        $cantidad = $data['cantidades'][$index];

        if ($producto->stock < $cantidad || $producto->stock <= 1) {
            throw new Exception("No hay stock suficiente");
        }

        CotizacionDetalle::create([
            'cotizacion_id' => $cotizacion->id,
            'producto_id' => $producto_id,
            'cantidad' => $cantidad,
            'precio' => $data['precios'][$index],
            'subtotal' => $data['subtotales'][$index],
            'desc1' => 0,
            'desc2' => 0
        ]);
    }

    public function cambiarEstado($cotizacion_id, $nuevoEstado) {
        $cotizacion = Cotizacion::findOrFail($cotizacion_id);
        $cotizacion->update([
            'estado' => $nuevoEstado
        ]);

        $mensaje = match ($cotizacion->estado) {
            0 => 'anulada',
            1 => 'cotizada',
            2 => 'aceptada',
            default => 'estado desconocido'
        };

        return [
            'succes' => true,
            'cotizacion' => $cotizacion,
            'mensaje' => $mensaje
        ];
    }
}
