@if (count($operations) > 0)
    @foreach ($operations as $operation)
        <div class="p-3 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-900 whitespace-nowrap dark:text-white">{{ $operation->code }}</h1>

            <div class="flex items-center">
                <small class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Data de inicio: {{ $operation->start_at }}</small>

                @if (!$open)
                    <form action="/finish/operation/{{ $operation->id }}" method="post" class="ps-6">
                        @csrf
                        <x-primary-button>
                            {{ __('Finalizar Operação') }}
                        </x-primary-button>
                    </form>
                @endif
            </div>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 relative overflow-visible">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Inicio / Hora</th>
                    <th class="px-6 py-3">Valor Compra</th>
                    <th class="px-6 py-3">Valor Fim</th>
                    <th class="px-6 py-3">Ganho</th>
                    <th class="px-6 py-3">Tipo</th>
                    <th class="px-6 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($operation->executions as $index => $execution)
                <tr class="{{ $execution->end_value != '0,00' ? 'bg-gray-50 dark:bg-gray-700' : '' }} execution">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $execution->start_at }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $execution->start_value }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $execution->end_value }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        @if($execution->end_at)
                            <p class="{{ $execution->gain > 0 ? 'text-green-500' : 'text-red-500' }} font-bold">{{ $execution->gain }}</p>
                        @else
                            {{ $execution->gain }}
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        @if($execution->type === 'P')
                            <p class="{{ !$execution->end_at ? 'text-green-500 font-bold' : '' }}">Comprado</p>
                        @else
                            <p class="{{ !$execution->end_at ? 'text-yellow-500 font-bold' : '' }}">Vendido</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-lg flex text-gray-500 whitespace-nowrap dark:text-white justify-end">
                        <a href="#" title="Deletar"
                           x-on:click.prevent="deleteId = {{ $execution->id }}; $dispatch('open-modal', 'modal-delete-execution')"
                           class="block px-4 py-2">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach
@else
    <td colspan="7" scope="row" class="px-6 py-4 text-lg font-medium text-gray-900 whitespace-nowrap dark:text-white">
        <p class="p-5">
            Nenhuma operação iniciada.
        </p>
    </td>
@endif
