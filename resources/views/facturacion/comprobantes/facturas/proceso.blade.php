<x-app-layout>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <label for="ruc">RUC</label>
                <input type="text" value="{{ $cotizacion->cliente->ruc }}">
            </div>
        </div>
    </div>
</x-app-layout>
