<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">List Sekolah</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">

                <button id="btnAdd" class="mb-4 px-4 py-2 bg-blue-600 text-white rounded">
                    + Tambah Sekolah
                </button>

                <table id="tableSchool" class="w-full border">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NPSN</th>
                            <th>Address</th>
                            <th>Kecamatan</th>
                            <th>Desa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        @push('scripts')
            <script src="{{ asset('js/pages/school.js') }}"></script>
        @endpush
        <x-school.modal />
</x-app-layout>
