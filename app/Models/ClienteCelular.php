<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteCelular extends Model
{
    protected $table = 'cliente_celulares';

    protected $fillable = [
        'celular',
        'cliente_id'
    ];
}
