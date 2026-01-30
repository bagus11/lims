getCallback('getMachine', null, function(response){
    swal.close()
    mapping(response.data)
})
getActiveItems('getCategoryMachine',null,'select_category_edit','Category')
$('#btn_add_machine').on('click', function(){
    getActiveItems('getCategoryMachine',null,'select_category','Category')
    $('#name').val('')
    $('#description').val('')
    $('#category_id').val('')
    $('#no_machine').val('')
    $('.message_error')
})

$("#btn_save_machine").on('click', function(){
    var data ={
        'name'  :$("#name").val(),
        'description'  :$("#description").val(),
        'no_machine'  :$("#no_machine").val(),
        'category_id'  :$("#category_id").val(),
    };
    postCallback('addMachine',data, function(response){
        swal.close()
        $('#addMachineModal').modal('hide')
        toastr['success'](response.meta.message)
        getCallbackNoSwal('getMachine', null, function(response){
            mapping(response.data)
        })
    })
})
onChange('select_category','category_id')

$('#machine_table').on('change', '.check', function(){
    var data = {'id' : $(this).data('id'), 'status' : $(this).data('status')}
    postCallbackNoSwal('udpateStatusMachine',data,function(response){
        toastr['success'](response.meta.message)
        getCallbackNoSwal('getMachine', null, function(response){
            mapping(response.data)
        })
    })
})
$('#machine_table').on('click','.edit', function(){
    var id = $(this).data('id')
    var description = $(this).data('desc')
    var no_machine = $(this).data('no')
    var name = $(this).data('name')
    var category = $(this).data('category')

    $('#name_edit').val(name)
    $('#category_id_edit').val(category)
    $('#description_edit').val(description)
    $('#no_machine_edit').val(description)
    $('#edit_id').val(id)
    $('#select_category_edit').val(category)
    $('#select_category_edit').select2().trigger('change')

})
onChange('select_category_edit','category_id_edit')
$('#btn_update_machine').on('click', function(){
    var data ={
        'name_edit' : $('#name_edit').val(),
        'category_id_edit' : $('#category_id_edit').val(),
        'no_machine_edit' : $('#no_machine_edit').val(),
        'description_edit' : $('#description_edit').val(),
        'id'            : $('#edit_id').val()
    }
    postCallback('updateMachine',data,function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#editMasterMachineModal').modal('hide')
        getCallbackNoSwal('getMachine', null, function(response){
            mapping(response.data)
        })
    })
})
function mapping(response){
    var data =''
        
        $('#machine_table').DataTable().clear();
        $('#machine_table').DataTable().destroy();
        for(i = 0; i < response.length; i++ )
            {
                data +=`
                    <tr style ="text-align :left">
                     <td style="text-align: center;"> <input type="checkbox" id="check" name="check" class="check" style="border-radius: 5px !important;" value="${response[i]['id']}"  data-status="${response[i]['status']}" data-id="${response[i]['id']}" ${response[i]['status'] == 1 ?'checked':'' }></td>
                        <td> ${response[i].status == 1 ? 'Active' : 'Inactive'}</td>
                        <td> ${response[i].no_machine}</td>
                        <td> ${response[i].name}</td>
                        <td> ${response[i].category_relation.name}</td>
                        <td style ="text-align :center"> 
                            <button class="edit btn btn-sm btn-warning btn-rounded" data-name="${response[i].name}" data-category="${response[i].category_id}" data-no="${response[i].no}" data-id ="${response[i].id}" data-desc="${response[i].description}"  data-toggle="modal" data-target="#editMasterMachineModal"> <i class="fas fa-edit"></i></button>
                        </td>
                        
                    </tr>
                `
            }
        $('#machine_table > tbody:first').html(data);
        $('#machine_table').DataTable({
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