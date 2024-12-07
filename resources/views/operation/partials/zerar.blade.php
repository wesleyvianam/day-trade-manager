<div x-data="{actionType: ''}" class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="text-gray-900 dark:text-gray-100 w-full">
        <form method="post" action="{{ route('execution.finish') }}" class="p-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="type_action" :value="actionType">

            <input type="hidden" name="finish_dollar_value" id="finish_dollar_value" value="">

            <div class="grid grid-cols-1">
                <div class="w-full">
                    <x-input-label for="quantity_finish" :value="__('Quantidade')" />
                    <x-text-input id="quantity_finish" class="block mt-1 w-full"
                                  type="number"
                                  name="quantity_finish"
                                  value="0"
                                  disabled />

                    <x-text-input id="finish_ids" type="hidden" name="ids" />

                    <div class="pt-5 w-full" x-data="{ finishValue: '' }">
                        <x-input-label for="finish_value" :value="__('(R$) Dolar Futuro')" />
                        <x-text-input id="finish_value" class="block mt-1 w-full"
                              type="text"
                              name="finish_value"
                              required autocomplete="Valor de Compra"
                              x-model="finishValue"
                              @input="finishValue = formatCurrency(finishValue)"
                        />
                    </div>

                    <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 grid grid-cols-3 gap-2">
                <x-primary-button class="w-full flex justify-center py-3 bg-red-500 hover:bg-red-700" @click="actionType = 'zerar'">
                    {{ __('Zerar') }}
                </x-primary-button>

                <x-primary-button class="w-full flex justify-center py-3 bg-yellow-600 hover:bg-yellow-500" @click="actionType = 'vender'">
                    {{ __('Vender') }}
                </x-primary-button>

                <x-primary-button class="w-full flex justify-center py-3 bg-green-500 hover:bg-green-400" @click="actionType = 'comprar'">
                    {{ __('Comprar') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
