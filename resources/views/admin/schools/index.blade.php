<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List Sekolah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="table-auto" border="1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sekolah</th>
                            <th>NPSN</th>
                            <th>Alamat</th>
                            <th>Kode Kecamatan</th>
                            <th>Kecamatan</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schools as $i => $school)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $school['name'] ?? '-' }}</td>
                                <td>{{ $school['npsn'] ?? '-' }}</td>
                                <td>{{ $school['address'] ?? '-' }}</td>
                                <td>{{ $school['district_code'] ?? '-' }}</td>
                                <td>{{ $school['district_name'] ?? '-' }}</td>
                                <td>{{ $school['lang'] ?? '-' }}</td>
                                <td>{{ $school['long'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">Data tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
