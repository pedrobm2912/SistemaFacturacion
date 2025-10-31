<x-app-layout>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <button id="storage" class="p-2 bg-blue-500 rounded-full">Generar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const storageBtn = document.getElementById("storage")

        function useLocalStorage() {
            localStorage.setItem("papa", "papo")
        }

        storageBtn.addEventListener("click", useLocalStorage)
    </script>
</x-app-layout>
