   
    function saveHelper(url,data,route){
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            type: "post",
            dataType: 'json',
            async: true,
            data: data,
            beforeSend: function() {
                SwalLoading('Please wait ...');
            },
            success: function(response) {
                swal.close();
                $('.message_error').html('')
                toastr['success'](response.meta.message);
                window.location = route;
            },
            error: function(response) {
                $('.message_error').html('')
                swal.close();
                if(response.status == 500){
                    console.log(response)
                    toastr['error'](response.responseJSON.meta.message);
                    return false
                }
                if(response.status === 422)
                {
                    $.each(response.responseJSON.errors, (key, val) => 
                        {
                            $('span.'+key+'_error').text(val)
                        });
                }else{
                    toastr['error']('Failed to get data, please contact Developer');
                }
            }
        });
    }
    function saveRepo(url,data,route){
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "post",
                dataType: 'json',
                async: true,
                data: data,
                beforeSend: function() {
                    SwalLoading('Please wait ...');
                },
                success: function(response) {
                    swal.close();
                    if(response.status==500){
                        toastr['warning'](response.message);
                    }
                    else{
                        toastr['success'](response.message);
                        window.location = route;
                    }
                    
                },
                error: function(xhr, status, error) {
                    swal.close();
                    toastr['error']('Failed to get data, please contact ICT Developer');
                }
            });
        }
    function onChange(selectId, id){
        $('#'+selectId).on('change', function(){
            var x = $('#'+ selectId).val()
            $('#'+id).val(x)
        })
    }
    function onChangeSelect(selectId, id){
        $('#'+selectId).on('change', function(){
            var x = $('#'+ selectId).val()
            $('#'+id).val(x)
        })
    }
    function getActiveItems(url,data,id,name){
        $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                async: true,
                data : data,
                success: function(response) {
                    $('#'+id).empty()
                    $('#'+id).append('<option value ="">Choose '+ name +'</option>');
                    $.each(response.data,function(i,data){
                        $('#'+id).append('<option data-name="'+ data.name +'" value="'+data.id+'">' + data.name +'</option>');
                    });
                },
                error: function(response) {
                    toastr['error']('Failed to get data, please contact Developer');
                }
            });   
    }
    function getActiveItemTransaction(url,data,id,name){
        $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                async: true,
                data : data,
                beforeSend: function() {
                    SwalLoading('Please wait ...');
                },
                success: function(response) {
                swal.close();
                    $('#'+id).empty()
                    $('#'+id).append('<option value ="">Choose '+ name +'</option>');
                    $.each(response.data,function(i,data){
                        $('#'+id).append('<option data-max ="'+ data.max_transaction +'" data-name="'+ data.name +'" value="'+data.id+'">' + data.name +'</option>');
                    });
                },
                error: function(response) {
                    swal.close();
                    toastr['error']('Failed to get data, please contact Developer');
                }
            });   
    }
    function getProductItems(url,data,id,name){
        $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                async: true,
                data : data,
                beforeSend: function() {
                    SwalLoading('Please wait ...');
                },
                success: function(response) {
                    swal.close();
                    $('#'+id).empty()
                    $('#'+id).append('<option value ="">Choose '+ name +'</option>');
                    $.each(response.data,function(i,data){
                        $('#'+id).append('<option data-uom="'+data.uom+'" data-buffer="'+ data.quantity_buffer +'" data-min="'+ data.min_quantity +'" data-max="'+ data.max_quantity +'"  data-name="'+ data.name +'" value="'+data.id+'">' + data.name +'</option>');
                    });
                },
                error: function(response) {
                    swal.close();
                    toastr['error']('Failed to get data, please contact Developer');
                }
            });   
    }
    
    function postCallback(route,data,callback){
        $.ajax({
            url: route,
            type: "post",
            dataType: 'json',
            data:data,
            async: true,
            beforeSend: function() {
                SwalLoading('Please wait ...');
            },
            success:callback,
            error: function(response) {
                $('.message_error').html('')
                swal.close();
                if(response.status == 500){
                    console.log(response)
                    toastr['error'](response.responseJSON.meta.message);
                    return false
                }
                if(response.status === 422)
                {
                    $.each(response.responseJSON.errors, (key, val) => 
                        {
                            $('span.'+key+'_error').text(val)
                        });
                }if(response.status == 400){
                    toastr['error'](response.responseJSON.meta.message);
                }else{
                    toastr['error']('Failed to get data, please contact ICT Developer');
                }
            }
        });  
    }
    function postCallbackNoSwal(route,data,callback){
        $.ajax({
            url: route,
            type: "post",
            dataType: 'json',
            data:data,
            async: true,
            success:callback,
            error: function(response) {
                $('.message_error').html('')
                swal.close();
                console.log(response)
                if(response.status == 500){
                    toastr['error'](response.responseJSON.meta.message);
                    return false
                }
                if(response.status === 422)
                {
                    $.each(response.responseJSON.errors, (key, val) => 
                        {
                            $('span.'+key+'_error').text(val)
                        });
                }if(response.status == 400){
                    toastr['error'](response.meta.message);
                }
                else{
                    toastr['error']('Failed to get data, please contact ICT Developer');
                }
            }
        });  
    }
    function getCallback(route,data,callback){
        $.ajax({
        url: route,
        type: "get",
        dataType: 'json',
        data:data,
        beforeSend: function() {
            SwalLoading('Please wait ...');
        },
        success: callback,
        error: function(xhr, status, error) {
            swal.close();
            toastr['error']('Failed to get data, please contact ICT Developer');
            }
        }); 
    }
    function getCallbackNoSwal(route,data,callback){
        $.ajax({
        url: route,
        type: "get",
        dataType: 'json',
        data:data,
        success: callback,
        error: function(xhr, status, error) {
            swal.close();
            toastr['error']('Failed to get data, please contact ICT Developer');
            }
        }); 
    }
    function selectName(route, id, name){
            $.ajax({
            url: route,
            type: "get",
            dataType: 'json',
            async: true,
            success: function(response) {
                swal.close();
                $('#'+id).empty();
                $('#'+id).append('<option value ="">Choose '+ name +'</option>');
                $.each(response.data,function(i,data,param){
                    $('#'+id).append('<option value="'+data.id+'">' + data.name +'</option>');
                });
                
            },
            error: function(xhr, status, error) {
                swal.close();
                toastr['error']('Failed to get data, please contact ICT Developer');
                }
            });
    }
    function postAttachment(route, data, withFile, callback) {
        $.ajax({
            url: route,
            type: 'POST',
            dataType: 'json',
            async: true,
            processData: false,  // Important: Set to false for file uploads
            contentType: false,  // Important: Set to false for file uploads
            data: data,
            beforeSend: function() {
                SwalLoading('Please wait ...');
            },
            success: callback,
            error: function(response) {
                $('.message_error').html('');
                swal.close();
                if (response.status == 500) {
                    console.log(response);
                    toastr['error'](response.responseJSON.meta.message);
                    return false;
                }
                if (response.status === 422) {
                    $.each(response.responseJSON.errors, (key, val) => {
                        $('span.' + key + '_error').text(val);
                    });
                } else {
                    toastr['error']('Failed to get data, please contact ICT Developer');
                }
            }
        });
    }
    
    // Format Number
        function formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }


        function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.
        
        // get input value
        var input_val = input.val();
        
        // don't validate empty input
        if (input_val === "") { return; }
        
        // original length
        var original_len = input_val.length;

        // initial caret position 
        var caret_pos = input.prop("selectionStart");
            
        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);
            
            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
            right_side += "00";
            }
            
            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);
            input_val =  input_val;
            
            // final formatting
            if (blur === "blur") {
            input_val += ".00";
            }
        }
        
        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
        }
        function formatRupiah(money) {
            return new Intl.NumberFormat('id-ID',
                { style: 'currency', currency: 'IDR' }
            ).format(money);
        }

        function convertToRupiah(angka) {
            var rupiah = '';
            var angkarev = angka.toString().split('').reverse().join('');
            for (var i = 0; i < angkarev.length; i++)
                if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + ',';
            return rupiah.split('', rupiah.length - 1).reverse().join('');
        }
    // Format Number

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        return [year, month, day].join('-');
    }
    function autoAdjustColumns(table) {
        var container = table.table().container();
        var resizeObserver = new ResizeObserver(function () {
            table.columns.adjust();
        });
        resizeObserver.observe(container);
    }
    function findIndexByProperty(array, property, value) {
    for (let i = 0; i < array.length; i++) {
        if (array[i][property] === value) {
            return i;
        }
    }
    return -1; // Return -1 if not found
}
function convertDate(inputDate) {
    // Parse the input date string
    const dateParts = inputDate.split("-");
    const year = parseInt(dateParts[0]);
    const month = parseInt(dateParts[1]);
    const day = parseInt(dateParts[2]);

    // Create a Date object
    const date = new Date(year, month - 1, day);

    // Define month names
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    // Format the date
    const formattedDate = day + " " + monthNames[month - 1] + " " + year;

    return formattedDate;
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
                    <td><input class="form-control update_wt" data-id="${response[i].id}" type="number" id="dynamic_wt_1" value="${response[i].wt_1}" /></td>
                    <td><input class="form-control update_wt" data-id="${response[i].id}" type="number" id="dynamic_wt_2" value="${response[i].wt_2}" /></td>
                    <td><input class="form-control update_wt" data-id="${response[i].id}" type="number" id="dynamic_wt_3" value="${response[i].wt_3}" /></td>
                    <td><input class="form-control update_wt" data-id="${response[i].id}" type="number" id="dynamic_wt_4" value="${response[i].wt_4}" /></td>
                    <td><input class="form-control update_wt" data-id="${response[i].id}" type="number" id="dynamic_wt_5" value="${response[i].wt_5}" /></td>
                    <td><input class="form-control update_wt" data-id="${response[i].id}" type="number" id="dynamic_wt_6" value="${response[i].wt_6}" /></td>
                    <td><input class="form-control update_wt" data-id="${response[i].id}" type="number" id="dynamic_wt_7" value="${response[i].wt_7}" /></td>
                    <td><input class="form-control update_wt" data-id="${response[i].id}" type="number" id="dynamic_wt_8" value="${response[i].wt_8}" /></td>
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
function pieChart(title, label, data, color, type, id) {
    const x = {
        labels: label,
        datasets: [{
            data: data,
            label: title,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
        }],
    };

    const option = {
        responsive: true,
        plugins: {
            legend: {
                labels: {
                    font: {
                        size: 14
                    },
                    usePointStyle: true,
                }
            },
            datalabels: {
                formatter: (value, ctx) => {
                    let sum = ctx.dataset.data.reduce((a, b) => a + b, 0);
                    let percentage = (value * 100 / sum).toFixed(2) + "%";
                    return percentage;
                },
                color: '#fff',
            }
        },
        scales: type === 'bar' || type === 'line' ? { // Only add scales for applicable chart types
            y: {
                beginAtZero: true,
                min: 0
            },
            x: {
                beginAtZero: true,
                min: 0
            }
        } : 'test' // No scales for pie or similar chart types
    };

    const chart = new Chart(id, {
        type: type,
        data: x,
        options: option
    });
}
