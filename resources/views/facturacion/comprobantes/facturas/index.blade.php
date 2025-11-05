<x-app-layout>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table id="facturaciones" class="display w-full">
                    <thead>
                        <tr class="text-sm">
                            <th>#</th>
                            <th>N° Factura</th>
                            <th>Cotización</th>
                            <th>RUC</th>
                            <th>Razón Social</th>
                            <th>IGV</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facturas as $factura)
                            <tr class="text-sm">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $factura->cod_factura }}</td></td>
                                <td>{{ $factura->cotizacion->cod_cotizacion }}</td></td>
                                <td>{{ $factura->cliente->ruc }}</td>
                                <td>{{ $factura->cliente->razon_social }}</td>
                                <td>{{ $factura->igv }}</td>
                                <td>{{ $factura->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new DataTable('#facturaciones', {
                    responsive: true
                });
            });
        </script>
    @endpush
</x-app-layout>
