getCallback('getCategoryMachine',null, function(response){
    swal.close()
    mapping(response.data)
})
$('#btn_save_category').on('click', function(){
    var data ={
        'name' : $('#name').val(),
        'description' : $('#description').val(),
    }
    postCallback('addCategoryMachine', data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#name').val('')
        $('#description').val('')
        $('#addCategoryModal').modal('hide')
        getCallbackNoSwal('getCategoryMachine',null, function(response){
            mapping(response.data)
        })
    })
})
$('#btn_update_category').on('click', function(){
    var data ={
        'id'        :$('#edit_id').val(),
        'name_edit' : $('#name_edit').val(),
        'description_edit' : $('#description_edit').val(),
    }
    postCallback('updateCategoryMachine', data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#name_edit').val('')
        $('#description_edit').val('')
        $('#editCategoryModal').modal('hide')
        getCallbackNoSwal('getCategoryMachine',null, function(response){
            mapping(response.data)
        })
    })
})
$('#category_table').on("click", '.edit', function(){
    var id = $(this).data('id')
    var name = $(this).data('name')
    var description = $(this).data('description')

    $('#name_edit').val(name)
    $('#description_edit').val(description)
    $('#edit_id').val(id)
})
function mapping(response){
    var data =''
        
        $('#category_table').DataTable().clear();
        $('#category_table').DataTable().destroy();
        for(i = 0; i < response.length; i++ )
            {
                data +=`
                    <tr style ="text-align :left">
                        <td> ${response[i].name}</td>
                        <td style="width:70%"> ${response[i].description}</td>
                        <td style ="text-align :center"> 
                            <button class="edit btn btn-sm btn-warning btn-rounded" data-name="${response[i].name}" data-description="${response[i].description}" data-id ="${response[i].id}" data-toggle="modal" data-target="#editCategoryModal"> <i class="fas fa-edit"></i></button>
                        </td>
                        
                    </tr>
                `
            }
        $('#category_table > tbody:first').html(data);
        $('#category_table').DataTable({
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