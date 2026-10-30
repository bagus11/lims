<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Argon Dashboard') }}</title>
        <!-- Favicon -->
        <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="{{ asset('assets/select2/select2.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/css/bootstrap5-toggle.min.css" rel="stylesheet">
        <!-- Extra details for Live View on GitHub Pages -->

        <!-- Icons -->
        <link href="{{ asset('argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
        <link href="{{ asset('argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/css/bootstrap5-toggle.min.css" rel="stylesheet">

        <!-- Argon CSS -->
        <link type="text/css" href="{{ asset('argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">
        <style>
            .card{
                border-radius: 20px !important;
            }
            .card-header{
                border-top-left-radius: 20px !important;
                border-top-right-radius: 20px !important;
            }
            .card-footer{
                border-bottom-left-radius: 20px !important;
                border-bottom-right-radius: 20px !important;
            }
            .cardTitle{
                font-size:14px !important;
            }
            .datatable-stepper{
            font-family: Poppins;
            border-collapse: collapse;
            border-spacing: 0;
            font-size: 9px;
            width: 100% !important;
            /* border: 1px solid #ddd; */
            }
            .datatable-stepper tr:nth-child(even){background-color: #f2f2f2;}

            .datatable-stepper tr:hover {background-color: #ddd;}

            .datatable-stepper th {
            /* border: 1px solid #ddd; */
            text-align: center;
            color: white;
            /* color: black; */
            overflow-x:auto !important;
            background-color: #C96868 !important;
            padding: 5px !important;
            font-weight: bold;
            }

            .datatable-stepper td {
                /* border: 1px solid #ddd; */
                padding: 10px;
                font-size: 9px !important;
            }
            .dataTables_length,.dataTables_filter,.dataTables_info{
              font-size: 12px !important;
            }
          p{
            font-size: 11px !important;
          }
          .form-control{
            height: 30px;
            font-size: 9px !important;
          }
          .select2{
              width: 100% !important;
              font-size:11px;
          }
          /* the rule above also matches the original <select class="select2">,
             which select2 hides but keeps absolutely positioned -- at 100% it
             stretched past the viewport and gave the page a horizontal scrollbar */
          select.select2-hidden-accessible {
              width: 1px !important;
          }
          .select2-selection__rendered {
              line-height: 30px !important;
              font-size:9px;
          }
          .select2-container .select2-selection--single {
              height: 35px !important;
              font-size:9px;
          }
          .select2-selection__arrow {
              height: 34px !important;
              font-size:12px !important;
          }
          .select2-dropdown{
            font-size: 9px !important;
          }
          .selectOption2{
            font-size: 9px !important;
          }
          .select2-dropdown--below{
            font-size: 9px !important;
          }
          .select2-results__option{
            font-size: 9px !important;
          }
          .select2-search__field{
            height: 35px !important;
            font-size: 9px !important;
          }
          .select2-container .select2-search--inline {
            display: inline-flex !important;
            align-items: center;
          }
          .select2-container--default .select2-search--inline .select2-search__field {
            min-width: 120px;
          }
          .modal {
            overflow-y:auto !important;
          }
          #toast-container > .toast {
            font-size: 12px; /* Adjust the font size as needed */
        }
        .toggle-group .toggle-on,
        .toggle-group .toggle-off {
            font-size: 10px; /* Set your desired font size */
        }
        .toggle.ios,
        .toggle-on.ios,
        .toggle-off.ios {
          border-radius: 20rem;
        }
        .toggle.ios .toggle-handle {
          border-radius: 20rem;
        }
        .menu-type-2 {
        }
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
            }

            ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px grey;
            border-radius: 10px;
            }
            .message_error{
              font-size: 9px !important;
              font-family: Poppins !important;
            }

            ::-webkit-scrollbar-thumb {
            background:#179BAE;
            border-radius: 10px;
            }

            ::-webkit-scrollbar-thumb:hover {
            background:#EEEDEB;
            }
            fieldset.scheduler-border {
                border: 1px groove #ddd !important;
                padding: 0 1.5em 1.5em 1.5em !important;
                font-family: Poppins !important;
                margin: 0 0 1.5em 0 !important;
                -webkit-box-shadow:  0px 0px 0px 0px #000;
                        box-shadow:  0px 0px 0px 0px #000;
            }

            legend.scheduler-border {
                font-size: 12px !important;
                font-weight: bold !important;
                text-align: left !important;
                font-family: Poppins !important;
            }
            .open\:bg-green-200[open] {
  --tw-bg-opacity: 1;
  background-color: rgb(187 247 208 / var(--tw-bg-opacity));
}
.open\:bg-red-600[open] {
  --tw-bg-opacity: 1;
  background-color: rgb(220 38 38 / var(--tw-bg-opacity));
}
.open\:bg-red-200[open] {
  --tw-bg-opacity: 1;
  background-color: rgb(254 202 202 / var(--tw-bg-opacity));

}
.open\:bg-amber-200[open] {
  --tw-bg-opacity: 1;
  background-color: rgb(253 230 138 / var(--tw-bg-opacity));
}
th.details-control {
  background-color: #04AA6D;
  color: white;
}
td.details-control {
background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
cursor: alias;
}
tr.shown td.details-control {
    background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
}

td.details-click {
    background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
    cursor: alias;
}
tr.shown td.details-click {
    background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
}

th.subdetails-control {
  background-color: #04AA6D;
  color: white;
}
td.subdetails-control {
background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
cursor: alias;
}
tr.shown td.subdetails-control {
    background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
}

td.subdetails-click {
    background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
    cursor: alias;
}
tr.shown td.subdetails-click {
    background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
}
.message_error{
  color: #F5004F !important;
  font-family: Poppins !important;
  font-size: 9px !important;
}
.btn-small{
    width: 20px;
    height: 20px;
    border-radius: 20px !important;
    display: flex;
    justify-content: center;
    align-items: center;
}
        </style>
    </head>
    <body class="{{ $class ?? '' }}">
        @auth()
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @include('layouts.navbars.sidebar')
        @endauth

        <div class="main-content">
            @include('layouts.navbars.navbar')
            @yield('content')
        </div>

        @guest()
            @include('layouts.footers.guest')
        @endguest

        <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>
        <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
        <script src="{{ asset('assets/sweetalert2/sweetalert.min.js') }}"></script>
        <script src="{{ asset('assets/select2/select2.full.min.js') }}"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/js/bootstrap5-toggle.jquery.min.js"></script>

        @csrf

        <script src="{{ asset('lims') }}/helper.js"></script>
        <script>
          $(document).ready(function() {
            // Initialize select2 globally for initial elements
            $(".select2").select2({
                dropdownCssClass: "selectOption2",
                tags: true,
                minimumResultsForSearch: 0
            });

            // Set up AJAX
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
        });


            $('input[data-toggle="toggle"]').bootstrapToggle();
               function SwalLoading(html = 'Loading ...', title = '') {
                return Swal.fire({
                  title: title,
                  html: html,
                  customClass: 'swal-wide',
                  timerProgressBar: true,
                  allowOutsideClick: false,
                  didOpen: () => {
                      Swal.showLoading()
                  }
              });
          }
          toastr.options = {
              "closeButton": false,
              "debug": false,
              "newestOnTop": false,
              "progressBar": true,
              "positionClass": "toast-bottom-right",
              "preventDuplicates": false,
              "onclick": null,
              "showDuration": "300",
              "hideDuration": "1000",
              "timeOut": "5000",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            }
            $(document).ready(function() {
              var url = window.location.href;
              // For the entire sidebar menu but not covering treeview
              $('ul.navbar-nav a').filter(function() {
                  return this.href === url;
              }).addClass('active')
                .css('color', '#179BAE') // Use the correct color code with the hash symbol
                .attr('aria-expanded', 'true');

              // For treeview (submenu)
              $('ul.nav-sm a').filter(function() {
                  return this.href === url;
              }).addClass('active')
                .css({
                    'font-weight': 'bold' // Make submenu text bold
                })
                .attr('aria-expanded', 'true')  // Expand the submenu
                .parentsUntil(".navbar-nav", ".collapse").addClass('show').prev('a')
                .css('color', '#179BAE')  // Set the parent menu item color
                .attr('aria-expanded', 'true'); // Ensure the parent menu item is expanded


            });

          $(".select2").select2({ width: '300px', dropdownCssClass: "bigdrop" });
        </script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0-rc.1/chartjs-plugin-datalabels.min.js"
              integrity="sha512-+UYTD5L/bU1sgAfWA0ELK5RlQ811q8wZIocqI7+K0Lhh8yVdIoAMEs96wJAIbgFvzynPm36ZCXtkydxu1cs27w==" crossorigin="anonymous"
              referrerpolicy="no-referrer"></script>
        @stack('js')

        <!-- Argon JS -->
        {{-- <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script> --}}
    </body>
</html>
