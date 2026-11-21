@extends('main.customer.layout.master')
@section('content')

    <!-- cinema details start  -->
    <section class="container-lg" id="cinema-details">
        <div class="d-flex align-content-center justify-content-between">
            <div id="span-block">
                <span></span>
                <h4 class="fw-bold">Participating Cinemas</h4>
            </div>
            <div class="swiper-btn2s">
                <button class="btn button-prev2 me-2">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="btn button-next2">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
        <hr>
        @if (count($cinema)!=0)
            <div class="swiper mySwiper3">
                <div class="swiper-wrapper">
                    @foreach ($cinema as $item)
                        <div class="swiper-slide">
                            <div class="cinema-detail-content">
                                <div id="cinema{{$item->id}}" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach ($item->image as $image_item)active
                                            <div class="carousel-item @if ($loop->index==0) active  @endif">
                                                <img src="{{Storage::url('cinema/'.$image_item)}}" class="d-block w-100" alt="cinema_photo">
                                            </div>
                                        @endforeach
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#cinema{{$item->id}}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#cinema{{$item->id}}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                                <div class="py-2">
                                    <h5>{{$item->name}}</h5>
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-mobile-screen me-3"></i>
                                        <span class="pt-3">{!!$item->contact!!}</span>
                                    </div>
                                    <div class="d-flex align-items-center mt-1">
                                        <i class="fa-solid fa-map-location-dot me-3"></i>
                                        <span>{{$item->location}}</span>
                                    </div>
                                    <div class="mt-4 d-flex justify-content-end">
                                        <a href="{{route('customer#cinemaView',$item->id)}}">
                                            <button class="btn" id="ticket-btn">
                                                <label>Write Review</label>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="py-5 px-3 mt-5">
                <h6 class="text-center text-secondary" style="font-size: 10pt;">No cinemas available at the moment.</h6>
            </div>
        @endif
    </section>
    <!-- cinema details end  -->


    <!-- contact us start  -->
    <section class="px-5" id="contact-us">
        <div class="row">
            <div class="col-lg-4 col-md-4 pt-3" id="contact-us-info">
                <h6>GET IN TOUCH</h6>
                <p>Whether you’re curious about our cinemas, in love with our service, or have feedback for us, we’d love to hear from you.</p>
                <div class="mt-4">
                    <strong class="d-block">Customer Relations Hotline</strong>
                    <div id="contact-block">
                        <span class="mb-3"></span>
                        <p>{!!$website->contact!!}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <strong class="d-block">Our Social Page</strong>
                    @if (count($social)!=0)
                        <div class="d-flex align-items-center mt-4">
                            <div class="d-flex me-4 mb-1" id="footer-icon">
                                @foreach ($social as $item)
                                    <a href="{{$item->link}}"><img src="{{Storage::url('social/'.$item->logo)}}" class="w-100 img-thumbnail" alt=""></a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mt-4">
                            <h6 class="text-secondary" style="font-size: 10pt;">No social pages available yet.</h6>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-8 col-md-8 px-4" id="contact-us-form">
                <div>
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
                    </div>
                    <form action="{{route('customer#contactSend')}}" method="POST">
                        @csrf
                        <div class="row form-group">
                            <div class="col-lg-6 col-sm-6 mb-4">
                                <label>Name *</label>
                                <input type="text" value="{{old('name')}}" class="form-control" name="name" placeholder="Enter Your Name">
                                @error('name')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-sm-6 mb-4">
                                <label>Email *</label>
                                <input type="email" value="{{old('email')}}" class="form-control" name="email" placeholder="Enter Your Email">
                                @error('email')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label>Subject *</label>
                            <input type="text" value="{{old('subject')}}" class="form-control" name="subject" placeholder="Enter Your Subject">
                            @error('subject')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label>Message *</label>
                            <textarea name="message" class="form-control" rows="6" placeholder="Enter Your Message">{{old('message')}}</textarea>
                            @error('message')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-end">
                            <button type="submit" class="btn" id="ticket-btn">Send Your Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- contact us end -->

@endsection
@section('script')
@endsection
