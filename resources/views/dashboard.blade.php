<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow-sm rounded-lg h-full">
                    <div class="p-4 border-b">
                        <p class="text-sm text-gray-500 text-center">
                            {{ __('Jumlah Pengunjung Website') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-center h-32">
                        <p class="text-4xl font-bold text-gray-900">
                            {{ $visitorsCount }}
                        </p>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg h-full">
                    <div class="p-4 border-b">
                        <p class="text-sm text-gray-500 text-center">
                            {{ __('Jumlah Sekolah') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-center h-32">
                        <p class="text-4xl font-bold text-gray-900">
                            {{ $schoolsCount }}
                        </p>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg h-full flex flex-col">
                    <div class="p-4 border-b text-center">
                        <p class="text-sm text-gray-500">
                            {{ __('Jumlah Kecamatan & Desa') }}
                        </p>
                    </div>

                    <div class="flex-1 flex flex-col items-center justify-center space-y-4">

                        <div class="flex items-center gap-3 group relative">
                            <span class="w-3 h-3 bg-yellow-400 rounded-full"></span>
                            <p class="text-4xl font-bold text-gray-900">
                                {{ $districtsCount ?? 0 }}
                            </p>

                            <div
                                class="absolute -top-8 opacity-0 group-hover:opacity-100 transition bg-gray-900 text-white text-xs px-2 py-1 rounded shadow">
                                Jumlah Kecamatan
                            </div>
                        </div>

                        <div class="flex items-center gap-3 group relative">
                            <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                            <p class="text-4xl font-bold text-gray-900">
                                {{ $villagesCount ?? 0 }}
                            </p>

                            <div
                                class="absolute -top-8 opacity-0 group-hover:opacity-100 transition bg-gray-900 text-white text-xs px-2 py-1 rounded shadow">
                                Jumlah Desa
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-10">
            <div class="bg-white shadow-sm rounded-lg h-full">
                <canvas id="visitorChart" data-labels='@json($visitorChart->pluck('date'))'
                    data-values='@json($visitorChart->pluck('total'))'>
                </canvas>
            </div>
        </div>

    </div>
</x-app-layout>
