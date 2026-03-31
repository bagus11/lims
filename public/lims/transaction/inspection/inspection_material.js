
getCallback('getInspectionMaterial', null, function(response){
    swal.close()
   mappingTable(response.data)
})

$('#btn_add_header').on('click', function(){
    getActiveItems('getActiveMaterial', null,'select_material','Material');
    getActiveItems('getLocation', null,'select_location','Location');
    getActiveItems('getUser', null,'select_inspector','Inspector');
    getActiveItems('getSupplier', null,'select_supplier','Supplier');
    $('#type').val('')
    $('#location').val('')
    $('#material').val('')
    $('#inspector').val('')
    $('#supplier').val('')
    $('#brand').val('')
    $('#amount').val('')
    $('#weight').val('')
    $('#certificate').val('')
    $('#standard').val('')
    $('#lot_number').val('')
})
onChange('select_type','type')
onChange('select_location','location')
onChange('select_material','material')
onChange('select_inspector','inspector')
onChange('select_supplier','supplier')

$("#btn_save_header").on('click', function(){
    var data = {
        'type'  : $('#type').val(),
        'location'  : $('#location').val(),
        'material'  : $('#material').val(),
        'inspector'  : $('#inspector').val(),
        'supplier'  : $('#supplier').val(),
        'brand'  : $('#brand').val(),
        'amount'  : $('#amount').val(),
        'weight'  : $('#weight').val(),
        'certificate'  : $('#certificate').val(),
        'standard'  : $('#standard').val(),
        'no_lot'  : $('#no_lot').val(),
    }
    postCallback('addInspectionMaterial', data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#addInspectionHeader').modal('hide')
        getCallbackNoSwal('getInspectionMaterial', null, function(response){
            mappingTable(response.data)
        })
    })
})
$('#inspection_material_table').on('click','.resin',function(){
    var inspection = $(this).data('inspection')
    $('#inspection_id').val(inspection)
    var data ={
        'inspection_code'   : inspection
    }
    getCallback('getResinInspection', data, function(response){
        swal.close()

        // General Information
            $('#resin_inspection_code').html(': ' + response.detail.inspection_code)
            $('#resin_location').html(': ' + response.detail.location_relation.name)
            $('#resin_inspector').html(': ' + response.detail.inspector_relation.name)
            $('#resin_supplier').html(': ' + response.detail.supplier_relation.name)
            $('#resin_brand').html(': ' + response.detail.merk)
            $('#resin_standard').html(': ' + response.detail.standard)
            $('#resin_certificate').html(': ' + response.detail.certificate)
            $('#resin_weight').html(': ' + response.detail.weight +  ' kg')
            $('#resin_amount').html(': ' + response.detail.amount + ' sak')
            $('#resin_no_lot').html(': ' + response.detail.no_lot)
        // General Information
        // Bulk Density
            $('canvas#resin_graphic').remove()
            $('canvas#resin_graphic_container').remove();
            $('#resin_product_id').html(': ' + response.detail.material_relation.name)
            $("#resin_step").html(': ' + response.header.step)
            $('#resin_spec').html(': ' + response.detail.material_relation.spec)
            $('#resin_min').html(': ' + response.min.value)
            $('#resin_max').html(': ' + response.max.value)
            let limitedNumber = response.avg.toFixed(5);
            $('#resin_avg').html(': ' + limitedNumber)
            if(response.data.length > 0){
                var data = []
                var label =[]
                var masterColor ='#293462'
                var color = []
                var detail = ''
                $('#step_container').empty()
                for (let i = 0; i < response.data.length; i++) {
                    data.push(response.data[i].value);
                    label.push(response.data[i].step);
                    color.push(masterColor); // Assign the same masterColor to each item

                    // Ensure the input displays the full precision value
                    const formattedValue = parseFloat(response.data[i].value);

                    detail += `
                        <div class="col-3 mt-2">
                            <p>Step ${i + 1}</p>
                        </div>
                        <div class="col-3">
                            <input class="form-control resin_detail-number" style="text-align:center"
                                onchange="updateResin('${response.data[i].inspection_code}', '${response.data[i].step}', this.value)"
                                type="text"
                                data-step="${response.data[i].step}"
                                data-inspection="${response.data[i].inspection_code}"
                                value="${formattedValue}">
                        </div>
                    `;
                    document.querySelectorAll('.resin_detail-number').forEach(input => {
                        input.addEventListener('keydown', function (event) {
                            // Allow specific control keys
                            const allowedKeys = [
                                'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Enter', // Navigation keys
                                ','
                            ];

                            // Allow numbers
                            if (
                                allowedKeys.includes(event.key) ||
                                (event.key >= '0' && event.key <= '9') // Numbers
                            ) {
                                return; // Allow these keys
                            }

                            // Prevent everything else
                            event.preventDefault();
                        });

                        input.addEventListener('input', function () {
                            // As a fallback, sanitize any invalid input
                            this.value = this.value.replace(/[^0-9,]/g, ''); // Remove non-numeric and non-comma characters

                            // Prevent consecutive commas or starting with a comma
                            this.value = this.value.replace(/,,+/g, ','); // Replace multiple commas with a single comma
                            this.value = this.value.replace(/^,/, '');   // Remove leading comma
                        });
                    });
                }

                $('#step_container').html(detail)
                $('#resin_graphic_container').append('<canvas id="resin_graphic" style="width:100px !important; height:100px !important" ></canvas>')
                var canvas = document.getElementById("resin_graphic");
                var chart = canvas.getContext('2d')
                pieChart('Bulk Density',label,data,color,'line',chart)
            }
        // Bulk Density

        // Density Properties
            $('#select_vivsible_properties').val(response.header.visible_properties)
            $('#select_vivsible_properties').select2().trigger('change')
            $('#select_resin_package').val(response.header.packaging)
            $('#select_resin_package').select2().trigger('change')
            $('#resin_color').val(response.header.color)
            $('#resin_particle_size').val(response.header.particle_size)
            $('#resin_volatile_matter').val(response.header.volatile_matter)
            $('#resin_amount_per_sak').val(response.header.amount)
            $('#resin_weight_per_sak').val(response.header.weight)
            $('#resin_remark').val(response.detail.remark)
            $('#resin_pof_number').val(response.detail.no_pof)

        // Density Properties
    })
})
$('#btn_update_density_properties').on('click', function(){
    var data = {
        'inspection_code'  : $('#inspection_id').val(),
        'select_vivsible_properties' : $('#select_vivsible_properties').val(),
        'select_resin_package' : $('#select_resin_package').val(),
        'resin_color' : $('#resin_color').val(),
        'resin_particle_size' : $('#resin_particle_size').val(),
        'resin_volatile_matter' : $('#resin_volatile_matter').val(),
        'resin_amount_per_sak' : $('#resin_amount_per_sak').val(),
        'resin_weight_per_sak' : $('#resin_weight_per_sak').val(),
        'resin_pof_number' : $('#resin_pof_number').val(),
        'resin_remark' : $('#resin_remark').val(),
    }
    postCallback('updateResinProperties', data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#resinInspectionModal').modal('hide')
        getCallbackNoSwal('getInspectionMaterial', null, function(response){
            mappingTable(response.data)
        })
    })
})
$('#inspection_material_table').on('click','.pellet',function(){
    var inspection = $(this).data('inspection');
    var data ={
        'inspection_code' : inspection
    }
    $('#inspection_id').val(inspection)
    getCallback('getPelletInspection',data, function(response){
        swal.close()
        // General Information
            $('#pellet_inspection_code').html(': ' + response.detail.inspection_code)
            $('#pellet_location').html(': ' + response.detail.location_relation.name)
            $('#pellet_inspector').html(': ' + response.detail.inspector_relation.name)
            $('#pellet_supplier').html(': ' + response.detail.supplier_relation.name)
            $('#pellet_brand').html(': ' + response.detail.merk)
            $('#pellet_standard').html(': ' + response.detail.standard)
            $('#pellet_certificate').html(': ' + response.detail.certificate)
            $('#pellet_weight').html(': ' + response.detail.weight +  ' kg')
            $('#pellet_amount').html(': ' + response.detail.amount + ' sak')
            $('#pellet_no_lot').html(': ' + response.detail.no_lot)
        // General Information
        // Detail Inspectoin
                $('#select_pellet_visible_properties').val(response.header.visible_properties)
                $('#select_pellet_visible_properties').select2().trigger('change')
                $('#select_pellet_packaging').val(response.header.packaging)
                $('#select_pellet_packaging').select2().trigger('change')
                $('#pellet_amount_per_sak').val(response.header.amount_per_sak)
                $('#pellet_weight_per_sak').val(response.header.weight_per_sak)
                $('#pelet_mfr').val(response.header.mfr)
                $('#pellet_remark').val(response.detail.remark)
                $('#pellet_pof_number').val(response.detail.no_pof)
        // Detail Inspectoin

    })
})
$('#btn_save_pellet').on('click', function(){
    var data  ={
        'inspection_code' : $('#inspection_id').val(),
        'select_pellet_visible_properties' : $('#select_pellet_visible_properties').val(),
        'select_pellet_packaging' : $('#select_pellet_packaging').val(),
        'pellet_amount_per_sak' : $('#pellet_amount_per_sak').val(),
        'pellet_weight_per_sak' : $('#pellet_weight_per_sak').val(),
        'pelet_mfr' : $('#pelet_mfr').val(),
        'pellet_remark' : $('#pellet_remark').val(),
        'pellet_pof_number' : $('#pellet_pof_number').val(),
    }
    postCallback('updatePelletProperties', data, function(response){
        swal.close()
        toastr['success'](response.meta.message)
        $('#pelletInspectionModal').modal('hide')
        getCallbackNoSwal('getInspectionMaterial', null, function(response){
            mappingTable(response.data)
        })
    })
})
// Lock Inspection
    $('#inspection_material_table').on('click', '.lock', function(){
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
              var inspection = $(this).data('inspection')
              var data ={
                  'inspection_code' : inspection
              }
              postCallback('lockInspectionMaterial',data,function(response){
                  swal.close()
                  Swal.fire({
                    title: "locked!",
                    text: response.meta.message,
                    icon: "success"
                  });
              })
              getCallbackNoSwal('getInspectionMaterial', null, function(response){
                mappingTable(response.data)
            })
            }
          });

    })
// Lock Inspection

// Detail Inspection
    $('#inspection_material_table').on('click', '.detail', function(){
        var type = $(this).data('type')
        var inspection = $(this).data('inspection')
        var data ={
            'inspection_code'   : inspection
        }
        if( type==1){
            $('#detailResinModal').modal('show')
            getCallback('getResinInspection', data, function(response){
                swal.close()

                // General Information
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
                            document.querySelectorAll('.resin_detail-number').forEach(input => {
                                input.addEventListener('keydown', function (event) {
                                    // Allow specific control keys
                                    const allowedKeys = [
                                        'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Enter', // Navigation keys
                                        ','
                                    ];

                                    // Allow numbers
                                    if (
                                        allowedKeys.includes(event.key) ||
                                        (event.key >= '0' && event.key <= '9') // Numbers
                                    ) {
                                        return; // Allow these keys
                                    }

                                    // Prevent everything else
                                    event.preventDefault();
                                });

                                input.addEventListener('input', function () {
                                    // As a fallback, sanitize any invalid input
                                    this.value = this.value.replace(/[^0-9,]/g, ''); // Remove non-numeric and non-comma characters

                                    // Prevent consecutive commas or starting with a comma
                                    this.value = this.value.replace(/,,+/g, ','); // Replace multiple commas with a single comma
                                    this.value = this.value.replace(/^,/, '');   // Remove leading comma
                                });
                            });
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
            })
        }else{
            $('#detailPelletModal').modal('show')
            alert('test')
            getCallback('getPelletInspection',data, function(response){
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
                    console.log(response)
                    return false
                // Detail Inspectoin
                        $('#label_pellet_amount_per_sak').html(': ' + response.header.amount_per_sak +  ' sak')
                        $('#label_pellet_visible_properties').html(': ' + response.header.visible_properties)
                        $('#label_pellet_packaging').html(': ' + response.header.packaging)
                        $('#label_pellet_weight_per_sak').html(': ' + response.header.weight_per_sak +  ' kg')
                        $('#label_pelet_mfr').html(': ' + response.header.mfr + ' G/10 mm')
                        $('#label_pellet_remark').html(': ' + response.detail.remark)
                        $('#label_pellet_pof_number').html(': ' + response.detail.no_pof)
                // Detail Inspectoin

            })

        }
    })
// Detail Inspection

// Approval Log
    $('#inspection_material_table').on('click', '.approval',function(){
       var inspection = $(this).data('inspection')
       var location = $(this).data('location')
       var locations = $(this).data('locations')
       var detail = $(this).data('detail')
       var data ={
        'inspection_type' : 2,
        'location': location,
        'detail': detail,
        'shift' : 1
       }
       $('#approval_location').html(': ' + locations)
       $('#approval_shift').html(': 1')
        getCallback('getApprovalList',data, function(response){
            swal.close()
            mappingApproval(response.data, response.history)
        })
    })
// Approval Log


// Export
    $(document).on('click','.export_progress', function(e){
        var detail = $(this).data('detail')
        $('#export_code').val(detail)
    })
    $('#btn_export_progress').on('click', function(){
        var type            =  $('#select_report').val()
        var detail_code     =  $('#export_code').val().replaceAll("/","_");

        var data = {
            'detail_code' : $('#export_code').val(),
            'type'        : $('#select_report').val()
        }
        if(type == 1 ){
            window.open(`exportInspectionMaterials/${detail_code}`, '_blank');
        }
    })
// Export
// Function
function mappingTable(response){
    var data =''

        $('#inspection_material_table').DataTable().clear();
        $('#inspection_material_table').DataTable().destroy();
        for(i = 0; i < response.length; i++ )
            {
                var type = ''
                var status_table = ''
                switch(response[i].type){
                    case  1 :
                        type = 'Resin'
                        break
                    case 2 :
                        type = 'Pellet HDPE'
                }

                switch(response[i].status){
                    case 0 :
                        status_table = 'Draft'
                        break
                    case 1 :
                        status_table = 'In Progress'
                        break
                    case 2 :
                        status_table = 'Approval Progress'
                        break
                    case 3 :
                        status_table = 'DONE'
                        break

                    case 4:
                        status_table="Rejected"

                }
                var btn_lock =''
                var btn_edit = ''
                var btn_approval = ''
                var btn_report = ''
                var btn_detail = `
                  <button class="detail btn btn-sm btn-info"
                        data-inspection="${response[i].inspection_code}"
                        data-location="${response[i].location_relation.name}"
                        data-type="${response[i].type}"
                        data-shift="${response[i].shift}
                  ">
                                    <i class="fa-solid fa-eye"></i>
                  </button>
                `
                var btn_print = ''
                const d = new Date(response[i].created_at)
                const date = d.toISOString().split('T')[0];
                const time = d.toTimeString().split(' ')[0];
                const dateTime = date + ' ' + time
                if(response[i].lock == 0){
                    btn_lock = response[i].status == 1 ? `
                    <button class="lock btn btn-sm btn-danger" data-inspection="${response[i].inspection_code}">
                              <i class="fa-solid fa-lock"></i>
                             </button>
                  `:''

                    if(response[i].status == 0 || response[i].status == 1){
                        btn_edit = response[i].type ==1 ? `
                         <button class="resin btn btn-sm btn-dark" data-inspection="${response[i].inspection_code}" data-toggle="modal" data-target="#resinInspectionModal">
                                    <i class="fa-solid fa-flask-vial"></i>
                                   </button>
                       ` : `
                          <button class="pellet btn btn-sm btn-warning" data-inspection="${response[i].inspection_code}" data-toggle="modal" data-target="#pelletInspectionModal">
                                       <i class="fa-solid fa-vial-virus"></i>
                                   </button>
                       `
                    }
                }else{
                    btn_approval =`
                         <button class="approval btn btn-sm btn-primary"
                           data-location="${response[i].location}"
                           data-locations="${response[i].location_relation.name}"
                         data-detail="${response[i].inspection_code}"
                         data-shift="${response[i].shift}"
                         title="Approval Log"
                         data-inspection="${response[i].inspection_code}"
                         data-toggle="modal" data-target="#logApprovalModal">
                    <i class="fas fa-person-circle-check"></i>
                    </button>
                    `
                    btn_print =`
                     <button class="export_progress btn btn-sm btn-success" data-detail="${response[i].inspection_code}" data-toggle="modal" title="Export Progress" data-target="#reportProgressModal">
                            <i class="fa-solid fa-print"></i>
                    </button>
                    `
                }
                data +=`
                    <tr style ="text-align :left">
                        <td style="text-align: center">${dateTime}</td>
                        <td>${response[i].location_relation.name}</td>
                        <td style="text-align: center">${response[i].inspection_code}</td>
                        <td>${response[i].material_relation.name}</td>
                        <td>${type}</td>
                        <td>${status_table}</td>
                        <td style="width:20%">
                          ${btn_detail}
                          ${btn_approval}
                          ${btn_edit}
                          ${btn_lock}
                          ${btn_print}
                        </td>

                    </tr>
                `
            }
        $('#inspection_material_table > tbody:first').html(data);
        $('#inspection_material_table').DataTable({
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
function updateResin(x,y,z){
    var data = {
        'inspection_code' : x,
        'step' : y,
        'value' : z,
    }
    var data_test = {
        'inspection_code' : x
    }
    postCallbackNoSwal('updateResinDetail', data, function(response){
        // toastr['success'](response.meta.message)
        getCallbackNoSwal('getResinInspection', data_test, function(response){

        // General Information
            $('#resin_inspection_code').html(': ' + response.detail.inspection_code)
            $('#resin_location').html(': ' + response.detail.location_relation.name)
            $('#resin_inspector').html(': ' + response.detail.inspector_relation.name)
            $('#resin_supplier').html(': ' + response.detail.supplier_relation.name)
            $('#resin_brand').html(': ' + response.detail.merk)
            $('#resin_standard').html(': ' + response.detail.standard)
            $('#resin_certificate').html(': ' + response.detail.certificate)
            $('#resin_weight').html(': ' + response.detail.weight +  ' kg')
            $('#resin_amount').html(': ' + response.detail.amount + ' sak')
            $('#resin_no_lot').html(': ' + response.detail.no_lot)
        // General Information
        // Bulk Density
            $('canvas#resin_graphic').remove()
            $('canvas#resin_graphic_container').remove();
            $('#resin_product_id').html(': ' + response.detail.material_relation.name)
            $("#resin_step").html(': ' + response.header.step)
            $('#resin_spec').html(': ' + response.detail.material_relation.spec)
            $('#resin_min').html(': ' + response.min.value)
            $('#resin_max').html(': ' + response.max.value)
            let limitedNumber = response.avg.toFixed(5);
            $('#resin_avg').html(': ' + limitedNumber)
            if(response.data.length > 0){
                var data = []
                var label =[]
                var masterColor ='#293462'
                var color = []
                var detail = ''
                $('#step_container').empty()
                for (let i = 0; i < response.data.length; i++) {
                    data.push(response.data[i].value);
                    label.push(response.data[i].step);
                    color.push(masterColor); // Assign the same masterColor to each item

                    // Ensure the input displays the full precision value
                    const formattedValue = parseFloat(response.data[i].value);

                    detail += `
                        <div class="col-3 mt-2">
                            <p>Step ${i + 1}</p>
                        </div>
                        <div class="col-3">
                            <input class="form-control resin_detail-number" style="text-align:center"
                                onchange="updateResin('${response.data[i].inspection_code}', '${response.data[i].step}', this.value)"
                                type="text"
                                data-step="${response.data[i].step}"
                                data-inspection="${response.data[i].inspection_code}"
                                value="${formattedValue}">
                        </div>
                    `;
                    document.querySelectorAll('.resin_detail-number').forEach(input => {
                        input.addEventListener('keydown', function (event) {
                            // Allow specific control keys
                            const allowedKeys = [
                                'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Enter', // Navigation keys
                                ','
                            ];

                            // Allow numbers
                            if (
                                allowedKeys.includes(event.key) ||
                                (event.key >= '0' && event.key <= '9') // Numbers
                            ) {
                                return; // Allow these keys
                            }

                            // Prevent everything else
                            event.preventDefault();
                        });

                        input.addEventListener('input', function () {
                            // As a fallback, sanitize any invalid input
                            this.value = this.value.replace(/[^0-9,]/g, ''); // Remove non-numeric and non-comma characters

                            // Prevent consecutive commas or starting with a comma
                            this.value = this.value.replace(/,,+/g, ','); // Replace multiple commas with a single comma
                            this.value = this.value.replace(/^,/, '');   // Remove leading comma
                        });
                    });
                }
                $('#step_container').html(detail)
                $('#resin_graphic_container').append('<canvas id="resin_graphic" style="width:100px !important; height:100px !important" ></canvas>')
                var canvas = document.getElementById("resin_graphic");
                var chart = canvas.getContext('2d')
                pieChart('Bulk Density',label,data,color,'line',chart)
            }
        // Bulk Density

        // Density Properties
            $('#select_vivsible_properties').val(response.header.visible_properties)
            $('#select_vivsible_properties').select2().trigger('change')
            $('#select_resin_package').val(response.header.packaging)
            $('#select_resin_package').select2().trigger('change')
            $('#resin_color').val(response.header.color)
            $('#resin_particle_size').val(response.header.particle_size)
            $('#resin_volatile_matter').val(response.header.volatile_matter)
            $('#resin_amount_per_sak').val(response.header.amount)
            $('#resin_weight_per_sak').val(response.header.weight)
            $('#resin_remark').val(response.detail.remark)
            $('#resin_pof_number').val(response.detail.no_pof)

        // Density Properties
        })
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
// Function
