getCallbackNoSwal('getApprovalUser', null, function(response){
   mappingApproval(response.data)
  
})  
function mappingApproval(response){
    $('#apporval_container').empty()
    var data =''
    for(i = 0 ; i < response.length; i++){
        data +=`
                <li class="list-group-item mx-4 p-0" style="max-height:60px !important">
                    <div class="row p-0">
                    <div class="p-0 col-10 mt-2">
                        <label style="font-size:10px !important;font-weight:bold">${response[i].transaction_code}</label>
                        <p>${response[i].remark}</p>
                        </div>
                        <div class="p-0 col-2 mt-1 mt-4">
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
function detail(transaction_code,inspection_type,type){ 
    if(inspection_type == 3 && type== 1){
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
    else if(inspection_type == 2 && type == 1){
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
       })
    }
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