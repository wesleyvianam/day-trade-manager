<x-app-layout>
    <div>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Vizualizar') }}
                </h2>

                <x-secondary-link href="{{route('operation.index')}}">
                    Voltar
                </x-secondary-link>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 w-full">
                        {{--                        @if (count($operations) > 0)--}}
                        {{--                            @foreach ($operations as $operation)--}}
                        {{--                                <div class="pb-3 flex justify-between">--}}
                        {{--                                    <h1 class="text-xl font-bold text-gray-900 whitespace-nowrap dark:text-white">{{ $operation->code }}</h1>--}}
                        {{--                                    <small class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Data de inicio: {{ $operation->start_at }}</small>--}}
                        {{--                                </div>--}}
                        {{--                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">--}}
                        {{--                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">--}}
                        {{--                                    <tr>--}}
                        {{--                                        <th class="px-6 py-3">Código</th>--}}
                        {{--                                        <th class="px-6 py-3">Inicio / Hora</th>--}}
                        {{--                                        <th class="px-6 py-3">Valor Compra</th>--}}
                        {{--                                        <th class="px-6 py-3">Valor Fim</th>--}}
                        {{--                                        <th class="px-6 py-3">Valor Médio</th>--}}
                        {{--                                        <th class="px-6 py-3">Tipo Operação</th>--}}
                        {{--                                        <th class="px-6 py-3">Ações</th>--}}
                        {{--                                    </tr>--}}
                        {{--                                    </thead>--}}
                        {{--                                    <tbody>--}}
                        {{--                                    @foreach($operation->executions as $index => $execution)--}}
                        {{--                                        <tr>--}}
                        {{--                                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">#{{ ++$index }}</td>--}}
                        {{--                                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $execution->start_at }}</td>--}}
                        {{--                                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $execution->purchase_value }}</td>--}}
                        {{--                                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $execution->sale_value ?: '0,00' }}</td>--}}
                        {{--                                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $execution->avarage_value ?: '0,00' }}</td>--}}
                        {{--                                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">--}}
                        {{--                                                @if($execution->type === 'P') Comprado @else Vendido @endif--}}
                        {{--                                            </td>--}}
                        {{--                                            <td scope="row" class="px-6 py-4 text-lg flex text-gray-900 whitespace-nowrap dark:text-white">--}}
                        {{--                                                <a href="#"--}}
                        {{--                                                   x-on:click.prevent="--}}
                        {{--                                                           fetchOperation({{ $execution->id }});--}}
                        {{--                                                           $dispatch('open-modal', 'modal-show');--}}
                        {{--                                                        "--}}
                        {{--                                                   id="abrirModal" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">--}}
                        {{--                                                    <i class="bi bi-eye-fill"></i>--}}
                        {{--                                                </a>--}}

                        {{--                                                <a href="#" title="Editar" class="block px-4 py-2 hover:bg-gray-100"><i class="bi bi-pencil-square"></i></a>--}}

                        {{--                                                <a href="#"--}}
                        {{--                                                   x-on:click.prevent="--}}
                        {{--                                                           getExecutionForFinish({{ $execution->id }});--}}
                        {{--                                                           $dispatch('open-modal', 'modal-finish');--}}
                        {{--                                                        "--}}
                        {{--                                                   id="abrirModal" class="block px-4 py-2 text-gray-700 hover:bg-gray-100"--}}
                        {{--                                                   title="Finalizar">--}}
                        {{--                                                    <i class="bi bi-check-square-fill"></i>--}}
                        {{--                                                </a>--}}

                        {{--                                                <a href="#" title="Deletar"--}}
                        {{--                                                   x-on:click.prevent="deleteId = {{ $execution->id }}; $dispatch('open-modal', 'modal-delete-execution')"--}}
                        {{--                                                   class="block px-4 py-2 text-red-700 hover:bg-gray-100">--}}
                        {{--                                                    <i class="bi bi-trash-fill"></i>--}}
                        {{--                                                </a>--}}
                        {{--                                            </td>--}}
                        {{--                                        </tr>--}}
                        {{--                                    @endforeach--}}
                        {{--                                    </tbody>--}}
                        {{--                                </table>--}}
                        {{--                            @endforeach--}}
                        {{--                        @else--}}
                        {{--                            <td colspan="7" scope="row" class="px-6 py-4 text-lg font-medium text-gray-900 whitespace-nowrap dark:text-white">--}}
                        {{--                                Nenhuma operação iniciada.--}}
                        {{--                            </td>--}}
                        {{--                        @endif--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
