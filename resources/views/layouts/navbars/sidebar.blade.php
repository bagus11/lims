<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('home') }}">
            <img src="{{ asset('lims') }}/logo.png" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg">
                        </span>
                    </div>
                </a>
              
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-calendar-grid-58"></i>
                        <span>{{ __('Activity') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-support-16"></i>
                        <span>{{ __('Support') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('argon') }}/img/brand/blue.png">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Navigation -->
            <ul class="navbar-nav">
                @php
                    $menus = DB::table('menus')
                        ->join('permissions', 'permissions.name','=','menus.permission_name')
                        ->join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                        ->join('roles', 'roles.id','role_has_permissions.role_id')
                        ->join('model_has_roles', 'model_has_roles.role_id', 'roles.id')
                        ->select('menus.*')
                        ->where('status',1)
                        ->where('model_has_roles.model_id', auth()->user()->id)
                        ->orderBy('order','asc')
                        ->get();
                        // dd($menus);
                @endphp
                @foreach ($menus as $item)
                @if ($item->type ==  1)
                <li class="nav-item" >
                    <a class="nav-link" href="{{$item->link}}" style="font-size: 12px !important">
                        <i class="{{$item->icon}}"></i> {{$item->name}}
                    </a>
                </li> 
                @else
                @php
                    $submenus = DB::table('submenus')->select('submenus.*')
                        ->join('permissions','permissions.name','=','submenus.permission_name')
                        ->join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                        ->join('roles', 'roles.id','role_has_permissions.role_id')
                        ->join('model_has_roles', 'model_has_roles.role_id', 'roles.id')
                        ->where('submenus.id_menu', $item->id)
                        ->where('submenus.status', 1)
                        ->where('model_has_roles.model_id', auth()->user()->id)
                        // ->orderBy('order','asc')
                        ->get();
                @endphp
                <li class="nav-item menu-type-2"> <!-- Apply custom CSS class -->
                    <a class="nav-link" href="#navbar-{{$item->link}}" style="font-size: 12px !important" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-{{$item->link}}">
                        <i class="{{$item->icon}}"></i>
                        <span class="nav-link-text">{{ $item->name}}</span>
                    </a>
                    <div class="collapse" id="navbar-{{$item->link}}">
                        <ul class="nav nav-sm flex-column">
                            @foreach ($submenus as $row)
                                <li class="nav-item">
                                   <a class="nav-link" href="{{ $row->link }}" style="font-size: 12px !important">
                                    <i class="{{$row->icon}}"></i>  {{$row->name}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li> 
                @endif
              
                @endforeach
            </ul>
            <!-- Divider -->
         
        </div>
    </div>
</nav>
