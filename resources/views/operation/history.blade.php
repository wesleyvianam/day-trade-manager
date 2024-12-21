<x-app-layout>
    <style>
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");
    </style>

    <div x-data="{ deleteId: null }">
        <div class="py-6 px-60">
            <div class="grid grid-cols-4 gap-6">
                <div class="p-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <p class="font-medium">Operações</p>
                    <h2 id="dollarNow" class="text-xl font-bold">{{ $count ?? 0 }}</h2>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <p class="font-medium">Ganho Médio</p>
                    <h2 class="text-xl font-bold">{{ $midGain ?? '0,00' }}</h2>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <p class="font-medium">Maior Ganho</p>
                    <h2 class="text-xl font-bold">{{ $highGain ?? '0,00' }}</h2>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <p class="font-medium">Ganho Total</p>
                    <h2 class="text-xl font-bold">{{ $totalGain }}</h2>
                </div>
            </div>
        </div>

        <div class="px-60">
            <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include('operation.partials.historyList')
            </div>
        </div>

        @include('operation.partials.delete')
    </div>

    @include('operation.partials.scripts');
</x-app-layout>
