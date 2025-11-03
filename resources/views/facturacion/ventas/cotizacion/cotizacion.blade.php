<x-app-layout>
    <div class="py-10">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                COTIZACIÓN DE PRODUCTOS
            </h2>
        </x-slot>

        <div class="px-8">
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

                <button class="px-3 py-2 bg-black/100 rounded-lg text-sm text-white">Cotizar</button>
            </div>
        </div>
    </div>

   @push('scripts')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>

    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let carrito = JSON.parse(localStorage.getItem('carrito')) || []

            const tabla = new DataTable('#tablaCotizacion', {
                data: carrito.map((item, index) => [
                    item.codigo || '-',
                    item.descripcion || '-',
                    `<input type="number" class="precio form-control" value="${item.precio || 0}" min="0" step="0.01">`,
                    `<input type="number" class="cantidad form-control" value="${item.cantidad || 1}" min="1">`,
                    `<input type="text" class="subtotal form-control" value="" readonly>`,
                    `<button class="eliminar btn btn-danger" data-index="${index}">❌</button>`
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
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                initComplete: function () {
                    document.querySelectorAll('#tablaCotizacion tbody tr').forEach(fila => {
                        calcularSubtotal(fila)
                    });
                    actualizarTotalGeneral()
                    calcularIgv()
                    calcularTotalConIgv()
                }
            });

            function calcularSubtotal(fila) {
                let precio = parseFloat(fila.querySelector('.precio').value) || 0
                let cantidad = parseInt(fila.querySelector('.cantidad').value) || 0
                let subtotal = precio * cantidad
                fila.querySelector('.subtotal').value = subtotal.toFixed(2)
            }

            function actualizarTotalGeneral() {
                let total = 0;
                document.querySelectorAll('.subtotal').forEach(input => {
                    total += parseFloat(input.value) || 0
                });
                document.getElementById('totalGeneral').textContent = total.toFixed(2)
            }

            function calcularIgv() {
                let totalGeneral = parseFloat(document.getElementById('totalGeneral').textContent) || 0
                let igv = totalGeneral * 0.18
                document.getElementById('igv').textContent = igv.toFixed(2)
            }

            function calcularTotalConIgv() {
                let totalGeneral = parseFloat(document.getElementById('totalGeneral').textContent) || 0
                let igv = parseFloat(document.getElementById('igv').textContent) || 0
                let totalConIgv = totalGeneral + igv
                document.getElementById('totalConIgv').textContent = totalConIgv.toFixed(2)
            }

            document.querySelector('#tablaCotizacion').addEventListener('input', e => {
                if (e.target.classList.contains('precio') || e.target.classList.contains('cantidad')) {
                    let fila = e.target.closest('tr')
                    calcularSubtotal(fila)
                    actualizarTotalGeneral()
                    calcularIgv()
                    calcularTotalConIgv()
                }
            });

            actualizarTotalGeneral()
            calcularIgv()
            calcularTotalConIgv()

            document.querySelector('#tablaCotizacion').addEventListener('click', e => {
                if (e.target.classList.contains('eliminar')) {
                    let fila = e.target.closest('tr')
                    fila.remove()
                    carrito = carrito.filter((_, i) => i != e.target.dataset.index)
                    localStorage.setItem('carrito', JSON.stringify(carrito))
                    actualizarTotalGeneral()
                    calcularIgv()
                    calcularTotalConIgv()
                }
            });
        });
    </script>
</x-app-layout>
