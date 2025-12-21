function initDistrictVillageSelect(districtSelector, villageSelector) {

    $(districtSelector).select2({
        placeholder: 'Pilih Kecamatan',
        ajax: {
            url: '/api/v1/districts',
            dataType: 'json',
            delay: 250,
            data: params => ({
                q: params.term
            }),
            processResults: data => ({
                results: data.map(item => ({
                    id: item.id,
                    text: item.name
                }))
            })
        }
    });

    $(villageSelector).select2({
        placeholder: 'Pilih Desa',
        ajax: {
            url: '/api/v1/villages',
            dataType: 'json',
            delay: 250,
            data: params => ({
                q: params.term,
                district_id: $(districtSelector).val()
            }),
            processResults: data => ({
                results: data.map(item => ({
                    id: item.id,
                    text: item.name
                }))
            })
        }
    });

    $(districtSelector).on('change', function () {
        $(villageSelector).val(null).trigger('change');
    });
}
