let map;
let marker;


$(document).ready(function () {

    initDistrictVillageSelect('#district_id', '#village_id');

    const table = $('#tableSchool').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        searching: true,
        saveState: true,
        ajax: '/api/v1/schools',
        columns: [{
                data: null,
                render: (d, t, r, m) => m.row + 1
            },
            {
                data: 'name'
            },
            {
                data: 'npsn'
            },
            {
                data: 'address'
            },
            {
                data: 'district_name'
            },
            {
                data: 'village_name'
            },
            {
                data: null,
                render: data => `
                    <button class="btnEdit" data-id="${data.id}">
                        <i class="fa-sharp fa-solid fa-pencil"></i>
                    </button>
                    <button class="btnDelete" data-id="${data.id}">
                        <i class="fa-sharp fa-solid fa-trash"></i>
                    </button>
                `
            }
        ]
    });

    $('#btnAdd').on('click', function () {
        resetForm();
        $('#modalTitle').text('Tambah Sekolah');
        $('#schoolModal').removeClass('hidden');

        setTimeout(initMap, 200); // ⬅️ PENTING
    });

    $('#btnClose').on('click', function () {
        $('#schoolModal').addClass('hidden');
    });

    $('#schoolForm').on('submit', function (e) {
        e.preventDefault();

        let id = $('#school_id').val();
        let url = id ? `/api/v1/schools/${id}` : '/api/v1/schools';
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: {
                name: $('#name').val(),
                npsn: $('#npsn').val(),
                address: $('#address').val(),
                district_id: $('#district_id').val(),
                village_id: $('#village_id').val(),
                latitude: $('#latitude').val(),
                longitude: $('#longitude').val()
            },
            success: function () {
                $('#schoolModal').addClass('hidden');
                table.ajax.reload();
            }
        });
    });

    $('#tableSchool').on('click', '.btnEdit', function () {
        let id = $(this).data('id');

        $.get(`/api/v1/schools/${id}`, function (res) {
            let data = res.data;

            $('#school_id').val(data.id);
            $('#name').val(data.name);
            $('#npsn').val(data.npsn);
            $('#address').val(data.address);
            $('#latitude').val(data.latitude);
            $('#longitude').val(data.longitude);

            $('#district_id')
                .append(new Option(data.district.name, data.district_id, true, true))
                .trigger('change');

            $('#village_id')
                .append(new Option(data.village.name, data.village_id, true, true))
                .trigger('change');

            $('#modalTitle').text('Edit Sekolah');
            $('#schoolModal').removeClass('hidden');

            setTimeout(() => initMap(data.latitude, data.longitude), 200);
        });
    });

    $('#tableSchool').on('click', '.btnDelete', function () {
        const id = $(this).data('id');

        if (!confirm('Yakin ingin menghapus sekolah ini?')) return;

        $.ajax({
            url: `/api/v1/schools/${id}`,
            type: 'DELETE',
            success: function (res) {
                console.log(res.message);
                $('#tableSchool').DataTable().ajax.reload();
            },
            error: function (xhr) {
                console.error(xhr);
                alert('Gagal menghapus data');
            }
        });
    });

});

function initMap(lat = -6.914744, lng = 107.609810) {

    if (map) {
        map.remove();
    }

    map = L.map('schoolMap').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    marker = L.marker([lat, lng]).addTo(map);

    map.on('click', function (e) {
        let latitude = e.latlng.lat;
        let longitude = e.latlng.lng;

        $('#latitude').val(latitude);
        $('#longitude').val(longitude);

        marker.setLatLng(e.latlng);
    });

    setTimeout(() => {
        map.invalidateSize();
    }, 100);
}


function resetForm() {
    $('#schoolForm')[0].reset();
    $('#school_id').val('');
    $('#district_id, #village_id').val(null).trigger('change');
    $('#latitude').val('');
    $('#longitude').val('');
}
