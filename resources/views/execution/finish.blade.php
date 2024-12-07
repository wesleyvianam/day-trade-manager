<x-app-layout>
    <div>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Finalizar') }}
                </h2>

                <div class="flex">
                    <x-text-input type="text" id="dollarNow" name="dollarNow" class=" me-4" />

                    <x-primary-link class="me-4" href="{{route('finish.execution', $execution->id)}}">Refresh</x-primary-link>
                </div>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <form method="post" action="{{ route('execution.finish', $execution->id) }}" class="p-6">
                        @csrf
                        @Method('PUT')

                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Finalizar operação') }}
                        </h2>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-8 gap-4">
                            <div class="w-full">
                                <x-input-label for="code" :value="__('Código')" />
                                <x-text-input id="code" class="block mt-1 w-full"
                                    type="text"
                                    name="code"
                                    disabled
                                    value="{{ $operation[0]->code }}" />
                            </div>

                            <div class="w-full">
                                <x-input-label for="type" value="{{ __('Tipo') }}"/>
                                <x-text-input class="block mt-1 w-full"
                                    id="type"
                                    name="type"
                                    disabled
                                    value="{{ $execution->type === 'P' ? 'Compra' : 'Venda' }}"
                                />
                            </div>

                            <div class="w-full">
                                <x-input-label for="quantity" :value="__('Quantidade')" />
                                <x-text-input id="quantity" class="block mt-1 w-full"
                                    type="number"
                                    name="quantity"
                                    disabled
                                    value="{{ $execution->quantity }}" />
                            </div>

                            <div class="w-full">
                                <x-input-label for="purchase_value" :value="__('(R$) Valor inicial')" />
                                <x-text-input id="purchase_value" class="block mt-1 w-full"
                                    type="text"
                                    name="purchase_value"
                                    disabled
                                    value="{{ $execution->purchase_value }}" />
                            </div>

                            <div class="w-full">
                                <x-input-label for="purchase_dollar_value" value="{{ $execution->type == 'P' ? 'Dólar na Compra' : 'Dólar na Venda'  }}"/>
                                <x-text-input class="block mt-1 w-full"
                                    id="purchase_dollar_value"
                                    name="purchase_dollar_value"
                                    disabled
                                    value="{{ $execution->purchase_dollar_value }}" />
                            </div>

                            <div class="w-full">
                                <x-input-label for="dollar_diff" value="{{ __('Dolar Diff') }}"/>
                                <x-text-input class="block mt-1 w-full"
                                    id="dollar_diff"
                                    name="dollar_diff"
                                />
                            </div>

                            <div class="w-full">
                                <x-input-label for="diff_value" value="{{ __('Diff value') }}"/>
                                <x-text-input class="block mt-1 w-full"
                                    id="diff_value"
                                    name="diff_value"
                                />
                            </div>

                            <div class="w-full">
                                <x-input-label for="result_value" value="{{ __('Resultado') }}"/>
                                <x-text-input class="block mt-1 w-full"
                                    id="result_value"
                                    name="result_value"
                                />
                            </div>

                            <div class="w-full">
                                <x-text-input class="block mt-1 w-full"
                                    type="hidden"
                                    id="sale_dollar_value"
                                    name="sale_dollar_value"
                                />
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-secondary-link href="{{route('operation.index')}}">Back</x-secondary-link>

                            <x-primary-button class="ms-2">
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        setDollar()

        function calcDollar(dollar) {
            const initDollar = {{ $execution->purchase_dollar_value ?: 0 }};
            const initValue = '{{ $execution->purchase_value ?: 0 }}';
            const dollarDiff = initDollar - dollar;

            const initValueInFloat = parseFloat(initValue.replace(/\./g, '').replace(',', '.'));
            const initialValueInDolar = initValueInFloat / initDollar;

            const result = initialValueInDolar * dollar;

            const inputDollarDiff = document.querySelector('#dollar_diff')
            inputDollarDiff.value = initDollar < dollar ? Math.abs(dollarDiff.toFixed(4)) : -dollarDiff.toFixed(4)

            const resultDiff = Math.round(result * 100) - Math.round(initValueInFloat * 100);

            const valueDiff = document.querySelector('#diff_value')
            valueDiff.value = (Math.round(resultDiff)  / 100).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });

            const finalValue = document.querySelector('#result_value')
            finalValue.value = (Math.round(result * 100)  / 100).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });

            const currentDolar = document.querySelector('#dollarNow');
            currentDolar.value = dollar;
            currentDolar.addEventListener('change', () => {
                calcDollar(currentDolar.value)
            })

            const saleDolar = document.querySelector('#sale_dollar_value');
            saleDolar.value = dollar;
        }

        function setDollar() {
            fetch(`https://economia.awesomeapi.com.br/last/USD-BRL`)
                .then(response => response.json())
                .then(data => {
                    calcDollar(data.USDBRL.bid);
                })
                .catch(error => {
                    console.error("Erro ao buscar dados:", error);
                });

            return false;
        }
    </script>
</x-app-layout>
