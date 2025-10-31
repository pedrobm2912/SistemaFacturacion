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
        'igv',
        'producto_id'
    ];
}
