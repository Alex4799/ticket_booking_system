@extends('main.customer.layout.master')
@section('content')
        <!-- home section start  -->
        <section id="home" class="position-relative">
            <div>
                <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner cover-carousel-inner">
                        @if (count($website->cover_image)!=0)
                            @foreach ($website->cover_image as $item)
                                <div class="carousel-item @if($loop->index==0) active @endif" data-bs-interval="3000">
                                    <img src="{{Storage::url('website/'.$item)}}" class="d-block w-100" alt="...">
                                </div>
                            @endforeach
                        @else
                            <div class="carousel-item active" data-bs-interval="3000">
                                <img src="{{asset('image/default.jpg')}}" class="d-block w-100" alt="...">
                            </div>
                        @endif
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Next</span>
                    </button>
                  </div>
            </div>
            <div class="container-lg" id="search-ticket">
                <div class="row mx-auto align-items-center">
                    <div class="col-lg-7 col-sm-7">
                        <h2>FIND TICKET NOW</h2>
                    </div>
                    <div class="col-lg-5 col-sm-5 z-100">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#tutorial" class="fs-6 btn">
                            <i class="fa-brands fa-youtube"></i>
                            <span class="ms-2">Ticket Booking Guide</span>
                        </a>
                    </div>
                </div>

            <div class="mt-3" id="home-search">
                <div class="">
                    <div>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" style="font-size: 10pt;" role="alert">
                                {{session('success')}}
                                <button type="button" class="btn-close shadow-none border-0" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" style="font-size: 10pt;" role="alert">
                                {{session('warning')}}
                                <button type="button" class="btn-close shadow-none border-0" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('danger'))
                            <div class="alert alert-danger alert-dismissible fade show" style="font-size: 10pt;" role="alert">
                                {{session('danger')}}
                            <button type="button" class="btn-close shadow-none border-0" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show" style="font-size: 10pt;" role="alert">
                                    {{$error}}
                                    <button type="button" class="btn-close shadow-none border-0" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <form action="#" method="POST" id="cinema-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="custom-select">
                                    <button class="select-button" role="combobox" aria-label="select button" aria-haspopup="listbox" aria-expanded="false" aria-controls="select-dropdown">
                                        <span class="selected-value"><i class="fa-solid fa-map-location-dot"></i>Location</span>
                                        <span class="arrow"></span>
                                    </button>
                                    <ul class="select-dropdown" role="listbox" >
                                        @foreach ($cinema as $item)
                                            <li role="option">
                                                <input type="radio" id="c-location{{$item->id}}" value="{{$item->id}}" name="cinemaLocation"/>
                                                <label for="c-location{{$item->id}}">{{$item->name}}({{$item->location}})</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="custom-select">
                                    <button class="select-button" role="combobox" aria-label="select button" aria-haspopup="listbox" aria-expanded="false" aria-controls="select-dropdown">
                                        <span class="selected-value"><i class="fa-regular fa-calendar-check"></i>Date</span>
                                        <span class="arrow"></span>
                                    </button>
                                    <ul class="select-dropdown" role="listbox" >
                                        @foreach ($filterData['date'] as $item)
                                            <li role="option">
                                                <input type="radio" id="c-date{{$item}}" value="{{$item}}" name="cinemaDate"/>
                                                <label for="c-date{{$item}}">{{$item}}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-11">
                                <div class="custom-select">
                                    <button class="select-button" role="combobox" aria-label="select button" aria-haspopup="listbox" aria-expanded="false" aria-controls="select-dropdown">
                                        <span class="selected-value"><i class="fa-solid fa-film"></i>Movie</span>
                                        <span class="arrow"></span>
                                    </button>
                                    <ul class="select-dropdown" role="listbox" >
                                        @foreach ($filterData['movie'] as $item)
                                            <li role="option">
                                                <input type="radio" id="c-movie{{$item['movie_id']}}" value="{{$item['movie_id']}}" name="cinemaMovie"/>
                                                <label for="c-movie{{$item['movie_id']}}">{{$item['movie_name']}}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 mt-1">
                                <button type="submit" class="btn search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            </div>
        </section>
        <!-- home section end  -->

        <!-- popular start  -->
        <section class="container-lg" id="popular-movie">
            <div class="d-flex align-content-center justify-content-between">
                <div id="span-block">
                    <span></span>
                    <h4 class="fw-bold">Popular Movies</h4>
                </div>
            </div>
            <hr>

            <div class="swiper popularSwiper p-0 mx-auto">
                <div class="swiper-wrapper" id="popular-wrapper">
                    @foreach ($movies['popular'] as $item)
                        <div class="swiper-slide">
                            <div class="item">
                                <span class="rank">{{$loop->index+1}}</span>
                                @if ($loop->index+1==1)
                                    <span class="first-rank"><i class="fa-solid fa-crown"></i></span>
                                @endif
                                <div class="img-content">
                                    <a href="{{route('customer#movieView',$item->id)}}" class="text-decoration-none">
                                        <img src="{{Storage::url('poster/'.$item->poster)}}" alt="">
                                    </a>
                                    <span class="popular-rate"><i class="fa-solid fa-star"></i> {{$item->rating}} </span>
                                </div>
                                <a href="{{route('customer#movieView',$item->id)}}" class="text-decoration-none text-center">
                                    <label for="">{{$item->name}}</label>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </section>
        <!-- popular end  -->

        <!-- now showing movie start  -->
        <section class="container-lg" id="now-showing-movie">
            <div class="d-flex align-content-center">
                <div id="span-block">
                    <span></span>
                    <h4 class="fw-bold">Now Showing</h4>
                </div>
            </div>
            <hr>
            <div class="row movie-list">
                @foreach ($movies['now'] as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div>
                            <a href="{{route('customer#movieView',$item->id)}}">
                                <div class="zoom-img">
                                    <img src="{{Storage::url('poster/'.$item->poster)}}"  alt="">
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <h5 class="text-center height-add m-auto"><span>{{$item->name}}</span></h5>
                                </div>
                            </a>
                            <hr>
                            <div class="d-flex align-content-center justify-content-between">
                                <a href="{{route('customer#movieBook',$item->id)}}">
                                    <button class="btn" id="ticket-btn">
                                        <span><i class="fa-solid fa-ticket"></i></span>
                                        <label>Book Ticket</label>
                                    </button>
                                </a>
                                <p class="rate-display">
                                    @if ($item->language!='Myanmar')
                                        <img src="{{asset('image/imdb.png')}}" alt="">
                                    @endif
                                    <span><i class="fa-regular fa-star"></i></span>
                                    <label>{{$item->rating}}</label>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        <!-- now showing movie end  -->

        <!-- coming soon start  -->
        <section class="container-lg" id="coming-soon">
            <div class="d-flex align-content-center">
                <div id="span-block">
                    <span></span>
                    <h4 class="fw-bold">Coming Soon</h4>
                </div>
            </div>
            <hr>
            @if (count($movies['comming']) != 0)
                <div class="row movie-list">
                    @foreach ($movies['comming'] as $item)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div>
                                <a href="{{route('customer#movieView',$item->id)}}">
                                    <div class="zoom-img">
                                        <img src="{{Storage::url('poster/'.$item->poster)}}"  alt="">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <h5 class="text-center height-add m-auto"><span>{{$item->name}}</span></h5>
                                    </div>
                                </a>
                                <hr>
                                <div class="d-flex align-content-center justify-content-between">
                                    <a href="{{route('customer#movieBook',$item->id)}}">
                                        <button class="btn" id="ticket-btn">
                                            <span><i class="fa-solid fa-ticket"></i></span>
                                            <label>Book Ticket</label>
                                        </button>
                                    </a>
                                    <p class="rate-display">
                                        @if ($item->language!='Myanmar')
                                            <img src="{{asset('image/imdb.png')}}" alt="">
                                        @endif
                                        <span><i class="fa-regular fa-star"></i></span>
                                        <label>{{$item->rating}}</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-5 px-3 mt-3">
                    <h6 class="text-center text-secondary" style="font-size: 10pt;">No upcoming movies available at the moment.</h6>
                </div>
            @endif
        </section>
        <!-- coming soon end  -->

        <!-- youtube modal  -->
        <div class="modal fade" id="tutorial" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="trailerLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0 bg-transparent">
                    <div class="d-flex justify-content-end mb-2">
                        <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <iframe id="videoTrailer" src="https://www.youtube.com/embed/omNKZFp6ajo?si=nwFk5BOwdTjR-qno" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <!-- movie details end  -->
@endsection
@section('script')
    <script>
        const modal = document.getElementById('tutorial');
        const video = document.getElementById('videoTrailer');

        // When the tutorial modal is hidden
        modal.addEventListener('hidden.bs.modal', function () {
            video.src = video.src;
        });

        //..................... popular movies scroller animation start ..........................
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.popularSwiper');
            const wrapper = container.querySelector('.swiper-wrapper');
            const MIN_SLIDES = 12;
            const originalSlides = Array.from(wrapper.children);
            if (wrapper.children.length < MIN_SLIDES) {
                let i = 0;
                while (wrapper.children.length < MIN_SLIDES) {
                    const cloned = originalSlides[i % originalSlides.length].cloneNode(true);
                    wrapper.appendChild(cloned);
                    i++;
                }
            }
            const totalSlides = wrapper.querySelectorAll('.swiper-slide').length;

            const swiper = new Swiper('.popularSwiper', {
            loop: true,
            slidesPerView: 'auto',
            spaceBetween: 20,
            speed: 5000,
            // freeMode: true,
            freeModeMomentum: false,
            allowTouchMove: true,
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
                pauseOnMouseEnter: false,
            },
            loopedSlides: totalSlides
            });
        });
        //..................... popular movies scroller animation end ..........................
    </script>
@endsection
