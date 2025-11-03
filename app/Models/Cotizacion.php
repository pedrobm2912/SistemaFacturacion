<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = [
        'cod_cotizacion',
        'estado',
        'igv',
        'total',
        'dias_valido',
        'cliente_id',
        'user_id'
    ];

    public function cotizacion_detalles() {
        return $this->hasMany(CotizacionDetalle::class, 'cotizacion_id', 'id');
    }

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
