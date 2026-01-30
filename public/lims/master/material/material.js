getCallback('getMaterial',null, function(response){
    swal.close()
    mapping(response.data)
})
$('#btn_save_material').on('click', function(){
    var data ={
        'name' : $('#name').val(),
        'spec' : $('#spec').val(),
        'description' : $('#description').val(),
    }
    postCallback('addMaterial', data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#addMaterialModal').modal('hide')
        getCallbackNoSwal('getMaterial',null, function(response){
            mapping(response.data)
        })
    })
})
$('#material_table').on('change', '.check', function(){
    var data = {'id' : $(this).data('id'), 'status' : $(this).data('status')}
    postCallbackNoSwal('udpateStatusMaterial',data,function(response){
        toastr['success'](response.meta.message)
        getCallbackNoSwal('getMaterial',null, function(response){
            mapping(response.data)
        })
    })
})
$('#material_table').on('click', '.edit', function(){
    var id = $(this).data('id')
    var name = $(this).data('name')
    var spec = $(this).data('spec')
    var description = $(this).data('desc')
    $('#edit_id').val(id)
    $('#name_edit').val(name)
    $('#spec_edit').val(spec)
    $('#description_edit').val(description)
})
$('#btn_update_material').on('click', function(){
    var data ={
        'id' : $('#edit_id').val(),
        'name_edit' : $('#name_edit').val(),
        'spec_edit' : $('#spec_edit').val(),
        'description_edit' : $('#description_edit').val(),
    }
    postCallback('updateMaterial', data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#editMaterialModal').modal('hide')
        getCallbackNoSwal('getMaterial',null, function(response){
            mapping(response.data)
        })
    })
})
function mapping(response){
    var data =''
        
        $('#material_table').DataTable().clear();
        $('#material_table').DataTable().destroy();
        for(i = 0; i < response.length; i++ )
            {
                data +=`
                    <tr style ="text-align :left">
                     <td style="text-align: center;"> <input type="checkbox" id="check" name="check" class="check" style="border-radius: 5px !important;" value="${response[i]['id']}"  data-status="${response[i]['status']}" data-id="${response[i]['id']}" ${response[i]['status'] == 1 ?'checked':'' }></td>
                        <td> ${response[i].status == 1 ? 'Active' : 'Inactive'}</td>
                        <td> ${response[i].name}</td>
                        <td> ${response[i].spec}</td>
                        <td style ="text-align :center"> 
                            <button class="edit btn btn-sm btn-warning btn-rounded" data-spec="${response[i].spec}" data-name="${response[i].name}" data-id ="${response[i].id}" data-desc="${response[i].description}"  data-toggle="modal" data-target="#editMaterialModal"> <i class="fas fa-edit"></i></button>
                        </td>
                        
                    </tr>
                `
            }
        $('#material_table > tbody:first').html(data);
        $('#material_table').DataTable({
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