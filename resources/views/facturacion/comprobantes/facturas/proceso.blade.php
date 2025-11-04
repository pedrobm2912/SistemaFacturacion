<x-app-layout>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-sm">
               <div class="flex items-center gap-3">
                    <div class="flex gap-2 items-center">
                        <label for="ruc">RUC:</label>
                        <input type="text" value="{{ $cotizacion->cliente->ruc }}" class="text-xs p-1 rounded-lg">
                    </div>

                    <div class="flex gap-2 items-center">
                        <label for="razon_social">Razón Social:</label>
                        <input type="text" value="{{ $cotizacion->cliente->razon_social }}" class="text-xs p-1 rounded-lg">
                    </div>

                    <div class="flex gap-2 items-center">
                        <label for="tipo_cambio">Tipo de cambio:</label>
                        <label for="fecha" id="fecha"></label>
                        <input type="number" value="{{ $tipoCambio->valor_venta_banca }}" class="text-xs p-1 rounded-lg">
                    </div>

               </div>

                <div class="max-w-full mx-auto sm:px-6 lg:px-8 bg-white shadow-sm sm:rounded-lg p-4">
                    <table id="tablaFactura" class="display w-full">
                        <thead>
                            <tr class="text-sm">
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($cotizacion->cotizacion_detalles as $detalle)
                                <tr class="text-sm">
                                    <td>{{ $detalle->producto->codigo }}</td>
                                    <td>{{ $detalle->producto->descripcion }}</td>
                                    <td>{{ $detalle->precio }}</td>
                                    <td>{{ $detalle->cantidad }}</td>
                                    <td>{{ $detalle->subtotal }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr class="text-sm">
                                <th colspan="4" class="text-end">TOTAL GENERAL:</th>
                                <th id="totalGeneral">0.00</th>
                                <th></th>
                            </tr>
                            <tr class="text-sm">
                                <th colspan="4" class="text-end">IGV:</th>
                                <th id="igv">0.00</th>
                                <th></th>
                            </tr>
                            <tr class="text-sm">
                                <th colspan="4" class="text-end">Total con IGV:</th>
                                <th id="totalConIgv">0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>

                    <button class="px-3 py-2 bg-black/100 rounded-lg text-sm text-white" type="submit">Facturar</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new DataTable('#tablaFactura')

            const fecha = document.getElementById('fecha')
            const dateCurrent = new Date()

            const formato = dateCurrent.toISOString().split('T')[0]

            fecha.textContent = formato
        })
    </script>
</x-app-layout>
