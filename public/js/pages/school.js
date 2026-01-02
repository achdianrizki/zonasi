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
                    <button class="btnEdit bg-yellow-400s" data-id="${data.id}"><i class="fa-sharp fa-solid fa-pencil"></i></button>
                    <button class="btnDelete" data-id="${data.id}"><i class="fa-sharp fa-solid fa-trash"></i></button>
                `
        }
        ]
    });

    $('#btnAdd').on('click', function () {
        resetForm();
        $('#modalTitle').text('Tambah Sekolah');
        $('#schoolModal').removeClass('hidden');
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
                village_id: $('#village_id').val()
            },
            success: function () {
                $('#schoolModal').addClass('hidden');
                table.ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                });
            },
            error(err) {
                console.log(err);

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

            console.log(data);

            $('#district_id')
                .append(new Option(data.district.name, data.district_id, true, true))
                .trigger('change');
            $('#village_id')
                .append(new Option(data.village.name, data.village_id, true, true))
                .trigger('change');

            $('#modalTitle').text('Edit Sekolah');
            $('#schoolModal').removeClass('hidden');
        });
    });

    function resetForm() {
        $('#schoolForm')[0].reset();
        $('#school_id').val('');
        $('#district_id, #village_id').val(null).trigger('change');
    }

});
