<div id="modal" class="modal">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Visualizar Operação') }}
        </h2>

        <div>
            <p><strong>ID:</strong> <span x-text="operationData.id"></span></p>
            <p><strong>Nome:</strong> <span x-text="operationData.name"></span></p>
            <p><strong>Descrição:</strong> <span x-text="operationData.description"></span></p>
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button>
                {{ __('Fechar') }}
            </x-secondary-button>
        </div>
    </div>
</x-modal>


<script>
    const abrirModalBtn = document.getElementById('abrirModal');
    const modal = document.getElementById('modal');
    const cerrarModalBtn = document.getElementById('cerrarModal');

    abrirModalBtn.addEventListener('click', () => {
        modal.style.display = 'block';
    });

    cerrarModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });
</script>