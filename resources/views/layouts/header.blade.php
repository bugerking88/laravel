<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <title>{{ config('app.name', 'Rowave Wiki') }}</title>
    <!-- Scripts -->
<!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}" defer></script>

    <script src="{{ asset('js/jquery/jquery.easing.min.js') }}" defer></script>
    <script src="{{ asset('js/datatables/jquery.dataTables.min.js') }}" defer></script>
    <script src="{{ asset('js/datatables/dataTables.bootstrap4.min.js') }}" defer></script>
    <!-- Custom scripts for all pages -->
    <script src="{{ asset('js/sb-admin-2.min.js') }}" defer></script>
    <script src="{{ asset('js/common.js') }}" defer></script>
@stack('styles')
<!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-text mx-3"> {{__('rowave auth')}}</div>
            </a>

            <!-- Menus -->
            <hr class="sidebar-divider my-0">
            <li class="nav-item {{ request()->is('home*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('home')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>{{__('home')}}</span></a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item {{ request()->is('user*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('users.index')}}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>{{__('users')}}</span></a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item {{ request()->is('customer*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('customers.index')}}">
                    <i class="fas fa-fw fa-user-friends"></i>
                    <span>{{__('customers')}}</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item {{ request()->is('system-license*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('system-licenses.index')}}">
                    <i class="fas fa-fw fa-certificate"></i>
                    <span>{{__('licenses')}}</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <ul class="navbar-nav ml-auto">
                        @php $locale = session()->get('locale'); @endphp
                        <li class="nav-item dropdown">
                            <a href="#" id="navbarDropDown" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <?php
                                switch($locale) : case 'cn':
                                    echo '<img src="img/china.png" alt="China Flag">';
                                    break;
                                    default :
                                        echo '<img src="img/us.png" alt="US Flag">';
                                endswitch;
                                ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="lang/en" class="dropdown-item"> <span> <img src="img/us_small.png" alt="US Flag"> {{__('english')}} </span></a>
                                <a href="lang/cn" class="dropdown-item"> <span> <img src="img/china_small.png" alt="China Flag"> {{__('chinese')}} </span></a>
                            </div>
                        </li>
                        @guest
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                        {{ __('logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#profileModal"> {{__('change password')}}</button>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </nav>
                <main class="right-container">
                    @yield('content')
                </main>
            </div><!-- End of Footer -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>{{__('copyright')}} &copy; {{__('rowave auth')}} 2020</span>
                    </div>
                </div>
            </footer><!-- End of Footer -->
        </div><!-- End of content-wrapper -->
    </div><!-- End of wrapper -->
</div>
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profile-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="profile-form" class="form-horizontal" method="POST" action="{{ route('change_password') }}">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="profile-modal-label">{{__('change password')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="attachment-body-content">
                    @if($errors->any())
                        <small class="text-small text-center text-danger"> {{$errors->first()}} </small>
                    @endif
                    <div class="form-group">
                        <label class="col-form-label" for="current_password">{{__('current password')}}</label>
                        <input type="password" name="current_password" class="form-control" id="current_password" required autofocus>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="password">{{__('new password')}}</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="confirm_password">{{__('confirm password')}}</label>
                        <input type="password" name="confirm_password" class="form-control" id="confirm_password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{__('save')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('close')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="confirmModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <strong class="confirmation-message">  </strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="yes"> {{__('yes')}} </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal"> {{__('no')}} </button>
            </div>
        </div>
    </div>
</div>
@stack('scripts')
<script>
    $(document).ready(function() {
        var hasError = "{{session()->get('profileError')}}";
        if(hasError) {
            var options = {
                'backdrop': 'static'
            };
            $('#profileModal').modal(options);
        }
    });
</script>
</body>
</html>
