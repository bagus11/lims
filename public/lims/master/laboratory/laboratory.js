var laboratoryMethods = []
var laboratoryParameters = []
var laboratoryProducts = []
var selectedProductMethods = []

loadLaboratoryMaster()

function loadLaboratoryMaster() {
    getCallback('getLaboratoryMasterOptions', null, function (response) {
        swal.close()
        laboratoryProducts = response.data.products || []
        laboratoryMethods = response.data.methods || []
        laboratoryParameters = response.data.parameters || []
        populateLaboratoryOptions()
        loadLaboratoryMethods()
        loadLaboratoryParameters()
    })
}

function populateLaboratoryOptions() {
    var productOptions = '<option value="">Choose Master Product</option>'
    laboratoryProducts.forEach(function (product) {
        productOptions += '<option value="' + product.id + '">' + escapeLaboratoryHtml(product.seri + ' · ' + product.ukuran + ' · ' + product.marking) + '</option>'
    })
    $('#laboratory_product_id').html(productOptions).select2({ width: '100%' })

    var methodOptions = ''
    laboratoryMethods.filter(function (method) { return parseInt(method.status) === 1 }).forEach(function (method) {
        methodOptions += '<option value="' + method.id + '">' + escapeLaboratoryHtml(method.code + ' · ' + method.name) + '</option>'
    })
    $('#laboratory_method_ids').html(methodOptions).select2({ width: '100%', placeholder: 'Choose Test Methods' })
}

function loadLaboratoryMethods() {
    getCallbackNoSwal('getLaboratoryTestMethods', null, function (response) {
        laboratoryMethods = response.data || []
        $('#laboratory_method_table').DataTable({
            data: laboratoryMethods,
            destroy: true,
            responsive: true,
            order: [[0, 'asc']],
            columns: [
                { data: 'code', render: function (data) { return '<span class="laboratory-code">' + escapeLaboratoryHtml(data) + '</span>' } },
                { data: 'name', render: function (data) { return escapeLaboratoryHtml(data) } },
                { data: 'description', defaultContent: '-', render: function (data) { return escapeLaboratoryHtml(data || '-') } },
                { data: 'status', className: 'text-center', render: function (data) { return statusLaboratoryBadge(data) } },
                { data: 'id', className: 'text-center', orderable: false, render: function (data) {
                    return '<button type="button" class="btn btn-sm btn-warning edit-laboratory-method" data-id="' + data + '" data-toggle="modal" data-target="#laboratoryMethodModal" title="Edit method"><i class="fa-solid fa-pen"></i></button>'
                } }
            ]
        })
        populateLaboratoryOptions()
    })
}

function loadLaboratoryParameters() {
    getCallbackNoSwal('getLaboratoryTestParameters', null, function (response) {
        laboratoryParameters = response.data || []
        $('#laboratory_parameter_table').DataTable({
            data: laboratoryParameters,
            destroy: true,
            responsive: true,
            order: [[0, 'asc']],
            columns: [
                { data: 'code', render: function (data) { return '<span class="laboratory-code">' + escapeLaboratoryHtml(data) + '</span>' } },
                { data: 'name', render: function (data) { return escapeLaboratoryHtml(data) } },
                { data: 'result_type', className: 'text-center', render: function (data) { return '<span class="badge badge-info text-uppercase">' + escapeLaboratoryHtml(data) + '</span>' } },
                { data: 'default_unit', defaultContent: '-' },
                { data: 'status', className: 'text-center', render: function (data) { return statusLaboratoryBadge(data) } },
                { data: 'id', className: 'text-center', orderable: false, render: function (data) {
                    return '<button type="button" class="btn btn-sm btn-warning edit-laboratory-parameter" data-id="' + data + '" data-toggle="modal" data-target="#laboratoryParameterModal" title="Edit parameter"><i class="fa-solid fa-pen"></i></button>'
                } }
            ]
        })
    })
}

$('#btn_add_laboratory_method').on('click', function () {
    $('#laboratory_method_mode').val('add')
    $('#laboratoryMethodModalTitle').text('Add Test Method')
    $('#laboratory_method_edit_id, #laboratory_method_code, #laboratory_method_name, #laboratory_method_description').val('')
    $('#laboratory_method_status').val('1').trigger('change')
    $('.laboratory-method-status').hide()
    $('#laboratoryMethodModal .text-danger').html('')
})

$(document).on('click', '.edit-laboratory-method', function () {
    var id = parseInt($(this).data('id'))
    var method = laboratoryMethods.find(function (item) { return parseInt(item.id) === id })
    if (!method) return
    $('#laboratory_method_mode').val('edit')
    $('#laboratoryMethodModalTitle').text('Edit Test Method')
    $('#laboratory_method_edit_id').val(method.id)
    $('#laboratory_method_code').val(method.code)
    $('#laboratory_method_name').val(method.name)
    $('#laboratory_method_description').val(method.description || '')
    $('#laboratory_method_status').val(String(method.status)).trigger('change')
    $('.laboratory-method-status').show()
    $('#laboratoryMethodModal .text-danger').html('')
})

$('#btn_save_laboratory_method').on('click', function () {
    var editMode = $('#laboratory_method_mode').val() === 'edit'
    var data = {
        id: $('#laboratory_method_edit_id').val(),
        code: $('#laboratory_method_code').val(),
        name: $('#laboratory_method_name').val(),
        description: $('#laboratory_method_description').val(),
        status: $('#laboratory_method_status').val()
    }
    postCallback(editMode ? 'updateLaboratoryTestMethod' : 'addLaboratoryTestMethod', data, function (response) {
        swal.close()
        toastr['success'](response.meta.message)
        $('#laboratoryMethodModal').modal('hide')
        getCallbackNoSwal('getLaboratoryTestMethods', null, function (result) {
            laboratoryMethods = result.data || []
            loadLaboratoryMethods()
        })
    })
})

$('#btn_add_laboratory_parameter').on('click', function () {
    $('#laboratory_parameter_mode').val('add')
    $('#laboratoryParameterModalTitle').text('Add Test Parameter')
    $('#laboratory_parameter_edit_id, #laboratory_parameter_code, #laboratory_parameter_name, #laboratory_parameter_default_unit, #laboratory_parameter_description').val('')
    $('#laboratory_parameter_result_type').val('numeric').trigger('change')
    $('#laboratory_parameter_status').val('1').trigger('change')
    $('.laboratory-parameter-status').hide()
    $('#laboratoryParameterModal .text-danger').html('')
})

$(document).on('click', '.edit-laboratory-parameter', function () {
    var id = parseInt($(this).data('id'))
    var parameter = laboratoryParameters.find(function (item) { return parseInt(item.id) === id })
    if (!parameter) return
    $('#laboratory_parameter_mode').val('edit')
    $('#laboratoryParameterModalTitle').text('Edit Test Parameter')
    $('#laboratory_parameter_edit_id').val(parameter.id)
    $('#laboratory_parameter_code').val(parameter.code)
    $('#laboratory_parameter_name').val(parameter.name)
    $('#laboratory_parameter_result_type').val(parameter.result_type).trigger('change')
    $('#laboratory_parameter_default_unit').val(parameter.default_unit || '')
    $('#laboratory_parameter_description').val(parameter.description || '')
    $('#laboratory_parameter_status').val(String(parameter.status)).trigger('change')
    $('.laboratory-parameter-status').show()
    $('#laboratoryParameterModal .text-danger').html('')
})

$('#btn_save_laboratory_parameter').on('click', function () {
    var editMode = $('#laboratory_parameter_mode').val() === 'edit'
    var data = {
        id: $('#laboratory_parameter_edit_id').val(),
        code: $('#laboratory_parameter_code').val(),
        name: $('#laboratory_parameter_name').val(),
        result_type: $('#laboratory_parameter_result_type').val(),
        default_unit: $('#laboratory_parameter_default_unit').val(),
        description: $('#laboratory_parameter_description').val(),
        status: $('#laboratory_parameter_status').val()
    }
    postCallback(editMode ? 'updateLaboratoryTestParameter' : 'addLaboratoryTestParameter', data, function (response) {
        swal.close()
        toastr['success'](response.meta.message)
        $('#laboratoryParameterModal').modal('hide')
        getCallbackNoSwal('getLaboratoryTestParameters', null, function (result) {
            laboratoryParameters = result.data || []
            loadLaboratoryParameters()
            renderParameterConfiguration([])
        })
    })
})

$('#laboratory_product_id').on('change', function () {
    loadProductMethodConfiguration($(this).val())
})

function loadProductMethodConfiguration(productId) {
    $('#laboratory_product_method_id').val('')
    $('#btn_save_method_parameters').prop('disabled', true)
    $('#laboratory_parameter_configuration').html('<div class="laboratory-empty">Choose a configured method.</div>')
    if (!productId) {
        $('#laboratory_method_ids').val([]).trigger('change')
        $('#configured_method_table tbody').html('<tr><td colspan="2" class="laboratory-empty">Select a product first.</td></tr>')
        return
    }

    getCallback('getLaboratoryProductMethods', { product_id: productId }, function (response) {
        swal.close()
        selectedProductMethods = response.data || []
        var activeMethods = selectedProductMethods.filter(function (item) { return parseInt(item.status) === 1 })
        $('#laboratory_method_ids').val(activeMethods.map(function (item) { return String(item.test_method_id) })).trigger('change')
        mapConfiguredMethods(activeMethods)
    })
}

function mapConfiguredMethods(methods) {
    $('#configured_method_count').text(methods.length)
    if (!methods.length) {
        $('#configured_method_table tbody').html('<tr><td colspan="2" class="laboratory-empty">No active method assigned.</td></tr>')
        return
    }
    var html = ''
    methods.forEach(function (item) {
        var method = item.method_relation || {}
        html += '<tr><td><span class="laboratory-code">' + escapeLaboratoryHtml(method.code || '-') + '</span><br><small>' + escapeLaboratoryHtml(method.name || '') + '</small></td>' +
            '<td class="text-center"><button type="button" class="btn btn-sm btn-info configure-laboratory-parameters" data-id="' + item.id + '" data-name="' + escapeLaboratoryHtml(method.code || '') + '" title="Configure parameters"><i class="fa-solid fa-sliders"></i></button></td></tr>'
    })
    $('#configured_method_table tbody').html(html)
}

$('#btn_save_product_methods').on('click', function () {
    var productId = $('#laboratory_product_id').val()
    var methodIds = $('#laboratory_method_ids').val() || []
    if (!productId || !methodIds.length) {
        toastr['warning']('Choose a product and at least one test method.')
        return
    }
    postCallback('saveLaboratoryProductMethods', { product_id: productId, test_method_ids: methodIds }, function (response) {
        swal.close()
        toastr['success'](response.meta.message)
        $('#product-configuration-panel').addClass('show active')
        getCallbackNoSwal('getLaboratoryProductMethods', { product_id: productId }, function (result) {
            selectedProductMethods = result.data || []
            mapConfiguredMethods(selectedProductMethods.filter(function (item) { return parseInt(item.status) === 1 }))
        })
    })
})

$(document).on('click', '.configure-laboratory-parameters', function () {
    var productMethodId = $(this).data('id')
    $('#laboratory_product_method_id').val(productMethodId)
    $('#parameter_configuration_title').text('Parameter Configuration · ' + $(this).data('name'))
    $('#parameter_configuration_hint').text('Enable parameters and define their source, rule, measurements, and calculation.')
    getCallback('getLaboratoryMethodParameters', { product_method_id: productMethodId }, function (response) {
        swal.close()
        renderParameterConfiguration(response.data || [])
        $('#btn_save_method_parameters').prop('disabled', false)
    })
})

function renderParameterConfiguration(configurations) {
    var activeParameters = laboratoryParameters.filter(function (parameter) { return parseInt(parameter.status) === 1 })
    if (!activeParameters.length) {
        $('#laboratory_parameter_configuration').html('<div class="laboratory-empty">Create an active test parameter first.</div>')
        return
    }

    var configByParameter = {}
    configurations.forEach(function (item) { configByParameter[item.test_parameter_id] = item })

    // show the parameters in the order they are actually tested, not by code
    activeParameters = activeParameters.slice().sort(function (a, b) {
        var configA = configByParameter[a.id]
        var configB = configByParameter[b.id]
        var sequenceA = configA && configA.sequence ? parseInt(configA.sequence) : 9999
        var sequenceB = configB && configB.sequence ? parseInt(configB.sequence) : 9999
        if (sequenceA !== sequenceB) return sequenceA - sequenceB
        return String(a.code).localeCompare(String(b.code))
    })

    var html = ''
    activeParameters.forEach(function (parameter, index) {
        var config = configByParameter[parameter.id] || {}
        var enabled = config.id && parseInt(config.status) === 1
        var sourceType = config.source_type || 'STATIC'
        var operator = config.operator || (parameter.result_type === 'numeric' ? 'BETWEEN' : '=')
        html += '<div class="laboratory-config-row ' + (enabled ? '' : 'is-disabled') + '" data-parameter-id="' + parameter.id + '" data-result-type="' + parameter.result_type + '">' +
            '<div class="laboratory-config-head"><div><div class="custom-control custom-checkbox">' +
            '<input type="checkbox" class="custom-control-input laboratory-config-enabled" id="laboratory_config_' + parameter.id + '" ' + (enabled ? 'checked' : '') + '>' +
            '<label class="custom-control-label" for="laboratory_config_' + parameter.id + '"><span class="laboratory-code">' + escapeLaboratoryHtml(parameter.code) + '</span> · ' + escapeLaboratoryHtml(parameter.name) + '</label></div>' +
            '<small class="text-muted">' + escapeLaboratoryHtml(parameter.result_type) + (parameter.default_unit ? ' · ' + escapeLaboratoryHtml(parameter.default_unit) : '') + '</small></div>' +
            '<span class="badge badge-light">Sequence <span class="sequence-preview">' + (config.sequence || index + 1) + '</span></span></div>' +
            '<div class="laboratory-config-grid">' +
            fieldInput('Sequence', 'sequence', config.sequence || index + 1, 'number', 'min="1"') +
            fieldSelect('Source', 'source_type', sourceType, [['STATIC', 'Static'], ['PRODUCT_FIELD', 'Product Field']], 'numeric-config') +
            fieldSelect('Operator', 'operator', operator, [['BETWEEN', 'Between'], ['>=', '≥ Minimum'], ['<=', '≤ Maximum'], ['=', 'Equal / Target']], 'numeric-config') +
            fieldSelect('Min Product Field', 'source_min_field', config.source_min_field || '', productFieldOptions(), 'numeric-config product-field-config') +
            fieldSelect('Max Product Field', 'source_max_field', config.source_max_field || '', productFieldOptions(), 'numeric-config product-field-config') +
            fieldSelect('Target Product Field', 'source_target_field', config.source_target_field || '', productFieldOptions(), 'numeric-config product-field-config') +
            fieldInput('Static Min', 'min_value', laboratoryNullableValue(config.min_value), 'number', 'step="any"', 'numeric-config static-config') +
            fieldInput('Static Max', 'max_value', laboratoryNullableValue(config.max_value), 'number', 'step="any"', 'numeric-config static-config') +
            fieldInput('Static Target', 'target_value', laboratoryNullableValue(config.target_value), 'number', 'step="any"', 'numeric-config static-config') +
            fieldInput('Tolerance +', 'tolerance_plus', laboratoryNullableValue(config.tolerance_plus), 'number', 'step="any" min="0"', 'numeric-config') +
            fieldInput('Tolerance −', 'tolerance_minus', laboratoryNullableValue(config.tolerance_minus), 'number', 'step="any" min="0"', 'numeric-config') +
            fieldInput('Expected Value', 'expected_value', config.expected_value || '', 'text', '', 'expected-config') +
            fieldInput('Unit', 'unit', config.unit || parameter.default_unit || '', 'text') +
            fieldInput('Measurement Count', 'measurement_count', config.measurement_count || 1, 'number', 'min="1" max="100"', 'numeric-config') +
            fieldSelect('Calculation', 'calculation_type', config.calculation_type || 'NONE', [['NONE', 'None'], ['AVG', 'Average'], ['MIN', 'Minimum'], ['MAX', 'Maximum']], 'numeric-config') +
            fieldSelect('Required', 'is_required', String(config.is_required === undefined ? 1 : parseInt(config.is_required)), [['1', 'Required'], ['0', 'Optional']]) +
            '</div></div>'
    })
    $('#laboratory_parameter_configuration').html(html)
    initMasterLaboratorySelect2($('#laboratory_parameter_configuration').find('select'), { width: '100%' })
    $('.laboratory-config-row').each(function () { updateConfigurationVisibility($(this)) })
}

$(document).on('change', '.laboratory-config-enabled', function () {
    $(this).closest('.laboratory-config-row').toggleClass('is-disabled', !this.checked)
})
$(document).on('input', '.laboratory-field-sequence', function () {
    $(this).closest('.laboratory-config-row').find('.sequence-preview').text($(this).val())
})
$(document).on('change', '.laboratory-field-source_type', function () {
    updateConfigurationVisibility($(this).closest('.laboratory-config-row'))
})

function updateConfigurationVisibility(row) {
    var numeric = row.data('result-type') === 'numeric'
    row.find('.numeric-config').toggle(numeric)
    row.find('.expected-config').toggle(!numeric)
    if (numeric) {
        var productField = row.find('.laboratory-field-source_type').val() === 'PRODUCT_FIELD'
        row.find('.product-field-config').toggle(productField)
        row.find('.static-config').toggle(!productField)
    }
}

$('#btn_save_method_parameters').on('click', function () {
    var productMethodId = $('#laboratory_product_method_id').val()
    var parameters = []
    $('.laboratory-config-row').each(function () {
        var row = $(this)
        if (!row.find('.laboratory-config-enabled').is(':checked')) return
        var resultType = row.data('result-type')
        var sourceType = resultType === 'numeric' ? row.find('.laboratory-field-source_type').val() : 'STATIC'
        parameters.push({
            test_parameter_id: row.data('parameter-id'),
            sequence: row.find('.laboratory-field-sequence').val(),
            source_type: sourceType,
            source_min_field: sourceType === 'PRODUCT_FIELD' ? row.find('.laboratory-field-source_min_field').val() : null,
            source_max_field: sourceType === 'PRODUCT_FIELD' ? row.find('.laboratory-field-source_max_field').val() : null,
            source_target_field: sourceType === 'PRODUCT_FIELD' ? row.find('.laboratory-field-source_target_field').val() : null,
            operator: resultType === 'numeric' ? row.find('.laboratory-field-operator').val() : '=',
            min_value: sourceType === 'STATIC' ? row.find('.laboratory-field-min_value').val() : null,
            max_value: sourceType === 'STATIC' ? row.find('.laboratory-field-max_value').val() : null,
            target_value: sourceType === 'STATIC' ? row.find('.laboratory-field-target_value').val() : null,
            tolerance_plus: row.find('.laboratory-field-tolerance_plus').val(),
            tolerance_minus: row.find('.laboratory-field-tolerance_minus').val(),
            expected_value: row.find('.laboratory-field-expected_value').val(),
            unit: row.find('.laboratory-field-unit').val(),
            measurement_count: resultType === 'numeric' ? row.find('.laboratory-field-measurement_count').val() : null,
            calculation_type: resultType === 'numeric' ? row.find('.laboratory-field-calculation_type').val() : 'NONE',
            is_required: row.find('.laboratory-field-is_required').val(),
            status: 1
        })
    })
    if (!productMethodId || !parameters.length) {
        toastr['warning']('Enable at least one parameter before saving.')
        return
    }
    postCallback('saveLaboratoryMethodParameters', { product_method_id: productMethodId, parameters: parameters }, function (response) {
        swal.close()
        toastr['success'](response.meta.message)
        $('#product-configuration-panel').addClass('show active')
        getCallbackNoSwal('getLaboratoryMethodParameters', { product_method_id: productMethodId }, function (result) {
            renderParameterConfiguration(result.data || [])
        })
    })
})

function fieldInput(label, name, value, type, attributes, extraClass) {
    return '<div class="' + (extraClass || '') + '"><p for="">' + label + '</p><input type="' + type + '" class="form-control laboratory-field-' + name + '" value="' + escapeLaboratoryHtml(String(value)) + '" ' + (attributes || '') + '></div>'
}

function fieldSelect(label, name, selected, options, extraClass) {
    var html = '<div class="' + (extraClass || '') + '"><p for="">' + label + '</p><select class="form-control laboratory-field-' + name + '">'
    options.forEach(function (option) {
        html += '<option value="' + option[0] + '" ' + (String(option[0]) === String(selected) ? 'selected' : '') + '>' + escapeLaboratoryHtml(option[1]) + '</option>'
    })
    return html + '</select></div>'
}

function productFieldOptions() {
    return [['', 'Not Used'], ['min_od', 'Minimum OD'], ['max_od', 'Maximum OD'], ['avg_od', 'Average OD'], ['min_wt', 'Minimum WT'], ['max_wt', 'Maximum WT'], ['avg_wt', 'Average WT']]
}

function statusLaboratoryBadge(status) {
    return parseInt(status) === 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>'
}

function laboratoryNullableValue(value) {
    return value === null || value === undefined ? '' : value
}

function escapeLaboratoryHtml(value) {
    // escapeHtml (helper.js) juga meng-escape tanda kutip — wajib karena nilai
    // disuntik ke atribut title="..."/value="..."/data-*
    return window.escapeHtml ? window.escapeHtml(value) : $('<div>').text(value === null || value === undefined ? '' : value).html()
}

/**
 * The layout sweeps $(".select2") twice -- once inline and once on ready --
 * and that sweep also matches the container span select2 itself creates. Any
 * init that runs between the two sweeps ends up with a second, empty widget on
 * top of the real one, so initialise after ready and destroy first.
 */
function initMasterLaboratorySelect2(selector, options) {
    $(selector).each(function () {
        if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).select2('destroy')
        }
    })
    $(selector).select2(options)
}

$(function () {
    initMasterLaboratorySelect2('#laboratory_method_status', { width: '100%', dropdownParent: $('#laboratoryMethodModal') })
    initMasterLaboratorySelect2('#laboratory_parameter_result_type, #laboratory_parameter_status', { width: '100%', dropdownParent: $('#laboratoryParameterModal') })
})
