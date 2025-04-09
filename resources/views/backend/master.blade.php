<!DOCTYPE html>
<html lang="en">

@php
    $generalInfo = DB::table('general_infos')->where('id', 1)->select('logo', 'company_name', 'fav_icon', 'guest_checkout')->first();
@endphp

<head>
    <meta charset="utf-8" />
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- to stop indexing --}}
    <meta name="robots" content="noindex, nofollow">

    <meta content="Admin Panel" name="description"/>
    <meta content="Getup Ltd." name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    @if($generalInfo->fav_icon != '' && $generalInfo->fav_icon != Null && file_exists(public_path($generalInfo->fav_icon)))
        <link rel="shortcut icon" href="{{ url($generalInfo->fav_icon) }}">
    @else
        <link rel="shortcut icon" href="{{ url('assets') }}/images/favicon.ico">
    @endif

    <!-- App css -->
    <link href="{{ url('assets') }}/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets') }}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets') }}/css/theme.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets') }}/css/toastr.min.css" rel="stylesheet" type="text/css" />
    

    @yield('header_css')
    @yield('header_js')

    

    <link href="{{ url('assets') }}/css/custom.css" rel="stylesheet" type="text/css" />

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <div data-simplebar class="h-100">

                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="{{ url('/home') }}" class="logo mt-2" style="display: inline-block;">
                        @if($generalInfo->logo != '' && $generalInfo->logo != Null && file_exists(public_path($generalInfo->logo)))
                        <span>
                            <img src="{{url($generalInfo->logo)}}" alt="" class="img-fluid" style="max-height: 100px; max-width: 150px;">
                        </span>
                        @else
                            <h3 style="color: white; margin-top: 20px">{{$generalInfo->company_name}}</h3>
                        @endif
                    </a>
                </div>

                <!--- Sidemenu -->
                <div id="sidebar-menu">

                    @if(Auth::user()->user_type == 1)
                        @include('backend.sidebar')
                    @else
                        @include('backend.sidebarWithAssignedMenu')
                    @endif

                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-sm mr-2 d-lg-none header-item" id="vertical-menu-btn">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>

                        <div class="header-breadcumb">
                            <h6 class="header-pretitle d-none d-md-block">Pages <i
                                    class="dripicons-arrow-thin-right"></i> @yield('page_title')</h6>
                            <h2 class="header-title">@yield('page_heading')</h2>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">

                        {{-- <div class="dropdown d-inline-block ml-2">
                            <button type="button" class="btn header-item noti-icon" id="page-header-notifications-dropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-bell-outline"></i>
                                <span class="badge badge-danger badge-pill">6</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                                aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0"> Notifications </h6>
                                        </div>
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 230px;">
                                    <a href="" class="text-reset">
                                        <div class="media py-2 px-3">
                                            <img src="{{url('assets')}}/images/users/avatar-2.jpg"
                                                class="mr-3 rounded-circle avatar-xs" alt="user-pic">
                                            <div class="media-body">
                                                <h6 class="mt-0 mb-1">Samuel Coverdale</h6>
                                                <p class="font-size-12 mb-1">You have new follower on Instagram</p>
                                                <p class="font-size-12 mb-0 text-muted"><i class="mdi mdi-clock-outline"></i> 2 min ago</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="" class="text-reset">
                                        <div class="media py-2 px-3">
                                            <div class="avatar-xs mr-3">
                                                <span class="avatar-title bg-success rounded-circle">
                                                    <i class="mdi mdi-cloud-download-outline"></i>
                                                </span>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="mt-0 mb-1">Download Available !</h6>
                                                <p class="font-size-12 mb-1">Latest version of admin is now available. Please download here.</p>
                                                <p class="font-size-12 mb-0 text-muted"><i class="mdi mdi-clock-outline"></i> 4 hours ago</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div> --}}


                        <div class="dropdown d-inline-block ml-2">
                            <label class="btn text-white rounded mr-2 mb-0" style="cursor:pointer; background: linear-gradient(to right, #17263ADE, #2c3e50f5, #17263A);">
                                <input type="checkbox" id="guest_checkout" onchange="guestCheckout()" @if($generalInfo->guest_checkout == 1) checked @endif> Guest Checkout
                            </label>
                            <a href="{{env('APP_FRONTEND_URL')}}" target="_blank" class="btn text-white rounded" style="background: linear-gradient(to right, #17263ADE, #2c3e50f5, #17263A);"><i class="fas fa-paper-plane"></i> Visit Website</a>
                        </div>

                        <div class="dropdown d-inline-block ml-2">
                            <button type="button" class="btn header-item" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="{{ url('assets') }}/images/users/avatar-1.jpg" alt="Header Avatar">
                                <span class="d-none d-sm-inline-block ml-1">@auth {{ Auth::user()->name }} @endauth</span>
                                <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                {{-- <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                    Profile
                                </a> --}}
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ url('/change/password/page') }}">
                                    <span class="d-none d-sm-inline-block"><i class="fas fa-key"></i> Change Password</span>
                                </a>
                                <a href="{{ route('logout') }}" class="dropdown-item d-flex align-items-center justify-content-between logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <span class="d-none d-sm-inline-block"><i class="fas fa-sign-out-alt"></i> Logout</span>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </header>

            <div class="page-content">
                <div class="container-fluid">

                    @yield('content')

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= date('Y') ?> © {{$generalInfo->company_name}}
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-right d-none d-sm-block">
                                Design & Developed by Getup Ltd.
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Overlay-->
    <div class="menu-overlay"></div>


    <!-- jQuery  -->
    <script src="{{ url('assets') }}/js/jquery.min.js"></script>
    <script src="{{ url('assets') }}/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('assets') }}/js/metismenu.min.js"></script>
    <script src="{{ url('assets') }}/js/waves.js"></script>
    <script src="{{ url('assets') }}/js/simplebar.min.js"></script>
    <script src="{{ url('assets') }}/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="{{ url('assets') }}/plugins/morris-js/morris.min.js"></script>
    <script src="{{ url('assets') }}/plugins/raphael/raphael.min.js"></script>
    <script src="{{ url('assets') }}/pages/dashboard-demo.js"></script>
    <script src="{{ url('assets') }}/js/theme.js"></script>
    <script src="{{ url('assets') }}/js/ajax.js"></script>
    <script src="{{ url('assets') }}/js/ajax_two.js"></script>
    <script src="{{ url('assets') }}/js/search_product_ajax.js"></script>

    <script>
        const handleScroll = () => {
            var Sidebar = document.querySelector('.simplebar-content-wrapper')
            var scrollPosition = Sidebar.scrollTop;
            localStorage.setItem('scroll_nav', scrollPosition);
        }
        document.addEventListener('DOMContentLoaded', function() {
            var Sidebar = document.querySelector('.simplebar-content-wrapper');
            const Location = window.location.pathname;
            Sidebar.onscroll = handleScroll;

            let scroll_nav = localStorage.getItem('scroll_nav');
            if (scroll_nav && Location != '/dashboard') {
                Sidebar.scrollTop = scroll_nav;
            } else {
                Sidebar.scrollTop = 0;
                localStorage.setItem('scroll_nav', 0);
            }
        });
        
        function guestCheckout(){
            $.get("{{ url('change/guest/checkout/status') }}", function (data) {
                const checkbox = document.getElementById("guest_checkout");
                if (checkbox.checked) {
                    toastr.success("Guest Checkout has Enabled");
                } else {
                    console.log("Checkbox is not checked.");
                    toastr.error("Guest Checkout has Disabled");
                }
            })
        }
    </script>

    @yield('footer_js')

    <script src="{{ url('assets') }}/js/toastr.min.js"></script>
    {!! Toastr::message() !!}

</body>

</html>