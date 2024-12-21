<x-app-layout>
    <style>
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");
    </style>

    <div x-data="{ deleteId: null }">
        <div class="py-6 px-60">
            <div class="grid grid-cols-4 gap-6">
                <div class="p-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <p class="font-medium">Valor dolar Futuro</p>
                    <h2 id="dollarNow" class="text-xl font-bold">0,00</h2>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <p class="font-medium">Valor Médio</p>
                    <h2 class="text-xl font-bold">{{ $operations[0]->average_value ?? 0 }}</h2>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <p class="font-medium">Ordem Ativa</p>
                    <h2 class="text-xl font-bold">{{ $open ?? 0 }}</h2>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <p class="font-medium">Gain</p>
                    <h2 class="text-xl font-bold">{{ $operations[0]->gain ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="px-60 grid grid-cols-4 gap-6">
            <div class="col-span-3">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    @include('operation.partials.list')
                </div>
            </div>

            <div class="col-span-1">

                @include('operation.partials.add')
            </div>
        </div>

        @include('operation.partials.delete')
    </div>

    @include('operation.partials.scripts')
</x-app-layout>
