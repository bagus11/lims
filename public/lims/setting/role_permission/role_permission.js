
    // Call Function
    getRole()
    getPermission()
    // End Call Function

    // Operation
        // Add Role
            $('#btn_add_role').on('click', function(){
                $('#roles_name').val('')
                $('.message_error').html('')
            })
            $('#btn_save_role').on('click', function(){
                var data={
                    'roles_name':$('#roles_name').val()
                }
                postCallback('addRole',data,function(response){
                    swal.close();
                    $('.message_error').html('')
                    toastr['success'](response.meta.message);
                    window.location = 'role_permission'
                });
            })
        // End Add Role

        // Edit Role
            $('#roles_table').on('click', '.editRoles', function(){
                var id = $(this).data('id')
                var data ={
                    'id':id
                }
                getCallback('detailRole', data,function(response){
                    swal.close()
                    $('#roles_name_edit').val(response.detail.name)
                    $('#roleId').val(id)
                })
              
            })

            $('#btn_update_role').on('click', function(){
                var data ={
                    'id':$('#roleId').val(),
                    'roles_name_edit':$('#roles_name_edit').val()
                }
                postCallback('updateRole',data,function(response){
                    swal.close();
                    $('.message_error').html('')
                    toastr['success'](response.meta.message);
                    window.location = 'role_permission'
                });
            })
        // End Edit Role

        // Delete Role
            $('#roles_table').on('click','.deleteRoles', function(){
                var data ={
                    'id':$(this).data('id')
                }
                getCallback('deleteRole', data,function(response){
                    swal.close()
                    if(response.status == 200){
                        toastr['success'](response.message);
                        getRole()
                    }else{
                        toastr['error'](response.message);
                    }
                  
                })

            })
        // Delete Role

        // Add Permission
            $('#btn_add_permission').on('click',function(){
                $('.message_error').html('')
                getCallback('permissionMenus',null,function(response){
                    swal.close();
                    $('#menus_name').empty();
                    $('#menus_name').append('<option value ="">Choose Menus</option>');
                    $.each(response.menus_name,function(i,data){
                        $('#menus_name').append('<option value="'+data.link+'">' + data.name +'</option>');
                    });
                })
            })
            $('#option').on('change', function(){
                var option = $('#option').val()
                var menus_name = $('#menus_name').val()
                $('#permission_name').val(option+'-'+menus_name)
            })
            $('#menus_name').on('change', function(){
                var option = $('#option').val()
                var menus_name = $('#menus_name').val()
                $('#permission_name').val(option+'-'+menus_name)
            })

            $('#btn_save_permission').on('click', function(){
                var data={
                    'permission_name':$('#permission_name').val(),
                }
                postCallback('savePermission',data,function(response){
                    swal.close()
                    if(response.status ==200){
                        $('#addPermissionModal').modal('hide');
                        toastr['success'](response.message);
                        getPermission()

                    }else{
                        toastr['error'](response.message);
                    }
                    
                })
                
            })
        // Add Permission

        // Delete Permission
            $('#permission_table').on('click', '.deletePermission', function(){
                var data ={
                    'id':$(this).data('id')
                }
                getCallback('deletePermission',data,function(response){
                    swal.close()
                    if(response.status == 200){
                        toastr['success'](response.message);
                        getPermission()
                    }else{
                        toastr['error'](response.message);
                    }
                })
            })
        // Delete Permission

    // End Operation

    // Function
        function getRole(){
            $('#roles_table').DataTable().clear();
            $('#roles_table').DataTable().destroy();
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "getRole",
                type: "get",
                dataType: 'json',
                async: true,
                beforeSend: function() {
                    SwalLoading('Please wait ...');
                },
                success: function(response) {
                    swal.close();
                    var data=''
                    for(i = 0; i < response.data.length; i++ )
                    {
                        data += `<tr style="text-align: center;">
                                <td style="text-align: left;">${response.data[i]['name']==null?'':response.data[i]['name']}</td>
                                <td style="width:25%;text-align:center">
                                       
                                        <button title="Detail" class="editRoles btn btn-sm btn-primary rounded"data-id="${response.data[i]['id']}" data-toggle="modal" data-target="#editRolesModal">
                                           <i class="fa-solid fa-circle-info"></i>
                                        </button>
                                        <button title="Delete" class="deleteRoles btn btn-sm btn-danger"data-id="${response.data[i]['id']}">
                                        <i class="fas fa-solid fa-trash"></i>
                                        </button>   
                                        
                                </td>
                            </tr>
                            `;
                    }
                        $('#roles_table > tbody:first').html(data);
                        $('#roles_table').DataTable({
                            scrollX  : true,
                            searching  :true,
                            language: {
                                'paginate': {
                                'previous': '<span class="prev-icon"><i class="fa-solid fa-arrow-left"></i></span>',
                                'next': '<span class="next-icon"><i class="fa-solid fa-arrow-right"></i></span>'
                                }
                            },
                            scrollY  :230
                        }).columns.adjust()
                },
                error: function(xhr, status, error) {
                    swal.close();
                    toastr['error']('Failed to get data, please contact Developer');
                }
            });
        }

        function getPermission(){
            $('#permission_table').DataTable().clear();
            $('#permission_table').DataTable().destroy();
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "getPermission",
                type: "get",
                dataType: 'json',
                async: true,
                beforeSend: function() {
                    SwalLoading('Please wait ...');
                },
                success: function(response) {
                    swal.close();
                    var data=''
                    for(i = 0; i < response.data.length; i++ )
                    {
                        data += `<tr style="text-align: center;">
                                <td style="text-align: left;">${response.data[i]['name']==null?'':response.data[i]['name']}</td>
                                <td style="width:25%;text-align:center">
                                        <button title="Delete" class="deletePermission btn-sm btn btn-danger"data-id="${response.data[i]['id']}">
                                        <i class="fas fa-solid fa-trash"></i>
                                        </button>   
                                        
                                </td>
                            </tr>
                            `;
                    }
                        $('#permission_table > tbody:first').html(data);
                        $('#permission_table').DataTable({
                            scrollX  : true,
                            searching  :true,
                            language: {
                                'paginate': {
                                'previous': '<span class="prev-icon"><i class="fa-solid fa-arrow-left"></i></span>',
                                'next': '<span class="next-icon"><i class="fa-solid fa-arrow-right"></i></span>'
                                }
                            },
                            scrollY  :230
                        }).columns.adjust()
                },
                error: function(xhr, status, error) {
                    swal.close();
                    toastr['error']('Failed to get data, please contact Developer');
                }
            });
        }
    // Function
