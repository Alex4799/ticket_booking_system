<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cinema</title>

    <!-- css bootstrap  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- font-awesome  -->
    <link rel="stylesheet" href="{{asset('fontawesome/css/all.css')}}"/>
    {{-- <script src="https://kit.fontawesome.com/04534957fb.js" crossorigin="anonymous"></script> --}}

    <!-- animate  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- swiper css  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- css -->
    <link rel="stylesheet" href="{{asset("customer/css/style.css")}}?v={{ filemtime(public_path('customer/css/style.css')) }}">

    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.css" />

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#000000">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-192x192.png') }}">

    <script>
        (function() {
          const doc = document.documentElement;
          const theme = localStorage.getItem('theme');
          doc.classList.add('preload');

          if (theme === 'light') {
            doc.classList.add('light');
          }

          window.addEventListener('load', () => {
            doc.classList.remove('preload');
          });
        })();
    </script>

    @yield('style')
</head>
<body>
    <!-- nav bar start-->
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm" id="nav">
        <div class="container-fluid">
            <a href="{{route('customer#home')}}" class="d-flex align-items-center text-decoration-none">
                <img src="{{asset('image/default.png')}}" alt="" id="logo-img">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <button type="button" class="bg-transparent border-0 text-white w-100 text-end" data-bs-dismiss="offcanvas" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav m-auto" id="nav-order">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('customer#home')}}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('customer#movieList')}}">Movies</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('customer#cinemaList')}}">Time Table</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('customer#promotionList')}}">Promotions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('customer#orderCheckPage')}}">Ticket</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('customer#aboutUsPage')}}">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('customer#contactPage')}}">Contact</a>
                        </li>
                    </ul>
                    <div class="" id="nav-icon">
                        <div class="dropdown">
                            <a class="btn border-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical ell-vertical"></i>
                            </a>
                            <ul class="dropdown-menu text-center dropdown-menu-md-end">
                                <li class="mb-3">
                                    <div class="">
                                        <span>Theme: </span>
                                        <label class="switch ms-3">
                                            <span class="sun"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="#ffd43b"><circle r="5" cy="12" cx="12"></circle><path d="m21 13h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm-17 0h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm13.66-5.66a1 1 0 0 1 -.66-.29 1 1 0 0 1 0-1.41l.71-.71a1 1 0 1 1 1.41 1.41l-.71.71a1 1 0 0 1 -.75.29zm-12.02 12.02a1 1 0 0 1 -.71-.29 1 1 0 0 1 0-1.41l.71-.66a1 1 0 0 1 1.41 1.41l-.71.71a1 1 0 0 1 -.7.24zm6.36-14.36a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm0 17a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm-5.66-14.66a1 1 0 0 1 -.7-.29l-.71-.71a1 1 0 0 1 1.41-1.41l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.29zm12.02 12.02a1 1 0 0 1 -.7-.29l-.66-.71a1 1 0 0 1 1.36-1.36l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.24z"></path></g></svg></span>
                                            <span class="moon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="m223.5 32c-123.5 0-223.5 100.3-223.5 224s100 224 223.5 224c60.6 0 115.5-24.2 155.8-63.4 5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6-96.9 0-175.5-78.8-175.5-176 0-65.8 36-123.1 89.3-153.3 6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z"></path></svg></span>
                                            <input type="checkbox" class="theme-toggle" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    @if (empty(Auth::user()))
                                        <a href="{{route('login')}}" class="btn text-white" role="button" id="login-btn">
                                            Login
                                        </a>
                                    @else
                                        <a href="{{route('admin#profile')}}" class="btn text-white" role="button" id="login-btn">
                                            Dashboard
                                        </a>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="text-center" id="nav-ph">
                        <div class="mb-5">
                            <span>Theme: </span>
                            <label class="switch ms-3">
                                <span class="sun"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="#ffd43b"><circle r="5" cy="12" cx="12"></circle><path d="m21 13h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm-17 0h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm13.66-5.66a1 1 0 0 1 -.66-.29 1 1 0 0 1 0-1.41l.71-.71a1 1 0 1 1 1.41 1.41l-.71.71a1 1 0 0 1 -.75.29zm-12.02 12.02a1 1 0 0 1 -.71-.29 1 1 0 0 1 0-1.41l.71-.66a1 1 0 0 1 1.41 1.41l-.71.71a1 1 0 0 1 -.7.24zm6.36-14.36a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm0 17a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm-5.66-14.66a1 1 0 0 1 -.7-.29l-.71-.71a1 1 0 0 1 1.41-1.41l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.29zm12.02 12.02a1 1 0 0 1 -.7-.29l-.66-.71a1 1 0 0 1 1.36-1.36l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.24z"></path></g></svg></span>
                                <span class="moon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="m223.5 32c-123.5 0-223.5 100.3-223.5 224s100 224 223.5 224c60.6 0 115.5-24.2 155.8-63.4 5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6-96.9 0-175.5-78.8-175.5-176 0-65.8 36-123.1 89.3-153.3 6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z"></path></svg></span>
                                <input type="checkbox" class="theme-toggle" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        @if (empty(Auth::user()))
                            <a href="{{route('login')}}" class="btn text-white" role="button" id="login-btn">
                                Login
                            </a>
                        @else
                            <a href="{{route('admin#profile')}}" class="btn text-white" role="button" id="login-btn">
                                Dashboard
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- nav bar end  -->

    @yield('content')

    <!-- footer section start  -->
    <section id="footer">
        <div class="px-4 pt-3">
            <div class="d-flex align-items-center justify-content-between" id="footer-bottom">
                <p>© 2024 <span class="company_name">MCL</span>. All rights reserved.</p>
                <p id="developer">Developed By : <a href="">Alex</a> & <a href="">HP</a></p>
            </div>
        </div>
    </section>
    <!-- footer section end -->
</body>

    {{-- <script src="{{asset('fontawesome/js/all.js')}}"></script> --}}

    <!-- js bootstrap  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- swiper js  -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- jquery -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <script src="{{asset('jquery/jquery.js')}}"></script>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/html2canvas"></script>

    <!-- js  -->
    <script>
        $(document).ready(function(){
            $.ajax({
                type:'get',
                url:`{{route('getWebsiteInfo')}}`,
                dataType:'json',
                success:function(data){
                    if (data.logo!=null) {
                        $image=`{{asset('storage/website')}}/${data.logo}`;
                        $('#logo-img').attr("src",$image);
                    }
                    $('.company_name').html(data.name);
                    $('#footer_letter').html(data.footer_letter);
                }
            })
        })
    </script>

    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.2/"
            }
        }
    </script>
    @yield('script')
    <script src="{{asset('customer/js/script.js')}}?v={{ filemtime(public_path('customer/js/script.js')) }}"></script>
</html>
