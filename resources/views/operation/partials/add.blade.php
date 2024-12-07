<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="text-gray-900 dark:text-gray-100 w-full">
        <form method="post" action="{{ route('operation.store') }}" class="p-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                <div class="w-full" x-data="{ code: '' }" x-init="code = generateCode()">
                    <x-input-label for="code" :value="__('Código')" />
                    <x-text-input id="code" class="block mt-1 w-full"
                                  type="text"
                                  name="code"
                                  required autocomplete="Código"
                                  x-model="code" />
                </div>

                <div class="w-full">
                    <x-input-label for="quantity" :value="__('Quantidade')" />
                    <x-text-input id="quantity" class="block mt-1 w-full"
                        type="number"
                        name="quantity"
                        value="1"
                        required autocomplete="Quantidade" />
                </div>

                <div class="w-full" x-data="{ purchaseValue: '' }">
                    <x-input-label for="purchase_value" :value="__('(R$) Valor')" />
                    <x-text-input id="purchase_value" class="block mt-1 w-full"
                        type="text"
                        name="purchase_value"
                        required autocomplete="Valor de Compra"
                        x-model="purchaseValue"
                        @input="purchaseValue = formatCurrency(purchaseValue)"
                    />
                </div>

                <div class="w-full" x-init="getDollar()">
                    <x-input-label for="type" value="{{ __('Tipo') }}"/>
                    <x-select-input
                        id="type"
                        name="type"
                        class="block mt-1 w-full"
                        :options="['P' => 'Compra', 'S' => 'Venda']"
                    />
                </div>

                <x-text-input
                    id="purchase_dollar_value"
                    name="purchase_dollar_value"
                    class="w-full sr-only"
                />
            </div>

            <div class="mt-6">
                <x-primary-button class="w-full flex justify-center py-3 bg-gray-500 hover:bg-gray-100">
                    {{ __('Comprar') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
