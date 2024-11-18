<x-modal name="modal-snacks" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('operation.store') }}" class="p-6">
        @csrf

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Nova operação') }}
        </h2>

        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="w-full" x-data="{ code: '' }" x-init="code = generateCode()">
                <x-input-label for="code" :value="__('Código')" />
                <x-text-input id="code" class="block mt-1 w-full"
                    type="text"
                    name="code"
                    required autocomplete="Código"
                    x-model="code" />

                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            <div class="w-full">
                <x-input-label for="quantity" :value="__('Quantidade')" />
                <x-text-input id="quantity" class="block mt-1 w-full"
                    type="number"
                    name="quantity"
                    value="1"
                    required autocomplete="Quantidade" />

                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
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

                <x-input-error :messages="$errors->get('purchase_value')" class="mt-2" />
            </div>

            <div class="w-full">
                <x-input-label for="type" value="{{ __('type') }}" class="sr-only" />
                <x-select-input
                    id="type"
                    name="type"
                    class="w-full"
                    :options="['P' => 'Compra', 'S' => 'Venda']"
                />
            </div>

            <div class="w-full" x-init="getDollar()">
                <x-input-label for="purchase_dollar_value" value="{{ __('Valor do Dólar') }}" class="sr-only" />
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
                {{ __('Save') }}
            </x-primary-button>
        </div>
    </form>

    @include('operation.partials.scripts');
</x-modal>
