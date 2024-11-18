<x-modal name="modal-delete-execution" focusable>
    <form x-bind:action="`/execution/${deleteId}`" method="post" class="p-6">
        @csrf
        @method('DELETE')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Deletar ordem') }}
        </h2>

        <p class="text-gray-900 dark:text-gray-100">
            {{ __('Tem certeza que deseja deletar essa ordem?') }}
        </p>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ms-3 w-24">
                {{ __('Deletar') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>

<x-modal name="modal-delete-operation" focusable>
    <form x-bind:action="`/operation/${deleteId}`" method="post" class="p-6">
        @csrf
        @method('DELETE')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Deletar operação') }}
        </h2>

        <p class="text-gray-900 dark:text-gray-100">
            {{ __('Tem certeza que deseja deletar a operação?') }}
        </p>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ms-3 w-24">
                {{ __('Deletar') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
