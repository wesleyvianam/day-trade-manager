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
                    <h2 class="text-xl {{ $totalGain > 0 ? 'text-green-500' : 'text-red-500' }} font-bold">{{  $totalGain ?? '0,00' }}</h2>
                </div>
            </div>
        </div>

        <div class="px-60">
            <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if($operations->count())
                    @foreach($operations as $operation)
                        <div class="border-b border-slate-200">
                            <button onclick="toggleAccordion({{ $operation->id }})" class="w-full py-5 text-slate-800">
                                <div class="w-full grid grid-cols-4 gap-2">
                                    <span class="col-span-1 text-left">{{ $operation->code }}</span>
                                    <span class="col-span-1 text-center">{{ $operation->start_at }}</span>
                                    <span class="col-span-1 text-center">{{ $operation->end_at }}</span>
                                    <span class="col-span-1 text-right {{ $gains[$operation->id] && $gains[$operation->id] > 0 ? 'text-green-500' : 'text-red-500' }} font-bold">{{ $gains[$operation->id] ?? '0,00' }}</span>
                                </div>
                            </button>

                            <div id="content-{{ $operation->id }}" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                                <div class="w-full pb-5 font-semibold text-gray-700 grid grid-cols-10 gap-2">
                                    <span class="col-span-2 text-left">Início</span>
                                    <span class="col-span-2 text-left">Fim</span>
                                    <span class="col-span-1 text-center">Tipo</span>
                                    <span class="col-span-1 text-right">Dolar Compra</span>
                                    <span class="col-span-1 text-right">Valor Compra</span>
                                    <span class="col-span-1 text-right">Dolar Venda</span>
                                    <span class="col-span-1 text-right">Valor Venda</span>
                                    <span class="col-span-1 text-right">Ganho</span>
                                </div>

                                @foreach($operation->executions as $execution)
                                    <div class="w-full pb-5 text-gray-600 grid grid-cols-10 gap-2">
                                        <span class="col-span-2 text-left">{{ $execution->start_at }}</span>
                                        <span class="col-span-2 text-left">{{ $execution->end_at }}</span>
                                        <span class="col-span-1 text-center">{{ $execution->type === 'P' ? 'Comprado' : 'Vendido' }}</span>
                                        <span class="col-span-1 text-right">{{ $execution->purchase_dollar_value }}</span>
                                        <span class="col-span-1 text-right">{{ $execution->purchase_value }}</span>
                                        <span class="col-span-1 text-right">{{ $execution->sale_dollar_value }}</span>
                                        <span class="col-span-1 text-right">{{ $execution->sale_value }}</span>
                                        <span class="col-span-1 text-right {{ $execution->average_value > 0 ? 'text-green-500' : 'text-red-500' }} font-bold">{{ $execution->average_value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Paginação -->
                    <div class="mt-5">
                        {{ $operations->links() }}
                    </div>
                @else
                    <p>NADA A MOSTRAR</p>
                @endif
{{--                @include('operation.partials.historyList')--}}
            </div>
        </div>

        @include('operation.partials.delete')
    </div>

    <script>
        function toggleAccordion(index) {
            const content = document.getElementById(`content-${index}`);
            const icon = document.getElementById(`icon-${index}`);

            // Toggle the content's max-height for smooth opening and closing
            if (content.style.maxHeight && content.style.maxHeight !== '0px') {
                content.style.maxHeight = '0';
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        }
    </script>

    @include('operation.partials.scripts');
</x-app-layout>
