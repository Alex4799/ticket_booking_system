<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title class="company_name">MCL Cinema</title>

    <!-- bootstrap -->
    <link rel="stylesheet" href="{{asset('bootstrap-5.3.0-alpha2-dist/css/bootstrap.min.css')}}">

    <!-- font-awesome  -->
    <link rel="stylesheet" href="{{asset('fontawesome/css/all.min.css')}}"/>

    <!-- ckeditor -->
    <link rel="stylesheet" href="{{asset('ckeditor5-42.0.2/build/ckeditor.css')}}">

    <!-- owlcarousel -->
    <link rel="stylesheet" href="{{asset('owlcarousel/dist/assets/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('owlcarousel/dist/assets/owl.theme.default.min.css')}}">

    <!-- css  -->
    <link rel="stylesheet" href="{{asset('admin/css/style.css')}}?v={{ filemtime(public_path('admin/css/style.css')) }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    @yield('style')
</head>
<body class="@yield('body-class')">
    <section class="wrapper">
        <div id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="sidebar-logo">
                    <a href=""><h4 class="company_name">Nanobots Cinema</h4></a>
                </div>
            </div>
            @include('main.admin.layout.sidebar')
        </div>
        <div class="main">
            <nav class="navbar navbar-expand shadow-sm sticky-top">
                <button class="toggle-btn p-0 ms-4" type="button" id="sidebar-toggle">
                    <i class="fa-solid fa-bars fs-5"></i>
                </button>
                <div class="">
                    <h4>@yield('title')</h4>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown me-5">
                            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-info">
                                <i class="fa-solid fa-bell"></i>
                                <span class="d-none position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle" id="alert-dot">
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <div class="" id="no-noti">
                                    <a class="dropdown-item">There is no notification</a>
                                </div>
                                <div class="d-none" id="order-noti">
                                    <a href="{{route('admin#orderList')}}" class="dropdown-item" id="order-info"></a>
                                </div>
                                <div class="d-none" id="contact-noti">
                                    <a href="{{route('admin#customerReportList')}}" class="dropdown-item" id="contact-info"></a>
                                </div>

                            </div>
                        </li>
                        <li class="nav-item dropdown ms-3 mt-1">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-3 fs-5 text-info">
                                <i class="fa-regular fa-user"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="{{route('admin#profile')}}" class="dropdown-item">Profile</a>
                                <form action="{{route('logout')}}" method="post">
                                    @csrf
                                    <input type="submit" class="btn btn-danger dropdown-item" value="Logout">
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- admin dashboard start  -->
                <div class=" position-relative">
                    <div class=" position-absolute top-0 end-0" id="order-alert">

                    </div>
                    @if($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger py-3 alert-dismissible fade show" role="alert">
                                {{ $error }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endforeach
                    @endif
                    <div class="px-2">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{session('success')}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                {{session('warning')}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('danger'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{session('danger')}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>
                    @yield('content')
                </div>
            <!-- admin dashboard end -->
        </div>
    </section>

</body>

    <!-- bootstrap -->
    <script src="{{asset('bootstrap-5.3.0-alpha2-dist/js/bootstrap.bundle.min.js')}}"></script>

    <!-- jquery -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <script src="{{asset('jquery/jquery.js')}}"></script>
    <!-- owlcarousel  -->
    <script src="{{asset('owlcarousel/dist/owl.carousel.min.js')}}"></script>

    <!-- chart-js  -->
    <script src="{{asset('chart-js/chart.min.js')}}"></script>

    <!-- ckeditor  -->
    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "/ckeditor5-42.0.2/build/ckeditor.js",
                "ckeditor5/": "/ckeditor5-42.0.2/build/"
            }
        }
    </script>

    <!-- pusher  -->
    <script src="{{asset('pusher-js-8.2.0/dist/web/pusher.min.js')}}"></script>

    <!-- js  -->
    <script src="{{asset('admin/js/script.js')}}?v={{ filemtime(public_path('admin/js/script.js')) }}"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <script>
        $.ajax({
            type:'get',
            url:`{{route('getWebsiteInfo')}}`,
            dataType:'json',
            success:function(data){
                $('.company_name').html(data.name);
            }
        })
        $.ajax({
            type:'get',
            url:`{{route('getContact')}}`,
            dataType:'json',
            success:function(data){
                if (data.length!=0) {
                    $('#alert-dot').removeClass('d-none');
                    $('#no-noti').hide();
                    $('#contact-noti').removeClass('d-none');
                    $('#contact-info').html(`${data.length} unread customer report`);
                }

            }
        })

    </script>
    @if (Auth::user()->position=='ticket-seller')
        <script>
            function orderCheck(){
                $.ajax({
                    type:'get',
                    url:`{{route('admin#orderPendingGet')}}`,
                    dataType:'json',
                    success:function(data){
                            if (data.length!=0) {
                                $('#alert-dot').removeClass('d-none');
                                $('#no-noti').hide();
                                $('#order-noti').removeClass('d-none');
                                $('#order-info').html(`${data.length} unresponse order.`);
                            }
                        }
                    })
            }

            Pusher.logToConsole = false;

            var pusher = new Pusher(`{{ env('PUSHER_APP_KEY') }}`, {
            cluster: 'ap1'
            });

            var channel = pusher.subscribe('order-alert-channel');
            channel.bind('order-alert-event', function(data) {
                orderCheck();
            });

            orderCheck();
        </script>
    @endif
    @yield('script')
</html>
