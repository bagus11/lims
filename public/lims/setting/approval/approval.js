getCallback('getApprovalHeader', null, function(response){
    swal.close()
    mappingTable(response.data)
})
$('#stepApprovalModal').on('shown.bs.modal', function () {
    // Initialize or reinitialize Select2 for elements inside the modal
    $('#approver_step_table select.select2').select2({
        dropdownParent: $("#stepApprovalModal"),
        dropdownCssClass: "myFont",
        tags: true,
        minimumResultsForSearch: 0
    });
});
$('#btn_add_approval').on('click', function(){
    getActiveItems('getLocation', null,'select_location','Location')
    
})
onChange('select_location','location')
onChange('select_inspection_type','inspection_type')
onChange('select_type','type')
onChange('select_shift','shift')
$('#btn_save_approval_header').on('click', function(){
    var data ={
            'location' : $('#location').val(),
            'inspection_type' : $('#inspection_type').val(),
            'step' : $('#step').val(),
            'shift' : $('#shift').val(),
            'type' : $('#type').val(),
    }
    postCallback('addApprovalHeader', data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#addApprovalModal').modal('hide')
        getCallbackNoSwal('getApprovalHeader', null, function(response){
            mappingTable(response.data)
        })
    })
})
$('#approval_table').on('click', '.stepApproval',function(){
    var step = $(this).data('step')
    var id = $(this).data('id')
    var approval_code = $(this).data('approvalid')
    var data ={
        'id':id,
        'approval_code':approval_code,
    }
    $('#step_approval_code').val(approval_code)
    getCallbackNoSwal('getStepApproval',data,function(response){
            swal.close()
            mappingStepApprover(step,response.data)
    })
            
})
$('#btn_set_approver').on('click', function(){
    var select_approver =document.getElementsByClassName('select_approver');
    var array_approver =[]
     for(i =0; i< select_approver.length; i++){
         var post ={
             'step' : i+1,
             'user_id' :select_approver[i].value ,
         }
         array_approver.push(post)
     }
     var data ={
         'approval_id' : $('#step_approval_code').val(),
         'user_array'  : array_approver
     }
     if(array_approver.length == 0)
     {
         toastr['warning']('approver cannot be null');  
     }else{
         postCallback('updateApproval',data, function(response){
         swal.close()
         array_approver.length = 0;
         $('.message_error').html('')
         toastr['success'](response.meta.message);
         $('#stepApprovalModal').modal('hide')
         getCallback('getApprovalHeader',null,function(response){
             swal.close()
             mappingTable(response.data)
         })
     })
     }

 })
function mappingTable(response){
    var data =''
      
    $('#approval_table').DataTable().clear();
    $('#approval_table').DataTable().destroy();
    for(i = 0; i < response.length; i++ )
        {
            var type = ''
            var category = ''
            switch(response[i].inspection_type ){
                case 1:
                        type = 'Laborat'
                    break
                case 2:
                        type = 'Material'
                    break
                case 3:
                        type = 'Goods'
                    break
                case 4:
                        type = 'Resales Goods'
                    break
            }
            switch(response[i].type ){
                case 1:
                        category = 'Inspection Type 1'
                    break
                case 2:
                        category = 'Inspection Type 2'
                    break
                case 3:
                        category = 'Inspection Type 3'
                    break
                case 4:
                        category = 'Inspection Type 4'
                    break
            }
            var btn_detail = `
              <button title="Detail Step Approval" class="stepApproval btn btn-sm btn-primary rounded"  data-step="${response[i].step}" data-approvalid="${response[i].approval_code}" data-id="${response[i]['id']}" data-toggle="modal" data-target="#stepApprovalModal">
                                            <i class="fas fa-solid fa-user"></i>
                    </button>
            `
            data +=`
                <tr style ="text-align :left">
                 <td style="text-align: center;"> <input type="checkbox" id="check" name="check" class="check" style="border-radius: 5px !important;" value="${response[i]['id']}"  data-status="${response[i]['status']}" data-id="${response[i]['id']}" ${response[i]['status'] == 1 ?'checked':'' }></td>
                    <td style="text-align: center;"> ${response[i].status == 1 ? 'Active' : 'Inactive'}</td>
                    <td style="text-align: center;"> ${response[i].approval_code}</td>
                    <td> ${response[i].location_relation.name}</td>
                    <td> ${type}</td>
                    <td style="width:30% !important"> ${category}</td>
                    <td style="text-align: center"> ${response[i].shift}</td>
                    <td style="text-align:center">
                        <button title="Detail Step Approval" class="stepApproval btn btn-sm btn-primary rounded"   data-step="${response[i].step}" data-approvalid="${response[i].approval_code}" data-id="${response[i]['id']}" data-toggle="modal" data-target="#stepApprovalModal">
                                            <i class="fas fa-solid fa-user"></i>
                        </button>
                    </td>
                </tr>
            `
        }
    $('#approval_table > tbody:first').html(data);
    $('#approval_table').DataTable({
        scrollX  : true,
        language: {
                            'paginate': {
                                    'previous': '<span class="prev-icon"><i class="fa-solid fa-arrow-left"></i></span>',
                                    'next': '<span class="next-icon"><i class="fa-solid fa-arrow-right"></i></span>'
                            }
                        },
        scrollY  :265
    }).columns.adjust()
}

function mappingStepApprover(step, response) {
    var data = '';

    // Clear and destroy DataTable before re-initializing
    $('#approver_step_table').DataTable().clear().destroy();

    // Generate rows dynamically
    for (let i = 0; i < step; i++) {
        var selectTitle = 'select_approver_' + i;
        data += `
            <tr>
                <td style="width:10%; text-align:center;">${i + 1}</td>
                <td style="width:90%">
                    <select name="${selectTitle}" class="select2 select_approver" style="font-size:9px;" id="${selectTitle}">
                        <option value="">Choose Approver</option>
                    </select>
                </td>
            </tr>
        `;
        getApproval(response[i] ? response[i].user_id : '', selectTitle);
    }

    // Insert new rows into the table
    $('#approver_step_table > tbody:first').html(data);

    // Initialize DataTable again
    $('#approver_step_table').DataTable({
        scrollX: false,
        language: {
            'paginate': {
                'previous': '<span class="prev-icon"><i class="fa-solid fa-arrow-left"></i></span>',
                'next': '<span class="next-icon"><i class="fa-solid fa-arrow-right"></i></span>'
            }
        }
    }).columns.adjust();

    // Initialize Select2 only for new elements
    $('#approver_step_table select.select2').select2({
        dropdownCssClass: "myFont",
        tags: true,
        minimumResultsForSearch: 0
    });
}

function getApproval(response, title) {
    getCallbackNoSwal('getUser', null, function(res) {
        var $select = $('#' + title);
        $select.empty().append('<option value="">Choose Approver</option>');
        
        $.each(res.data, function(i, data) {
            $select.append('<option data-name="' + data.name + '" value="' + data.id + '">' + data.name + '</option>');
        });

        if (response) {
            $select.val(response).trigger('change');
        }
    });
}