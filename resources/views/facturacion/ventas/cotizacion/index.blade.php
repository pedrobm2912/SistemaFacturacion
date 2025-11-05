<x-app-layout>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

        </div>

        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table id="myTable" class="display w-full">
                    <thead>
                        <tr class="text-sm">
                            <th>#</th>
                            <th>N° Cotización</th>
                            <th>RUC</th>
                            <th>Razón Social</th>
                            <th>IGV</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cotizaciones as $cotizacion)
                            <tr class="text-sm">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $cotizacion->cod_cotizacion }}</td></td>
                                <td>{{ $cotizacion->cliente->ruc }}</td>
                                <td>{{ $cotizacion->cliente->razon_social }}</td>
                                <td>{{ $cotizacion->igv }}</td>
                                <td>{{ $cotizacion->total }}</td>
                                <td class="min-w-max">
                                    @if ($cotizacion->estado == 0)
                                        <div class="flex items-center gap-1 h-full">
                                            <div class="bg-gray-400 w-2 h-full rounded-lg p-1"></div>
                                            <span class="text-gray-400">Anulado</span>
                                        </div>
                                    @elseif ($cotizacion->estado == 1)
                                        <div class="flex items-center gap-1 h-full">
                                            <div class="bg-yellow-500 w-2 h-full rounded-lg p-1"></div>
                                            <span class="text-yellow-500">Generado</span>
                                        </div>
                                    @elseif ($cotizacion->estado == 2)
                                        <div class="flex items-center gap-1 h-full">
                                            <div class="bg-green-500 w-2 h-full rounded-lg p-1"></div>
                                            <span class="text-green-500">Aceptado</span>
                                        </div>
                                    @elseif ($cotizacion->estado == 3)
                                        <div class="flex items-center gap-1 h-full">
                                            <div class="bg-sky-500 w-2 h-full rounded-lg p-1"></div>
                                            <span class="text-sky-500">Facturada</span>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <form action="{{ route('changeState.cotizacion', $cotizacion->id) }}" id="form-estado-cotizacion-{{ $cotizacion->id }}" class="hidden" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="estado" id="input-estado-{{ $cotizacion->id }}">
                                    </form>

                                    @if ($cotizacion->estado !== 3)
                                        <select name="estado" class="text-xs cursor-pointer rounded-lg" id="valor-estado-{{ $cotizacion->id }}" value="" onchange="cambiarEstado({{ $cotizacion->id }})">
                                            <option value="">-- Seleccionar estado --</option>
                                            @if ($cotizacion->estado == 0)
                                                <option value="1">Generar</option>
                                                <option value="2">Aceptar</option>
                                            @elseif ($cotizacion->estado == 1)
                                                <option value="0">Anular</option>
                                                <option value="2">Aceptar</option>
                                            @elseif ($cotizacion->estado == 2)
                                                <option value="0">Anular</option>
                                                <option value="1">Generar</option>
                                            @endif
                                        </select>
                                    @endif

                                    @if ($cotizacion->estado == 2)
                                        <button class="px-2 py-1 bg-red-500 text-sm rounded-full">
                                            G
                                        </button>

                                        <form action="{{ route('proceso.factura', $cotizacion->id) }}" id="form-cotizacionFact-{{ $cotizacion->id }}" class="hidden" method="GET">
                                        </form>
                                        <button type="button"
                                            class="px-2 py-1 bg-sky-500 text-sm rounded-full btn-cotiToFact"
                                            data-id="{{ $cotizacion->id }}"
                                            data-codigo="{{ $cotizacion->cod_cotizacion }}"
                                        >
                                            F
                                        </button>
                                    @elseif ($cotizacion->estado == 3)
                                        <button class="px-2 py-1 bg-red-500 text-sm rounded-full">
                                            G
                                        </button>
                                    @endif
                                </td>
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
                new DataTable('#myTable', {
                    responsive: true
                });
            });
        </script>
    @endpush

    <script>
        function cambiarEstado(cotizacionId) {
            const selectEstado = document.getElementById(`valor-estado-${cotizacionId}`)
            const inputEstado = document.getElementById(`input-estado-${cotizacionId}`)
            const formCotizacion = document.getElementById(`form-estado-cotizacion-${cotizacionId}`)

            const valorEstado = selectEstado.value

            inputEstado.value = valorEstado

            if (valorEstado !== "") formCotizacion.submit()
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Swal === 'undefined') {
                // console.error('SweetAlert2 no cargado. Asegúrate de importar y exponer window.Swal en app.js.');
                return;
            }

            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.btn-cotiToFact');
                if (!btn) return;

                const cotizacionId = btn.dataset.id;
                if (!cotizacionId) return;

                const codigoCot = btn.dataset.codigo

                Swal.fire({
                    title: 'Facturar',
                    text: `¿Deseas facturar esta cotización ${codigoCot}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById(`form-cotizacionFact-${cotizacionId}`);
                        if (form) {
                            form.submit();
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>
