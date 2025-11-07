<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Services\CotizacionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CotizacionController extends Controller
{
    protected $cotizacionService;

    public function __construct(CotizacionService $cotizacionService) {
        $this->cotizacionService = $cotizacionService;
    }

    public function getCotizaciones() {

        $cotizaciones = Cotizacion::with(['cotizacion_detalles', 'cliente'])->get();

        // return $cotizaciones;

        return view("facturacion.ventas.cotizacion.index", [
            "cotizaciones" => $cotizaciones
        ]);
    }

    public function index() {
        $clientes = Cliente::get();

        return view('facturacion.ventas.cotizacion.cotizacion',[
            'clientes' => $clientes
        ]);
    }

    public function store(Request $request) {
        try {
            // return $request->all();

            $this->cotizacionService->crearCotizacion($request->all());

            return redirect()->route('cotizaciones.index')->with('success', 'Cotización creada exitosamente');

        } catch (Exception $e) {

            DB::rollBack();
            // return $e;
            return redirect()->back()->with('error', $e->getMessage())->withInput();
            // return redirect()->back()->with('error', 'Error interno del servidor');

        }
    }

    public function changeStateCotizacion(Request $request, $cotizacion_id) {
        try {

            $result = $this->cotizacionService->cambiarEstado($cotizacion_id, $request->estado);

            return redirect()->back()->with('success', "{$result['cotizacion']->cod_cotizacion} {$result['mensaje']}");

        } catch (Exception $e) {

            // return $e;
            return redirect()->back()->with('error', 'Error interno del servidor');

        }
    }
}
