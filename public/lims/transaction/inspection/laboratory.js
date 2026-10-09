var laboratoryOptions = { products: [], locations: [], machines: [], users: [] }
var laboratoryCurrentHeader = null
var laboratoryActivePane = null

loadLaboratoryOptions()
loadLaboratoryTable()


function laboratoryFilterData() {
    return {
        from: $('#laboratory_filter_from').val(),
        to: $('#laboratory_filter_to').val(),
        status: $('#laboratory_filter_status').val()
    }
}

function loadLaboratoryOptions() {
    getCallbackNoSwal('getLaboratoryOptions', null, function (response) {
        laboratoryOptions = response.data || { products: [], locations: [], machines: [], users: [] }
        populateLaboratoryHeaderOptions()
    })
}

function populateLaboratoryHeaderOptions() {
    var productHtml = '<option value="">Choose Master Product</option>'
    laboratoryOptions.products.forEach(function (product) {
        productHtml += '<option value="' + product.id + '">' + laboratoryEscape(product.seri + ' · ' + product.ukuran + ' · ' + product.marking) + '</option>'
    })
    $('#laboratory_add_product_id').html(productHtml)

    var locationHtml = '<option value="">Choose Location</option>'
    laboratoryOptions.locations.forEach(function (location) {
        locationHtml += '<option value="' + location.id + '">' + laboratoryEscape(location.name) + '</option>'
    })
    $('#laboratory_add_location_id').html(locationHtml)

    initLaboratorySelect2('#laboratory_add_product_id, #laboratory_add_product_method_id, #laboratory_add_location_id, #laboratory_add_machine_id, #laboratory_add_shift, #laboratory_add_product_type', {
        width: '100%',
        dropdownParent: $('#addLaboratoryModal')
    })
    populateLaboratoryMachines('')
}

function populateLaboratoryMachines(locationId) {
    var html = '<option value="">No Machine</option>'
    laboratoryOptions.machines.filter(function (machine) {
        return !locationId || parseInt(machine.location_id) === parseInt(locationId)
    }).forEach(function (machine) {
        html += '<option value="' + machine.id + '">' + laboratoryEscape(machine.no_machine + ' · ' + machine.name) + '</option>'
    })
    $('#laboratory_add_machine_id').html(html).val('').trigger('change')
}

function loadLaboratoryTable() {
    getCallback('getProductLaboratory', laboratoryFilterData(), function (response) {
        swal.close()
        mapLaboratoryTable(response.data || [])
    })
}

function mapLaboratoryTable(data) {
    $('#laboratory_table').DataTable({
        data: data,
        destroy: true,
        scrollX: true,
        order: [[0, 'desc']],
        columns: [
            { data: 'test_date', className: 'text-center' },
            { data: 'laboratory_code', render: function (value) { return '<b class="text-mainCore">' + laboratoryEscape(value) + '</b>' } },
            { data: 'product_relation', render: function (product) { return product ? laboratoryEscape(product.seri + ' · ' + product.ukuran) : '-' } },
            { data: 'product_method_relation', render: function (mapping) { return mapping && mapping.method_relation ? laboratoryEscape(mapping.method_relation.code) : '-' } },
            { data: 'location_relation', render: function (location) { return location ? laboratoryEscape(location.name) : '-' } },
            { data: 'status', className: 'text-center', render: function (status) { return laboratoryStatus(status) } },
            { data: 'approval_user_relation', render: function (user) { return user ? laboratoryEscape(user.name) : '-' } },
            { data: 'laboratory_code', className: 'text-center', orderable: false, render: function (code, type, row) {
                var buttons = '<button type="button" class="btn btn-sm btn-info open-laboratory-detail" data-code="' + laboratoryEscape(code) + '" title="Open laboratory detail"><i class="fa-solid fa-eye"></i></button>'
                if ([2, 3].indexOf(parseInt(row.status)) !== -1) {
                    buttons += ' <button type="button" class="btn btn-sm btn-outline-success laboratory-export-excel" data-code="' + laboratoryEscape(code) + '" title="Download Excel report"><i class="fa-solid fa-file-excel"></i></button>'
                    buttons += ' <button type="button" class="btn btn-sm btn-outline-danger laboratory-export-pdf" data-code="' + laboratoryEscape(code) + '" title="Open PDF report"><i class="fa-solid fa-file-pdf"></i></button>'
                }
                return buttons
            } }
        ]
    }).columns.adjust()
}

$('#btn_filter_laboratory').on('click', loadLaboratoryTable)

$('#btn_add_laboratory').on('click', function () {
    $('#addLaboratoryModal .text-danger').html('')
    $('#laboratory_add_product_id, #laboratory_add_product_method_id, #laboratory_add_location_id, #laboratory_add_machine_id').val('').trigger('change')
    $('#laboratory_add_report_no, #laboratory_add_production_code, #laboratory_add_material_lot, #laboratory_add_temperature, #laboratory_add_humidity').val('')
    $('#laboratory_add_product_type').val('').trigger('change')
    $('#laboratory_add_shift').val('1').trigger('change')
    $('#laboratory_add_test_date').val(laboratoryLocalDate())
    populateLaboratoryHeaderOptions()
})

$('#laboratory_add_product_id').on('change', function () {
    var productId = $(this).val()
    $('#laboratory_add_product_method_id').html('<option value="">Choose Test Method</option>').trigger('change')
    if (!productId) return
    getCallbackNoSwal('getActiveLaboratoryProductMethods', { product_id: productId }, function (response) {
        var html = '<option value="">Choose Test Method</option>'
        ;(response.data || []).forEach(function (mapping) {
            if (!mapping.method_relation) return
            html += '<option value="' + mapping.id + '">' + laboratoryEscape(mapping.method_relation.code + ' · ' + mapping.method_relation.name) + '</option>'
        })
        $('#laboratory_add_product_method_id').html(html).trigger('change')
    })
})

$('#laboratory_add_location_id').on('change', function () {
    populateLaboratoryMachines($(this).val())
})

$('#btn_save_laboratory_header').on('click', function () {
    var data = {
        product_id: $('#laboratory_add_product_id').val(),
        product_method_id: $('#laboratory_add_product_method_id').val(),
        test_date: $('#laboratory_add_test_date').val(),
        report_no: $('#laboratory_add_report_no').val(),
        production_code_number: $('#laboratory_add_production_code').val(),
        material_lot_number: $('#laboratory_add_material_lot').val(),
        location_id: $('#laboratory_add_location_id').val(),
        shift: $('#laboratory_add_shift').val(),
        machine_id: $('#laboratory_add_machine_id').val(),
        temperature: $('#laboratory_add_temperature').val(),
        humidity: $('#laboratory_add_humidity').val(),
        product_type: $('#laboratory_add_product_type').val()
    }
    postCallback('addProductLaboratory', data, function (response) {
        swal.close()
        toastr['success'](response.meta.message)
        $('#addLaboratoryModal').modal('hide')
        getCallbackNoSwal('getProductLaboratory', laboratoryFilterData(), function (result) {
            mapLaboratoryTable(result.data || [])
        })
    })
})

$(document).on('click', '.open-laboratory-detail', function () {
    openLaboratoryDetail($(this).data('code'))
})

function openLaboratoryDetail(code) {
    laboratoryActivePane = null
    getCallback('getProductLaboratoryDetail', { laboratory_code: code }, function (response) {
        swal.close()
        laboratoryCurrentHeader = response.data
        $('#detail_laboratory_code').val(code)
        $('#detailLaboratoryModalTitle').text('Laboratory Test · ' + code)
        renderLaboratorySummary(laboratoryCurrentHeader)
        renderLaboratoryResults(laboratoryCurrentHeader)
        renderLaboratoryHistory(response.history || [])
        $('#detailLaboratoryModal').modal('show')
    })
}

function renderLaboratorySummary(header) {
    var product = header.product_relation || {}
    var method = header.method_snapshot || {}
    var location = header.location_relation || {}
    var values = {
        product: (product.seri || '-') + ' \u00b7 ' + (product.ukuran || '-'),
        method: (method.code || '-') + ' \u00b7 ' + (method.name || '-'),
        test_date: header.test_date || '-',
        location: (location.name || '-') + ' \u00b7 Shift ' + (header.shift || '-'),
        production_code: header.production_code_number || '-',
        material_lot: header.material_lot_number || '-',
        report_no: header.report_no || '-'
    }
    Object.keys(values).forEach(function (key) {
        $('#label_laboratory_' + key).html(': ' + laboratoryEscape(values[key]))
    })
    $('#laboratory_detail_status').html(laboratoryStatus(header.status))
    $('#laboratory_comment').val(header.comment || '')

    // the printed report only exists once the test has left the lab
    var printable = [2, 3].indexOf(parseInt(header.status)) !== -1
    $('#detailLaboratoryModal .laboratory-export-pdf, #detailLaboratoryModal .laboratory-export-excel').toggle(printable)

    var editable = !parseInt(header.lock) && [0, 1, 4].indexOf(parseInt(header.status)) !== -1
    $('#laboratory_edit_state').text(editable ? 'Results are editable.' : 'This transaction is locked.')
    $('#btn_lock_laboratory').toggle(editable && [1, 4].indexOf(parseInt(header.status)) !== -1)
    $('#laboratory_comment').prop('disabled', !editable)
    $('#btn_save_laboratory_comment').toggle(editable)
}

function renderLaboratoryResults(header) {
    var editable = !parseInt(header.lock) && [0, 1, 4].indexOf(parseInt(header.status)) !== -1
    var details = header.details_relation || []
    if (!details.length) {
        $('#laboratory_param_nav').html('')
        $('#laboratory_param_progress').text('')
        $('#laboratory_result_container').html('<div class="text-muted text-center py-4">No test parameter was generated.</div>')
        return
    }

    var navHtml = ''
    var paneHtml = ''
    var evaluated = 0
    details.forEach(function (detail) {
        if (detail.evaluation) evaluated++
        var dot = detail.evaluation === 'GOOD' ? ' is-good' : (detail.evaluation ? ' is-nogood' : '')
        var name = laboratoryEscape(detail.parameter_name_snapshot)
        navHtml += '<div class="laboratory-param-link" data-pane="' + detail.id + '">' +
            '<span class="laboratory-param-dot' + dot + '"></span>' +
            '<span class="laboratory-param-seq">' + detail.sequence + '</span>' +
            '<span class="laboratory-param-name" title="' + name + '">' + name + (detail.is_required_snapshot ? ' <span class="text-danger">*</span>' : '') + '</span>' +
            '</div>'

        var evaluation = detail.evaluation ? laboratoryEvaluation(detail.evaluation) : '<span class="badge badge-light">Not evaluated</span>'
        paneHtml += '<div class="laboratory-param-pane" data-pane="' + detail.id + '" style="display:none">' +
            '<div class="laboratory-result-item" data-detail-id="' + detail.id + '" data-result-type="' + detail.result_type_snapshot + '">' +
            '<div class="laboratory-result-head"><div><b>' + detail.sequence + '. ' + name + '</b> ' + (detail.is_required_snapshot ? '<span class="text-danger">*</span>' : '') +
            '<div class="mt-1">' + evaluation + '</div></div><div class="laboratory-standard">Standard<br>' + laboratoryEscape(formatLaboratoryStandard(detail.standard_snapshot || {}, detail.unit_snapshot)) + '</div></div>' +
            '<div class="laboratory-result-body">' + renderLaboratoryResultInput(detail, editable) + '</div></div></div>'
    })

    navHtml += '<div class="laboratory-param-divider"></div>' +
        '<div class="laboratory-param-link" data-pane="comment">' +
        '<span class="laboratory-param-dot"></span>' +
        '<span class="laboratory-param-seq"><i class="fa-solid fa-comment-dots"></i></span>' +
        '<span class="laboratory-param-name">Comment</span></div>' +
        '<div class="laboratory-param-link" data-pane="history">' +
        '<span class="laboratory-param-dot"></span>' +
        '<span class="laboratory-param-seq"><i class="fa-solid fa-clock-rotate-left"></i></span>' +
        '<span class="laboratory-param-name">Approval History</span></div>'

    $('#laboratory_param_progress').text(evaluated + ' / ' + details.length + ' evaluated')
    $('#laboratory_param_nav').html(navHtml)
    $('#laboratory_result_container').html(paneHtml)
    $('#laboratory_result_container').find('.laboratory-in-charge, .laboratory-checker, .laboratory-boolean-result').select2({
        width: '100%',
        dropdownParent: $('#detailLaboratoryModal')
    })

    var target = laboratoryActivePane
    if (target === null || !$('#detailLaboratoryModal .laboratory-param-pane[data-pane="' + target + '"]').length) target = details[0].id
    activateLaboratoryPane(target)
}

function activateLaboratoryPane(pane) {
    laboratoryActivePane = pane
    $('#detailLaboratoryModal .laboratory-param-pane').hide()
    $('#detailLaboratoryModal .laboratory-param-pane[data-pane="' + pane + '"]').show()
    $('#laboratory_param_nav .laboratory-param-link').removeClass('active')
    $('#laboratory_param_nav .laboratory-param-link[data-pane="' + pane + '"]').addClass('active')
    $('.laboratory-param-panel').scrollTop(0)
}

$(document).on('click', '#laboratory_param_nav .laboratory-param-link', function () {
    activateLaboratoryPane($(this).data('pane'))
})

function renderLaboratoryResultInput(detail, editable) {
    var disabled = editable ? '' : 'disabled'
    var resultInput = ''
    if (detail.result_type_snapshot === 'numeric') {
        var values = {}
        ;(detail.measurements_relation || []).forEach(function (measurement) { values[measurement.sequence] = measurement.value })
        var count = parseInt(detail.measurement_count_snapshot || 1)
        var inputs = ''
        for (var i = 1; i <= count; i++) {
            inputs += '<div><p for="">Measurement ' + i + '</p><input type="number" step="any" class="form-control laboratory-measurement" data-sequence="' + i + '" value="' + laboratoryEscape(values[i] === undefined || values[i] === null ? '' : values[i]) + '" ' + disabled + '></div>'
        }
        resultInput = '<div class="laboratory-measurement-wrap mb-3"><div class="laboratory-measurement-grid">' + inputs + '</div></div>'
    } else if (detail.result_type_snapshot === 'boolean') {
        var booleanValue = detail.boolean_result === null ? '' : (detail.boolean_result ? '1' : '0')
        resultInput = '<div><p for="">Result</p><select class="form-control laboratory-boolean-result" ' + disabled + '><option value="">Choose Result</option><option value="1" ' + (booleanValue === '1' ? 'selected' : '') + '>Yes / Pass</option><option value="0" ' + (booleanValue === '0' ? 'selected' : '') + '>No / Fail</option></select></div>'
    } else {
        resultInput = '<div><p for="">Observation / Result</p><textarea class="form-control laboratory-text-result" rows="2" ' + disabled + '>' + laboratoryEscape(detail.text_result || '') + '</textarea></div>'
    }

    var userOptions = '<option value="">Choose User</option>'
    laboratoryOptions.users.forEach(function (user) {
        userOptions += '<option value="' + user.id + '">' + laboratoryEscape(user.name) + '</option>'
    })
    var meta = '<div class="laboratory-result-grid mt-3">' +
        '<div><p for="">In Charge</p><select class="form-control laboratory-in-charge" ' + disabled + '>' + selectedLaboratoryOptions(userOptions, detail.in_charge_user_id) + '</select></div>' +
        '<div><p for="">Checker</p><select class="form-control laboratory-checker" ' + disabled + '>' + selectedLaboratoryOptions(userOptions, detail.checker_user_id) + '</select></div>' +
        '<div><p for="">Calculated Result</p><input class="form-control" value="' + laboratoryEscape(detail.result_value === null ? '-' : detail.result_value + (detail.unit_snapshot ? ' ' + detail.unit_snapshot : '')) + '" disabled></div>' +
        '<div><p for="">Remark</p><input type="text" class="form-control laboratory-remark" value="' + laboratoryEscape(detail.remark || '') + '" ' + disabled + '></div></div>'
    var action = editable ? '<div class="text-right mt-3"><button type="button" class="btn btn-sm btn-success save-laboratory-result"><i class="fa-solid fa-check mr-1"></i> Save Result</button></div>' : ''
    return resultInput + meta + action
}

$(document).on('click', '.save-laboratory-result', function () {
    var item = $(this).closest('.laboratory-result-item')
    var type = item.data('result-type')
    var measurements = []
    item.find('.laboratory-measurement').each(function () { measurements.push($(this).val()) })
    var data = {
        detail_id: item.data('detail-id'),
        measurements: type === 'numeric' ? measurements : null,
        text_result: type === 'text' ? item.find('.laboratory-text-result').val() : null,
        boolean_result: type === 'boolean' ? item.find('.laboratory-boolean-result').val() : null,
        in_charge_user_id: item.find('.laboratory-in-charge').val(),
        checker_user_id: item.find('.laboratory-checker').val(),
        remark: item.find('.laboratory-remark').val()
    }
    postCallback('saveLaboratoryMeasurement', data, function (response) {
        swal.close()
        toastr['success'](response.meta.message)
        getCallbackNoSwal('getProductLaboratoryDetail', { laboratory_code: $('#detail_laboratory_code').val() }, function (result) {
            laboratoryCurrentHeader = result.data
            renderLaboratorySummary(result.data)
            renderLaboratoryResults(result.data)
            renderLaboratoryHistory(result.history || [])
            loadLaboratoryTableNoSwal()
        })
    })
})

$('#btn_lock_laboratory').on('click', function () {
    var code = $('#detail_laboratory_code').val()
    Swal.fire({
        title: 'Submit laboratory test?',
        text: 'Results will be locked and sent to the configured approver.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit'
    }).then(function (result) {
        if (!result.isConfirmed) return
        postCallback('lockProductLaboratory', { laboratory_code: code }, function (response) {
            swal.close()
            toastr['success'](response.meta.message)
            $('#detailLaboratoryModal').modal('hide')
            getCallbackNoSwal('getProductLaboratory', laboratoryFilterData(), function (tableResponse) {
                mapLaboratoryTable(tableResponse.data || [])
            })
        })
    })
})

function loadLaboratoryTableNoSwal() {
    getCallbackNoSwal('getProductLaboratory', laboratoryFilterData(), function (response) {
        mapLaboratoryTable(response.data || [])
    })
}

function renderLaboratoryHistory(history) {
    if (!history.length) {
        $('#laboratory_history_container').html('<div class="text-muted text-center py-4">No approval history.</div>')
        return
    }
    var html = ''
    history.forEach(function (item) {
        var status = parseInt(item.status) === 0 ? 'Waiting' : (parseInt(item.status) === 1 ? 'Approved' : 'Revision')
        html += '<div class="laboratory-history-item"><div class="d-flex justify-content-between"><b>Step ' + item.step + ' · ' + laboratoryEscape(item.user_relation ? item.user_relation.name : '-') + '</b><span>' + status + '</span></div><div class="text-muted mt-1">' + laboratoryEscape(item.remark || '-') + '</div></div>'
    })
    $('#laboratory_history_container').html(html)
}

function formatLaboratoryStandard(standard, unit) {
    var suffix = unit ? ' ' + unit : ''
    if (standard.expected_value !== null && standard.expected_value !== undefined && standard.expected_value !== '') return '= ' + standard.expected_value
    if (standard.operator === 'BETWEEN') return standard.min_value + ' – ' + standard.max_value + suffix
    if (standard.operator === '>=') return '≥ ' + standard.min_value + suffix
    if (standard.operator === '<=') return '≤ ' + standard.max_value + suffix
    if (standard.operator === '=') return '= ' + standard.target_value + suffix
    return 'Observation only'
}

function laboratoryStatus(status) {
    status = parseInt(status)
    if (status === 0) return '<span class="laboratory-status badge-secondary"><i class="fa-solid fa-file"></i> Draft</span>'
    if (status === 1) return '<span class="laboratory-status badge-primary"><i class="fa-solid fa-flask"></i> In Progress</span>'
    if (status === 2) return '<span class="laboratory-status badge-info"><i class="fa-solid fa-hourglass-half"></i> Approval</span>'
    if (status === 3) return '<span class="laboratory-status badge-success"><i class="fa-solid fa-circle-check"></i> Done</span>'
    return '<span class="laboratory-status badge-danger"><i class="fa-solid fa-rotate-left"></i> Revision</span>'
}

function laboratoryEvaluation(evaluation) {
    return evaluation === 'GOOD' ? '<span class="badge badge-success">GOOD</span>' : '<span class="badge badge-danger">NO GOOD</span>'
}

function selectedLaboratoryOptions(html, selected) {
    if (!selected) return html
    return html.replace('value="' + selected + '"', 'value="' + selected + '" selected')
}

function laboratoryEscape(value) {
    // escapeHtml (helper.js) juga meng-escape tanda kutip — wajib karena nilai
    // disuntik ke atribut title="..."/value="..."/data-*
    return window.escapeHtml ? window.escapeHtml(value) : $('<div>').text(value === null || value === undefined ? '' : value).html()
}

function laboratoryLocalDate() {
    var today = new Date()
    var month = String(today.getMonth() + 1).padStart(2, '0')
    var day = String(today.getDate()).padStart(2, '0')
    return today.getFullYear() + '-' + month + '-' + day
}

$('#btn_save_laboratory_comment').on('click', function () {
    postCallback('saveLaboratoryComment', {
        laboratory_code: $('#detail_laboratory_code').val(),
        comment: $('#laboratory_comment').val()
    }, function (response) {
        swal.close()
        toastr['success'](response.meta.message)
    })
})

function laboratoryReportCode(element) {
    return $(element).data('code') || $('#detail_laboratory_code').val()
}

$(document).on('click', '.laboratory-export-pdf', function () {
    window.open('printLaboratoryPdf?laboratory_code=' + encodeURIComponent(laboratoryReportCode(this)), '_blank')
})

$(document).on('click', '.laboratory-export-excel', function () {
    window.location = 'exportLaboratoryExcel?laboratory_code=' + encodeURIComponent(laboratoryReportCode(this))
})

/**
 * The layout sweeps $(".select2") twice -- once inline and once on ready --
 * and that sweep also matches the container span select2 itself creates. Any
 * init that runs between the two sweeps ends up with a second, empty widget on
 * top of the real one, so initialise after ready and destroy first.
 */
function initLaboratorySelect2(selector, options) {
    $(selector).each(function () {
        if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).select2('destroy')
        }
    })
    $(selector).select2(options)
}

$(function () {
    initLaboratorySelect2('#laboratory_filter_status', { width: '145px' })
})
