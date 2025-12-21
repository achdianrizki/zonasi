<div id="schoolModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-xl rounded-lg p-6">
        <h3 id="modalTitle" class="text-lg font-semibold mb-4">
            Tambah Sekolah
        </h3>

        <form id="schoolForm">
            @csrf
            <input type="hidden" id="school_id">

            <div class="mb-3">
                <label>Nama Sekolah</label>
                <input type="text" id="name" class="w-full border border-gray-400 rounded px-2 py-2">
            </div>

            <div class="mb-3">
                <label>NPSN</label>
                <input type="text" id="npsn" class="w-full border border-gray-400 rounded px-2 py-2">
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <input type="text" id="address" class="w-full border border-gray-400 rounded px-2 py-2">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Kecamatan</label>
                <select id="district_id" class="w-full" style="width: 100%"></select>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Desa</label>
                <select id="village_id" class="w-full" style="width: 100%"></select>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" id="btnClose" class="px-4 py-2 border rounded">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
