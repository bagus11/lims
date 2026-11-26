var SPEC_FIELDS = ['symbol', 'value', 'tolerance', 'min', 'max'];
var DRAWING_MAX_BYTES = 2 * 1024 * 1024;

function renderDrawingPreview(prefix, dataUri) {
    var $box = $('#' + prefix + '_drawing_preview');
    if (!dataUri) {
        $box.html('<span class="text-muted" style="font-size:12px;">Belum ada drawing.</span>');
        return;
    }
    $box.html(
        '<img src="' + escapeHtml(dataUri) + '" alt="Drawing" ' +
        '     style="max-width:100%; max-height:180px; border:1px solid #e3e3e3; padding:4px;">' +
        '<button type="button" class="btn btn-sm btn-outline-danger ml-2 btn_remove_drawing" data-prefix="' + prefix + '">' +
        '  <i class="fas fa-trash"></i> Hapus' +
        '</button>'
    );
}

/**
 * Drawing disimpan sebagai data URI base64 (pola yang sama dengan tanda tangan
 * user), supaya mPDF bisa langsung mencetaknya tanpa direktori upload.
 */
function bindDrawingInput(prefix) {
    $('#' + prefix + '_drawing_file').on('change', function () {
        var file = this.files && this.files[0];
        var $err = $('.drawing_error');
        $err.html('');
        if (!file) return;
        if (file.size > DRAWING_MAX_BYTES) {
            $err.html('Ukuran gambar maksimal 2 MB.');
            this.value = '';
            return;
        }
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#' + prefix + '_drawing').val(e.target.result);
            renderDrawingPreview(prefix, e.target.result);
        };
        reader.readAsDataURL(file);
    });
}

/* Lampiran drawing PDF per produk — tidak dicetak di report, hanya diunduh. */

function drawingAttachmentSize(bytes) {
    var size = parseInt(bytes, 10);
    if (!size) return '-';
    if (size < 1024) return size + ' B';
    if (size < 1024 * 1024) return (size / 1024).toFixed(0) + ' KB';
    return (size / 1024 / 1024).toFixed(1) + ' MB';
}

function loadDrawingAttachments(productId) {
    var $list = $('#drawing_attachment_list');
    if (!productId) {
        $list.html('');
        return;
    }
    getCallbackNoSwal('getResalesAttachments', {
        owner_type: 'product',
        owner_key: productId,
        kind: 'drawing'
    }, function (resp) {
        var rows = resp.data || [];
        if (!rows.length) {
            $list.html('<div class="text-muted small">Belum ada lampiran drawing.</div>');
            return;
        }
        var html = '';
        $.each(rows, function (i, attachment) {
            var icon = String(attachment.mime_type).indexOf('pdf') !== -1
                ? '<i class="fas fa-file-pdf text-danger mr-2"></i>'
                : '<i class="fas fa-file-image text-primary mr-2"></i>';
            html += '<div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2" style="font-size:12px;">' +
                '  <div>' + icon + escapeHtml(attachment.file_name) +
                '    <span class="text-muted ml-2" style="font-size:11px;">' + drawingAttachmentSize(attachment.file_size) + '</span>' +
                '  </div>' +
                '  <div>' +
                '    <a class="btn btn-sm btn-outline-primary mr-1" target="_blank" href="downloadResalesAttachment?id=' + encodeURIComponent(attachment.id) + '"><i class="fas fa-eye"></i></a>' +
                '    <button type="button" class="btn btn-sm btn-outline-danger btn_delete_drawing_attachment" data-id="' + attachment.id + '"><i class="fas fa-trash"></i></button>' +
                '  </div>' +
                '</div>';
        });
        $list.html(html);
    });
}

$(document).on('change', '#drawing_attachment_file', function () {
    var file = this.files && this.files[0];
    var productId = $('#edit_id').val();
    if (!file || !productId) return;

    var form = new FormData();
    form.append('attachment', file);
    form.append('owner_type', 'product');
    form.append('owner_key', productId);
    form.append('kind', 'drawing');

    var input = this;
    postAttachment('uploadResalesAttachment', form, true, function (response) {
        swal.close();
        input.value = '';
        toastr['success'](response.meta.message);
        loadDrawingAttachments(productId);
    });
});

$(document).on('click', '.btn_delete_drawing_attachment', function () {
    var id = $(this).data('id');
    if (!confirm('Hapus lampiran drawing ini?')) return;
    postCallback('deleteResalesAttachment', { id: id }, function (response) {
        swal.close();
        toastr['success'](response.meta.message);
        loadDrawingAttachments($('#edit_id').val());
    });
});

$(document).on('click', '.btn_remove_drawing', function () {
    var prefix = $(this).data('prefix');
    $('#' + prefix + '_drawing').val('');
    $('#' + prefix + '_drawing_file').val('');
    renderDrawingPreview(prefix, '');
});

bindDrawingInput('add');
bindDrawingInput('edit');

function generateSpecRowHtml(prefix, spec) {
    var s = spec || {};
    var cell = function (field, placeholder) {
        var val = (s[field] === null || s[field] === undefined) ? '' : s[field];
        return '<div class="col">' +
            '  <input type="text" class="form-control form-control-sm ' + prefix + '_spec_' + field + '"' +
            '         placeholder="' + placeholder + '" value="' + escapeHtml(val) + '">' +
            '</div>';
    };
    return '<div class="form-row spec-row align-items-center mb-1">' +
        cell('symbol', 'D') +
        cell('value', '201.0') +
        cell('tolerance', '0.5') +
        cell('min', 'otomatis') +
        cell('max', 'otomatis') +
        '<div class="col-auto">' +
        '  <button type="button" class="btn btn-sm btn-danger btn_delete_spec">' +
        '    <i class="fas fa-trash"></i>' +
        '  </button>' +
        '</div>' +
        '</div>';
}

/**
 * Isi Min & Max dari Nilai ± Toleransi. Tidak menimpa Min/Max yang diketik
 * manual kecuali memang hasil hitungan sebelumnya, supaya spesifikasi bergaya
 * rentang ("55 s/d 58") yang diisi tangan tidak hilang.
 */
function recalcSpecBounds($row, prefix) {
    var num = function (field) {
        var raw = ($row.find('.' + prefix + '_spec_' + field).val() || '').toString().trim().replace(',', '.');
        if (raw === '') return null;
        var n = parseFloat(raw);
        return isNaN(n) ? null : n;
    };
    var value = num('value');
    var tolerance = num('tolerance');
    var $min = $row.find('.' + prefix + '_spec_min');
    var $max = $row.find('.' + prefix + '_spec_max');
    if (value === null || tolerance === null) return;
    if ($min.data('manual') || $max.data('manual')) return;
    $min.val(Number((value - tolerance).toFixed(4)));
    $max.val(Number((value + tolerance).toFixed(4)));
}

function collectSpecRows(prefix) {
    var specs = [];
    var $container = (prefix == 'add') ? $('#add_spec_container') : $('#edit_spec_container');
    $container.find('.spec-row').each(function () {
        var $row = $(this);
        var spec = {};
        var filled = false;
        for (var i = 0; i < SPEC_FIELDS.length; i++) {
            var field = SPEC_FIELDS[i];
            var raw = $row.find('.' + prefix + '_spec_' + field).val();
            spec[field] = (raw) ? raw.toString().trim() : '';
            if (spec[field] !== '') filled = true;
        }
        if (filled) specs.push(spec);
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
                data: 'uom',
                className: 'text-center',
                render: function (data, type, row) {
                    if (!data || data == '') return '<span class="text-muted">-</span>';
                    return escapeHtml(data);
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
                                    var std = formatSpecStandard(sp);
                                    if (sym == '' && std == '') continue;
                                    html += '<span class="badge badge-info badge-spec">' + escapeHtml(sym) + (std ? ': ' + escapeHtml(std) : '') + '</span>';
                                }
                            } else {
                                for (var j = 0; j < specList.length; j++) {
                                    var s = specList[j];
                                    if (s && s.toString().trim() != '') {
                                        html += '<span class="badge badge-info badge-spec">' + escapeHtml(s) + '</span>';
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
                    return limsFormatDateTime(data);
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
    var rowHtml = generateSpecRowHtml('add', {});
    $('#add_spec_container').append(rowHtml);
});

$('#btn_edit_add_spec').on('click', function () {
    var rowHtml = generateSpecRowHtml('edit', {});
    $('#edit_spec_container').append(rowHtml);
});

$('#add_spec_container').on('click', '.btn_delete_spec', function () {
    $(this).closest('.spec-row').remove();
});

$('#edit_spec_container').on('click', '.btn_delete_spec', function () {
    $(this).closest('.spec-row').remove();
});

// Nilai ± Toleransi mengisi Min/Max otomatis; begitu Min/Max disentuh manual,
// baris itu berhenti dihitung ulang supaya rentang "55 s/d 58" tetap utuh.
$('#add_spec_container').on('input', '.add_spec_value, .add_spec_tolerance', function () {
    recalcSpecBounds($(this).closest('.spec-row'), 'add');
});
$('#edit_spec_container').on('input', '.edit_spec_value, .edit_spec_tolerance', function () {
    recalcSpecBounds($(this).closest('.spec-row'), 'edit');
});
$('#add_spec_container').on('input', '.add_spec_min, .add_spec_max', function () {
    $(this).data('manual', true);
});
$('#edit_spec_container').on('input', '.edit_spec_min, .edit_spec_max', function () {
    $(this).data('manual', true);
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
        var rowHtml = generateSpecRowHtml('add', defaultSpecs[i]);
        $('#add_spec_container').append(rowHtml);
    }
    $('#add_name').val('');
    $('#add_size').val('');
    $('#add_uom').val('');
    $('#add_drawing').val('');
    $('#add_drawing_file').val('');
    renderDrawingPreview('add', '');
    $('.drawing_error').html('');
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
        $('#edit_uom').val(response.detail.uom ? response.detail.uom : '');
        $('#edit_drawing').val(response.detail.drawing ? response.detail.drawing : '');
        $('#edit_drawing_file').val('');
        $('.drawing_error').html('');
        renderDrawingPreview('edit', response.detail.drawing ? response.detail.drawing : '');
        loadDrawingAttachments(id);
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
                    var row = {};
                    for (var k = 0; k < SPEC_FIELDS.length; k++) {
                        var field = SPEC_FIELDS[k];
                        row[field] = (sp && sp[field] !== undefined && sp[field] !== null)
                            ? sp[field].toString()
                            : '';
                    }
                    return row;
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
            var row = generateSpecRowHtml('edit', pairArr[j]);
            $('#edit_spec_container').append(row);
        }
        // Min/Max yang datang dari master dianggap sudah final: jangan ditimpa
        // hitungan otomatis saat user menyentuh Nilai/Toleransi.
        $('#edit_spec_container').find('.spec-row').each(function () {
            var $row = $(this);
            if (($row.find('.edit_spec_min').val() || '').trim() !== '' ||
                ($row.find('.edit_spec_max').val() || '').trim() !== '') {
                $row.find('.edit_spec_min, .edit_spec_max').data('manual', true);
            }
        });

        $('#editMasterProductResalesGoods').modal('show');
    });
});

$('#btn_submit_add').on('click', function () {
    var specs = collectSpecRows('add');
    $('#add_spesification').val(JSON.stringify(specs));
    var postData = {
        'name': $('#add_name').val(),
        'size': $('#add_size').val(),
        'uom': $('#add_uom').val(),
        'drawing': $('#add_drawing').val(),
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
        'uom': $('#edit_uom').val(),
        'drawing': $('#edit_drawing').val(),
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
