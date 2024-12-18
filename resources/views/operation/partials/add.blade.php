<div x-data="{actionType: ''}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="text-gray-900 dark:text-gray-100 w-full">
        <form method="post" action="{{ route('operation.store') }}" class="p-6" id="change-orders">
            @csrf

            <input type="hidden" name="type_action" :value="actionType">

            <input type="hidden" name="dollar_value" id="dollar_value" value="" required>

            <div class="grid grid-cols-1">
                <div class="w-full" x-data="{ code: '' }" x-init="code = generateCode()">
                    <x-input-label for="code" :value="__('Código')" />
                    <x-text-input id="code" class="block mt-1 w-full"
                                  type="text"
                                  name="code"
                                  required autocomplete="Código"
                                  x-model="code" />
                </div>

                <div class="w-full">
                    <div class="pt-3 w-full" x-data="{ value: '' }">
                        <x-input-label for="value" :value="__('(R$) Dolar Futuro')" />
                        <x-text-input id="value" class="block mt-1 w-full"
                              type="text"
                              name="value"
                              required autocomplete="Valor de Compra"
                              x-model="value"
                              @input="value = formatCurrency(value)"
                        />
                    </div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-2">
                <x-primary-button class="w-full flex justify-center py-3 bg-gray-400 hover:bg-gray-300" @click="actionType = 'vender'">
                    {{ __('Vender') }}
                </x-primary-button>

                <x-primary-button class="w-full flex justify-center py-3 bg-green-500 hover:bg-green-400" @click="actionType = 'comprar'">
                    {{ __('Comprar') }}
                </x-primary-button>

                <x-primary-button class="col-span-2 w-full flex justify-center py-3 bg-red-500 hover:bg-red-700" @click="actionType = 'zerar'">
                    {{ __('Zerar') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>


<script>
    document.getElementById('change-orders').addEventListener('submit', function (event) {
        const hiddenValue = document.getElementById('finish_dollar_value');
        if (!hiddenValue.value) {
            event.preventDefault();
            alert('Digite o valor atual do dolar.');
        }

        const hiddenIds = document.getElementById('finish_ids');
        if (!hiddenIds.value) {
            event.preventDefault();
            alert('Selecione as ordens.');
        }
    });
</script>
