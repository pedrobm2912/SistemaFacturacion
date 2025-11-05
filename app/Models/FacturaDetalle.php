<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaDetalle extends Model
{
    protected $table = 'factura_detalles';

    protected $fillable = [
        'precio',
        'cantidad',
        'desc1',
        'desc2',
        'subtotal',
        'producto_id',
        'factura_id'
    ];
}
