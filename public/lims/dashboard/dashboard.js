getCallbackNoSwal('getApprovalUser', null, function(response){
   mappingApproval(response.data)

})
function mappingApproval(response){
    $('#apporval_container').empty()
    var data =''
    for(i = 0 ; i < response.length; i++){
        console.log(response[i].transaction_code + '  ==  ' + response[i].inspection_type + ' == ' + response[i].type)
        data +=`
                <li class="list-group-item mx-4 p-0">
                    <div class="row px-4">
                    <div class="px-2 col-10">
                        <label style="font-size:10px !important;font-weight:bold">${response[i].transaction_code}</label>
                        <p>${response[i].remark}</p>
                        </div>
                        <div class="px-2 col-2">
                            <button class="btn btn-sm btn-info rounded" onclick="detail('${response[i].transaction_code}','${response[i].inspection_type}','${response[i].type}')" title ="Detail">
                            <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </li>
        `
    }
    $('#apporval_container').html(data)
}
$('#btn_ig_approve').on('click', function(){
    var data ={
        'approval_insepection_goods_shift_id'   : $('#approval_insepection_goods_shift_id').val(),
        'transaction_code'                      : $('#ig_id').val(),
        'ig_remark'                             : $('#ig_remark').val(),
        'inspection_type'                       :3,
        'type'                                  :1
    }
   postCallback('updateInspectionGoodsApproval',data, function(response){
    swal.close()
    $('#detailSubDetailInspection').modal('hide')
    toastr['success'](response.meta.message)
    var transaction_code = $('#ig_id').val()
    getCallbackNoSwal('getApprovalUser', null, function(response){
        mappingApproval(response.data)

     })

   })
})
onChange('select_approval_insepection_goods_shift','approval_insepection_goods_shift_id')
$(document).on('click','.subdetailinspection', function(e){
    var subdetail = $(this).data('subdetail')
   mapping_progress(subdetail)
})
onChange('select_approval_resin','approval_resin')
$('#btn_imr_approval').on('click', function(){
    var data = {
        'approval_resin'                        : $('#approval_resin').val(),
        'transaction_code'                      : $('#approval_material_id').val(),
        'imr_remark'                            : $('#imr_remark').val(),
        'inspection_type'                       :2,
        'type'                                  :1
    }
    postCallback('updateInspectionGoodsApproval',data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#detailResinModal').modal('hide')
        getCallbackNoSwal('getApprovalUser', null, function(response){
            mappingApproval(response.data)
        })
    })
})
onChange('select_approval_pellet', 'approval_pellet')
$('#btn_approval_pellet').on('click', function(){
    var rawText = $('#label_pellet_inspection_code').text().trim();
    var data = {
        imr_pellet_remark: $('#imr_pellet_remark').val(),
        approval_pellet: $('#approval_pellet').val(),
        label_pellet_inspection_code: rawText.replace(':', '').trim(),
        type: 2,
        inspection_type : 2
    };
    console.log(data)
       postCallback('updateInspectionGoodsApproval',data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#detailPelletModal').modal('hide')
        getCallbackNoSwal('getApprovalUser', null, function(response){
            mappingApproval(response.data)
        })
    })

});

function detail(transaction_code,inspection_type,type){
    if(inspection_type == 1 && type == 1){
        getCallback('getProductLaboratoryDetail', { laboratory_code: transaction_code }, function(response){
            swal.close()
            dashboardMapLaboratoryApproval(response.data)
            $('#dashboardLaboratoryApprovalModal').modal('show')
        })
    }
    else if(inspection_type == 3 && type== 1){
        var data ={
            'inspection_detail' : transaction_code,
        }
        $('#detailSubDetailInspection').modal('show')

        getCallback('mappingInspectionProgress',data, function(response){
            swal.close()
            $('#ig_id').val(response.detail.inspection_detail)
            var progress_tab = ''; // Initialize the progress_tab variable
            var responseData = response.data; // Use a different variable name to avoid conflicts
            $('#ig_footer').prop('hidden', false)
            $('#ig_approval_container').prop('hidden', false)
            for (var i = 0; i < responseData.length; i++) {
                var is_active = (i === 0) ? 'active' : ''; // Set active class for the first tab
                progress_tab += `
                <li class="nav-item">
                <a style="font-size: 12px !important" class="nav-link mb-sm-3 mb-md-0 ${is_active} subdetailinspection"
                id="${responseData[i].inspection_sub_detail}-tab"
                data-toggle="tab"
                href="#${responseData[i].inspection_sub_detail}"
                data-subdetail="${responseData[i].inspection_sub_detail}"
                role="tab"
                aria-controls="${responseData[i].inspection_sub_detail}"
                aria-selected="${is_active ? 'true' : 'false'}">
                <i class="ni ni-sound-wave mr-2"></i>progress ${i + 1}
                </a>
                </li>
                `;
            }

            // Update the HTML content of the tab progress element
            $('#tab_progress').html(progress_tab);
            $('#label_user_id').html(': ' + response.detail.user_relation.name)
            $('#label_time').val(response.detail.time)
            $('#label_machine').html(': ' + response.detail.machine_relation.name)
            $('#label_product').html(': ' + response.detail.product_relation.marking)
            $('#label_manufacturing_code').html(': ' + response.detail.product_relation.marking)
            $('#label_length_of_pipe').html(': ' + response.detail.length_of_pipe)
            $('#label_material').html(': ' + response.detail.material_relation.name)
            $('#label_tape_diameter').html(': ' + response.detail.tape_diameter)

            $('#label_appearance_of_pipe').html(': ' + response.detail.appearance_of_pipe)
            $('#label_marking').html(': ' + response.detail.marking)
            $('#label_condition_pipe_of_cut').html(': ' + response.detail.condition_pipe_of_cut)
            $('#label_angle_pipe_of_spigot').html(': ' + response.detail.angle_pipe_of_spigot)
            $('#label_color_of_pipes').html(': ' + response.detail.color_of_pipes)
            mappingWT(response.wt,'label_wt')
            $('#label_evaluation_spec').html(': ' + response.detail.evaluation_spec)

        })
    }
    else if(inspection_type == '2' && type == '1'){
        var data ={
            'inspection_code' : transaction_code,
        }
       getCallback('getResinInspection',data,function(response){
            swal.close()
            if(response.detail.type == 1){
                $('#detailResinModal').modal('show')
                $('.approval_resin_container').prop('hidden', false)
                // General Information
                $('#approval_material_id').val(response.detail.inspection_code)
                $('#label_resin_inspection_code').html(': ' + response.detail.inspection_code)
                $('#label_resin_location').html(': ' + response.detail.location_relation.name)
                $('#label_resin_inspector').html(': ' + response.detail.inspector_relation.name)
                $('#label_resin_supplier').html(': ' + response.detail.supplier_relation.name)
                $('#label_resin_brand').html(': ' + response.detail.merk)
                $('#label_resin_standard').html(': ' + response.detail.standard)
                $('#label_resin_certificate').html(': ' + response.detail.certificate)
                $('#label_resin_weight').html(': ' + response.detail.weight +  ' kg')
                $('#label_resin_amount').html(': ' + response.detail.amount + ' sak')
                $('#label_resin_no_lot').html(': ' + response.detail.no_lot)
            // General Information
            // Bulk Density
                $('label_canvas#resin_graphic').remove()
                $('label_canvas#resin_graphic_container').remove();
                $('#label_resin_product_id').html(': ' + response.detail.material_relation.name)
                $("#label_resin_step").html(': ' + response.header.step)
                $('#label_resin_spec').html(': ' + response.detail.material_relation.spec)
                $('#label_resin_min').html(': ' + response.min.value)
                $('#label_resin_max').html(': ' + response.max.value)
                let limitedNumber = response.avg.toFixed(5);
                $('#label_resin_avg').html(': ' + limitedNumber)
                if(response.data.length > 0){
                    var data = []
                    var label =[]
                    var masterColor ='#293462'
                    var color = []
                    var detail = ''
                    $('#label_step_container').empty()
                    for (let i = 0; i < response.data.length; i++) {
                        data.push(response.data[i].value);
                        label.push(response.data[i].step);
                        color.push(masterColor); // Assign the same masterColor to each item

                        // Ensure the input displays the full precision value
                        const formattedValue = parseFloat(response.data[i].value);

                        detail += `
                            <div class="col-6">
                                <p>Step ${i + 1} : ${formattedValue}</p>
                            </div>
                        `;
                    }

                    $('#label_step_container').html(detail)
                    $('#label_resin_graphic_container').append('<canvas id="label_resin_graphic" style="width:50px !important; height:50px !important" ></canvas>')
                    var canvas = document.getElementById("label_resin_graphic");
                    var chart = canvas.getContext('2d')
                    pieChart('Bulk Density',label,data,color,'line',chart)
                }

                 // Density Properties
                    $('#label_vivsible_properties').html(': '+ response.header.visible_properties)
                    $('#label_resin_package').html(': '+ response.header.packaging)
                    $('#label_resin_color').html(': '+ response.header.color)
                    $('#label_resin_particle_size').html(': '+ response.header.particle_size)
                    $('#label_resin_volatile_matter').html(': '+ response.header.volatile_matter)
                    $('#label_resin_amount_per_sak').html(': '+ response.header.amount)
                    $('#label_resin_weight_per_sak').html(': '+ response.header.weight)
                    $('#label_resin_remark').html(': '+ response.detail.remark)
                    $('#label_resin_pof_number').html(': '+ response.detail.no_pof)
                // Density Properties
            }
            else if(response.detail.type == 2){
                  $(document).ready(function() {
                      $('.approval_pellet_container').prop('hidden', false)
                      $('#detailPelletModal').modal('show')
                  })
                   // General Information
                    $('#btn_approval_pellet').prop('hidden', false)
                    $('#label_pellet_inspection_code').html(': ' + response.detail.inspection_code)
                    $('#label_pellet_location').html(': ' + response.detail.location_relation.name)
                    $('#label_pellet_inspector').html(': ' + response.detail.inspector_relation.name)
                    $('#label_pellet_supplier').html(': ' + response.detail.supplier_relation.name)
                    $('#label_pellet_brand').html(': ' + response.detail.merk)
                    $('#label_pellet_standard').html(': ' + response.detail.standard)
                    $('#label_pellet_certificate').html(': ' + response.detail.certificate)
                    $('#label_pellet_weight').html(': ' + response.detail.weight +  ' kg')
                    $('#label_pellet_amount').html(': ' + response.detail.amount + ' sak')
                    $('#label_pellet_no_lot').html(': ' + response.detail.no_lot)
                // General Information
                // Detail Inspectoin
                    $('#label_pellet_amount_per_sak').html(': ' + response.header.amount_per_sak +  ' sak')
                    $('#label_pellet_visible_properties').html(': ' + response.header.visible_properties)
                    $('#label_pellet_packaging').html(': ' + response.header.packaging)
                    $('#label_pellet_weight_per_sak').html(': ' + response.header.weight_per_sak +  ' kg')
                    $('#label_pelet_mfr').html(': ' + response.header.mfr + ' G/10 mm')
                    $('#label_pellet_remark').html(': ' + response.detail.remark)
                    $('#label_pellet_pof_number').html(': ' + response.detail.no_pof)
                // Detail Inspectoin
                }
       })
    }
    else if(inspection_type == 2 && type == 2){
        var data ={
            'inspection_code' : transaction_code,
        }

       getCallback('getPelletInspection',data,function(response){
        swal.close()
                // General Information
                    $('#label_pellet_inspection_code').html(': ' + response.detail.inspection_code)
                    $('#label_pellet_location').html(': ' + response.detail.location_relation.name)
                    $('#label_pellet_inspector').html(': ' + response.detail.inspector_relation.name)
                    $('#label_pellet_supplier').html(': ' + response.detail.supplier_relation.name)
                    $('#label_pellet_brand').html(': ' + response.detail.merk)
                    $('#label_pellet_standard').html(': ' + response.detail.standard)
                    $('#label_pellet_certificate').html(': ' + response.detail.certificate)
                    $('#label_pellet_weight').html(': ' + response.detail.weight +  ' kg')
                    $('#label_pellet_amount').html(': ' + response.detail.amount + ' sak')
                    $('#label_pellet_no_lot').html(': ' + response.detail.no_lot)
                // General Information
                // Detail Inspectoin
                        $('#label_pellet_amount_per_sak').html(': ' + response.header.amount_per_sak +  ' sak')
                        $('#label_pellet_visible_properties').html(': ' + response.header.visible_properties)
                        $('#label_pellet_packaging').html(': ' + response.header.packaging)
                        $('#label_pellet_weight_per_sak').html(': ' + response.header.weight_per_sak +  ' kg')
                        $('#label_pelet_mfr').html(': ' + response.header.mfr + ' G/10 mm')
                        $('#label_pellet_remark').html(': ' + response.detail.remark)
                        $('#label_pellet_pof_number').html(': ' + response.detail.no_pof)
                // Detail Inspectoin
        $('#detailPelletModal').modal('show')
        $('.approval_pellet_container').prop('hidden', false)
        $('#btn_approval_pellet').prop('hidden', false)
       })
    }
    else if(inspection_type == '4' && type == '1'){
          var data ={
            'inspection_code' : transaction_code,
        }
        console.log(data)
       getCallback('getPelletInspection',data,function(response){
        swal.close()
                // General Information
                    $('#label_pellet_inspection_code').html(': ' + response.detail.inspection_code)
                    $('#label_pellet_location').html(': ' + response.detail.location_relation.name)
                    $('#label_pellet_inspector').html(': ' + response.detail.inspector_relation.name)
                    $('#label_pellet_supplier').html(': ' + response.detail.supplier_relation.name)
                    $('#label_pellet_brand').html(': ' + response.detail.merk)
                    $('#label_pellet_standard').html(': ' + response.detail.standard)
                    $('#label_pellet_certificate').html(': ' + response.detail.certificate)
                    $('#label_pellet_weight').html(': ' + response.detail.weight +  ' kg')
                    $('#label_pellet_amount').html(': ' + response.detail.amount + ' sak')
                    $('#label_pellet_no_lot').html(': ' + response.detail.no_lot)
                // General Information
                // Detail Inspectoin
                        $('#label_pellet_amount_per_sak').html(': ' + response.header.amount_per_sak +  ' sak')
                        $('#label_pellet_visible_properties').html(': ' + response.header.visible_properties)
                        $('#label_pellet_packaging').html(': ' + response.header.packaging)
                        $('#label_pellet_weight_per_sak').html(': ' + response.header.weight_per_sak +  ' kg')
                        $('#label_pelet_mfr').html(': ' + response.header.mfr + ' G/10 mm')
                        $('#label_pellet_remark').html(': ' + response.detail.remark)
                        $('#label_pellet_pof_number').html(': ' + response.detail.no_pof)
                // Detail Inspectoin
        $('#detailPelletModal').modal('show')
        $('.approval_pellet_container').prop('hidden', false)
        $('#btn_approval_pellet').prop('hidden', false)
       })
    }


}

$('#btn_laboratory_approval').on('click', function(){
    var data = {
        laboratory_code: $('#dashboard_laboratory_code').val(),
        decision: $('#dashboard_laboratory_decision').val(),
        remark: $('#dashboard_laboratory_remark').val()
    }
    postCallback('updateLaboratoryApproval', data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#dashboardLaboratoryApprovalModal').modal('hide')
        getCallbackNoSwal('getApprovalUser', null, function(result){
            mappingApproval(result.data)
        })
    })
})

function dashboardMapLaboratoryApproval(header){
    $('#dashboard_laboratory_code').val(header.laboratory_code)
    $('#dashboardLaboratoryApprovalTitle').text('Laboratory Approval · ' + header.laboratory_code)
    $('#dashboard_laboratory_decision, #dashboard_laboratory_remark').val('')
    $('#dashboardLaboratoryApprovalModal .text-danger').html('')

    var product = header.product_relation || {}
    var method = header.method_snapshot || {}
    var location = header.location_relation || {}
    var summary = [
        ['Product', (product.seri || '-') + ' · ' + (product.ukuran || '-')],
        ['Method', (method.code || '-') + ' · ' + (method.name || '-')],
        ['Location / Shift', (location.name || '-') + ' · Shift ' + (header.shift || '-')],
        ['Test Date', header.test_date || '-']
    ]
    var summaryHtml = ''
    summary.forEach(function(item){
        summaryHtml += '<div class="col-md-3 mb-2"><div class="p-3 bg-light rounded"><small class="text-muted d-block">' + dashboardLaboratoryEscape(item[0]) + '</small><b style="font-size:11px">' + dashboardLaboratoryEscape(item[1]) + '</b></div></div>'
    })
    $('#dashboard_laboratory_summary').html(summaryHtml)

    var resultHtml = ''
    ;(header.details_relation || []).forEach(function(detail){
        var result = '-'
        if(detail.result_type_snapshot === 'numeric') {
            result = detail.result_value === null ? '-' : detail.result_value + (detail.unit_snapshot ? ' ' + detail.unit_snapshot : '')
        } else if(detail.result_type_snapshot === 'boolean') {
            result = detail.boolean_result === null ? '-' : (detail.boolean_result ? 'Yes / Pass' : 'No / Fail')
        } else {
            result = detail.text_result || '-'
        }
        var measurement = (detail.measurements_relation || []).map(function(item){ return item.value }).join(', ')
        resultHtml += '<div class="border rounded p-3 mb-2"><div class="row align-items-center"><div class="col-md-4"><b>' + detail.sequence + '. ' + dashboardLaboratoryEscape(detail.parameter_name_snapshot) + '</b><small class="text-muted d-block">' + dashboardLaboratoryEscape(dashboardLaboratoryStandard(detail.standard_snapshot || {}, detail.unit_snapshot)) + '</small></div>' +
            '<div class="col-md-3"><small class="text-muted d-block">Result</small><b>' + dashboardLaboratoryEscape(result) + '</b></div>' +
            '<div class="col-md-3"><small class="text-muted d-block">Measurements</small><span style="font-size:11px">' + dashboardLaboratoryEscape(measurement || '-') + '</span></div>' +
            '<div class="col-md-2 text-right">' + dashboardLaboratoryEvaluation(detail.evaluation) + '</div></div></div>'
    })
    $('#dashboard_laboratory_results').html(resultHtml || '<div class="text-muted text-center py-3">No laboratory result.</div>')
}

function dashboardLaboratoryStandard(standard, unit){
    var suffix = unit ? ' ' + unit : ''
    if(standard.expected_value !== null && standard.expected_value !== undefined && standard.expected_value !== '') return 'Expected: ' + standard.expected_value
    if(standard.operator === 'BETWEEN') return 'Standard: ' + standard.min_value + ' – ' + standard.max_value + suffix
    if(standard.operator === '>=') return 'Standard: ≥ ' + standard.min_value + suffix
    if(standard.operator === '<=') return 'Standard: ≤ ' + standard.max_value + suffix
    if(standard.operator === '=') return 'Standard: = ' + standard.target_value + suffix
    return 'Observation only'
}

function dashboardLaboratoryEvaluation(evaluation){
    if(evaluation === 'GOOD') return '<span class="badge badge-success">GOOD</span>'
    if(evaluation === 'NO_GOOD') return '<span class="badge badge-danger">NO GOOD</span>'
    return '<span class="badge badge-light">N/A</span>'
}

function dashboardLaboratoryEscape(value){
    return $('<div>').text(value === null || value === undefined ? '' : value).html()
}
function mapping_progress(subdetail){
    var data ={
        'inspection_sub_detail': subdetail
    }
    getCallback('detailInspectionProgress',data, function(response){
        swal.close()

        $('#label_user_id').html(': ' + response.detail.user_relation.name)
        $('#label_time').val(response.detail.time)
        $('#label_machine').html(': ' + response.detail.machine_relation.name)
        $('#label_product').html(': ' + response.detail.product_relation.marking)
        $('#label_manufacturing_code').html(': ' + response.detail.product_relation.marking)
        $('#label_length_of_pipe').html(': ' + response.detail.length_of_pipe)
        $('#label_material').html(': ' + response.detail.material_relation.name)
        $('#label_tape_diameter').html(': ' + response.detail.tape_diameter)

        $('#label_appearance_of_pipe').html(': ' + response.detail.appearance_of_pipe)
        $('#label_marking').html(': ' + response.detail.marking)
        $('#label_condition_pipe_of_cut').html(': ' + response.detail.condition_pipe_of_cut)
        $('#label_angle_pipe_of_spigot').html(': ' + response.detail.angle_pipe_of_spigot)
        $('#label_color_of_pipes').html(': ' + response.detail.color_of_pipes)
        $('#label_wt_1').val(response.wt.wt_1)
        $('#label_wt_2').val(response.wt.wt_2)
        $('#label_wt_3').val(response.wt.wt_3)
        $('#label_wt_4').val(response.wt.wt_4)
        $('#label_wt_5').val(response.wt.wt_5)
        $('#label_wt_6').val(response.wt.wt_6)
        $('#label_wt_7').val(response.wt.wt_7)
        $('#label_wt_8').val(response.wt.wt_8)
        $('#label_evaluation_spec').html(': ' + response.detail.evaluation_spec)

    })
}
