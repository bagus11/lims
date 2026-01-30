
getCallback('getMenus',null,function(response){
    swal.close()
    mappingTable(response.data)
})
getCallbackNoSwal('getSubMenus', null, function(response){
    mappingTableSub(response.data)
})
onChange('select_type','type_id');
onChange('select_type_edit','type_edit');
onChange('select_parent_edit','parent_id_edit');
$('#btn_save_menus').on('click', function(){
    var data ={
        'name' : $('#name').val(),
        'type_id' : $('#type_id').val(),
        'icon' : $('#icon').val(),
        'link' : $('#link').val(),
    }
    postCallback('addMenus', data,function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#addMenusModal').modal('hide')
        $('#name').val('')
        $('#type_id').val('')
        $('#select_type').val('')
        $('#select_type').select2().trigger('change')
        $('#icon').val('')
        $('#link').val('')
       
        getCallbackNoSwal('getMenus',null,function(response){
            mappingTable(response.data)
        })
    })
})

getActiveItems('getActiveMenus',null, 'select_parent_edit','Menus')
$('#btn_add_subemnus').on('click', function(){
    getActiveItems('getActiveMenus',null, 'select_parent','Menus')
})
onChange('select_parent','parent_id');
$('#btn_save_submenus').on('click', function(){
    var data ={
        'submenus_name' : $('#submenus_name').val(),
        'submenus_link' : $('#submenus_link').val(),
        'submenus_icon' : $('#submenus_icon').val(),
        'parent_id'     : $('#parent_id').val(),
    }
    postCallback('addSubmenus',data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#addSubmenusModal').modal('hide')
        getCallbackNoSwal('getSubMenus',null,function(response){
            mappingTableSub(response.data)
        })
    })
})
$('#menus_table').on('click', '.edit', function(){
    $('#status_container').empty();  // Use '#' to select the element by ID
    var id = $(this).data('id');
    var name = $(this).data('name');
    var link = $(this).data('link');
    var type = $(this).data('type');
    var status = $(this).data('status');
    var icon = $(this).data('icon');
    
    $('#menus_id').val(id);
    $('#name_edit').val(name);
    $('#icon_edit').val(icon);
    $('#link_edit').val(link);
    $('#select_type_edit').val(type);
    $('#select_type_edit').select2().trigger('change');
    $('#type_edit').val(type);

    var status_checked = status == 1 ? 'checked' : '';
    var status_result = `
        <input type="checkbox" ${status_checked} data-toggle="toggle" data-style="ios" data-size="md">
    `;
    $('#status_container').html(status_result); // Append the toggle switch to the container
    $('#status_container input[data-toggle="toggle"]').bootstrapToggle();
});

$('#btn_update_menus').on('click', function(){
    var data ={
        'id' : $('#menus_id').val(),
        'name_edit' : $('#name_edit').val(),
        'icon_edit' : $('#icon_edit').val(),
        'link_edit' : $('#link_edit').val(),
        'type_edit' : $('#type_edit').val(),
        'status'    : $('#status').val(),
    }
    postCallback('updateMenus', data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#editMenusModal').hide()
        getCallbackNoSwal('getMenus',null,function(response){
            mappingTable(response.data)
        })
    })
})
$('#submenus_table').on('click', '.edit', function(){
    var menu = $(this).data('menu');
    var id = $(this).data('id');
    var link = $(this).data('link');
    var name = $(this).data('name');
    var icon = $(this).data('icon');
    var status = $(this).data('status');

    $('#submenus_name_edit').val(name);
    $('#submenus_link_edit').val(link);
    $('#submenus_icon_edit').val(icon);
    $('#select_parent_edit').val(menu)
    $('#select_parent_edit').select2().trigger('change')
    
    var status_checked = status == 1 ? 'checked' : '';
    var status_result = `
        <input type="checkbox" ${status_checked} data-toggle="toggle" data-style="ios" data-size="md">
    `;
    $('#status_container_submenus').html(status_result); // Append the toggle switch to the container
    // Initialize the toggle switch
    $('#status_container_submenus input[data-toggle="toggle"]').bootstrapToggle();
})
$('#btn_update_submenus').on('click', function(){
    var data ={
        'submenus_name_edit' : $('#submenus_name_edit').val(),
        'submenus_link_edit' : $('#submenus_link_edit').val(),
        'submenus_icon_edit' : $('#submenus_icon_edit').val(),
        'parent_id_edit'     : $('#parent_id_edit').val(),
    }
    postCallback('updateSubmenus',data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#editSubmenusModal').modal('hide')
        getCallbackNoSwal('getSubMenus',null,function(response){
            mappingTableSub(response.data)
        })
    })
})
function mappingTable(response){
    var data =''
        
        $('#menus_table').DataTable().clear();
        $('#menus_table').DataTable().destroy();
        for(i = 0; i < response.length; i++ )
            {
                var type = response[i].type == 1 ? 'Menu' : 'Submenu'
                var status = response[i].status == 1 ? 'Active' : 'Inactive'
                data +=`
                    <tr style ="text-align :left">
                        <td> ${response[i].name}</td>
                        <td> ${type}</td>
                        <td> ${status}</td>
                        <td style ="text-align :center"> 
                            <button class="edit btn btn-sm btn-warning btn-rounded" data-name="${response[i].name}"  data-icon="${response[i].icon}" data-status="${response[i].status}" data-type="${response[i].type}" data-link="${response[i].link}" data-id ="${response[i].id}" data-toggle="modal" data-target="#editMenusModal"> <i class="fas fa-edit"></i></button>
                        </td>
                        
                    </tr>
                `
            }
        $('#menus_table > tbody:first').html(data);
        $('#menus_table').DataTable({
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

function mappingTableSub(response){
    var data =''
        
        $('#submenus_table').DataTable().clear();
        $('#submenus_table').DataTable().destroy();
        for(i = 0; i < response.length; i++ )
            {
                var status = response[i].status == 1 ? 'Active' : 'Inactive'
                data +=`
                    <tr style ="text-align :left">
                        <td> ${response[i].name}</td>
                        <td> ${response[i].menus_relation.name}</td>
                        <td> ${status}</td>
                        <td style ="text-align :center"> 
                            <button class="edit btn btn-sm btn-warning btn-rounded"  data-name="${response[i].name}"   data-status="${response[i].status}" data-link="${response[i].link}" data-id ="${response[i].id}" data-icon="${response[i].icon}" data-menu="${response[i].id_menu}" data-toggle="modal" data-target="#editSubmenusModal"> <i class="fas fa-edit"></i></button>
                        </td>
                        
                    </tr>
                `
            }
        $('#submenus_table > tbody:first').html(data);
        $('#submenus_table').DataTable({
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