<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Factura;
use App\Models\TipoCambio;
use App\Services\FacturacionService;
use Exception;
use Illuminate\Http\Request;

class FacturaController extends Controller
{

    protected $facturacionService;

    public function __construct(FacturacionService $facturacionService) {
        $this->facturacionService = $facturacionService;
    }

    public function getFacturas() {
        $facturas = Factura::with(['cotizacion', 'cliente', 'factura_detalles'])->get();

        // return $facturas;

        return view("facturacion.comprobantes.facturas.index", [
            "facturas" => $facturas
        ]);
    }

    public function index($cotizacion_id) {
        $cotizacion = Cotizacion::with(['cotizacion_detalles', 'cliente', 'user'])->findOrFail($cotizacion_id);
        $tipoCambio = TipoCambio::orderBy('id', 'desc')->first();

        // return [$cotizacion, $tipoCambio];

        return view("facturacion.comprobantes.facturas.proceso", [
            "cotizacion" => $cotizacion,
            'tipoCambio' => $tipoCambio
        ]);
    }

    public function cotiToFact(Request $request) {
        try {

            // return $request->all();
            $this->facturacionService->createFacturacion($request->all());

            return redirect()->route('cotizaciones.index')->with('success', 'Factura creada exitosamente');

        } catch (Exception $e) {

            // return $e;
            return redirect()->back()->with('error', 'Error interno del servidor');

        }
    }
}
