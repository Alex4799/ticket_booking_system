@extends('main.admin.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <!-- book ticket start  -->
    <section id="book-ticket">
        <div id="table-bg-show">
            <div class="py-2">
                <div class="" id="ticket-m-poster">
                    <img src="{{Storage::url('poster/'.$movie->poster)}}" class="mx-auto w-25 rounded border d-block img-thumbnail"  alt="">
                    <h4 class="text-center py-2">{{$movie->name}}</h4>
                    <ul class="d-flex align-items-center justify-content-center">
                        <li class="me-5">{{ TimeHelper::convertMinutesToHoursMinutes($movie->run_time) }}</li>
                        <li>{{$movie->language}}</li>
                    </ul>
                </div>
            </div>
            <div id="show-time">
                <div class="container-lg mt-4" id="select-date">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-caret-right me-3 mb-1" id="caret-right"></i>
                        <h5 class="fw-medium">Select Date</h5>
                    </div>
                    <ul class="row mt-2 mx-auto">
                        <li class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{route('seller#moviePlan',$movie->id)}}">
                                <button id="first-date-button" class="js-select-date @if(request('date')=='') js-select-active @endif">
                                    <span>All</span>
                                </button>
                            </a>
                        </li>
                        @foreach ($date as $item)
                            <li class="col-lg-3 col-md-4 col-sm-6">
                                <a href="{{route('seller#moviePlan',[$movie->id,'date'=>$item])}}">
                                    <button class="js-select-date @if(request('date')==$item) js-select-active @endif">
                                        <span>{{Carbon::parse($item)->format('j/ m/ Y (l)')}}</span>
                                    </button>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="container-lg" id="select-cinema-time">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-caret-right me-3 mb-1" id="caret-right"></i>
                            <h5 class="fw-medium">Select Time</h5>
                        </div>
                    </div>
                    <form action="" id="search-form-small" class="mb-3">
                        <div class="search-cinema">
                            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                            <input placeholder="Search" type="search" class="input">
                        </div>
                    </form>
                    <div id="cinema-time-content" class="mt-2">
                        <div class="cinema-section">
                            <div class="select-time">
                                <ul class="row mt-5 mx-auto ps-0">
                                    @foreach ($moviePlan as $item)
                                        <li class="col-lg-3 col-md-4 col-sm-6">
                                            <button data-bs-toggle="modal" data-bs-target="#{{'select-seat-modal'.$item->id}}">
                                                <div>{{$item->hall_name}}</div>
                                                <div>{{Carbon::parse($item->start_time)->format('h:i:A')}}</div>
                                            </button>
                                        </li>
                                        <!-- select seat modal  -->
                                        <div class="modal fade" id="{{'select-seat-modal'.$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="select-seat-modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content border-0 position-relative bg-transparent">
                                                    <div class="d-flex justify-content-end mb-2">
                                                        <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 text-center">
                                                            <img src="{{Storage::url('poster/'.$movie->poster)}}" class="mx-auto rounded border d-block w-75"  alt="">
                                                            <h5>{{$movie->name}}</h5>
                                                            <ul class="d-flex align-items-center justify-content-center">
                                                                <li class="me-5">{{ TimeHelper::convertMinutesToHoursMinutes($movie->run_time) }}</li>
                                                                <li>{{$movie->language}}</li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 text-start pt-4">
                                                            <div class="mb-4">
                                                                <label>Cinema</label>
                                                                <h6 class="mt-1">{{$item->cinema_name}}</h6>
                                                            </div>
                                                            <div class="mb-4">
                                                                <label>Hall</label>
                                                                <h6 class="mt-1">{{$item->hall_name}}</h6>
                                                            </div>
                                                            <div class="mb-4">
                                                                <label>Date & Time</label>
                                                                <h6 class="mt-1">{{$item->date}} , {{Carbon::parse($item->start_time)->format('h:i:A')}}</h6>
                                                            </div>
                                                            <a href="{{route('seller#selectSeat',$item->id)}}" class="text-decoration-none text-white">
                                                                <button type="button" class="btn w-100">Select Seats</button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </ul>
                            </div>
                            <hr>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- book ticket end  -->

@endsection

