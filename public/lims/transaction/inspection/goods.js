let data_start = {
    'from' : $('#from').val(),
    'to' : $('#to').val(),
}
getCallback('getInspectionGoods',data_start, function(response){
    swal.close()
    mappingTable(response.data)
})
$('#btn_add_header').on('click', function(){
    $('#location_id').val('')
    $('#machine_id').val('')

    getActiveItems('getLocation', null,'select_location','Location')
})
onChange('select_location','location_id');

$('#btn_save_header').on('click', function(){
    var data ={
        'location_id': $('#location_id').val(),
        'machine_id': $('#machine_id').val()
    }
    postCallback('addInspectionGoodsHeader', data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#addInspectionHeader').modal('hide')
        getCallbackNoSwal('getInspectionGoods',data_start, function(response){
            mappingTable(response.data)
        })
    })
})
// Add Sub Detail Inspection

        onChange('select_machine','machine_id');
        onChange('select_material', 'material')
        onChange('select_product', 'product')
        onChange('select_appearance_of_pipe', 'appearance_of_pipe')
        onChange('select_marking', 'marking')
        onChange('select_condition_pipe_of_cut', 'condition_pipe_of_cut')
        onChange('select_angle_pipe_of_spigot', 'angle_pipe_of_spigot')
        onChange('select_color_of_pipes', 'color_of_pipes')
        onChange('select_evaluation_spec', 'evaluation_spec')
        onChange('select_inspector', 'inspector')

        $('#btn_save_sub_detail').on('click', function(){
            var data ={

            // Inspection Item
                'detail_inspection_code_id' : $('#detail_inspection_code_id').val(),
                'product' : $('#product').val(),
                'length_of_pipe' : $('#length_of_pipe').val(),
                'appearance_of_pipe' : $('#appearance_of_pipe').val(),
                'marking' : $('#marking').val(),
                'manufacturing_code':$('#manufacturing_code').val(),
                'condition_pipe_of_cut' : $('#condition_pipe_of_cut').val(),
                'angle_pipe_of_spigot' : $('#angle_pipe_of_spigot').val(),
                'color_of_pipes' : $('#color_of_pipes').val(),
                'evaluation_spec' : $('#evaluation_spec').val(),
                'manufacturing_code' : $('#manufacturing_code').val(),
                'material' : $('#material').val(),
                'tape_diameter' : $('#tape_diameter').val(),
                'machine_id' : $('#machine_id').val(),
                'time' : $('#time').val(),
                'add_remark' : $('#add_remark').val(),
                'inspector' : $('#inspector').val(),
            // Inspection Item

            // Wall Thickness Dimension
                'wt_1' : $('#wt_1').val(),
                'wt_2' : $('#wt_2').val(),
                'wt_3' : $('#wt_3').val(),
                'wt_4' : $('#wt_4').val(),
                'wt_5' : $('#wt_5').val(),
                'wt_6' : $('#wt_6').val(),
                'wt_7' : $('#wt_7').val(),
                'wt_8' : $('#wt_8').val(),
            // Wall Thickness Dimension
        }
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok"
          }).then((result) => {
            if (result.isConfirmed) {

                postCallback('addSubDetailInspectionGoods',data,function(response){
                    swal.close()
                    toastr['success'](response.meta.message)
                    $('#addSubDetailInspection').modal('hide')
                    getCallbackNoSwal('getInspectionGoods',data_start, function(response){
                        swal.close()
                        mappingTable(response.data)
                    })
                })
            }
          });


    })
// Add Sub Detail Inspection

// Edit Sub Detail Inspection
    onChange('select_machine_edit','machine_id_edit');
    onChange('select_material_edit', 'material_edit')
    onChange('select_product_edit', 'product_edit')
    onChange('select_appearance_of_pipe_edit', 'appearance_of_pipe_edit')
    onChange('select_marking_edit', 'marking_edit')
    onChange('select_condition_pipe_of_cut_edit', 'condition_pipe_of_cut_edit')
    onChange('select_angle_pipe_of_spigot_edit', 'angle_pipe_of_spigot_edit')
    onChange('select_color_of_pipes_edit', 'color_of_pipes_edit')
    onChange('select_evaluation_spec_edit', 'evaluation_spec_edit')
    onChange('select_inspection_sub_detail_edit', 'inspection_sub_detail_edit')
    onChange('select_inspector_edit', 'inspector_edit')

    $('#select_inspection_sub_detail_edit').on('change', function(){
        var select_inspection_sub_detail_edit = $('#select_inspection_sub_detail_edit').val()
        var data ={
            'inspection_detail'    : $('#inspection_detail_edit').val(),
            'inspection_sub_detail'         : $('#select_inspection_sub_detail_edit').val()
        }
        getCallback('mappingInspectionProgress', data, function(response){
            swal.close()
            var responseData = response.data; // Use a different variable name to avoid conflicts
            $('#select_product_edit').val(response.detail.product_id)
            $('#select_product_edit').select2().trigger('change')
            $('#select_machine_edit').val(response.detail.machine_id)
            $('#select_machine_edit').select2().trigger('change')
            $('#select_material_edit').val(response.detail.material_id)
            $('#select_material_edit').select2().trigger('change')
            $('#select_inspector_edit').val(response.detail.inspector)
            $('#select_inspector_edit').select2().trigger('change')
            // Update the HTML content of the tab progress element
            $('#time_edit').val(response.detail.time)
            $('#machine_edit').val(response.detail.machine_relation.name)
            $('#product_edit').val(response.detail.product_id)
            $('#manufacturing_code_edit').val(response.detail.product_relation.marking)
            $('#length_of_pipe_edit').val(response.detail.length_of_pipe)
            $('#material_edit').val(response.detail.material_relation.id)
            $('#tape_diameter_edit').val(response.detail.tape_diameter)

            $('#select_appearance_of_pipe_edit').val(response.detail.appearance_of_pipe)
            $('#select_appearance_of_pipe_edit').select2().trigger('change')
            $('#appearance_of_pipe_edit').val(response.detail.appearance_of_pipe)

            $('#marking_edit').val(response.detail.marking)
            $('#select_marking_edit').val(response.detail.marking)
            $('#select_marking_edit').select2().trigger('change')

            $('#condition_pipe_of_cut_edit').val(response.detail.condition_pipe_of_cut)
            $('#select_condition_pipe_of_cut_edit').val(response.detail.condition_pipe_of_cut)
            $('#select_condition_pipe_of_cut_edit').select2().trigger('change')

            $('#angle_pipe_of_spigot_edit').val(response.detail.angle_pipe_of_spigot)
            $('#select_angle_pipe_of_spigot_edit').val(response.detail.angle_pipe_of_spigot)
            $('#select_angle_pipe_of_spigot_edit').select2().trigger('change')

            $('#color_of_pipes_edit').val(response.detail.color_of_pipes)
            $('#select_color_of_pipes_edit').val(response.detail.color_of_pipes)
            $('#select_color_of_pipes_edit').select2().trigger('change')

            mappingWT(response.wt, 'label_wt')
            $('#evaluation_spec_edit').val(response.detail.evaluation_spec)
            $('#select_evaluation_spec_edit').val(response.detail.evaluation_spec)
            $('#select_evaluation_spec_edit').select2().trigger('change')
        })
    })
    $('#btn_update_sub_detail').on('click', function(){
        var data ={
              // Inspection Item
            //   'detail_inspection_code_id_edit' : $('#detail_inspection_code_id_edit').val(),
              'product_edit' : $('#product_edit').val(),
              'length_of_pipe_edit' : $('#length_of_pipe_edit').val(),
              'appearance_of_pipe_edit' : $('#appearance_of_pipe_edit').val(),
              'marking_edit' : $('#marking_edit').val(),
              'condition_pipe_of_cut_edit' : $('#condition_pipe_of_cut_edit').val(),
              'angle_pipe_of_spigot_edit' : $('#angle_pipe_of_spigot_edit').val(),
              'color_of_pipes_edit' : $('#color_of_pipes_edit').val(),
              'evaluation_spec_edit' : $('#evaluation_spec_edit').val(),
              'manufacturing_code_edit' : $('#manufacturing_code_edit').val(),
              'material_edit' : $('#material_edit').val(),
              'tape_diameter_edit' : $('#tape_diameter_edit').val(),
              'machine_id_edit' : $('#machine_id_edit').val(),
              'inspection_detail_edit' : $('#inspection_detail_edit').val(),
              'inspection_sub_detail_edit': $('#inspection_sub_detail_edit').val(),
              'reason'                      : $('#reason').val(),
              'inspector_edit'                      : $('#inspector_edit').val(),
          // Inspection Item

          // Wall Thickness Dimension
            //   'wt_1_edit' : $('#wt_1_edit').val(),
            //   'wt_2_edit' : $('#wt_2_edit').val(),
            //   'wt_3_edit' : $('#wt_3_edit').val(),
            //   'wt_4_edit' : $('#wt_4_edit').val(),
            //   'wt_5_edit' : $('#wt_5_edit').val(),
            //   'wt_6_edit' : $('#wt_6_edit').val(),
            //   'wt_7_edit' : $('#wt_7_edit').val(),
              'wt_8_edit' : $('#wt_8_edit').val(),
          // Wall Thickness Dimension
        }
        $('.message_error').html('')
        postCallback('updateInspectionGoodSubDetail', data, function(response){
            swal.close()
            $('.message_error')
            $('#editSubDetailInspection').modal('hide')
            toastr['success'](response.meta.message)
            $('#reason').val('')
            getCallback('getInspectionGoods',data_start, function(response){
                swal.close()
                mappingTable(response.data)
            })
        })
    })
// Edit Sub Detail Inspection

// Log Approval Hedaer
    $('#inspection_header_table').on('click', '.approval_header', function(){
        var inspection = $(this).data('inspection')
        var location = $(this).data('location')
        $('#approval_location').html(': ' + location)
        $('#approval_inspection').html(': ' + inspection)
    })
// Log Approval Hedaer

// Function
    function mappingTable(response){
        $('#inspection_header_table').DataTable().clear();
        $('#inspection_header_table').DataTable().destroy();
        var data = ''
        for(i =0 ; i < response.length; i ++){
            const d = new Date(response[i].created_at)
            const date = d.toISOString().split('T')[0];
            const time = d.toTimeString().split(' ')[0];
            const dateTime = date + ' ' + time
            var status ='';
            var button_approval =''
            switch(response[i].status){
                case 0:
                        status = `<span style="font-size:8px !important" class="btn btn-block btn-outline-default">NEW</span>`
                    break
                case 1:
                        status = `<span style="font-size:8px !important" class="btn btn-block btn-outline-info">In Progress</span>`
                    break
                case 2:
                        status = `<span style="font-size:8px !important" class="btn btn-block btn-outline-warning">Approval Progress</span>`
                        button_approval  =`
                             <button class="approval_header btn btn-sm btn-primary"
                             data-locationid="${response[i].location_id}"
                             data-location="${response[i].location_relation.name}"
                             data-shift="${response[i].shift}"
                             title="Approval Log"
                             data-inspection="${response[i].inspection_code}"
                             data-toggle="modal"
                             data-target="#logApprovalHeaderModal">
                    <i class="fas fa-person-circle-check"></i>
                    </button>
                        `
                    break
                case 3:
                        status = `<span style="font-size:8px !important" class="btn btn-block btn-outline-success">DONE</span>`
                    break
                case 4:
                        status = `<span style="font-size:8px !important" class="btn btn-block btn-outline-danger">Reject</span>`
                    break
                case '' :
                        status ='Done'
                break

            }
            data += `<tr style="text-align: center;">

                            <td class='details-control'></td>
                            <td>${dateTime}</td>
                            <td class="inspection_code">${response[i].inspection_code}</td>
                            <td>${response[i].location_relation.name}</td>
                            <td>${status}</td>


                    </tr>
                    `;
        }
        $('#inspection_header_table > tbody:first').html(data);
        var table = $('#inspection_header_table').DataTable({
            scrollX  : true,
            scrollY: true,
            scrollCollapse : true,
            autoWidth:true,
            language: {
                paginate: {
                    previous: '<span class="prev-icon"><i class="ni ni-bold-left"></i></span>',
                    next: '<span class="next-icon"><i class="ni ni-bold-right"></i></span>'
                }
            }
        }).columns.adjust()
        $('#inspection_header_table tbody').off().on('click', 'td.details-control', function (e) {
        var tr = $(this).closest("tr");
        var row =   table.row( tr );
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass( 'shown' );
        }
        else {
            // Open this row
            detail_log(row.child,$(this).parents("tr").find('td.inspection_code').text()) ;
            tr.addClass( 'shown' );
        }
    } );
    }
    $(document).on('click','.mapping', function(e){
        $('#tab_progress').empty()
        var data ={
            'inspection_detail':  $(this).data('inspection')
        }
        getCallback('mappingInspectionProgress',data, function(response){
            swal.close()
            var progress_tab = ''; // Initialize the progress_tab variable
            var responseData = response.data; // Use a different variable name to avoid conflicts

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
                            <i class="ni ni-sound-wave mr-2"></i>Inspection  ${i + 1}
                        </a>
                    </li>
                `;
            }

            // Update the HTML content of the tab progress element
            $('#tab_progress').html(progress_tab);
            $('#tab_progress').prop('hidden', false)

            $('#label_inspector').html(response.detail.inspector ? ': ' + response.detail.inspector_relation.name : ': ')

            $('#label_user_id').html(': ' + response.detail.user_relation.name)
            $('#label_time').val(response.detail.time)
            $('#label_machine').html(': ' + response.detail.machine_relation.name)
            $('#label_product').html(': ' + response.detail.product_relation.marking)
            $('#label_manufacturing_code').html(': ' + response.detail.product_relation.marking)
            $('#label_length_of_pipe').html(': ' + response.detail.length_of_pipe)
            $('#label_material').html(': ' + response.detail.material_relation.name)
            $('#label_tape_diameter').html(': ' + response.detail.tape_diameter)
            $('#label_tape_diameter').html(': ' + response.detail.tape_diameter)
            $('#label_tape_diameter').html(': ' + response.detail.tape_diameter)

            $('#label_appearance_of_pipe').html(': ' + response.detail.appearance_of_pipe)
            $('#label_marking').html(': ' + response.detail.marking)
            $('#label_condition_pipe_of_cut').html(': ' + response.detail.condition_pipe_of_cut)
            $('#label_angle_pipe_of_spigot').html(': ' + response.detail.angle_pipe_of_spigot)
            $('#label_color_of_pipes').html(': ' + response.detail.color_of_pipes)
            mappingWT(response.wt,'label_wt')
            $('#label_evaluation_spec').html(': ' + response.detail.evaluation_spec)

        })
    });
    $(document).on('click','.add', function(e){
       
        var inspection = $(this).data('inspection')
        $('#detail_inspection_code_id').val(inspection)
        getCallbackNoSwal('getMasterProduct',null, function(response){
            var data = ''
            var response= response.data
            $('#select_product').empty()
            $('#select_product').append('<option value="">Choose Product</option>')
            for(i = 0; i < response.length; i++){
                data +=`<option value="${response[i].id}"> ${response[i].marking}</option>`
            }
            $('#select_product').append(data)
        })
        getActiveItems('getActiveMaterial',null,'select_material','Material')
        getActiveItems('getActiveMachine',null,'select_machine','Machine')
        getActiveItems('getUser',null,'select_inspector','Inspector')
    })
    getActiveItems('getUser',null,'select_inspector_edit','Inspector')
    $(document).on('click','.approval', function(e){
        var location = $(this).data('location');
        var detail = $(this).data('detail')
        var shift = $(this).data('shift');
        var location_id = $(this).data('locationid');

        $('#approval_shift').html(': ' + shift)
        $('#approval_location').html(': ' + location)
        var data  = {
            'location'  : location_id,
            'detail'    : detail,
            'shift'     : shift,
            'inspection_type':3,
        }
        getCallback('getApprovalList', data, function(response){
            swal.close()
            mappingApproval(response.data, response.history)
        })

    })
    $(document).on('click','.updateWT', function(e){
        var inspection = $(this).data('inspection');
        var data  = {
           'detail_inspection_code' : inspection
        }
        $('#update_detail_inspection_code').val(inspection)
        getCallback('getWallThickness', data, function(response){
            swal.close()
            $('#select_update_detail_inspection_code').empty()
            for(i =0; i < response.subdetail.length; i ++ ){
                $('#select_update_detail_inspection_code').append(`
                    <option value="${response.subdetail[i].inspection_sub_detail}">Inspection ${ i + 1} (${response.subdetail[i].inspection_sub_detail})</option>
                    `)
            }
            mappingWT(response.data,'wt_step')
        })

    })
    $(document).on('click','.lock', function(e){
        Swal.fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, lock it!"
        }).then((result) => {
          if (result.isConfirmed) {
            var detail = $(this).data('detail')
            var data ={
                'detail_inspection_code' : detail
            }
            postCallback('lockInspectionGoods',data,function(response){
                swal.close()
                Swal.fire({
                  title: "locked!",
                  text: response.meta.message,
                  icon: "success"
                });
            })
            getCallbackNoSwal('getInspectionGoods',data_start, function(response){
                swal.close()
                mappingTable(response.data)
            })
          }
        });
    })
    getCallbackNoSwal('getMasterProduct',null, function(response){
        var data = ''
        var response= response.data
        $('#select_product_edit').empty()
        $('#select_product_edit').append('<option value="">Choose Product</option>')
        for(i = 0; i < response.length; i++){
            data +=`<option value="${response[i].id}"> ${response[i].marking}</option>`
        }
        $('#select_product_edit').append(data)
    })
    getActiveItems('getActiveMaterial',null,'select_material_edit','Material')
    getActiveItems('getActiveMachine',null,'select_machine_edit','Machine')

    $(document).on('click','.edit', function(e){
        var inspection = $(this).data('inspection')

        $('#inspection_detail_edit').val(inspection)
        var data ={
            'inspection_detail' : inspection
        }
        getCallbackNoSwal('getInspectionGoodSubDetailItem',data, function(response){
            var data = ''
            var response= response.data
            $('#inspection_sub_detail_edit').val(response[0].inspection_sub_detail)
            $('#select_inspection_sub_detail_edit').empty()
            for(i = 0; i < response.length; i++){
                data +=`<option value="${response[i].inspection_sub_detail}"> ${response[i].inspection_sub_detail}</option>`
            }
            $('#select_inspection_sub_detail_edit').append(data)
        })
        getCallback('mappingInspectionProgress',data, function(response){
            swal.close()
            var responseData = response.data; // Use a different variable name to avoid conflicts
            $('#select_inspector_edit').val(response.detail.inspector)
            $('#select_inspector_edit').select2().trigger('change')
            $('#select_product_edit').val(response.detail.product_id)
            $('#select_product_edit').select2().trigger('change')
            $('#select_machine_edit').val(response.detail.machine_id)
            $('#select_machine_edit').select2().trigger('change')
            $('#select_material_edit').val(response.detail.material_id)
            $('#select_material_edit').select2().trigger('change')
            // Update the HTML content of the tab progress element
            $('#time_edit').val(response.detail.time)
            $('#machine_edit').val(response.detail.machine_relation.name)
            $('#product_edit').val(response.detail.product_id)
            $('#manufacturing_code_edit').val(response.detail.manufacturing_code)
            $('#length_of_pipe_edit').val(response.detail.length_of_pipe)
            $('#material_edit').val(response.detail.material_relation.id)
            $('#tape_diameter_edit').val(response.detail.tape_diameter)

            $('#select_appearance_of_pipe_edit').val(response.detail.appearance_of_pipe)
            $('#select_appearance_of_pipe_edit').select2().trigger('change')
            $('#appearance_of_pipe_edit').val(response.detail.appearance_of_pipe)

            $('#marking_edit').val(response.detail.marking)
            $('#select_marking_edit').val(response.detail.marking)
            $('#select_marking_edit').select2().trigger('change')

            $('#condition_pipe_of_cut_edit').val(response.detail.condition_pipe_of_cut)
            $('#select_condition_pipe_of_cut_edit').val(response.detail.condition_pipe_of_cut)
            $('#select_condition_pipe_of_cut_edit').select2().trigger('change')

            $('#angle_pipe_of_spigot_edit').val(response.detail.angle_pipe_of_spigot)
            $('#select_angle_pipe_of_spigot_edit').val(response.detail.angle_pipe_of_spigot)
            $('#select_angle_pipe_of_spigot_edit').select2().trigger('change')

            $('#color_of_pipes_edit').val(response.detail.color_of_pipes)
            $('#select_color_of_pipes_edit').val(response.detail.color_of_pipes)
            $('#select_color_of_pipes_edit').select2().trigger('change')

            mappingWT(response.wt,'label_wt')

            $('#evaluation_spec_edit').val(response.detail.evaluation_spec)
            $('#select_evaluation_spec_edit').val(response.detail.evaluation_spec)
            $('#select_evaluation_spec_edit').select2().trigger('change')
        })
    });

    $(document).on('click','.subdetailinspection', function(e){
        var subdetail = $(this).data('subdetail')
       mapping_progress(subdetail)
    })
    $(document).on('click','.export_progress', function(e){
        var detail = $(this).data('detail')
        $('#export_code').val(detail)
    })
    $('#btn_export_progress').on('click', function(){
        var type        =  $('#select_report').val()
        var detail_code        =  $('#export_code').val().replaceAll("/","_");
        var data = {
            'detail_code' : $('#export_code').val(),
            'type'        : $('#select_report').val()
        }
        if(type == 1 ){
            window.open(`exportInspectionGoodProgress/${detail_code}`, '_blank');
        }
    })
    $(document).on('click','.history', function(e){
        var inspection = $(this).data('inspection')
        var data ={
            'inspection_detail' : inspection
        }
        $('#select_history_subdetail').empty()
        getCallbackNoSwal('getInspectionGoodSubDetailItem',data, function(response){
            var data =''
            // var response = response.data

            for(i = 0; i < response.data.length; i++){
                data +=`<option value="${response.data[i].inspection_sub_detail}"> ${response.data[i].inspection_sub_detail}</option>`
            }
            $('#select_history_subdetail').append(data)

            mappingHistory(response.history,response.history_wt)
        })
    })
    $('#select_history_subdetail').on('change', function(){
        var inspection_sub_detail = $('#select_history_subdetail').val()
        var data ={
            'inspection_sub_detail' :inspection_sub_detail
        }
        getCallbackNoSwal('getInspectionGoodSubDetailItem',data, function(response){
            mappingHistory(response.history,response.history_wt)

        })
    })
    function detail_log( callback, inspection_code){
      var data ={
        'inspection_code' : inspection_code
      };
      getCallbackNoSwal('getInspectionGoodShift',data,function(response){
        if(response){
            response = response.data
            let row = '';
            for(let i = 0; i < response.length; i++){
                $(document).ready(function()
                {
                    $('.table_detail').DataTable
                    ({
                        "destroy": true,
                        "autoWidth" : false,
                        "searching": false,
                        "aaSorting" : false,
                        "paging":   false,
                        "scrollX":true
                    }).columns.adjust()
                });
                $('.table_detail tbody').append(``);
                const d = new Date(response[i].created_at)
                const date = d.toISOString().split('T')[0];
                const time = d.toTimeString().split(' ')[0];
                var lock_button =''
                var add_button = response[i].lock == 1 ?'' : ` <button title="Add Progress" class="add btn btn-sm btn-success mr-2" data-inspection ="${response[i].detail_inspection_code}" data-toggle="modal" data-target="#addSubDetailInspection">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>`
                var edit_button = response[i].length == 1 && response[i].lock == 0 ? ` <button title="Edit Progress" class="edit btn btn-sm btn-warning mr-2" data-inspection ="${response[i].detail_inspection_code}" data-toggle="modal" data-target="#editSubDetailInspection">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>`:''
                var detail_button = response[i].length == 1? `  <button class="mapping btn btn-sm btn-info mr-2" title="Detail" data-inspection ="${response[i].detail_inspection_code}" data-toggle="modal" data-target="#detailSubDetailInspection">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>`:''
                var  button_log   = response[i].length == 1 ?`<button class="history btn btn-sm btn-secondary" title="History Progress" data-inspection ="${response[i].detail_inspection_code}" data-toggle="modal" data-target="#historyProgressModal">
                   <i class="fa-solid fa-clock-rotate-left"></i>
                </button>
                ` : ''


                if(response[i].subdetail_relation.length > 0 && response[i].lock == 0 ){
                    lock_button = `<button title="Lock Progress" class="lock btn btn-sm btn-danger" data-detail="${response[i].detail_inspection_code}">
                    <i class="fas fa-lock"></i>
                    </button>
                    `
                }
                var btn_approval = response[i].lock == 0 ? '': `
                <button class="approval btn btn-sm btn-primary" data-locationid="${response[i].header_relation.location_id}" data-shift="${response[i].shift}" data-location="${response[i].header_relation.location_relation.name}" title="Approval Log" data-detail="${response[i].detail_inspection_code}" data-toggle="modal" data-target="#logApprovalModal">
                    <i class="fas fa-person-circle-check"></i>
                    </button>
                `
                var btn_report = response[i].lock == 0 ? '': `
                <button class="export_progress btn btn-sm btn-success" data-detail="${response[i].detail_inspection_code}" data-toggle="modal" title="Export Progress" data-target="#reportProgressModal">
                  <i class="fa-solid fa-print"></i>
                    </button>
                `
                var btn_udpate_wt = response[i].length == 1 && response[i].lock== 0 ? `<button class="updateWT btn btn-sm btn-dark" title="Update Wall Thickness" data-inspection ="${response[i].detail_inspection_code}" data-toggle="modal" data-target="#updateWallThicknessModal">
                       <i class="fa-solid fa-file-word"></i>
                    </button>
                    ` : ''
                var status =''
                switch(response[i].status){
                    case 0:
                            status = 'Incomplete'
                        break
                    case 1:
                            status = 'Approval Progress'
                        break
                    case 2:
                            status = 'Complete'
                        break
                    case 3:
                            status = 'Approval Progress'
                        break
                }

                    row += `<tr class="table-light" style="width:100% !important">
                                <td style="text-align:center">${response[i].detail_inspection_code}</td>
                                <td style="text-align:center">${response[i].shift}</td>
                                <td style="text-align:center">${status}</td>
                                <td style="text-align:left">
                                    ${add_button}
                                    ${btn_udpate_wt}
                                    ${edit_button}
                                    ${detail_button}
                                    ${button_log}
                                    ${lock_button}
                                    ${btn_approval}
                                    ${btn_report}
                                </td>
                            </tr>`;

            }
            callback($(`
              <table class="table_detail table-bordered" style="width:100% !important">
                <thead>
                    <tr>
                        <th style="text-align:center">Detail Code</th>
                        <th style="text-align:center">Shift</th>
                        <th style="text-align:center">Status</th>
                        <th style="text-align:center">Action</th>
                    </tr>
                </thead>
              <tbody class="table-bordered">${row}</tbody>
            </table>`)).show();

        }else{
            toastr["error"]('Data tidak ada')
            $('#loading').hide();
        }
      })

    }
    function mapping_progress(subdetail){
        var data ={
            'inspection_sub_detail': subdetail
        }
        getCallback('detailInspectionProgress',data, function(response){
            swal.close()

            $('#label_inspector').html(response.detail.inspector_relation !== null ? ': ' + response.detail.inspector_relation.name : ': ')
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
    function mappingApproval(response, history) {
        var data = '';
        $('#history_approval_container').empty();

        for (i = 0; i < response.length; i++) {
            var remark = '';
            var date = '';
            var time = '';
            var info = `<button class="btn btn-sm ml-2 btn-dark btn-small"><i class="fa-solid fa-question fa-2xs"></i></button>`;
            if (history[i]) {
                const d = new Date(history[i].updated_at);
                if (history[i].status != 0) {
                    // Format date as 'd F Y'
                    date = d.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                    time = d.toTimeString().split(' ')[0];
                }
                switch (history[i].status) {
                    case 0:
                        info = `<button class="btn btn-sm ml-2 btn-info btn-small"><i class="fa-solid fa-hourglass-start fa-2xs"></i></button>`;
                        break;
                    case 1:
                        info = `<button class="btn btn-sm ml-2 btn-success btn-small"><i class="fas fa-check fa-2xs"></i></button>`;
                        break;
                }
            }
            remark = history[i] != null ? history[i].remark : '...still waiting for approval';

            data += `
                <div class="list-group list-group-flush p-0">
                    <div class="list-group-item list-group-item-action">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img alt="Image placeholder" src="../../assets/img/theme/team-1.jpg" class="avatar rounded-circle">
                            </div>
                            <div class="col ml--2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <h5 class="mb-0" style="font-size:12px !important">${response[i].user_relation.name}</h5>
                                        ${info}
                                    </div>
                                    <div class="text-right text-muted">
                                        <small style="font-size:9px !important">${date} ${time}</small>
                                    </div>
                                </div>
                                <p style="font-size:10px !important" class="text-sm mb-0">${remark}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        $('#history_approval_container').html(data);
    }
    function mappingHistory(history, wt) {
        var data = '';
        $('#history_table').DataTable().clear();
        $('#history_table').DataTable().destroy();

        for (var i = 0; i < history.length; i++) {
            const d = new Date(history[i].created_at);
            const date = d.toISOString().split('T')[0];
            const time = d.toTimeString().split(' ')[0];
            const dateTime = `${date} ${time}`;

            var creatorStyle = '';
            var machineStyle = '';
            var productStyle = '';
            var manufacturingCodeStyle = '';
            var lengthOfPipeStyle = '';
            var appearanceOfPipeStyle = '';
            var markingStyle = '';
            var conditionPipeOfCutStyle = '';
            var anglePipeOfSpigotStyle = '';
            var colorOfPipesStyle = '';
            var materialStyle = '';
            var tapeDiameterStyle = '';
            var evaluationSpecStyle = '';
            var inspectorStyle = '';
            var remarkStyle = '';

            if (i < history.length - 1) {
                if (history[i].creator_relation.name !== history[i + 1].creator_relation.name) creatorStyle = 'color:red !important;';
                if (history[i].machine_relation.name !== history[i + 1].machine_relation.name) machineStyle = 'color:red !important;';
                if (history[i].product_relation.marking !== history[i + 1].product_relation.marking) productStyle = 'color:red !important;';
                if (history[i].manufacturing_code !== history[i + 1].manufacturing_code) manufacturingCodeStyle = 'color:red !important;';
                if (history[i].length_of_pipe !== history[i + 1].length_of_pipe) lengthOfPipeStyle = 'color:red !important;';
                if (history[i].appearance_of_pipe !== history[i + 1].appearance_of_pipe) appearanceOfPipeStyle = 'color:red !important;';
                if (history[i].marking !== history[i + 1].marking) markingStyle = 'color:red !important;';
                if (history[i].condition_pipe_of_cut !== history[i + 1].condition_pipe_of_cut) conditionPipeOfCutStyle = 'color:red !important;';
                if (history[i].angle_pipe_of_spigot !== history[i + 1].angle_pipe_of_spigot) anglePipeOfSpigotStyle = 'color:red !important;';
                if (history[i].color_of_pipes !== history[i + 1].color_of_pipes) colorOfPipesStyle = 'color:red !important;';
                if (history[i].material_relation.name !== history[i + 1].material_relation.name) materialStyle = 'color:red !important;';
                if (history[i].tape_diameter !== history[i + 1].tape_diameter) tapeDiameterStyle = 'color:red !important;';
                if (history[i].evaluation_spec !== history[i + 1].evaluation_spec) evaluationSpecStyle = 'color:red !important;';
                if (history[i].remark !== history[i + 1].remark) remarkStyle = 'color:red !important;';
                if (history[i].inspector !== history[i + 1].inspector) inspectorStyle = 'color:red !important;';
            }
            data += `
                <tr>
                    <td style="width:10%;text-align:center">${dateTime}</td>
                    <td style="width:10%;${creatorStyle}">${history[i].creator_relation.name}</td>
                    <td style="width:10%;${inspectorStyle}">${history[i].inspector_relation.name}</td>
                    <td style="width:10%;${machineStyle}">${history[i].machine_relation.name}</td>
                    <td style="width:10%;${productStyle}">${history[i].product_relation.marking}</td>
                    <td style="width:10%;${manufacturingCodeStyle}">${history[i].manufacturing_code}</td>
                    <td style="text-align:center;${lengthOfPipeStyle}">${history[i].length_of_pipe}</td>
                    <td style="text-align:center;${appearanceOfPipeStyle}">${history[i].appearance_of_pipe}</td>
                    <td style="text-align:center;${markingStyle}">${history[i].marking}</td>
                    <td style="text-align:center;${conditionPipeOfCutStyle}">${history[i].condition_pipe_of_cut}</td>
                    <td style="text-align:center;${anglePipeOfSpigotStyle}">${history[i].angle_pipe_of_spigot}</td>
                    <td style="text-align:center;${colorOfPipesStyle}">${history[i].color_of_pipes}</td>
                    <td style="text-align:left;width:10%;${materialStyle}">${history[i].material_relation.name}</td>
                    <td style="text-align:center;${tapeDiameterStyle}">${history[i].tape_diameter}</td>

                    <td style="text-align:center;${evaluationSpecStyle}">${history[i].evaluation_spec}</td>
                    <td style="width:20%;">${history[i].remark}</td>
                </tr>
            `;
        }

        $('#history_table > tbody:first').html(data);
        $('#history_table').DataTable({
            scrollX: true,
            autoWidth: false,
            order: [[0, 'desc']],
            language: {
                paginate: {
                    previous: '<span class="prev-icon"><i class="fa-solid fa-arrow-left"></i></span>',
                    next: '<span class="next-icon"><i class="fa-solid fa-arrow-right"></i></span>'
                }
            }
        }).columns.adjust();
    }
    function mappingWT(response,id){
        var data =''

        $('#'+id).DataTable().clear();
        $('#'+id).DataTable().destroy();
        for(i = 0; i < response.length; i++ )
            {
                data +=`
                    <tr style ="text-align :left">
                        <td style="text-align:center">${i + 1}</td>
                        <td><input class="form-control update_wt" data-id="${response[i].id}" data-i ="${i}" type="number" id="dynamic_wt_1${i}" value="${response[i].wt_1}" /></td>
                        <td><input class="form-control update_wt" data-id="${response[i].id}" data-i ="${i}" type="number" id="dynamic_wt_2${i}" value="${response[i].wt_2}" /></td>
                        <td><input class="form-control update_wt" data-id="${response[i].id}" data-i ="${i}" type="number" id="dynamic_wt_3${i}" value="${response[i].wt_3}" /></td>
                        <td><input class="form-control update_wt" data-id="${response[i].id}" data-i ="${i}" type="number" id="dynamic_wt_4${i}" value="${response[i].wt_4}" /></td>
                        <td><input class="form-control update_wt" data-id="${response[i].id}" data-i ="${i}" type="number" id="dynamic_wt_5${i}" value="${response[i].wt_5}" /></td>
                        <td><input class="form-control update_wt" data-id="${response[i].id}" data-i ="${i}" type="number" id="dynamic_wt_6${i}" value="${response[i].wt_6}" /></td>
                        <td><input class="form-control update_wt" data-id="${response[i].id}" data-i ="${i}" type="number" id="dynamic_wt_7${i}" value="${response[i].wt_7}" /></td>
                        <td><input class="form-control update_wt" data-id="${response[i].id}" data-i ="${i}" type="number" id="dynamic_wt_8${i}" value="${response[i].wt_8}" /></td>
                    </tr>
                `
            }
        $(`#${id} > tbody:first`).html(data);
        $('#'+id).DataTable({
            scrollX  : true,
            language: {
                                'paginate': {
                                        'previous': '<span class="prev-icon"><i class="fa-solid fa-arrow-left"></i></span>',
                                        'next': '<span class="next-icon"><i class="fa-solid fa-arrow-right"></i></span>'
                                }
                            },
            // scrollY  :265
        }).columns.adjust()
    }
    $('#btn_add_wt').on('click', function(){
        var data ={
            'update_wt_1' : $('#update_wt_1').val(),
            'update_wt_2' : $('#update_wt_2').val(),
            'update_wt_3' : $('#update_wt_3').val(),
            'update_wt_4' : $('#update_wt_4').val(),
            'update_wt_5' : $('#update_wt_5').val(),
            'update_wt_6' : $('#update_wt_6').val(),
            'update_wt_7' : $('#update_wt_7').val(),
            'update_wt_8' : $('#update_wt_8').val(),
            'inspection_sub_detail' : $('#select_update_detail_inspection_code').val(),
            'detail_inspection_code' : $('#update_detail_inspection_code').val()
        }
        postCallback('addWallThickness', data, function(response){
            swal.close()
            $('#update_wt_1').val('')
            $('#update_wt_2').val('')
            $('#update_wt_3').val('')
            $('#update_wt_4').val('')
            $('#update_wt_5').val('')
            $('#update_wt_6').val('')
            $('#update_wt_7').val('')
            $('#update_wt_8').val('')
            toastr['success'](response.meta.message)
            var data_test = {
                'inspection_sub_detail' : $('#select_update_detail_inspection_code').val(),
                'detail_inspection_code' : $('#update_detail_inspection_code').val()
            }
            getCallbackNoSwal('getWallThickness', data_test, function(response){
                mappingWT(response.data,'wt_step')
            })
        })
    })
    $('#wt_step').on('change','.update_wt', function(){
        var id = $(this).data('id');
        var i = $(this).data('i')
        var $row = $(this).closest('tr');

        var data = {
            'id': id,
            'dynamic_wt_1': $row.find('#dynamic_wt_1' + i).val(),
            'dynamic_wt_2': $row.find('#dynamic_wt_2' + i).val(),
            'dynamic_wt_3': $row.find('#dynamic_wt_3' + i).val(),
            'dynamic_wt_4': $row.find('#dynamic_wt_4' + i).val(),
            'dynamic_wt_5': $row.find('#dynamic_wt_5' + i).val(),
            'dynamic_wt_6': $row.find('#dynamic_wt_6' + i).val(),
            'dynamic_wt_7': $row.find('#dynamic_wt_7' + i).val(),
            'dynamic_wt_8': $row.find('#dynamic_wt_8' + i).val(),
        };

        postCallbackNoSwal('updateWallThickness', data, function(response){
            toastr['success'](response.meta.message)
            var data_test = {
                'inspection_sub_detail' : $('#select_update_detail_inspection_code').val(),
                'detail_inspection_code' : $('#update_detail_inspection_code').val()
            }
            getCallbackNoSwal('getWallThickness', data_test, function(response){
                mappingWT(response.data,'wt_step')
            })

        })
     })
     $('#select_update_detail_inspection_code').on('change', function(){
        var data ={
            'inspection_sub_detail' : $('#select_update_detail_inspection_code').val(),
            'detail_inspection_code' : $('#update_detail_inspection_code').val()
        }
        getCallbackNoSwal('getWallThickness', data, function(response){
            mappingWT(response.data,'wt_step')
        })

     })

// Function
