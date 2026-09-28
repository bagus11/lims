function generateSpecRowHtml(prefix, symbol, value) {
    var symbolVal = (symbol != null && symbol !== undefined) ? symbol : '';
    var valueVal = (value != null && value !== undefined) ? value : '';
    return '<div class="form-row spec-row align-items-center">' +
        '<div class="col-4">' +
        '  <div class="input-group">' +
        '    <input type="text" class="form-control ' + prefix + '_spec_symbol" placeholder="Contoh: D, d, L, Z, H, T, dll" value="' + symbolVal + '">' +
        '  </div>' +
        '</div>' +
        '<div class="col-6">' +
        '  <div class="input-group">' +
        '    <input type="text" class="form-control ' + prefix + '_spec_value" placeholder="Contoh: 201.0, 160.3, 405, 91, 250" value="' + valueVal + '">' +
        '  </div>' +
        '</div>' +
        '<div class="col-2">' +
        '  <button type="button" class="btn btn-sm btn-danger btn_delete_spec">' +
        '    <i class="fas fa-trash"></i>' +
        '  </button>' +
        '</div>' +
        '</div>';
}

function collectSpecRows(prefix) {
    var specs = [];
    var $container = (prefix == 'add') ? $('#add_spec_container') : $('#edit_spec_container');
    $container.find('.spec-row').each(function () {
        var sym = $(this).find('.' + prefix + '_spec_symbol').val();
        var val = $(this).find('.' + prefix + '_spec_value').val();
        var symStr = (sym) ? sym.toString().trim() : '';
        var valStr = (val) ? val.toString().trim() : '';
        if (symStr != '' || valStr != '') {
            specs.push({
                symbol: symStr,
                value: valStr
            });
        }
    });
    return specs;
}

function mappingTable(data) {
    $('#master_product_resales_goods_table').DataTable({
        data: data,
        destroy: true,
        columns: [
            { data: 'id', className: 'text-center' },
            { data: 'name', className: 'text-center' },
            {
                data: 'size',
                className: 'text-center',
                render: function (data, type, row) {
                    if (!data || data == '') return '<span class="text-muted">-</span>';
                    return data;
                }
            },
            {
                data: 'spesification',
                className: 'text-center',
                render: function (data, type, row) {
                    if (!data || data == '' || (Array.isArray(data) && data.length == 0)) {
                        return '<span class="text-muted">-</span>';
                    }
                    var html = '';
                    try {
                        var specList = (typeof data === 'string') ? JSON.parse(data) : data;
                        if (Array.isArray(specList) && specList.length > 0) {
                            if (typeof specList[0] === 'object') {
                                for (var i = 0; i < specList.length; i++) {
                                    var sp = specList[i];
                                    var sym = (sp && sp.symbol) ? sp.symbol.toString() : '';
                                    var val = (sp && sp.value !== undefined && sp.value !== null) ? sp.value.toString() : '';
                                    if (sym == '' && val == '') continue;
                                    html += '<span class="badge badge-info badge-spec">' + sym + (val ? ': ' + val : '') + '</span>';
                                }
                            } else {
                                for (var j = 0; j < specList.length; j++) {
                                    var s = specList[j];
                                    if (s && s.toString().trim() != '') {
                                        html += '<span class="badge badge-info badge-spec">' + s + '</span>';
                                    }
                                }
                            }
                        }
                    } catch (e) {
                        html = '<span class="text-muted">-</span>';
                    }
                    return html == '' ? '<span class="text-muted">-</span>' : html;
                }
            },
            {
                data: 'created_at',
                className: 'text-center',
                render: function (data, type, row) {
                    if (!data) return '<span class="text-muted">-</span>';
                    return data;
                }
            },
            {
                data: 'id',
                className: 'text-center',
                render: function (data, type, row) {
                    return '<div class="btn-group">' +
                        '<button class="btn btn-sm btn-primary btn_edit" data-id="' + row['id'] + '">' +
                        '  <i class="fas fa-edit"></i>' +
                        '</button>' +
                        '<button class="btn btn-sm btn-danger btn_delete ml-1" data-id="' + row['id'] + '">' +
                        '  <i class="fas fa-trash"></i>' +
                        '</button>' +
                        '</div>';
                }
            }
        ],
        order: [[0, 'desc']]
    });
}

getCallback('getMasterProductResalesGoods', null, function (response) {
    swal.close();
    mappingTable(response.data);
});

$('#btn_add_spec').on('click', function () {
    var rowHtml = generateSpecRowHtml('add', '', '');
    $('#add_spec_container').append(rowHtml);
});

$('#btn_edit_add_spec').on('click', function () {
    var rowHtml = generateSpecRowHtml('edit', '', '');
    $('#edit_spec_container').append(rowHtml);
});

$('#add_spec_container').on('click', '.btn_delete_spec', function () {
    $(this).closest('.spec-row').remove();
});

$('#edit_spec_container').on('click', '.btn_delete_spec', function () {
    $(this).closest('.spec-row').remove();
});

$('#addMasterProductResalesGoods').on('shown.bs.modal', function () {
    $('#add_spec_container').html('');
    var defaultSpecs = [
        { symbol: 'D', value: '' },
        { symbol: 'd', value: '' },
        { symbol: 'L', value: '' },
        { symbol: 'Z', value: '' },
        { symbol: 'H', value: '' },
    ];
    for (var i = 0; i < defaultSpecs.length; i++) {
        var rowHtml = generateSpecRowHtml('add', defaultSpecs[i].symbol, defaultSpecs[i].value);
        $('#add_spec_container').append(rowHtml);
    }
    $('#add_name').val('');
    $('#add_size').val('');
    $('.name_error').html('');
    $('.size_error').html('');
    $('.spesification_error').html('');
});

$('#master_product_resales_goods_table').on('click', '.btn_edit', function () {
    var id = $(this).data('id');
    var data = { 'id': id };
    $('#edit_id').val(id);
    getCallback('getDetailResalesGoods', data, function (response) {
        swal.close();
        $('.name_error').html('');
        $('.size_error').html('');
        $('.spesification_error').html('');
        $('#edit_name').val(response.detail.name);
        $('#edit_size').val(response.detail.size ? response.detail.size : '');
        $('#edit_spec_container').html('');

        var specList = [];
        if (response.detail.spesification && response.detail.spesification != '') {
            try {
                specList = (typeof response.detail.spesification === 'string')
                    ? JSON.parse(response.detail.spesification)
                    : response.detail.spesification;
            } catch (e) {
                specList = [];
            }
        }

        var pairArr = [];
        if (Array.isArray(specList) && specList.length > 0) {
            if (typeof specList[0] === 'object') {
                pairArr = specList.map(function (sp) {
                    return {
                        symbol: (sp && sp.symbol) ? sp.symbol.toString() : '',
                        value: (sp && sp.value !== undefined && sp.value !== null) ? sp.value.toString() : ''
                    };
                });
            } else {
                pairArr = specList.filter(function (s) { return s && s.toString().trim() != ''; })
                    .map(function (s) { return { symbol: s.toString().trim(), value: '' }; });
            }
        }

        if (pairArr.length == 0) {
            pairArr = [
                { symbol: 'D', value: '' },
                { symbol: 'd', value: '' },
                { symbol: 'L', value: '' },
                { symbol: 'Z', value: '' },
                { symbol: 'H', value: '' },
            ];
        }

        for (var j = 0; j < pairArr.length; j++) {
            var row = generateSpecRowHtml('edit', pairArr[j].symbol, pairArr[j].value);
            $('#edit_spec_container').append(row);
        }

        $('#editMasterProductResalesGoods').modal('show');
    });
});

$('#btn_submit_add').on('click', function () {
    var specs = collectSpecRows('add');
    $('#add_spesification').val(JSON.stringify(specs));
    var postData = {
        'name': $('#add_name').val(),
        'size': $('#add_size').val(),
        'spesification': $('#add_spesification').val()
    };
    postCallback('addMasterProductResalesGoods', postData, function (response) {
        swal.close();
        toastr['success'](response.meta.message);
        $('#addMasterProductResalesGoods').modal('hide');
        $('.name_error').html('');
        $('.size_error').html('');
        $('.spesification_error').html('');
        getCallbackNoSwal('getMasterProductResalesGoods', null, function (response) {
            mappingTable(response.data);
        });
    });
});

$('#btn_submit_edit').on('click', function () {
    var specs = collectSpecRows('edit');
    $('#edit_spesification').val(JSON.stringify(specs));
    var postData = {
        'id': $('#edit_id').val(),
        'name': $('#edit_name').val(),
        'size': $('#edit_size').val(),
        'spesification': $('#edit_spesification').val()
    };
    postCallback('updateMasterProductResalesGoods', postData, function (response) {
        swal.close();
        toastr['success'](response.meta.message);
        $('#editMasterProductResalesGoods').modal('hide');
        $('.name_error').html('');
        $('.size_error').html('');
        $('.spesification_error').html('');
        getCallbackNoSwal('getMasterProductResalesGoods', null, function (response) {
            mappingTable(response.data);
        });
    });
});

$('#master_product_resales_goods_table').on('click', '.btn_delete', function () {
    var id = $(this).data('id');
    if (confirm('Apakah Anda yakin ingin menghapus data ini? Semua data transaksi yang berhubungan mungkin ikut terhapus.')) {
        var postData = { 'id': id };
        postCallback('deleteMasterProductResalesGoods', postData, function (response) {
            swal.close();
            toastr['success'](response.meta.message);
            getCallbackNoSwal('getMasterProductResalesGoods', null, function (response) {
                mappingTable(response.data);
            });
        });
    }
});

$('#btn_upload_resales_goods').on('click', function (e) {
    e.preventDefault();
    var data = new FormData();
    var fileInput = $('#resales_goods_upload_file')[0].files[0];
    if (fileInput) {
        data.append('upload_file', fileInput);
        postAttachment('uploadMasterProductResalesGoods', data, true, function (response) {
            swal.close();
            $('#uploadMasterProductResalesGoods').modal('hide');
            $('.upload_file_error').html('');
            $('.message_error').html('');
            toastr['success'](response.meta.message);
            getCallbackNoSwal('getMasterProductResalesGoods', null, function (response) {
                mappingTable(response.data);
                $('#resales_goods_upload_file').val('');
            });
        });
    } else {
        toastr['error']('Please select a file to upload.');
    }
});

$('#uploadMasterProductResalesGoods').on('hidden.bs.modal', function () {
    $('#resales_goods_upload_file').val('');
    $('.upload_file_error').html('');
});
