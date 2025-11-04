<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoCambio extends Model
{
    protected $table = 'tipo_cambios';

    protected $fillable = [
        'valor_compra',
        'valor_venta',
        'valor_venta_banca',
        'fecha'
    ];
}
