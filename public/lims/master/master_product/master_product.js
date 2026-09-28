getCallback('getMasterProduct', null, function(response){
    swal.close()
    mappingTable(response.data)
})
$('#btn_upload_master_product').on('click', function(e){
    e.preventDefault();
    var data = new FormData();
    var fileInput = $('#upload_file')[0].files[0];
    
    if (fileInput) {
        data.append('upload_file', fileInput);
        postAttachment('uploadMasterProduct', data, true, function(response){
            swal.close();
            $('#uploadMasterProduct').modal('hide');
            $('.message_error').html('');
            toastr['success'](response.meta.message);
            getCallbackNoSwal('getMasterProduct', null, function(response){
                mappingTable(response.data);
                $('#upload_file').val('')
            });
        });
    } else {
        toastr['error']('Please select a file to upload.');
    }
});
$('#master_product_table').on('click', '.edit', function(){
    var id = $(this).data('id');
    var data = {
        'id' : id
    }
    $('#edit_id').val(id)
    getCallback('getDetailProductLog',data, function(response){
        swal.close()
        mappingTableLog(response.log)
        var kode_be = response.detail.kode_be =='' ? '-': response.detail.kode_be
        $('#label_seri').html(': ' + response.detail.seri)
        $('#label_join').html(': ' + response.detail.join)
        $('#label_marking').html(': ' + response.detail.marking)
        $('#label_kode_ts').html(': ' + response.detail.kode_ts)
        $('#label_kode_be').html(': ' + kode_be)

        $('#label_marking_2').html(': ' + response.detail.marking_2)
        $('#label_berat_pcs').html(': ' + response.detail.berat_pcs)
        $('#label_berat').html(': ' + response.detail.berat)
        $('#label_formula').html(': ' + response.detail.formula)
        $('#label_min_od').html(': ' + response.detail.min_od)
        $('#label_toleransi_od').html(': ' + response.detail.toleransi_od)
        $('#label_marking_od').html(': ' + response.detail.marking_od)
        $('#label_max_od').html(': ' + response.detail.max_od)
        $('#label_avg_od').html(': ' + response.detail.avg_od)
        $('#label_min_wt').html(': ' + response.detail.min_wt)
        $('#label_toleransi_wt').html(': ' + response.detail.toleransi_wt)
        $('#label_marking_wt').html(': ' + response.detail.marking_wt)
        $('#label_max_wt').html(': ' + response.detail.max_wt)
        $('#label_avg_wt').html(': ' + response.detail.avg_wt)
        

        $('#detail_berat_pcs').val(response.detail.berat_pcs)
        $('#detail_berat').val(response.detail.berat)
        $('#detail_formula').val(response.detail.formula)
        
        $('#detail_min_od').val(response.detail.min_od)
        $('#detail_toleransi_od').val(response.detail.toleransi_od)
        $('#detail_average_od').val(response.detail.average_od)
        $('#detail_max_od').val(response.detail.max_od)

        $('#detail_min_wt').val(response.detail.min_wt)
        $('#detail_toleransi_wt').val(response.detail.toleransi_wt)
        $('#detail_average_wt').val(response.detail.average_wt)
        $('#detail_max_wt').val(response.detail.max_wt)
        
    })
})
$('#btn_update_product').on('click', function(){
    var data = {
        'id' : $('#edit_id').val(),
        'detail_berat_pcs' : $("#detail_berat_pcs").val(),
        'detail_berat' : $("#detail_berat").val(),
        'detail_formula' : $("#detail_formula").val(),
        'detail_min_od' : $("#detail_min_od").val(),
        'detail_toleransi_od' : $("#detail_toleransi_od").val(),
        'detail_average_od' : $("#detail_average_od").val(),
        'detail_max_od' : $("#detail_max_od").val(),
        'detail_min_wt' : $("#detail_min_wt").val(),
        'detail_toleransi_wt' : $("#detail_toleransi_wt").val(),
        'detail_average_wt' : $("#detail_average_wt").val(),
        'detail_max_wt' : $("#detail_max_wt").val(),
    }
    postCallbackNoSwal('updateProduct', data, function(response){
        data_test = {
            'id': $('#edit_id').val()
        }
        toastr['success'](response.meta.message)
        getCallbackNoSwal('getDetailProductLog',data_test, function(response){
            mappingTableLog(response.log)
        });
        getCallbackNoSwal('getMasterProduct', null, function(response){
            mappingTable(response.data)
        })
    })
})
function mappingTable(response){
    var data = '';

    $('#master_product_table').DataTable().clear();
    $('#master_product_table').DataTable().destroy();
    for (i = 0; i < response.length; i++) {
        data += `
            <tr>
                <td style="text-align:center;width:5%">${response[i].seri}</td>
                <td style="text-align:center">${response[i].join}</td>
                <td style="text-align:center">${response[i].ukuran}</td>
                <td style="text-align:center">${response[i].marking}</td>
                <td style="text-align:center">${response[i].berat_pcs}</td>
                <td style="text-align:center">${response[i].berat}</td>
                <td style="text-align:center;">${response[i].kode_ts}</td>
                <td style="text-align:center;">${response[i].kode_be}</td>
                <td style="text-align:center">${response[i].marking_2}</td>
                <td style="text-align:center">${response[i].formula}</td>
                <td style="text-align:center">${response[i].min_od}</td>
                <td style="text-align:center">${response[i].toleransi_od}</td>
                <td style="text-align:center">${response[i].marking_od}</td>
                <td style="text-align:center">${response[i].avg_od}</td>
                <td style="text-align:center">${response[i].max_od}</td>
                <td style="text-align:center">${response[i].min_wt}</td>
                <td style="text-align:center">${response[i].toleransi_wt}</td>
                <td style="text-align:center">${response[i].marking_wt}</td>
                <td style="text-align:center">${response[i].avg_wt}</td>
                <td style="text-align:center">${response[i].max_wt}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit" data-id="${response[i].id}" data-toggle="modal" data-target="#editMasterProduct">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    $('#master_product_table > tbody:first').html(data);
    $('#master_product_table').DataTable({
        scrollX: true,    // Enable horizontal scrolling
        // scrollY: 265,     // Vertical scroll height
        fixedHeader: true, // Keep the header fixed while scrolling
        autoWidth: false, // Disable automatic column width adjustment
        language: {
            paginate: {
                previous: '<span class="prev-icon"><i class="fa-solid fa-arrow-left"></i></span>',
                next: '<span class="next-icon"><i class="fa-solid fa-arrow-right"></i></span>'
            }
        }
    }).columns.adjust(); // Adjust column sizes after initialization
}
function mappingTableLog(response){
    var data = '';

    $('#log_master_product_table').DataTable().clear();
    $('#log_master_product_table').DataTable().destroy();
    for (i = 0; i < response.length; i++) {
        const d = new Date(response[i].created_at)
        const date = d.toISOString().split('T')[0];
        const time = d.toTimeString().split(' ')[0];
        data += `
            <tr>

                <td style="text-align:center">${date} ${time}</td>
                <td style="text-align:left">${response[i].user_relation.name}</td>
                <td style="text-align:left">${response[i].berat_pcs}</td>
                <td style="text-align:left">${response[i].berat}</td>
                <td style="text-align:left">${response[i].formula}</td>
                <td style="text-align:left">${response[i].min_od}</td>
                <td style="text-align:left">${response[i].toleransi_od}</td>
                <td style="text-align:left">${response[i].marking_od}</td>
                <td style="text-align:left">${response[i].avg_od}</td>
                <td style="text-align:left">${response[i].max_od}</td>
                <td style="text-align:left">${response[i].min_wt}</td>
                <td style="text-align:left">${response[i].toleransi_wt}</td>
                <td style="text-align:left">${response[i].marking_wt}</td>
                <td style="text-align:left">${response[i].avg_wt}</td>
                <td style="text-align:left">${response[i].max_wt}</td>s 
            </tr>
        `;
    }

    $('#log_master_product_table > tbody:first').html(data);
    $('#log_master_product_table').DataTable({
        scrollX: true,    // Enable horizontal scrolling
        // scrollY: 265,     // Vertical scroll height
        fixedHeader: true, // Keep the header fixed while scrolling
        autoWidth: false, // Disable automatic column width adjustment
        language: {
            paginate: {
                previous: '<span class="prev-icon"><i class="fa-solid fa-arrow-left"></i></span>',
                next: '<span class="next-icon"><i class="fa-solid fa-arrow-right"></i></span>'
            }
        }
    }).columns.adjust(); // Adjust column sizes after initialization
}
