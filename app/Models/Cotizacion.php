<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = [
        'cod_cotizacion',
        'estado',
        'subtotal',
        'igv',
        'total',
        'dias_valido',
        'cliente_id',
        'user_id'
    ];
}
