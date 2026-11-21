@extends('main.customer.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <!-- movie details start  -->
    <section id="movie-details">
        <div class="mb-4">
            <div id="movie-cover-poster">
                @if (!empty($movie->cover[0]))
                    <img src="{{Storage::url('cover/'.$movie->cover[0])}}" alt="">
                @else
                    <img src="{{asset('image/placeholder.webp')}}" alt="">
                @endif
                <span></span>
            </div>
            <div class="row mx-auto" id="movie-detail-content">
                <div class="col-lg-4">
                    <img src="{{Storage::url('poster/'.$movie->poster)}}" class="mx-auto d-block"  alt="">
                </div>
                <div class="col-lg-8 pe-5">
                    <h1>{{$movie->name}}</h1>
                    <div class="mt-4">
                        <div class="first-side">
                            <div class="d-flex align-items-center flex-wrap mb-3">
                                <div>
                                    <label for="">• {{Carbon::parse($movie->release_date)->format('l, F j, Y')}}</label>
                                </div>
                                <div>
                                    <label for="">• {{ TimeHelper::convertMinutesToHoursMinutes($movie->run_time) }}</label>
                                </div>
                            </div>
                            <div id="detail-rating">
                                <span><i class="fa-solid fa-star"></i></span>
                                <span>{{$movie->rating}}/10</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-4" id="detail-flex">
                            <div class="me-5 w-100">
                                <div class="d-flex align-items-center flex-wrap mb-3">
                                    <h6 class="mb-0 me-2"> Genre : </h6>
                                    <span>
                                        @foreach ($movie->genre as $item)
                                            {{$item}} ,
                                        @endforeach
                                    </span>
                                </div>
                                <div class="d-flex align-items-center flex-wrap mb-3">
                                    <h6 class="mb-0 me-2">Directed By : </h6>
                                    <span>{{$movie->director}}</span>
                                </div>
                            </div>
                            <div class="ms-5 w-100">
                                <div class="d-flex align-items-center flex-wrap mb-3">
                                    <h6 class="mb-0 me-2">Spoken Language : </h6>
                                    <span>{{$movie->language}}</span>
                                </div>
                                <div class="d-flex align-items-center flex-wrap mb-3">
                                    <h6 class="mb-0 me-2">Subtitles : </h6>
                                    <span>{{$movie->subtitle}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $cleanSummary = preg_replace('/style="[^"]*background-color:\s*[^;"]+;?[^"]*"/i', '', $movie->summary);
                    @endphp
                    <p class="mt-2">{!! $cleanSummary !!}</p>
                    <div class="d-flex align-items-center justify-content-between mt-5">
                        <a href="{{route('customer#movieBook',$movie->id)}}">
                            <button class="btn fs-6" id="ticket-btn">
                                <span><i class="fa-solid fa-ticket"></i></span>
                                <label>Book Ticket</label>
                            </button>
                        </a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#trailer" class="fs-6" id="circle-play"><i class="fa-solid fa-circle-play"></i> Watch Trailer</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="more-cover-poster">
            <h4>Photos</h4>
            @if ($movie->cover!=null && count($movie->cover) != 0)
                <div class="swiper mySwiper5 mt-4">
                    <div class="swiper-wrapper">
                        @foreach ($movie->cover as $item)
                            <div class="swiper-slide">
                                <img src="{{Storage::url('cover/'.$item)}}" alt="">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            @else
                <div class="py-5 px-3 mt-3">
                    <h6 class="text-center text-secondary" style="font-size: 10pt;">No photo available at the moment.</h6>
                </div>
            @endif

        </div>
        <div id="movie-cast">
            <h4>Movie Cast</h4>
            @if ($movie->cast!=null && count($movie->cast) != 0)
                <div class="swiper mySwiper6 mt-4 px-1">
                    <div class="swiper-wrapper">
                        @foreach ($movie->cast as $item)
                            <div class="swiper-slide">
                                <img src="{{Storage::url('cast/'.$item['image'])}}" alt="">
                                <h5>
                                    <span>{{$item['name']}}</span>
                                </h5>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            @else
                <div class="py-5 px-3 mt-3">
                    <h6 class="text-center text-secondary" style="font-size: 10pt;">No cast available at the moment.</h6>
                </div>
            @endif
        </div>
    </section>

    <!-- youtube modal  -->
    <div class="modal fade" id="trailer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="trailerLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bg-transparent">
                <div class="d-flex justify-content-end mb-2">
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <iframe id="videoTrailer" src="{{$movie->trailer}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    <!-- movie details end  -->

@endsection
@section('script')
<script>
    const modal = document.getElementById('trailer');
    const video = document.getElementById('videoTrailer');

    modal.addEventListener('hidden.bs.modal', function () {
        video.src = video.src;
    });
</script>
@endsection
