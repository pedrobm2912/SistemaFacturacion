<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function index() {
        return view('facturacion.ventas.cotizacion.cotizacion');
    }

    public function store(Request $request) {
        try {

        } catch (Exception $e) {

        }
    }
}
