@extends('main.customer.layout.master')
@section('content')

<section class="container-lg" id="cinema-details">
    <div id="cinema-content">
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
        <div class="row">
            <div class="col-lg-5 col-md-5">
                <div id="cinema" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($cinema->image as $image_item)
                            <div class="carousel-item @if ($loop->index==0) active  @endif">
                                <img src="{{Storage::url('cinema/'.$image_item)}}" class="d-block w-100 h-100" alt="cinema_photo">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#cinema" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#cinema" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="col-lg-7 col-md-7 ps-5">
                <h4>{{$cinema->name}}</h5>
                <hr>
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-mobile-screen me-3"></i>
                    <span class="pt-3">{!!$cinema->contact!!}</span>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <i class="fa-solid fa-map-location-dot me-3"></i>
                    <span>{{$cinema->location}}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div id="reviews-info" class="mx-auto">
        <div class="d-flex justify-content-start text-start">
            <div id="span-block">
                <span></span>
                <h4 class="fw-bold">Reviews</h4>
            </div>
        </div>
        <hr>
        <div class="my-4">
            <button type="button" class="btn" id="ticket-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">Write a review</button>
        </div>
        @if (count($review)!=0)
            <div id="comment" class="mt-5">
                <div id="review" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($review as $item)
                            <div class="carousel-item @if ($loop->index==0) active  @endif">
                                <div class="d-flex justify-content-center">
                                    <div id="review-comment" class="w-75">
                                        <div class="d-flex text-center align-items-center">
                                            <h5 class="me-2">{{$item->name}} |</h5>
                                            <span for="" class="mb-2">{{$item->created_at}}</span>
                                        </div>
                                        <span class="text-warning">
                                            @for ($i = 0; $i < $item->rating; $i++)
                                                <i class="fa-solid fa-star"></i>
                                            @endfor
                                        </span>
                                        <p class="mt-2">{{$item->review}}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#review" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#review" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        @else
            <div class="p-3">
                <h6 class="text-center text-secondary" style="font-size: 10pt;">No reviews have been posted yet.</h6>
            </div>
        @endif
    </div>
    <!-- Button trigger modal -->

  <!-- Modal -->
</section>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content bg-nav-bar">
        <div class="modal-header d-flex align-items-center justify-content-between">
            <h1 class="modal-title fs-6">Reviews</h1>
            <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
      <div class="p-3">
             <form action="{{route('customer#reviewSend')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-sm-6 mb-4">
                        <label class="py-2">Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Your Name">
                        @error('name')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-sm-6 mb-4">
                        <label class="py-2">Email *</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter Your Email">
                        @error('email')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="py-2" for="">Rating *</label>
                    <div class="star-rating">
                        <input class="radio-input bg-transparent" type="radio" id="star5" name="rating" value="5" />
                        <label class="radio-label" class for="star5" title="5 stars"></label>

                        <input class="radio-input bg-transparent" type="radio" id="star4" name="rating" value="4" />
                        <label class="radio-label" for="star4" title="4 stars"></label>

                        <input class="radio-input bg-transparent" type="radio" id="star3" name="rating" value="3" />
                        <label class="radio-label" for="star3" title="3 stars"></label>

                        <input class="radio-input bg-transparent" type="radio" id="star2" name="rating" value="2" />
                        <label class="radio-label" for="star2" title="2 stars"></label>

                        <input class="radio-input bg-transparent" type="radio" id="star1" name="rating" value="1" />
                        <label class="radio-label" for="star1" title="1 star"></label>
                    </div>
                    @error('rating')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label class="py-2">Message *</label>
                    <textarea name="review" id="" class="form-control" rows="3" placeholder="Enter Your Message"></textarea>
                    @error('review')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <input type="hidden" name="cinema_id" value="{{$cinema->id}}">
                <button type="submit" class="btn w-100" id="ticket-btn">Send Your Message</button>
            </form>
      </div>
    </div>
  </div>
</div>

@endsection
