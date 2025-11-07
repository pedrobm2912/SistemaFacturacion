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

                <form action="{{ route('cotizacion.factura') }}" method="POST">
                    @csrf
                    <input type="hidden" name="cotizacion_id" value="{{ $cotizacion->id }}">

                    <div class="max-w-full mx-auto sm:px-6 lg:px-8 bg-white shadow-sm sm:rounded-lg p-4">
                        <label for="observaciones">Observaciones:</label>
                        <textarea name="observaciones"></textarea>
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
                                @foreach($cotizacion->cotizacion_detalles as $i => $detalle)
                                    <tr class="text-sm">
                                        <input type="hidden" name="detalles[{{ $i }}][producto_id]" value="{{ $detalle->producto_id }}">

                                        <td>{{ $detalle->producto->codigo }}</td>
                                        <td>{{ $detalle->producto->descripcion }}</td>
                                        <td>
                                            <input type="number" name="detalles[{{ $i }}][precio]" value="{{ $detalle->precio }}" step="0.01" id="precio">
                                        </td>
                                        <td>
                                            <input type="text" name="detalles[{{ $i }}][cantidad]" value="{{ $detalle->cantidad }}" id="cantidad">
                                        </td>
                                        <td>
                                            <input type="text" name="detalles[{{ $i }}][subtotal]" value="" id="subtotal">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr class="text-sm">
                                    <th colspan="3" class="text-end">TOTAL GENERAL:</th>
                                    <th id="totalGeneral">0.00</th>
                                    <th></th>
                                </tr>
                                <tr class="text-sm">
                                    <th colspan="3" class="text-end">IGV:</th>
                                    <th id="igv">0.00</th>
                                    <th></th>
                                </tr>
                                <tr class="text-sm">
                                    <th colspan="3" class="text-end">Total con IGV:</th>
                                    <th id="totalConIgv">0.00</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>

                        {{-- input oculto para enviarlo al backend --}}
                        <input type="hidden" name="total_general" id="input-total-general">
                        <input type="hidden" name="igv" id="input-igv">
                        <input type="hidden" name="total_con_igv" id="input-total-con-igv">
                        <button class="px-3 py-2 bg-black/100 rounded-lg text-sm text-white" type="submit">Facturar</button>
                    </div>
                </form>
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

            // fecha
            const fecha = document.getElementById('fecha')
            const dateCurrent = new Date()
            const formato = dateCurrent.toISOString().split('T')[0]
            fecha.textContent = formato

            // elementos para calcular valores
            const filas = document.querySelectorAll('tbody tr')
            const totalGeneral = document.getElementById('totalGeneral')
            const totalIgv = document.getElementById('igv')
            const totalConIgv = document.getElementById('totalConIgv')

            const calcularTotalGeneral = () => {
                let total = 0
                const subtotales = document.querySelectorAll('input[name*="[subtotal]"]')
                console.log(subtotales)
                subtotales.forEach(input => {
                    total += parseFloat(input.value) || 0
                })
                totalGeneral.textContent = total.toFixed(2)

                const inputTotal = document.getElementById('input-total-general')
                if (inputTotal) inputTotal.value = total.toFixed(2)
            }

            const calcularIgv = () => {
                const igv = parseFloat(totalGeneral.textContent) * 0.18
                totalIgv.textContent = igv.toFixed(2)

                const inputIgv = document.getElementById('input-igv')
                if (inputIgv) inputIgv.value = igv.toFixed(2)
            }

            const calcularTotalConIgv = () => {
                const totalIgvTotales = parseFloat(totalGeneral.textContent) + parseFloat(totalIgv.textContent)
                totalConIgv.textContent = totalIgvTotales.toFixed(2)

                const inputTotalConIgv = document.getElementById('input-total-con-igv')
                if (inputTotalConIgv) inputTotalConIgv.value = totalIgvTotales.toFixed(2)
            }

            const calcularTodo = () => {
                calcularTotalGeneral()
                calcularIgv()
                calcularTotalConIgv()
            }

            filas.forEach(fila => {
                const precioInput = fila.querySelector('input[name*="[precio]"]')
                const cantidadInput = fila.querySelector('input[name*="[cantidad]"]')
                const subtotalInput = fila.querySelector('input[name*="[subtotal]"]')

                const reCalcularSubtotal = () => {
                    const precio = parseFloat(precioInput.value) || 0
                    const cantidad = parseInt(cantidadInput.value) || 0
                    const subtotal = precio * cantidad

                    subtotalInput.value = subtotal.toFixed(2)
                    calcularTodo()
                }

                precioInput.addEventListener('input', reCalcularSubtotal)
                cantidadInput.addEventListener('input', reCalcularSubtotal)

                // al cargar la pagina, calcula instantaneamente
                reCalcularSubtotal()
            })

        })
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Toastify({
                    text: "{{ session('success') }}",
                    duration: 2000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#65C748",
                    }
                }).showToast();
            @endif

            @if(session('error'))
                Toastify({
                    text: "{{ session('error') }}",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#ef4444",
                    }
                }).showToast();
            @endif

            @if(session('warning'))
                Toastify({
                    text: "{{ session('warning') }}",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: '#facc15'
                    }
                }).showToast();
            @endif
        });
    </script>
</x-app-layout>
