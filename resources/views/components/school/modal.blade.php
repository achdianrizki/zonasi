<div id="schoolModal"
    class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-xl rounded-lg p-6">
        <h3 id="modalTitle" class="text-lg font-semibold mb-4">
            Tambah Sekolah
        </h3>

        <form id="schoolForm">
            @csrf
            <input type="hidden" id="school_id">

            <!-- NAMA -->
            <div class="mb-3">
                <label>Nama Sekolah</label>
                <input type="text" id="name"
                    class="w-full border border-gray-400 rounded px-2 py-2">
            </div>

            <!-- NPSN -->
            <div class="mb-3">
                <label>NPSN</label>
                <input type="text" id="npsn"
                    class="w-full border border-gray-400 rounded px-2 py-2">
            </div>

            <!-- ALAMAT -->
            <div class="mb-3">
                <label>Alamat</label>
                <input type="text" id="address"
                    class="w-full border border-gray-400 rounded px-2 py-2">
            </div>

            <!-- KECAMATAN -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Kecamatan</label>
                <select id="district_id" class="w-full"></select>
            </div>

            <!-- DESA -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Desa</label>
                <select id="village_id" class="w-full"></select>
            </div>

            <!-- 🔥 MAP -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Lokasi Sekolah
                </label>

                <div id="schoolMap"
                    class="w-full h-64 border rounded"></div>

                <div class="grid grid-cols-2 gap-2 mt-2">
                    <input type="text" id="latitude" readonly
                        placeholder="Latitude"
                        class="border px-2 py-1 rounded">

                    <input type="text" id="longitude" readonly
                        placeholder="Longitude"
                        class="border px-2 py-1 rounded">
                </div>

                <p class="text-xs text-gray-500 mt-1">
                    Klik pada peta untuk menentukan lokasi sekolah
                </p>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-2">
                <button type="button" id="btnClose"
                    class="px-4 py-2 border rounded">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
