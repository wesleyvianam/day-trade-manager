<x-app-layout>
    <style>
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");
    </style>

    <div x-data="{ deleteId: null }">
        <div class="py-6 px-60">
            <div class="grid grid-cols-4 gap-6">
                <div class="p-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <p class="font-medium">Valor dolar Futuro</p>
                    <h2 id="dollarNow" class="text-xl font-bold">0,00</h2>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <p class="font-medium">Valor Médio</p>
                    <h2 class="text-xl font-bold">{{ $medValue ?? '0,00' }}</h2>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <p class="font-medium">Ordem Ativa</p>
                    <h2 class="text-xl font-bold">{{ $open }}</h2>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <p class="font-medium">Gain</p>
                    <h2 class="text-xl font-bold">{{ $gain }}</h2>
                </div>
            </div>
        </div>

        <div class="px-60 grid grid-cols-4 gap-6">
            <div class="col-span-3">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    @include('operation.partials.list')
                </div>
            </div>

            <div class="col-span-1">
                {{--                @include('operation.partials.add')--}}

                @include('operation.partials.add')
            </div>
        </div>

        @include('operation.partials.delete')
    </div>

    @include('operation.partials.scripts');

    {{--    <script>--}}
    {{--        const checkboxes = document.querySelectorAll('input.check-execution[type="checkbox"]');--}}

    {{--        checkboxes.forEach(checkbox => {--}}
    {{--            checkbox.addEventListener('click', () => {--}}
    {{--                const row = checkbox.closest('tr');--}}

    {{--                if (checkbox.checked) {--}}
    {{--                    row.classList.add('bg-yellow-100')--}}
    {{--                    row.classList.add('hover:bg:yellow-100')--}}
    {{--                } else {--}}
    {{--                    row.classList.remove('bg-yellow-100');--}}
    {{--                    row.classList.remove('hover:bg-yellow-100');--}}
    {{--                }--}}

    {{--                const activeCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);--}}
    {{--                const activeIds = activeCheckboxes.map(cb => cb.value);--}}

    {{--                const ids = document.querySelector('#finish_ids');--}}
    {{--                ids.value = activeIds;--}}

    {{--                const quantity = document.querySelector('#quantity_finish');--}}
    {{--                quantity.value = activeIds.length;--}}
    {{--            });--}}
    {{--        });--}}
    {{--    </script>--}}
</x-app-layout>
