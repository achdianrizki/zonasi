<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List Kecamatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Kecamatan</th>
                            <th>Nama Kecamatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($districts as $i => $district)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $district['code'] }}</td>
                                <td>{{ $district['name'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Data tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
