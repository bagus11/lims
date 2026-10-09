var OPTIONS = { locations: [], products: [], suppliers: [], inspectors: [] }
var CURRENT_TRX_CODE = ''
var CURRENT_HEADER = null

function formatDateStandard(raw) {
    if (!raw) return '-'
    const d = new Date(raw)
    if (isNaN(d.getTime())) return raw
    const date = d.toISOString().split('T')[0]
    const time = d.toTimeString().split(' ')[0]
    return date + ' ' + time
}

function typeBadge(t) {
    if (!t) return '<span class="text-muted">-</span>'
    if (String(t).toLowerCase() === 'fitting') return '<span class="badge badge-success" style="font-size: 11px;"><i class="fas fa-puzzle-piece mr-1"></i> Fitting</span>'
    return '<span class="badge badge-warning" style="font-size: 11px;"><i class="fas fa-barcode mr-1"></i> Non Fitting</span>'
}

function idBadge(id, title) {
    title = title || 'ID'
    if (id === null || id === undefined || id === '') return '<span class="text-muted">-</span>'
    return '<span class="badge badge-light" style="font-size: 11px; color:#525f7f;" title="' + escapeHtml(title) + ' = ' + escapeHtml(id) + '"><i class="fas fa-hashtag mr-1"></i>' + escapeHtml(id) + '</span>'
}

function missingRelation(label) {
    return '<span class="text-danger"><i class="fas fa-exclamation-triangle mr-1"></i><i>Data ' + label + ' tidak ditemukan di master</i></span>'
}

function relName(relation, nameFields, extraHtmlAfter) {
    if (!relation) return null
    var finalName = ''
    $.each(nameFields || ['name'], function (i, f) {
        var v = relation[f]
        if (v !== null && v !== undefined && String(v).trim() !== '') { finalName = String(v).trim(); return false }
    })
    if (finalName === '') return null
    return escapeHtml(finalName) + (extraHtmlAfter || '')
}

function infoTr(label, value) {
    return '<tr>' +
        '<td style="width:30%; padding: 10px 14px; color:#525f7f; font-weight:600; border-top: 1px solid #e9ecef; vertical-align:middle;">' + label + '</td>' +
        '<td style="padding: 10px 14px; color:#32325d; border-top: 1px solid #e9ecef; vertical-align:middle; font-size: 14px;">' + (value || '<span class="text-muted">-</span>') + '</td>' +
        '</tr>'
}

function buildHeaderSummaryHtml(header) {
    if (!header) return ''
    $pr = header.productRelation || header.product_relation || null
    $uom = $pr && $pr.uom && String($pr.uom).trim() !== '' ? '<span class="badge badge-secondary ml-2" style="font-size:11px;">UoM: ' + escapeHtml($pr.uom) + '</span>' : ''
    $sizeSuffix = $pr && $pr.size ? ' <span class="text-muted">(' + escapeHtml($pr.size) + ')</span>' : ''
    var locationHtml = relName(header.location_relation || header.location_relation, ['name', 'nama_kantor', 'nama', 'initial']) || missingRelation('Location')
    var productHtml = relName($pr, ['name'], $sizeSuffix) || missingRelation('Product')
    if ($uom && productHtml && productHtml.indexOf('ditemukan') === -1) productHtml = productHtml + ' &nbsp; ' + $uom
    var inspectorHtml = relName(header.inspectorRelation || header.inspector_relation, ['name', 'username']) || missingRelation('Inspector')
    var supplierHtml = relName(header.supplierRelation || header.supplier_relation, ['name', 'supplier_name', 'nama_supplier']) || missingRelation('Supplier')
    var produsenHtml = relName(header.produsenRelation || header.produsen_relation, ['name', 'supplier_name', 'nama_supplier']) || missingRelation('Produsen')
    var createdByHtml = relName(header.created_by_relation || header.created_by_relation, ['name', 'username']) || missingRelation('Created User')

    var codeHtml = '<b class="text-primary" style="font-size:15px; white-space: nowrap;">' + escapeHtml(header.transaction_code) + '</b> &nbsp; ' + statusBadge(header.status)
    var qtyHtml = '<b style="font-size:15px;" class="text-info">' + escapeHtml(header.quantity || 0) + '</b> &nbsp; ' + typeBadge(header.type)
    var certStd = escapeHtml((header.certificate || '') + ((header.certificate && header.standard) ? ' / ' : '') + (header.standard || ''))
    if (!certStd) certStd = ''

    var extraRows = ''
    if ((header.brand && String(header.brand).trim() !== '') || (header.packaging && String(header.packaging).trim() !== '')) {
        extraRows += infoTr('Brand', header.brand ? '<span class="text-dark font-weight-medium">' + escapeHtml(header.brand) + '</span>' : '')
        extraRows += infoTr('Packaging', header.packaging ? '<span class="text-dark font-weight-medium">' + escapeHtml(header.packaging) + '</span>' : '')
    }
    if (header.no_pf && String(header.no_pf).trim() !== '') {
        extraRows += infoTr('No. PF', '<span class="text-dark font-weight-bold">' + escapeHtml(header.no_pf) + '</span>')
    }
    if (header.keterangan && String(header.keterangan).trim() !== '') {
        extraRows += infoTr('Keterangan', '<span class="text-muted" style="white-space: pre-wrap; line-height:1.45;">' + escapeHtml(header.keterangan) + '</span>')
    }

    return '<div class="table-responsive p-0 m-0"><table class="table m-0 table-sm" style="width:100%; border-collapse: collapse;">' +
        '<tbody>' +
        infoTr('Transaction Code', codeHtml) +
        infoTr('Location', locationHtml) +
        infoTr('Product', productHtml) +
        infoTr('Inspector', inspectorHtml) +
        infoTr('Supplier', supplierHtml) +
        infoTr('Produsen', produsenHtml) +
        infoTr('Quantity / Type', qtyHtml) +
        (certStd ? infoTr('Certificate / Standard', certStd) : '') +
        (header.no_pf ? infoTr('No. PF', '<b class="text-dark">' + escapeHtml(header.no_pf) + '</b>') : '') +
        (header.keterangan ? infoTr('Keterangan', '<span style="white-space:pre-wrap; line-height:1.45;">' + escapeHtml(header.keterangan) + '</span>') : '') +
        infoTr('Created By', createdByHtml) +
        infoTr('Created At', '<span class="text-muted">' + formatDateStandard(header.created_at) + '</span>') +
        extraRows +
        '</tbody></table></div>'
}

function loadOptions(callback) {
    getCallbackNoSwal('getOptionListsResalesGoods', null, function (resp) {
        OPTIONS = resp.data
        if (callback) callback()
    })
}

function initSelect2() {
    var $loc = $('#select_location')
    $loc.empty().append('<option value="">- Select Location -</option>')
    $.each(OPTIONS.locations, function (i, v) {
        $loc.append('<option value="' + escapeHtml(v.id) + '">' + escapeHtml(v.name || v.initial || v.id) + '</option>')
    })
    $loc.select2({ dropdownParent: $('#addResalesGoodHeader') })
    $loc.on('change', function () { $('#location_id').val($(this).val()) })

    var $prod = $('#select_product')
    $prod.empty().append('<option value="">- Select Product -</option>')
    $.each(OPTIONS.products, function (i, v) {
        var label = v.name
        if (v.size) label += ' (' + v.size + ')'
        if (v.uom && String(v.uom).trim() !== '') label += ' - [' + v.uom + ']'
        $prod.append('<option value="' + escapeHtml(v.id) + '">' + escapeHtml(label) + '</option>')
    })
    $prod.select2({ dropdownParent: $('#addResalesGoodHeader') })
    $prod.on('change', function () { $('#product_id').val($(this).val()) })

    var $ins = $('#select_inspector')
    $ins.empty().append('<option value="">- Select Inspector -</option>')
    $.each(OPTIONS.inspectors, function (i, v) {
        $ins.append('<option value="' + escapeHtml(v.id) + '">' + escapeHtml(v.name) + '</option>')
    })
    $ins.select2({ dropdownParent: $('#addResalesGoodHeader') })
    $ins.on('change', function () { $('#inspector_id').val($(this).val()) })

    var $sup = $('#select_supplier')
    $sup.empty().append('<option value="">- Select Supplier -</option>')
    $.each(OPTIONS.suppliers, function (i, v) {
        $sup.append('<option value="' + escapeHtml(v.id) + '">' + escapeHtml(v.name || v.supplier_name || v.id) + '</option>')
    })
    $sup.select2({ dropdownParent: $('#addResalesGoodHeader') })
    $sup.on('change', function () { $('#supplier_id').val($(this).val()) })

    var $produsen = $('#select_produsen')
    $produsen.empty().append('<option value="">- Select Produsen -</option>')
    $.each(OPTIONS.suppliers, function (i, v) {
        $produsen.append('<option value="' + escapeHtml(v.id) + '">' + escapeHtml(v.name || v.supplier_name || v.id) + '</option>')
    })
    $produsen.select2({ dropdownParent: $('#addResalesGoodHeader') })
    $produsen.on('change', function () { $('#produsen_id').val($(this).val()) })

    $('#select_type').select2({ dropdownParent: $('#addResalesGoodHeader') })
}

function statusBadge(status) {
    status = parseInt(status)
    var icon = '', label = '', cls = ''
    if (status === 0) { icon = 'fa-file'; label = 'Draft'; cls = 'secondary' }
    else if (status === 1) { icon = 'fa-spinner fa-spin'; label = 'In Progress'; cls = 'primary' }
    else if (status === 2) { icon = 'fa-hourglass-half'; label = 'Approval Progress'; cls = 'info' }
    else if (status === 3) { icon = 'fa-circle-check'; label = 'Done'; cls = 'success' }
    else { icon = 'fa-question'; label = status; cls = 'light' }
    return '<span class="badge badge-' + cls + ' ml-1" style="font-size:10px"><i class="fas ' + icon + ' mr-1"></i>' + label + '</span>'
}

function mappingTable(data) {
    $('#resales_goods_table').DataTable({
        data: data,
        destroy: true,
        order: [[1, 'desc']],
        columns: [
            { data: null, className: 'text-center', render: function (data, type, row, meta) { return meta.row + 1 } },
            {
                data: 'created_at', className: 'text-center', render: function (d) { return formatDateStandard(d) }
            },
            { data: 'transaction_code', className: 'text-center font-weight-bold', render: function (d) { return escapeHtml(d) } },
            { data: 'location_relation', className: 'text-center', render: function (d) { return d ? escapeHtml(d.name || d.initial || d.id) : '-' } },
            { data: 'no_pf', className: 'text-center font-weight-medium', render: function (d) { if (!d || String(d).trim() === '') return '<span class="text-muted">-</span>'; return '<span class="badge badge-dark" style="font-size:12px; padding:4px 8px;">' + escapeHtml(d) + '</span>' } },
            { data: 'keterangan', className: '', render: function (d) { if (!d || String(d).trim() === '') return '<span class="text-muted">-</span>'; return '<span style="font-size:12px; line-height:1.35;">' + escapeHtml(d) + '</span>' } },
            { data: 'created_by_relation', className: 'text-center', render: function (d) { return d ? escapeHtml(d.name) : '-' } },
            { data: 'status', className: 'text-center', render: function (d) { return statusBadge(d) } },
            {
                data: null, className: 'text-center', render: function (data, type, row) {
                    var code = escapeHtml(row.transaction_code)
                    var btn = ''
                    btn += '<button class="btn btn-sm btn-info mr-1 btn-info-row" data-code="' + code + '" title="Info Transaksi"> <i class="fa-solid fa-circle-info"></i></button>'
                    btn += '<button class="btn btn-sm btn-success mr-1 btn-detail" data-code="' + code + '" title="View Detail / Add Detail Rows"><i class="fa-solid fa-plus"></i></button>'
                    if (parseInt(row.status) >= 2) {
                        btn += '<button class="btn btn-sm btn-primary mr-1 btn-print" data-code="' + code + '" title="Print Report"><i class="fas fa-print"></i></button>'
                    }
                    if (parseInt(row.status) === 1) {
                        btn += '<button class="btn btn-sm btn-warning mr-1 btn-lock" data-code="' + code + '" title="Lock to Approval"><i class="fa-solid fa-lock"></i></button>'
                    }
                    if (parseInt(row.status) <= 1) {
                        btn += '<button class="btn btn-sm btn-danger mr-1 btn-delete" data-code="' + code + '" title="Delete"><i class="fa-solid fa-trash"></i></button>'
                    }
                    return btn
                }
            }
        ]
    })
}

function renderDetailRowsForDetailTable(details) {
    var data = []
    $.each(details, function (i, row) {
        var specsHtml = ''
        var specArr = row.spesification || []
        if (specArr.length > 0) {
            if (typeof specArr[0] === 'object') {
                $.each(specArr, function (j, sp) {
                    var sym = (sp.symbol || '').trim()
                    var val = (sp.value || '').trim()
                    if (sym === '' && val === '') return
                    if (val !== '') specsHtml += '<span class="badge badge-primary mr-1 mb-1" style="font-size: 11px;"><b>' + escapeHtml(sym) + '</b>: ' + escapeHtml(val) + '</span>'
                    else specsHtml += '<span class="badge badge-secondary mr-1 mb-1" style="font-size: 11px;"><b>' + escapeHtml(sym) + '</b></span>'
                })
            } else {
                $.each(specArr, function (j, s) {
                    specsHtml += '<span class="badge badge-light mr-1 mb-1" style="font-size: 11px;">' + escapeHtml(s) + '</span>'
                })
            }
        }
        if (specsHtml === '') specsHtml = '<small class="text-muted"><i>(no spec filled)</i></small>'
        $pr = row.productRelation || row.product_relation || null
        var productName = ''
        if ($pr) {
            productName = relName($pr, ['name'], $pr.size ? ' <small class="text-muted">(' + escapeHtml($pr.size) + ')</small>' : '')
            if ($pr.uom && String($pr.uom).trim() !== '') productName += ' <span class="badge badge-secondary ml-1" style="font-size:10px;">' + escapeHtml($pr.uom) + '</span>'
        } else {
            productName = relName(null, ['name'], '') || '-'
        }
        var createdByName = row.created_by_relation
            ? escapeHtml(row.created_by_relation.name || '') || relName(row.created_by_relation, ['name', 'username'], '')
            : '-'
        data.push({
            id: row.id,
            no: i + 1,
            product: productName,
            spesification: specsHtml,
            spesification_raw: specArr,
            createdBy: createdByName,
            createdAt: formatDateStandard(row.created_at),
            _row: row
        })
    })
    $('#resales_goods_detail_table').DataTable({
        data: data,
        destroy: true,
        columns: [
            { data: 'no', className: 'text-center', width: '50px' },
            { data: 'product', width: '18%' },
            { data: 'spesification' },
            { data: 'createdBy', className: 'text-center', width: '13%' },
            { data: 'createdAt', className: 'text-center', width: '15%' },
            {
                data: null, className: 'text-center', width: '110px', render: function (data, type, row) {
                    return '<button class="btn btn-sm btn-outline-primary btn-edit-detail-row mr-1" data-id="' + row.id + '" title="Edit Detail"><i class="fas fa-edit"></i></button>' +
                        '<button class="btn btn-sm btn-outline-danger btn-delete-detail-row" data-id="' + row.id + '" title="Delete Detail"><i class="fas fa-trash-alt"></i></button>'
                }
            }
        ]
    })
}

function generateSpecRowHtml(symbol, stdValue, editableSymbol, rowIdx) {
    var sym = (symbol || '').toString().trim()
    var std = (stdValue === null || stdValue === undefined) ? '' : String(stdValue).trim()
    var isStandard = !editableSymbol
    var symAttr = escapeHtml(isStandard ? sym : '')
    var stdAttr = escapeHtml(std)

    var symHtml
    if (isStandard) {
        symHtml = '<p class="mb-0 text-center text-dark font-weight-bold" style="font-size: 18px;">' + escapeHtml(sym) + '</p>'
    } else {
        symHtml = '<input type="text" class="form-control detail_spec_symbol" placeholder="Custom symbol..." style="height:40px; font-size:14px; text-align:center; font-weight:bold;" value="' + escapeHtml(sym) + '">'
    }

    var stdHtml
    if (std !== '') {
        stdHtml = '<p class="mb-0 text-center text-success font-weight-bold" style="font-size: 15px;">' + escapeHtml(std) + '</p>'
    } else if (isStandard) {
        stdHtml = '<p class="mb-0 text-center text-muted" style="font-size: 14px;">-</p>'
    } else {
        stdHtml = '<p class="mb-0 text-center text-muted" style="font-size:12px;"><i>custom</i></p>'
    }

    var valInput = '<input type="text" class="form-control detail_spec_value" style="height:40px; font-size:15px;" placeholder="Isi actual value..." data-symbol="' + escapeHtml(sym) + '">'

    var colSym = isStandard ? 'col-4 pl-1 pr-1 text-center' : 'col-5 pl-1 pr-1'
    var colStd = 'col-2 pl-1 pr-1 text-center'
    var colVal = isStandard ? 'col-5 pl-1 pr-1' : 'col-5 pl-1 pr-1'

    return '<div class="row spec-row mt-2 align-items-stretch p-2 rounded border bg-white" data-idx="' + (rowIdx || '') + '" data-sym-static="' + symAttr + '" data-std-static="' + stdAttr + '">' +
        '  <div class="' + colSym + ' d-flex align-items-center justify-content-center" style="min-height:44px;">' + symHtml + '</div>' +
        '  <div class="' + colStd + ' d-flex align-items-center justify-content-center" style="min-height:44px; background:#f0fdf4;">' + stdHtml + '</div>' +
        '  <div class="' + colVal + '">' + valInput + '</div>' +
        '  <div class="col-1 pl-1 pr-1 d-flex align-items-center justify-content-center" style="min-height:44px;">' +
        '    <button type="button" class="btn btn-sm btn-outline-danger btn-del-spec-row" title="Remove this row" style="height:36px;"><i class="fa-solid fa-trash-can"></i></button>' +
        '  </div>' +
        '</div>'
}

function renderDetailSpecRows(product_id) {
    var product = null
    $.each(OPTIONS.products, function (i, v) { if (v.id == product_id) { product = v; return false } })
    var $container = $('#detail_spec_container')
    $container.empty()
    if (!product) {
        $container.html('<div class="text-center text-danger small">Product tidak ditemukan di master.</div>')
        return
    }
    var html = ''
    html += '<div class="row mt-1 mb-2 small font-weight-bold text-uppercase" style="border-bottom: 2px solid #C96868; padding-bottom: 8px;">'
    html += '  <div class="col-4 text-center text-dark">Symbol</div>'
    html += '  <div class="col-2 text-center text-success">Standard <small class="d-block font-weight-normal text-capitalize text-muted">(Master)</small></div>'
    html += '  <div class="col-5 text-center text-primary">Actual Value <small class="d-block font-weight-normal text-capitalize text-muted">(Isi hasil ukur)</small></div>'
    html += '  <div class="col-1 text-center"></div>'
    html += '</div>'

    var specs = product.spesification || []
    if (specs.length === 0) {
        html += '<div class="alert alert-light small mb-2 mt-2" style="margin-top:10px;"><i class="fas fa-info-circle text-info mr-1"></i>Product ini tidak punya spec standard di master. Klik <b>[+ Add Custom]</b> untuk menambah simbol sendiri.</div>'
    } else {
        $.each(specs, function (i, sp) {
            var sym = '', val = ''
            if (typeof sp === 'object') { sym = sp.symbol || ''; val = sp.value || '' }
            else { sym = sp; val = '' }
            html += generateSpecRowHtml(sym, val, false, 'std_' + i)
        })
    }
    $container.html(html)
}

function addCustomSpecRow() {
    var $container = $('#detail_spec_container')
    var hasHeader = $container.find('.row.small.font-weight-bold').length > 0
    if (!hasHeader) {
        var header = '<div class="row mt-1 mb-2 small font-weight-bold text-uppercase" style="border-bottom: 2px solid #C96868; padding-bottom: 8px;">'
        header += '  <div class="col-5 text-center text-dark">Symbol</div>'
        header += '  <div class="col-2 text-center text-success">Standard <small class="d-block font-weight-normal text-capitalize text-muted">(Master)</small></div>'
        header += '  <div class="col-5 text-center text-primary">Actual Value <small class="d-block font-weight-normal text-capitalize text-muted">(Isi hasil ukur)</small></div>'
        header += '  <div class="col-1 text-center"></div>'
        header += '</div>'
        $container.html(header)
    }
    var count = $container.find('.spec-row').length
    $container.append(generateSpecRowHtml('', null, true, 'custom_' + count))
}

function collectDetailSpecs() {
    var arr = []
    $('#detail_spec_container').find('.spec-row').each(function () {
        var $row = $(this)
        var sym = ''
        var val = ($row.find('.detail_spec_value').val() || '').toString().trim()
        var symStatic = $row.attr('data-sym-static') || ''
        if (symStatic !== '') {
            sym = symStatic
        } else {
            var $symInput = $row.find('.detail_spec_symbol')
            if ($symInput.length > 0) sym = ($symInput.val() || '').toString().trim()
        }
        if (sym !== '' || val !== '') {
            arr.push({ symbol: sym, value: val })
        }
    })
    return arr
}

function loadTableHeader() {
    var payload = { from: $('#from').val(), to: $('#to').val() }
    getCallback('getResalesGood', payload, function (resp) {
        swal.close()
        mappingTable(resp.data)
    })
}

function findHeaderByCode(list, code) {
    var header = null
    $.each(list, function (i, h) { if (h.transaction_code === code) { header = h; return false } })
    return header
}

function clearHeaderForm() {
    $('#select_location').val('').trigger('change')
    $('#select_product').val('').trigger('change')
    $('#select_inspector').val('').trigger('change')
    $('#select_supplier').val('').trigger('change')
    $('#select_produsen').val('').trigger('change')
    $('#select_type').val('').trigger('change')
    $('#location_id,#product_id,#inspector_id,#supplier_id,#produsen_id').val('')
    $('#quantity').val(1)
    $('#standard,#certificate,#brand,#packaging').val('')
    $('.location_id_error,.product_id_error,.inspector_id_error,.supplier_id_error,.produsen_id_error,.type_error,.quantity_error').html('')
}

function clearDetailForm() {
    $('#detail_product_display').html('<span class="text-muted">Loading product...</span>')
    $('#detail_product_id').val('')
    $('#detail_transaction_code').val('')
    $('#detail_id').val('')
    $('#addDetailTitle').html('<i class="fas fa-plus mr-1"></i> Add Resales Goods Detail')
    $('#btn_save_detail').html('<i class="fa-solid fa-check"></i> Save Detail')
    $('#detail_spec_container').html('<div class="text-center text-muted small py-3">Loading specification...</div>')
    $('.detail_spesification_error').html('')
    CURRENT_HEADER = null
}

function openEditDetailModal(detailRowRaw) {
    if (!detailRowRaw) return
    clearDetailForm()
    $('#detail_id').val(detailRowRaw.id || '')
    $('#detail_transaction_code').val(detailRowRaw.transaction_code || CURRENT_TRX_CODE)
    CURRENT_TRX_CODE = detailRowRaw.transaction_code || CURRENT_TRX_CODE
    $('#addDetailTitle').html('<i class="fas fa-edit mr-1"></i> Edit Resales Goods Detail #' + (detailRowRaw.id || ''))
    $('#btn_save_detail').html('<i class="fas fa-save mr-1"></i> Update Detail')

    getCallbackNoSwal('getResalesGood', { from: '', to: '' }, function (resp) {
        var header = findHeaderByCode(resp.data, detailRowRaw.transaction_code || CURRENT_TRX_CODE)
        CURRENT_HEADER = header
        if (!header) {
            $('#detail_product_display').html('<span class="text-danger">Header tidak ditemukan</span>')
            $('#detail_spec_container').html('')
            return
        }
        $pr2 = header.productRelation || header.product_relation || null
        var productName = relName($pr2, ['name'], $pr2 && $pr2.size ? ' <small class="text-muted">(' + escapeHtml($pr2.size) + ')</small>' : '') || missingRelation('Product')
        if ($pr2 && $pr2.uom && String($pr2.uom).trim() !== '' && productName.indexOf('ditemukan') === -1) {
            productName += ' &nbsp; <span class="badge badge-secondary ml-1" style="font-size:11px;">' + escapeHtml($pr2.uom) + '</span>'
        }
        $('#detail_product_display').html(productName)
        $('#detail_product_id').val(header.product_id)

        var existingSpecs = detailRowRaw.spesification || []
        var productId = header.product_id
        var product = null
        $.each(OPTIONS.products, function (i, v) { if (v.id == productId) { product = v; return false } })
        var html = ''
        html += '<div class="row mt-1 mb-2 small font-weight-bold text-uppercase" style="border-bottom: 2px solid #C96868; padding-bottom: 8px;">'
        html += '  <div class="col-4 text-center text-dark">Symbol</div>'
        html += '  <div class="col-2 text-center text-success">Standard <small class="d-block font-weight-normal text-capitalize text-muted">(Master)</small></div>'
        html += '  <div class="col-5 text-center text-primary">Actual Value <small class="d-block font-weight-normal text-capitalize text-muted">(Isi hasil ukur)</small></div>'
        html += '  <div class="col-1 text-center"></div>'
        html += '</div>'

        var stdSpecs = (product && product.spesification) ? product.spesification : []

        var existingMap = {}
        $.each(existingSpecs, function (k, sp) {
            if (sp && typeof sp === 'object') {
                var s = (sp.symbol || '').toString().trim()
                if (s) existingMap[s] = (sp.value || '').toString().trim()
            }
        })

        var addedSymbols = {}
        $.each(stdSpecs, function (i, sp) {
            var sym = '', val = ''
            if (typeof sp === 'object') { sym = sp.symbol || ''; val = sp.value || '' }
            else { sym = sp; val = '' }
            sym = (sym || '').toString().trim()
            if (!sym) return
            addedSymbols[sym] = true
            var rowHtml = generateSpecRowHtml(sym, val, false, 'std_' + i)
            html += rowHtml
        })

        $.each(existingSpecs, function (k, sp) {
            var sym = '', val = ''
            if (sp && typeof sp === 'object') { sym = (sp.symbol || '').toString().trim(); val = (sp.value || '').toString().trim() }
            else { sym = (sp || '').toString().trim(); val = '' }
            if (!sym) return
            if (addedSymbols[sym]) return
            addedSymbols[sym] = true
            var rowHtml = generateSpecRowHtml(sym, null, true, 'custom_' + k)
            html += rowHtml
        })

        if (stdSpecs.length === 0 && Object.keys(addedSymbols).length === 0) {
            html += '<div class="alert alert-light small mb-2 mt-2" style="margin-top:10px;"><i class="fas fa-info-circle text-info mr-1"></i>Product ini tidak punya spec standard di master. Klik <b>[+ Add Custom]</b> untuk menambah simbol sendiri.</div>'
        }

        $('#detail_spec_container').html(html)

        setTimeout(function () {
            $.each(Object.keys(existingMap), function (__, sym) {
                var actualVal = existingMap[sym] || ''
                $('#detail_spec_container').find('.spec-row').each(function () {
                    var $row = $(this)
                    var symStatic = $row.attr('data-sym-static') || ''
                    if (symStatic === sym) {
                        var $valInp = $row.find('.detail_spec_value')
                        if ($valInp.length > 0 && actualVal !== '') $valInp.val(actualVal)
                        return false
                    } else if (symStatic === '') {
                        var $symInp = $row.find('.detail_spec_symbol')
                        if ($symInp.length > 0 && ($symInp.val() || '').trim() === sym) {
                            var $valInp2 = $row.find('.detail_spec_value')
                            if ($valInp2.length > 0 && actualVal !== '') $valInp2.val(actualVal)
                            return false
                        }
                    }
                })
            })
        }, 100)

        $('#addResalesGoodDetail').modal('show')
    })
}

$(document).ready(function () {
    loadOptions(function () {
        initSelect2()
        loadTableHeader()
    })

    $('#btnFilter').on('click', function () { loadTableHeader() })

    $('#btn_save_header').on('click', function () {
        var postData = {
            location_id: $('#location_id').val(),
            product_id: $('#product_id').val(),
            inspector_id: $('#inspector_id').val(),
            supplier_id: $('#supplier_id').val(),
            produsen_id: $('#produsen_id').val(),
            type: $('#select_type').val(),
            quantity: $('#quantity').val(),
            standard: $('#standard').val(),
            certificate: $('#certificate').val(),
            brand: $('#brand').val(),
            packaging: $('#packaging').val()
        }
        postCallback('addResalesGoodsHeader', postData, function (response) {
            swal.close()
            toastr['success'](response.meta.message)
            $('#addResalesGoodHeader').modal('hide')
            clearHeaderForm()
            getCallbackNoSwal('getResalesGood', { from: $('#from').val(), to: $('#to').val() }, function (resp) {
                mappingTable(resp.data)
            })
        })
    })

    $(document).on('click', '.btn-info-row', function () {
        var code = $(this).data('code')
        $('#infoTitle').html('<i class="fa-solid fa-circle-info mr-1"></i> Transaction Information - ' + escapeHtml(code))
        $('#infoModalBody').html('<div class="text-center text-muted small py-4">Loading...</div>')
        $('#infoResalesGoodModal').modal('show')
        getCallbackNoSwal('getResalesGood', { from: '', to: '' }, function (resp) {
            var header = findHeaderByCode(resp.data, code)
            if (!header) {
                $('#infoModalBody').html('<div class="alert alert-danger small text-center">Transaction not found.</div>')
                return
            }
            $('#infoModalBody').html(buildHeaderSummaryHtml(header))
        })
    })

    $(document).on('click', '.btn-detail', function () {
        var code = $(this).data('code')
        CURRENT_TRX_CODE = code
        $('#detail_transaction_code').val(code)
        $('#detailTitle').html('Detail - ' + escapeHtml(code))
        $('#detailHeaderInfo').html('Transaction Code: <b class="text-primary">' + escapeHtml(code) + '</b>')
        $('#detailResalesGoodModal').modal('show')
        $('#detailSummaryContainer').html('<div class="col text-muted small text-center py-2">Loading...</div>')
        getCallbackNoSwal('getResalesGood', { from: '', to: '' }, function (resp) {
            var header = findHeaderByCode(resp.data, code)
            if (header) {
                $('#detailSummaryContainer').html(buildHeaderSummaryHtml(header))
            } else {
                $('#detailSummaryContainer').html('<div class="col text-danger small text-center py-2">Header not found.</div>')
            }
        })
        getCallbackNoSwal('getResalesGoodDetails', { transaction_code: code }, function (resp) {
            renderDetailRowsForDetailTable(resp.data)
        })
    })

    $(document).on('click', '.btn-print', function () {
        var code = $(this).data('code')
        if (!code) return
        $('#print_transaction_code').val(code)
        $('#print_trx_name').html(escapeHtml(code))
        getCallbackNoSwal('getResalesGood', { from: '', to: '' }, function (resp) {
            var header = findHeaderByCode(resp.data, code)
            if (header) {
                var pr = header.productRelation || header.product_relation || null
                var pname = pr ? (pr.name + (pr.size ? ' (' + pr.size + ')' : '')) : ''
                $('#print_trx_name').html(escapeHtml(code + (pname ? ' — ' + pname : '')))
                var subTxt = 'Location: '
                subTxt += header.location_relation ? (header.location_relation.name || header.location_relation.initial || '-') : '-'
                subTxt += ' · Inspector: '
                subTxt += header.inspectorRelation ? (header.inspectorRelation.name || '-') : '-'
                subTxt += header.no_pf ? ' · PF: ' + header.no_pf : ''
                $('#print_trx_sub').html(escapeHtml(subTxt))
            }
        })
        $('#printResalesGoodModal').modal('show')
    })

    $(document).on('click', '#btn_print_pdf', function () {
        var code = ($('#print_transaction_code').val() || '').trim()
        if (!code) { toastr['error']('Transaction code missing'); return }
        var url = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '') + '/printResalesGoodsPdf?transaction_code=' + encodeURIComponent(code)
        window.open(url, '_blank')
    })
    $(document).on('click', '#btn_print_excel', function () {
        var code = ($('#print_transaction_code').val() || '').trim()
        if (!code) { toastr['error']('Transaction code missing'); return }
        var url = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '') + '/printResalesGoodsExcel?transaction_code=' + encodeURIComponent(code)
        window.open(url, '_blank')
    })

    $(document).on('click', '.btn-lock', function () {
        var code = $(this).data('code')
        if (!code) return
        $('#lock_transaction_code').val(code)
        $('#lock_no_pf').val('')
        $('#lock_keterangan').val('')
        $('.lock_no_pf_error').html('')
        $('.lock_keterangan_error').html('')
        getCallbackNoSwal('getResalesGood', { from: '', to: '' }, function (resp) {
            var header = findHeaderByCode(resp.data, code)
            if (header) {
                var infoHtml = '<i class="fas fa-exclamation-triangle mr-1"></i> '
                infoHtml += 'Anda akan <b>LOCK</b> transaksi <b class="text-primary">' + escapeHtml(header.transaction_code) + '</b>'
                if (header.productRelation) {
                    infoHtml += ' - ' + relName(header.productRelation, ['name'], header.productRelation.size ? ' (' + escapeHtml(header.productRelation.size) + ')' : '')
                }
                infoHtml += '. Setelah di-lock, status berubah menjadi <b class="text-info">Approval Progress</b> dan tidak bisa di-edit lagi. Silakan isi detail dibawah ini:'
                $('#lock_header_info').html(infoHtml)
            }
        })
        $('#lockResalesGoodModal').modal('show')
    })

    $(document).on('click', '#btn_submit_lock', function () {
        var code = $('#lock_transaction_code').val() || ''
        var noPf = ($('#lock_no_pf').val() || '').trim()
        var keterangan = ($('#lock_keterangan').val() || '').trim()
        var hasErr = false
        if (noPf === '') {
            $('.lock_no_pf_error').html('<i class="fas fa-exclamation-circle mr-1"></i>Nomor PF wajib diisi')
            hasErr = true
        } else {
            $('.lock_no_pf_error').html('')
        }
        if (hasErr) { toastr['error']('Perbaiki data sebelum submit'); return }
        if (code === '') { toastr['error']('Transaction code missing'); return }
        postCallback('lockResalesGoods', { transaction_code: code, no_pf: noPf, keterangan: keterangan }, function (response) {
            swal.close()
            toastr['success'](response.meta.message)
            $('#lockResalesGoodModal').modal('hide')
            getCallbackNoSwal('getResalesGood', { from: $('#from').val(), to: $('#to').val() }, function (resp) {
                mappingTable(resp.data)
            })
            if (CURRENT_TRX_CODE && CURRENT_TRX_CODE === code) {
                getCallbackNoSwal('getResalesGoodDetails', { transaction_code: CURRENT_TRX_CODE }, function (resp) {
                    renderDetailRowsForDetailTable(resp.data)
                })
            }
        })
    })

    $(document).on('click', '.btn-delete', function () {
        if (!confirm('Delete this transaction and ALL its detail rows?\n\nThis action cannot be undone.')) return
        var code = $(this).data('code')
        postCallback('deleteResalesGoods', { transaction_code: code }, function (response) {
            swal.close()
            toastr['success'](response.meta.message)
            getCallbackNoSwal('getResalesGood', { from: $('#from').val(), to: $('#to').val() }, function (resp) {
                mappingTable(resp.data)
            })
        })
    })

    $(document).on('click', '#btn_add_detail_row', function () {
        clearDetailForm()
        // clearDetailForm mengosongkan hidden transaction_code — isi ulang,
        // kalau tidak save detail selalu gagal validasi
        $('#detail_transaction_code').val(CURRENT_TRX_CODE)
        getCallbackNoSwal('getResalesGood', { from: '', to: '' }, function (resp) {
            var header = findHeaderByCode(resp.data, CURRENT_TRX_CODE)
            CURRENT_HEADER = header
            if (!header) {
                $('#detail_product_display').html('<span class="text-danger">Header tidak ditemukan</span>')
                $('#detail_spec_container').html('')
                return
            }
            $pr = header.productRelation || header.product_relation || null
            $sizeSuf = $pr && $pr.size ? ' <small class="text-muted">(' + $pr.size + ')</small>' : ''
            $uomSuf = ''
            if ($pr && $pr.uom && String($pr.uom).trim() !== '') $uomSuf = ' <span class="badge badge-secondary ml-2" style="font-size:11px;">' + $pr.uom + '</span>'
            var productName = relName($pr, ['name'], $sizeSuf) || missingRelation('Product')
            if ($uomSuf && productName.indexOf('ditemukan') === -1) productName = productName + ' &nbsp; ' + $uomSuf
            $('#detail_product_display').html(productName)
            $('#detail_product_id').val(header.product_id)
            renderDetailSpecRows(header.product_id)
        })
    })
    $(document).on('click', '#btn_add_custom_spec', function () { addCustomSpecRow() })

    $(document).on('click', '.btn-del-spec-row', function () {
        var $row = $(this).closest('.spec-row')
        $row.css({ 'transition': 'opacity .2s', opacity: 0 })
        setTimeout(function () { $row.remove() }, 200)
    })

    $(document).on('click', '.btn-edit-detail-row', function () {
        var detailId = $(this).data('id')
        if (!detailId) return
        getCallbackNoSwal('getResalesGoodDetails', { transaction_code: CURRENT_TRX_CODE }, function (resp) {
            var detailRow = null
            $.each(resp.data || [], function (i, d) { if (String(d.id) === String(detailId)) { detailRow = d; return false } })
            if (detailRow) openEditDetailModal(detailRow)
            else toastr['error']('Detail tidak ditemukan')
        })
    })

    $(document).on('click', '.btn-delete-detail-row', function () {
        var detailId = $(this).data('id')
        if (!detailId) return
        if (!confirm('Yakin ingin menghapus detail row ini?')) return
        postCallback('deleteResalesGoodsDetail', { id: detailId }, function (response) {
            swal.close()
            toastr['success'](response.meta.message)
            getCallbackNoSwal('getResalesGood', { from: $('#from').val(), to: $('#to').val() }, function (resp) {
                mappingTable(resp.data)
            })
            getCallbackNoSwal('getResalesGoodDetails', { transaction_code: CURRENT_TRX_CODE }, function (resp) {
                renderDetailRowsForDetailTable(resp.data)
            })
        })
    })

    $('#btn_save_detail').on('click', function () {
        var specs = collectDetailSpecs()
        var detailId = $('#detail_id').val()
        var isEdit = detailId && String(detailId).trim() !== ''
        var postData
        var routeName
        if (isEdit) {
            routeName = 'editResalesGoodsDetail'
            postData = {
                id: detailId,
                transaction_code: $('#detail_transaction_code').val(),
                product_id: $('#detail_product_id').val(),
                spesification: specs
            }
        } else {
            routeName = 'addResalesGoodsDetail'
            postData = {
                transaction_code: $('#detail_transaction_code').val() || CURRENT_TRX_CODE,
                product_id: $('#detail_product_id').val(),
                spesification: specs
            }
        }
        postCallback(routeName, postData, function (response) {
            swal.close()
            toastr['success'](response.meta.message)
            $('#addResalesGoodDetail').modal('hide')
            clearDetailForm()
            getCallbackNoSwal('getResalesGood', { from: $('#from').val(), to: $('#to').val() }, function (resp) {
                mappingTable(resp.data)
            })
            getCallbackNoSwal('getResalesGoodDetails', { transaction_code: CURRENT_TRX_CODE }, function (resp) {
                renderDetailRowsForDetailTable(resp.data)
            })
        })
    })
})
