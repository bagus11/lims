<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
    <h4 style="text-align: center"><u>Inspection Goods Report</u></h4>
    <table class="table" style="margin-top: 5px !important">
        <tr>
            <th>
                <p>Inspection Code : <b>{{$data[0]->inspection_header}}</b></p>
            </th>
            <th>
                <p>Location : {{$data[0]->headerRelation->locationRelation->name}}</p>
            </th>
        </tr>
        <tr>
            <th>
                <p>Detail Inspection Code :<b> {{$data[0]->inspection_detail}}</b> </p>
            </th>
            <th>
                <p>Shift : {{$data[0]->detailRelation->shift}}</p>
            </th>
        </tr>

    </table>

    <table class="table-stepper" style="margin-top : 5px !important">
        <thead>
            <tr>
                <th rowspan="2">Inspection Item</th>
                <th colspan="{{ count($data) }}">Machine</th>
            </tr>
            <tr>
                @foreach ($data as $item)
                    <th>{{ $item->machineRelation->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <!-- General Rows for Pipe Properties -->
            <tr>
                <td>Created At</td>
                @foreach ($data as $item)
                    <td style="text-align: center">{{ $item->created_at }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Specification of pipes</td>
                @foreach ($data as $item)
                    <td style="text-align: center">{{ $item->productRelation->marking }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Length of pipes</td>
                @foreach ($data as $item)
                    <td style="text-align: center">{{ $item->length_of_pipe }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Appearance of pipes</td>
                @foreach ($data as $item)
                    <td style="text-align: center">{{ $item->appearance_of_pipe }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Marking</td>
                @foreach ($data as $item)
                    <td style="text-align: center">{{ $item->marking }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Condition pipe of cut</td>
                @foreach ($data as $item)
                    <td style="text-align: center">{{ $item->condition_pipe_of_cut }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Angle Pipe of Spigot</td>
                @foreach ($data as $item)
                    <td style="text-align: center">{{ $item->angle_pipe_of_spigot }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Color of Pipes</td>
                @foreach ($data as $item)
                    <td style="text-align: center">{{ $item->color_of_pipes }}</td>
                @endforeach
            </tr>
    
            <!-- Section Title Row -->
            <tr>
                <td colspan="{{ count($data) + 1 }}" style="font-weight: bold">Dimension To Inspect Pipes</td>
            </tr>
    
            <!-- Pipe Size Row -->
            <tr>
                <td>Pipe Size</td>
                @foreach ($data as $item)
                    <td style="text-align: center">{{ $item->productRelation->marking }}</td>
                @endforeach
            </tr>
    
            <!-- Spec (mm) Row with Min and Max as sub-columns -->
            <tr>
                <td rowspan="3" style="text-align: center">Spec (mm)</td>
                @foreach ($data as $item)
                    <td style="text-align: center;">
                        <table style="width: 100%; border-collapse: collapse; padding: 0 !important;">
                            <tr>
                                <td style="text-align: center; padding: 4px; border: none;">Min</td>
                                <td style="text-align: center; padding: 4px; border: none;">-</td>
                                <td style="text-align: center; padding: 4px; border: none;">Max</td>
                            </tr>
                        </table>
                    </td>
                @endforeach
            </tr>
    
            <!-- OD Values Row -->
            <tr>
                @foreach ($data as $item)
                    <td style="text-align: center;border : none !important;">
                        <table style="width: 100%; border-collapse: collapse; padding: 0 !important;">
                            <tr>
                                <td style="text-align: left; padding: 1px; width:33% !important; border: none;">OD</td>
                                <td style="text-align: center; padding: 1px; width:33% !important; border: none;">{{ $item->productRelation->min_od }}</td>
                                <td style="text-align: center; padding: 1px; width:33% !important; border: none;">{{ $item->productRelation->max_od }}</td>
                            </tr>
                        </table>
                    </td>
                @endforeach
            </tr>
            <tr>
                @foreach ($data as $item)
                    <td style="text-align: center;border : none !important;">
                        <table style="width: 100%; border-collapse: collapse; padding: 0 !important;">
                            <tr>
                                <td style="text-align: left; padding: 1px; width:33% !important; border: none">WT</td>
                                <td style="text-align: center; padding: 1px; width:33% !important; border: none">{{ $item->productRelation->min_wt }}</td>
                                <td style="text-align: center; padding: 1px; width:33% !important; border: none">{{ $item->productRelation->max_wt }}</td>
                            </tr>
                        </table>
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Number Of Machine</td>
                @foreach ($data as $item)
                    <td style="text-align: center">{{ $item->machineRelation->no_machine}}</td>
                @endforeach		

            </tr>
            <tr>
                <td>Measure used</td>
                @foreach ($data as $item)
                    <td style="text-align: center">{{ $item->productRelation->measure_used}}</td>
                @endforeach		
            </tr>
            <tr>
                <td>Tape Diameter</td>
                @foreach ($data as $item)
                    <td style="text-align: center">{{ $item->tape_diameter}} mm</td>
                @endforeach		
            </tr>
            <tr>
                <td>Wall Thickness</td>
                @php
                    $array_result = [];
                    $array_merge = [];
                @endphp
            
                @for ($j = 0; $j < count($data); $j++)
                    @php
                        $array_j[$j] = [];
                        $array_k = new stdClass;
                    @endphp
                    <td>
                        <table style="width: 100%; border-collapse: collapse; padding: 0 !important;">
                            <tbody>
                                <tr>
                                    @for ( $k = 1; $k <= count($data[$j]->wallThicknessRelation); $k++)
                                        <td style="text-align: center; padding: 4px; border: none;"><b>step {{$k}}</b></td>
                                    @endfor
                                </tr>
                                @for ($i = 1; $i <= 8; $i++)
                                    <tr>
                                        @foreach ($data[$j]->wallThicknessRelation as $wt)
                                        @php
                                            
                                            $min = $data[$j]->productRelation->min_wt;
                                            $max = $data[$j]->productRelation->max_wt;
                                            $color = $wt->{'wt_' . $i} < $min || $wt->{'wt_' . $i} > $max ? 'color:red' : '';
                                        @endphp
                                            <td style="text-align: center; padding: 4px; border: none;{{$color}}">{{ $wt->{'wt_' . $i} }}</td>
                                        @endforeach
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </td>
                @endfor
            </tr>
            
            <tr>
                <td>Max Wall Thickness</td>
                @php
                     $result = new stdClass;
                @endphp
                @for ($j = 0; $j < count($data); $j++)
                    $array_test = [];
                    <td>
                        <table style="width: 100%; border-collapse: collapse;">
                          
                            <tr>
                                @php
                                    $k = 0;
                                    foreach ($data[$j]->wallThicknessRelation as $val) {
                                        $temp_arr = [];
                                        for ($i = 1; $i < 9; $i++) {
                                           
                                            
                                            $min = $data[$j]->productRelation->min_wt;
                                            $max = $data[$j]->productRelation->max_wt;
                                            $color = $wt->{'wt_' . $i} < $min || $wt->{'wt_' . $i} > $max ? 'color:red' : '';
                                        
                                            array_push($temp_arr, $val->{'wt_' . $i});
                                        }
                                        $result->data[$k] = new stdClass;
                                        $result->data[$k]->max = max($temp_arr);
                                        $k++;
                                    }
                                @endphp
                                @foreach ($result->data as $result)
                                    <td style="text-align: center; padding: 4px; border: none;{{$color}}">{{ $result->max }}</td>
                                @endforeach
                            </tr>
                        </table>
                    </td>
                @endfor
            </tr>
            <tr>
                <td>Min Wall Thickness</td>
                @php
                     $result = new stdClass;
                @endphp
                @for ($j = 0; $j < count($data); $j++)
                    $array_test = [];
                    <td>
                        <table style="width: 100%; border-collapse: collapse;;">
                            <tr style="padding-top:0 px !important;padding-bottom:0px !important">

                                @php
                                    $k = 0;
                                    $array_k = new stdClass;
                                    foreach ($data[$j]->wallThicknessRelation as $wt => $val){
                                        $temp_arr = [];
                                        for($i = 1; $i < 9; $i++){
                                            $min = $data[$j]->productRelation->min_wt;
                                            $max = $data[$j]->productRelation->max_wt;
                                            $color = (int)$val->{'wt_' . $i} < $min || (int)$val->{'wt_' . $i} > $max ? 'color:red' : '';

                                            array_push($temp_arr, $val->{'wt_' . $i});
                                        }
    
                                        $result->data[$k] = new stdClass;
                                        $result->data[$k]->max = max($temp_arr);
                                        $result->data[$k]->min = min($temp_arr);
                                        $result->data[$k]->avg = array_sum($temp_arr)/count($temp_arr);
                                        $result->data[$k]->range = min($temp_arr) .' - '. max($temp_arr);
                                        $k++;
                                    }
                                    
                                @endphp
                                @foreach ($result->data as $result)
                                    <td style="text-align: center; padding: 4px; border: none;{{$color}}">{{$result->min}}</td>
                                @endforeach
                            </tr>
                        </table>
                    </td>
                @endfor
                
            </tr>
            <tr>
                <td>Range Wall Thickness</td>
                @php
                     $result = new stdClass;
                @endphp
                @for ($j = 0; $j < count($data); $j++)
                    $array_test = [];
                    <td>
                        <table style="width: 100%; border-collapse: collapse;;">
                            <tr style="padding-top:0 px !important;padding-bottom:0px !important">

                                @php
                                    $k = 0;
                                    $array_k = new stdClass;
                                    foreach ($data[$j]->wallThicknessRelation as $wt => $val){
                                        $temp_arr = [];
                                        for($i = 1; $i < 9; $i++){
                                            $min = $data[$j]->productRelation->min_wt;
                                            $max = $data[$j]->productRelation->max_wt;
                                            $color = (int)$val->{'wt_' . $i} < $min || (int)$val->{'wt_' . $i} > $max ? 'color:red' : '';
                                            array_push($temp_arr, $val->{'wt_' . $i});
                                        }
    
                                        $result->data[$k] = new stdClass;
                                        $result->data[$k]->max = max($temp_arr);
                                        $result->data[$k]->min = min($temp_arr);
                                        $result->data[$k]->avg = array_sum($temp_arr)/count($temp_arr);
                                        $result->data[$k]->range =max($temp_arr) - min($temp_arr);
                                        $k++;
                                    }
                                    
                                @endphp
                                @foreach ($result->data as $result)
                                    <td style="text-align: center; padding: 4px; border: none;{{$color}}">{{$result->range}}</td>
                                @endforeach
                            </tr>
                        </table>
                    </td>
                @endfor
            </tr>
            <tr>
                <td>Avg Wall Thickness</td>
                @php
                     $result = new stdClass;
                @endphp
                @for ($j = 0; $j < count($data); $j++)
                    $array_test = [];
                    <td>
                        <table style="width: 100%; border-collapse: collapse;;">
                            <tr style="padding-top:0 px !important;padding-bottom:0px !important">

                                @php
                                    $k = 0;
                                    $array_k = new stdClass;
                                    foreach ($data[$j]->wallThicknessRelation as $wt => $val){
                                        $temp_arr = [];
                                        for($i = 1; $i < 9; $i++){
                                            $min = $data[$j]->productRelation->min_wt;
                                            $max = $data[$j]->productRelation->max_wt;
                                            $color = (int)$val->{'wt_' . $i} < $min || (int)$val->{'wt_' . $i} > $max ? 'color:red' : '';
                                            array_push($temp_arr, $val->{'wt_' . $i});
                                        }
    
                                        $result->data[$k] = new stdClass;
                                        $result->data[$k]->max = max($temp_arr);
                                        $result->data[$k]->min = min($temp_arr);
                                        $result->data[$k]->avg = array_sum($temp_arr)/count($temp_arr);
                                        $result->data[$k]->range = min($temp_arr) .' - '. max($temp_arr);
                                        $k++;
                                    }
                                    
                                @endphp
                                @foreach ($result->data as $result)
                                    <td style="text-align: center; padding: 4px; border: none;{{$color}}">{{$result->avg}}</td>
                                @endforeach
                            </tr>
                        </table>
                    </td>
                @endfor
                
            </tr>
         
            <tr>
                <td>Evaluation Spec</td>
                @foreach ($data as $item)
                    <td style="text-align: center">{{ $item->evaluation_spec}}</td>
                @endforeach		
            </tr>
        </tbody>
    </table>
    <b class="remark" style="margin-top:5px">Remark : </b>
    <ul class="a">
        @foreach ($data as $item)
            <li> {{$item->machineRelation->name}} :  {{$item->remark}}</li>
        @endforeach		
    </ul>
    
    <table class="table" id="approval_remark">
        <thead>
            <tr>
             
                @for ($i = 3 - 1; $i >= 0; $i--)
                    @php
                        $title = '';
                        switch ($i) {
                            case 0:
                            $title = "Karu Inspector";
                            break;
                            
                            case 1:
                            $title = "Kabag QC";
                            break;
                            
                            case 2:
                            $title = "Factory Manager";
                            break;
                            
                            default:
                                $title = "Approver " . ($i + 1); // Fallback title for additional columns if needed
                                break;
                        }
                    @endphp
                    <th>{{ $title }}</th>
                    @endfor
                    <th>Inspector</th>
                
            </tr>
        </thead>
        <tbody>
            <tr>
              
            @for ($i = 3 - 1; $i >= 0; $i--)
                @php
                    $signature = isset($set_approval[$i]) && $set_approval[$i]->status == 1 && isset($approval[$i]->userRelation->signature)
                        ? $approval[$i]->userRelation->signature
                        : '';
                @endphp
                <td style="text-align: center">
                    @if ($signature)
                        <img style='display:block; width:70px; height:50px;' id='base64image' src='{{$signature}}' />
                    @endif
                    <p>
                        {{$approval[$i]->userRelation->name ?? ''}}
                    </p>
                </td>
            @endfor
            <td style="text-align: center">
                @if ($data[0]->inspectorRelation)
                <img style='display:block; width:70px; height:50px;' id='base64image' src='{{$data[0]->inspectorRelation->signature}}' />
                <p>
                    {{$data[0]->inspectorRelation->name}}
                </p>
                @else
                <img style='display:block; width:70px; height:50px;' id='base64image' src='' />
                @endif
            </td>
            </tr>
        </tbody>
    </table>
   
</body>
</html>
<style>
    .remark{
        font-size: 10px !important;
    }
    ul.a {
    list-style-type: circle;
    font-size: 9px !important;
    }
    ul.a li{
        margin-bottom: 5px !important;
    }

    .table-stepper{
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      border-spacing: 0;
      font-size: 9px;
      width: 100% !important;
      border: 1px solid #ddd;
      
}
  .table-stepper tr:nth-child(even){background-color: #f2f2f2;}

  .table-stepper tr:hover {background-color: #ddd;}

  .table-stepper th {
      border: 1px solid #ddd;
      padding-top: 5px;
      padding-bottom: 5px;
      font-size: 9px;
      text-align: center;
      background-color: #26577C;
      color: white;
      overflow-x:auto !important;
  }
  .table-stepper td, .datatable-stepper th {
      border: 1px solid #ddd;
      padding: 4px;
     
  }
  table tr:last-child {
      font-weight: bold;  
  }
  p{
    font-size: 9px !important;
  }

.table {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100% !important;
    font-size: 12px;
}

.table th, .table td {
    border: none; /* Remove borders from cells */
    padding: 12px;
}

/* .table tr:nth-child(even) {
    background-color: #f2f2f2; 
} */

.table th {
    padding-top: 4px;
    padding-bottom: 4px;
    font-size: 9px !important;
    text-align: left;
    color: black;
}
#approver-remark-wrapper {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: white;
            padding-top: 5px;
        }
        #approval_remark th {
            padding-top: 4px;
            padding-bottom: 4px;
            font-size: 9px !important;
            text-align: center;
            color: black;
            /* border-top: 1px solid #ddd; */
        }

</style>