<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'descripcion',
        'precio',
        'stock',
        'marca_id'
    ];

    public function marca() {
        return $this->belongsTo(Marca::class, 'marca_id', 'id');
    }
}
