<x-modal name="modal-show" focusable>
    <div id="modal" class="modal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Visualizar Operação') }}
            </h2>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="w-full">
                    <x-input-label for="quantity" :value="__('Quantidade')" />
                    <x-text-input id="quantity" class="block mt-1 w-full"
                                  type="number"
                                  name="quantity" />
                </div>

                <div class="w-full">
                    <x-input-label for="purchase_value" :value="__('(R$) Valor')" />
                    <x-text-input id="purchase_value" class="block mt-1 w-full"
                                  type="text"
                                  name="purchase_value"
                    />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Fechar') }}
                </x-secondary-button>
            </div>
        </div>
    </div>

    <script>
        function fetchOperation(id) {
            fetch(`/execution/${id}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data)

                    const quantityInput = document.querySelector('#quantity');
                    quantityInput.value = data.quantity;
                    quantityInput.setAttribute('disabled', 'true');

                    const valueInput = document.querySelector('#purchase_value');
                    valueInput.value = formatMonetary(data.purchase_value);
                    valueInput.setAttribute('disabled', 'true');
                })
                .catch(error => {
                    console.error("Erro ao buscar dados:", error);
                });
        }
    </script>
</x-modal>
