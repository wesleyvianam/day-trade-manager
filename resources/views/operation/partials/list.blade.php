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
                    <th class="px-6 py-3"></th>
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
                <tr class="{{ $execution->sale_value != '0,00' ? 'bg-gray-50' : '' }} execution">
                    <td class="hidden">{{ $execution->purchase_dollar_value }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        @if(!$execution->end_at)
                            <input type="checkbox" class="check-execution w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" value="{{ $execution->id }}" />
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $execution->start_at }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $execution->purchase_value }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $execution->sale_value }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        @if($execution->end_at)
                            <p class="{{ $execution->average_value > 0 ? 'text-green-500' : 'text-red-500' }} font-bold">{{ $execution->average_value }}</p>
                        @else
                            {{ $execution->average_value }}
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
                        <a href="{{ route('execution.edit', $execution->id) }}" class="block px-4 py-2">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <a href="#" title="Deletar"
                           x-on:click.prevent="deleteId = {{ $execution->id }}; $dispatch('open-modal', 'modal-delete-execution')"
                           class="block px-4 py-2">
                            <i class="bi bi-trash-fill"></i>
                        </a>
{{--                        <x-dropdown align="right" width="48">--}}
{{--                            <x-slot name="trigger">--}}
{{--                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">--}}
{{--                                    <div>Ações</div>--}}

{{--                                    <div class="ms-1">--}}
{{--                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">--}}
{{--                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />--}}
{{--                                        </svg>--}}
{{--                                    </div>--}}
{{--                                </button>--}}
{{--                            </x-slot>--}}

{{--                            <x-slot name="content">--}}
{{--                                <x-dropdown-link x-on:click.prevent="deleteId = {{ $execution->id }}; $dispatch('open-modal', 'modal-delete-execution')">--}}
{{--                                    {{ __('Deletar') }}--}}
{{--                                </x-dropdown-link>--}}
{{--                            </x-slot>--}}
{{--                        </x-dropdown>--}}
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
