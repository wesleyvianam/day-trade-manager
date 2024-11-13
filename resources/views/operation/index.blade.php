<x-app-layout>
    <style>
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");
    </style>

    <div x-data="{ deleteId: null }">
        <x-slot name="header">
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Minhas operações') }}
                </h2>

                <div>
                    <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-snacks')">Nova Operação</x-primary-button>
                </div>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 w-full">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Código</th>
                                <th class="px-6 py-3">Data</th>
                                <th class="px-6 py-3">Quantidade</th>
                                <th class="px-6 py-3">Valor Compra</th>
                                <th class="px-6 py-3">Valor Médio</th>
                                <th class="px-6 py-3">Tipo Operação</th>
                                <th class="px-6 py-3">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if (count($operations) > 0)
                                    @foreach ($operations as $operation)
                                        <tr class="class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"">
                                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $operation->code }}</td>
                                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $operation->start_at }}</td>
                                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $operation->quantity }}</td>
                                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $operation->purchase_value }}</td>
                                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $operation->avarage_value ?: '0,00' }}</td>
                                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $operation->type === 'P' ? 'Comprado' : 'Vendido' }}</td>
                                            <td scope="row" class="px-6 py-4 text-lg flex text-gray-900 whitespace-nowrap dark:text-white">
                                                <a href="#" id="abrirModal" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                                    <i class="bi bi-eye-fill"></i>
                                                  </a>

                                                <a href="#" title="Editar" class="block px-4 py-2 hover:bg-gray-100"><i class="bi bi-pencil-square"></i></a>
                                                <a href="#" title="Finalizar" class="block px-4 py-2 hover:bg-gray-100"><i class="bi bi-check-square-fill"></i></a>

                                                <a href="#" title="Deletar"
                                                    x-on:click.prevent="deleteId = {{ $operation->id }}; $dispatch('open-modal', 'modal-delete')"
                                                    class="block px-4 py-2 text-red-700 hover:bg-gray-100">
                                                    <i class="bi bi-trash-fill"></i>
                                                </a>
                                            </td>
                                        </li>
                                    @endforeach
                                @else
                                    <td colspan="7" scope="row" class="px-6 py-4 text-lg font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Nenhuma operação iniciada.
                                    </td>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('operation.partials.delete')

        @include('operation.partials.show')

        <script>
            function fetchOperation(id) {
                fetch(`/operation/${id}`)
                .then(response => response.json())
                .then(data => {
                this.operationData = data;  // Set data to reactive variable
                    $dispatch('show-modal', 'modal-view');  // Dispatch event to show modal
                })
                .catch(error => {
                    console.error("Erro ao buscar dados:", error);
                });
        }
        </script>
    </div>

    @include('operation.partials.add');
</x-app-layout>
