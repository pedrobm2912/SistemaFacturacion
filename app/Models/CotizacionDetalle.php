<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CotizacionDetalle extends Model
{
    protected $table = 'cotizacion_detalles';

    protected $fillable = [
        'precio',
        'cantidad',
        'desc1',
        'desc2',
        'subtotal',
        'producto_id',
        'cotizacion_id'
    ];

    public function cotizacion() {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id', 'id');
    }

    public function producto() {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }
}
