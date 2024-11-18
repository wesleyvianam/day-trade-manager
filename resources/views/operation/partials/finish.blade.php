<x-modal name="modal-finish" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('operation.store') }}" class="p-6">
        @csrf

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Nova operação') }}
        </h2>

        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="w-full">
                <x-input-label for="end_at" :value="__('Finalização')" />
                <x-text-input id="end_at" class="block mt-1 w-full"
                    type="date"
                    name="end_at"
                    required autocomplete="Data de finalização"
                    x-model="end_at" />
            </div>

            <div class="w-full">
                <x-input-label for="sale_value" :value="__('(R$) Valor')" />
                <x-text-input id="sale_value"
                    class="block mt-1 w-full"
                    type="text"
                    name="sale_value"
                    required autocomplete="Valor de Compra"
                />
            </div>

            <div class="w-full">
                <x-input-label for="purchase_dollar_value" value="{{ __('Valor do Dólar') }}" s/>
                <x-text-input
                    id="purchase_dollar_value"
                    name="purchase_dollar_value"
                    class="w-full"
                />
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ms-3 w-24">
                {{ __('Finalizar') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function getExecutionForFinish(id) {
            fetch(`/execution/${id}`)
                .then(response => response.json())
                .then(data => {
                    const saleDollar = document.querySelector('#sale_dollar_value');
                    saleDollar.value = getDollarForFinish();

                    const saleValue = document.querySelector('#sale_value');
                    saleValue.value = 0.00// formatMonetary(data.purchase_value);

                    const endAt = document.querySelector('#end_at');
                    endAt.value = new Date();
                })
                .catch(error => {
                    console.error("Erro ao buscar dados:", error);
                });
        }

        function getDollarForFinish() {
            fetch(`https://economia.awesomeapi.com.br/last/USD-BRL`)
                .then(response => response.json())
                .then(data => {
                    return data.USDBRL.bid;
                })
                .catch(error => {
                    console.error("Erro ao buscar dados:", error);
                });

            return false;
        }
    </script>
</x-modal>
