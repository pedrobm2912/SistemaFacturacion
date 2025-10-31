<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'cod_factura',
        'estado',
        'subtotal',
        'igv',
        'moneda',
        'total',
        'cotizacion_id',
        'cliente_id',
        'user_id'
    ];
}
