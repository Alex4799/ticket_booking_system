@extends('main.customer.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp
    <!-- movie category start  -->
    <section class="mx-3" id="movies-category">
        <div class="row" >
            <div class="col-lg-9">
                <div id="movie-label">
                    <form action="">
                        <div class="group">
                            <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                            <input class="input" value="{{request('search_key')}}" type="text" placeholder="Search for movie" name="search_key"/>
                        </div>
                    </form>
                </div>
                <div id="movie-list">
                    <div class="row" id="movie-sorting">
                        <div class="col-lg-8 col-sm-8">
                            <div class="d-flex align-items-center">
                                <label>Sort By : </label>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        @if (!empty(request('type')))
                                            @if ((request('type'))=='now')
                                                Now Showing
                                            @else
                                                Coming Soon
                                            @endif
                                        @else
                                            All
                                        @endif
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li role="option">
                                            <a class="dropdown-item" href="{{route('customer#movieList')}}">All</a>
                                        </li>
                                        <li role="option">
                                            <a class="dropdown-item" href="{{route('customer#movieList',['type'=>'now'])}}">Now Showing</a>
                                        </li>
                                        <li role="option">
                                            <a class="dropdown-item" href="{{route('customer#movieList',['type'=>'coming'])}}">Coming Soon</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <ul id="movie-cell-category">
                                <li><a href="javascript:void(0)" class="cell-content " data-element-class="movieList"><i class="fa-solid fa-list"></i></a></li>
                                <li><a href="javascript:void(0)" class="cell-content cell-active" data-element-class="movieCell"><i class="fa-solid fa-table-cells-large"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="genre-ph">
                    <div class="d-flex align-items-center genre-scroll-container">
                        <a href="{{route('customer#movieList')}}" class="category-link">
                            <button class="btn">All</button>
                        </a>
                        @foreach ($genre as $item)
                            <a href="{{route('customer#movieList',['genre'=>$item->id])}}" class="category-link">
                                <button class="btn">{{$item->name}}</button>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div id="movie-list-content">
                     @if (count($movie)!=0)
                        <div class="container-lg" id="movie-list-show">
                            @foreach ($movie as $item)
                                <div class="row mb-4">
                                    <div class="col-lg-4 col-md-4 col-sm-5">
                                        <a href="{{route('customer#movieView',$item->id)}}">
                                            <img src="{{Storage::url('poster/'.$item->poster)}}" class="w-100 d-block rounded" alt="">
                                        </a>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-7">
                                        <a href="{{route('customer#movieView',$item->id)}}">
                                            <h4>{{$item->name}}</h4>
                                        </a>
                                        <span>{{ TimeHelper::convertMinutesToHoursMinutes($item->run_time) }}</span>
                                        <p class="mt-3">
                                            @foreach ($item->genre as $genreItem)
                                                {{$genreItem}} |
                                            @endforeach
                                        </p>
                                        <strong>Release Date :</strong><label class="ms-2">{{Carbon::parse($item->release_date)->format('l, F j, Y')}}</label>
                                        <hr>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <a href="{{route('customer#movieBook',$item->id)}}">
                                                <button class="btn" id="ticket-btn">
                                                    <span><i class="fa-solid fa-ticket"></i></span>
                                                    <label>Book Ticket</label>
                                                </button>
                                            </a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#trailer{{$item->id}}" id="circle-play"><i class="fa-solid fa-circle-play"></i> Watch Trailer</a>
                                        </div>
                                        <!-- youtube modal  -->
                                        <div class="modal fade trailer" id="trailer{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="trailerLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 bg-transparent">
                                                    <div class="d-flex justify-content-end mb-2">
                                                        <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                                    </div>
                                                    <iframe style="width: 100%;height: 300px;" id="videoTrailer{{$item->id}}" src="{{$item->trailer}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="container-lg" id="movie-cell-show">
                            <div class="row mx-auto w-100">
                                @foreach ($movie as $item)
                                    <div class="col-lg-3 col-md-3 col-sm-4 mb-4">
                                        <div>
                                            <a href="{{route('customer#movieView',$item->id)}}">
                                                <img src="{{Storage::url('poster/'.$item->poster)}}" class="w-100 d-block rounded" alt="">
                                                <h5>{{$item->name}}</h5>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="py-5 px-3 mt-5">
                            <h6 class="text-center text-secondary" style="font-size: 10pt;">No movies available at the moment.<h6>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-3 order-lg-first">
                <div id="category-form">
                    <form>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5>Genre</h5>
                        </div>
                        <hr>
                        <div id="category-content">
                            <div class="d-flex align-items-center justify-content-between">
                                <a href="{{route('customer#movieList')}}" class="category-link"><i class="fa-solid fa-caret-right me-2"></i><span>All</span></a>
                            </div>
                            @foreach ($genre as $item)
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="{{route('customer#movieList',['genre'=>$item->id])}}" class="category-link"><i class="fa-solid fa-caret-right me-2"></i><span>{{$item->name}}</span></a>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- movie category start  -->

@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                const video = modal.querySelector('iframe');
                modal.addEventListener('hidden.bs.modal', function () {
                    video.src = video.src;
                });
            });
        });
    </script>
@endsection
