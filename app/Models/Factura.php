<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'cod_factura',
        'estado',
        'igv',
        'moneda',
        'total',
        'cotizacion_id',
        'cliente_id',
        'user_id',
        'observaciones'
    ];

    public function cotizacion() {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id', 'id');
    }

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function factura_detalles() {
        return $this->hasMany(FacturaDetalle::class, 'factura_id', 'id');
    }
}
