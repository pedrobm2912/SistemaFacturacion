<?php

namespace App\Helpers;

use Exception;
use App\Models\Factura;
use App\Models\Cotizacion;

class CodeGenerator {
   public static function generate(string $prefijo, string $modelo, string $campo){
        try {

            $ultima = $modelo::orderBy('id', 'desc')->first();

            if ($ultima && isset($ultima->$campo)) {
                $ultimoNum = (int) substr($ultima->$campo, strlen($prefijo) + 1);
                $nuevoNum = $ultimoNum + 1;
            } else {
                $nuevoNum = 1;
            }

            return $prefijo . '-' . str_pad($nuevoNum, 6, '0', STR_PAD_LEFT);

        } catch (Exception $e) {

            throw new Exception('Error al generar código');

        }
    }
}
