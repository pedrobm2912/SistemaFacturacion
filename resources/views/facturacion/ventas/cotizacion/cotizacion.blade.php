<x-app-layout>
    <div class="py-10">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                COTIZACIÓN DE PRODUCTOS
            </h2>
        </x-slot>

        <form action="{{ route('procesar.cotizacion') }}" method="POST" id="form-cotizacion">
            @csrf
            <div class="px-8">
                <div class="flex flex-row gap-4 max-w-full mx-auto sm:px-6 lg:px-8 bg-white shadow-sm sm:rounded-lg p-4">
                    <div class="flex gap-2 text-sm items-center">
                        <label for="cliente_id">Cliente:</label>
                        <select class="js-example-basic-single text-sm rounded-lg" name="cliente_id">
                            <option value="" id="select-default">-- Escoger un cliente --</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombres }} {{ $cliente->apellidos }} || {{ $cliente->ruc }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2 text-sm justify-center items-center">
                        <label for="dias_valido">Días válidos:</label>
                        <select name="dias_valido" class="rounded-lg text-sm">
                            <option value="5">5 días</option>
                            <option value="10">10 días</option>
                            <option value="30">30 días</option>
                        </select>
                    </div>

                </div>

                <div class="max-w-full mx-auto sm:px-6 lg:px-8 bg-white shadow-sm sm:rounded-lg p-4">
                    <table id="tablaCotizacion" class="display w-full">
                        <thead>
                            <tr class="text-sm">
                            </tr>
                        </thead>
                        <tbody class="text-sm"></tbody>
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

                    {{-- input oculto para enviarlo al backend --}}
                    <input type="hidden" name="total_general" id="input-total-general">
                    <input type="hidden" name="total_con_igv" id="input-total-con-igv">
                    <input type="hidden" name="igv" id="input-igv">

                    <button class="px-3 py-2 bg-black/100 rounded-lg text-sm text-white" type="submit">Cotizar</button>
                </div>
            </div>
        </form>
    </div>

   @push('scripts')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

         <script>
            $(document).ready(function() {
                $('.js-example-basic-single').select2();
            });
        </script>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

            const tabla = new DataTable('#tablaCotizacion', {
                data: carrito.map((item, index) => [
                    item.codigo || '-',
                    item.descripcion || '-',
                    `
                    <input type="hidden" name="productos_id[]" value="${item.id}">
                    <input type="number" name="precios[]" class="precio form-control" value="${item.precio || 0}" min="0" step="0.01">
                    `,
                    `<input type="number" name="cantidades[]" class="cantidad form-control" value="${item.cantidad || 1}" min="1">`,
                    `<input type="text" name="subtotales[]" class="subtotal form-control" value="0.00" readonly>`,
                    `<button type="button" class="eliminar btn btn-danger" data-index="${index}">❌</button>`
                ]),
                columns: [
                    { title: "Código" },
                    { title: "Descripción" },
                    { title: "Precio (S/)" },
                    { title: "Cantidad" },
                    { title: "Subtotal (S/)" },
                    { title: "Acción" }
                ],
                paging: false,
                searching: false,
                info: false,
                language: { url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" },
                initComplete: function () {
                    document.querySelectorAll('#tablaCotizacion tbody tr').forEach(fila => calcularSubtotal(fila));
                    actualizarTotalGeneral();
                    calcularIgv();
                    calcularTotalConIgv();
                }
            });

            function calcularSubtotal(fila) {
                const precioInput = parseFloat(fila.querySelector('.precio'))
                const cantidadInput = parseInt(fila.querySelector('.cantidad'))
                if (!precioInput || !cantidadInput) return
                const subtotal = precioInput.value * cantidadInput.value
                fila.querySelector('.subtotal').value = subtotal.toFixed(2)
            }

            function actualizarTotalGeneral() {
                let total = 0;
                document.querySelectorAll('.subtotal').forEach(input => {
                    total += parseFloat(input.value) || 0
                });
                document.getElementById('totalGeneral').textContent = total.toFixed(2)

                const inputTotal = document.getElementById('input-total-general')
                if (inputTotal) inputTotal.value = total.toFixed(2)
            }

            function calcularIgv() {
                const totalGeneral = parseFloat(document.getElementById('totalGeneral').textContent) || 0
                const igv = totalGeneral * 0.18
                document.getElementById('igv').textContent = igv.toFixed(2)

                const inputIgv = document.getElementById('input-igv')
                if (inputIgv) inputIgv.value = igv.toFixed(2)
            }

            function calcularTotalConIgv() {
                const totalGeneral = parseFloat(document.getElementById('totalGeneral').textContent) || 0
                const igv = parseFloat(document.getElementById('igv').textContent) || 0
                const totalConIgv = totalGeneral + igv
                document.getElementById('totalConIgv').textContent = totalConIgv.toFixed(2)
                const inputTotalConIgv = document.getElementById('input-total-con-igv')
                if (inputTotalConIgv) inputTotalConIgv.value = totalConIgv.toFixed(2)
            }

            document.querySelector('#tablaCotizacion').addEventListener('input', e => {
                if (e.target.classList.contains('precio') || e.target.classList.contains('cantidad')) {
                    const fila = e.target.closest('tr')
                    calcularSubtotal(fila)
                    actualizarTotalGeneral()
                    calcularIgv()
                    calcularTotalConIgv()
                }
            });

            document.querySelector('#tablaCotizacion').addEventListener('click', e => {
                if (e.target.classList.contains('eliminar')) {
                    const fila = e.target.closest('tr')
                    fila.remove()
                    const idx = parseInt(e.target.dataset.index);
                    if (!isNaN(idx)) {
                        carrito.splice(idx, 1)
                        localStorage.setItem('carrito', JSON.stringify(carrito))
                        document.dispatchEvent(new Event('actualizarCarrito'));
                    }
                    actualizarTotalGeneral()
                    calcularIgv()
                    calcularTotalConIgv()
                }
            })

            const form = document.getElementById('form-cotizacion')
            if (!form) {
                console.error('Formulario no encontrado.')
                return
            }
            form.addEventListener('submit', function() {
                localStorage.removeItem('carrito')
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
