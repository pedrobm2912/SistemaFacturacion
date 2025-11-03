<x-app-layout>
    <div class="py-10">
        <x-slot name="header">
            {{-- <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                CONSULTAS
            </h2> --}}

            <button command="show-modal" commandfor="dialog" class="rounded-md bg-white/10 text-sm font-semibold text-black inset-ring inset-ring-white/5 hover:bg-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 14 14">
                    <path fill-rule="evenodd" d="M7,13 C3.6862915,13 1,10.3137085 1,7 C1,3.6862915 3.6862915,1 7,1 C10.3137085,1 13,3.6862915 13,7 C13,10.3137085 10.3137085,13 7,13 Z M8,8 L10,8 C10.5522847,8 11,7.55228475 11,7 C11,6.44771525 10.5522847,6 10,6 L8,6 L8,4 C8,3.44771525 7.55228475,3 7,3 C6.44771525,3 6,3.44771525 6,4 L6,6 L4,6 C3.44771525,6 3,6.44771525 3,7 C3,7.55228475 3.44771525,8 4,8 L6,8 L6,10 C6,10.5522847 6.44771525,11 7,11 C7.55228475,11 8,10.5522847 8,10 L8,8 Z"/>
                </svg>
            </button>

            <el-dialog>
                <dialog id="dialog" aria-labelledby="dialog-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
                    <el-dialog-backdrop class="fixed inset-0 bg-gray-500/75 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

                    <div tabindex="0" class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
                        <el-dialog-panel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg data-closed:sm:translate-y-0 data-closed:sm:scale-95">

                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <form action="{{ route('productos.store') }}" method="POST">
                                    @csrf
                                    <div class="">
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            {{-- header --}}
                                            <h3 id="dialog-title" class="text-base font-semibold text-gray-900">Crear producto</h3>

                                            {{-- body --}}
                                            <div class="flex flex-col mt-2 gap-2">
                                                <div class="flex flex-col">
                                                    <label for="descripcion" class="text-sm">Descripción</label>
                                                    <input type="text" name="descripcion" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                                </div>

                                                <div class="flex flex-col">
                                                    <label for="precio" class="text-sm">Precio</label>
                                                    <input type="number" name="precio" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                                </div>

                                                <div class="flex flex-col">
                                                    <label for="stock" class="text-sm">Stock</label>
                                                    <input type="number" name="stock" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                                </div>

                                                <div class="flex flex-col">
                                                    <label for="marca_id">Marca</label>
                                                    <select name="marca_id"
                                                        id="marca_id"
                                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="">-- Seleccionar marca --</option>
                                                        @forelse($marcas as $marca)
                                                            <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                                        @empty
                                                            <option disabled>No hay marcas registradas</option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- footer --}}
                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="submit" command="close" commandfor="dialog" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 sm:ml-3 sm:w-auto">Guardar</button>

                                    <button type="button" command="close" commandfor="dialog" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring inset-ring-gray-400 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
                                </div>
                            </form>
                        </el-dialog-panel>
                    </div>
                </dialog>
            </el-dialog>
        </x-slot>

        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table id="myTable" class="display w-full">
                    <thead>
                        <tr class="text-sm">
                            <th>#</th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Marca</th>
                            <th>Precio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr class="text-sm">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $producto->codigo }}</td>
                                <td>{{ $producto->descripcion }}</td>
                                <td>{{ $producto->marca->nombre }}</td>
                                <td>{{ $producto->precio }}</td>
                                <td>
                                    <x-dropdown align="left" width="48">
                                        <x-slot name="trigger">
                                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                <div>Acciones</div>
                                                <div class="ms-1">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <x-dropdown-link href="#">
                                                Ver
                                            </x-dropdown-link>
                                            <x-dropdown-link
                                                href="#"
                                                class="btn-cotizar"
                                                data-id="{{ $producto->id }}"
                                                data-codigo="{{ $producto->codigo }}"
                                                data-descripcion="{{ $producto->descripcion }}"
                                                data-precio="{{ $producto->precio }}"
                                                data-stock="{{ $producto->stock }}"
                                            >
                                                Cotizar
                                            </x-dropdown-link>
                                        </x-slot>
                                    </x-dropdown>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cotizarBtns = document.querySelectorAll('.btn-cotizar')

            cotizarBtns.forEach(btn => {
                btn.addEventListener('click', e => {
                    e.preventDefault()

                    const producto = {
                        id: btn.dataset.id,
                        codigo: btn.dataset.codigo,
                        descripcion: btn.dataset.descripcion,
                        precio: parseFloat(btn.dataset.precio),
                        stock: btn.dataset.stock,
                        cantidad: 1
                    }

                    let carrito = JSON.parse(localStorage.getItem('carrito')) || []

                    const existe = carrito.find(item => item.id === producto.id)

                    if (!existe) {
                        carrito.push(producto)
                    } else {
                        existe.cantidad += 1
                    }

                    localStorage.setItem('carrito', JSON.stringify(carrito))
                    document.dispatchEvent(new Event('actualizarCarrito'));

                })
            })
        })
    </script>


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
</x-app-layout>
