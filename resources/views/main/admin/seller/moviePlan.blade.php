@extends('main.admin.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <div class="content py-1 px-3 mb-5">
        <div class="container-fluid">
            <div class="">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('seller#movie')}}">Seller</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Booking</li>
                    </ol>
                </nav>
            </div>
            <div class="book-ticket">
                <div class="row">
                    <div class="col-lg-3 col-md-4 mb-5">
                        <div id="ticket-m-poster">
                            <img src="{{asset('storage/poster/'.$movie->poster)}}" class="mx-auto rounded border d-block img-thumbnail"  alt="">
                            <h4 class="text-center">{{$movie->name}}</h4>
                            <ul class="d-flex align-items-center justify-content-center gap-5 pe-3">
                                <li>{{ TimeHelper::convertMinutesToHoursMinutes($movie->run_time) }}</li>
                                <li>{{$movie->language}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div id="show-time">
                            <div id="select-date">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-calendar-day me-2"></i>
                                    <h6 class="fw-medium mb-0">Select Date</h6>
                                </div>
                                <ul class="row mt-2 p-1">
                                    <li class="col-lg-2 col-md-3 col-sm-6 px-2">
                                        <a href="{{route('seller#moviePlan',$movie->id)}}">
                                            <button id="first-date-button" class="js-select-date @if(request('date')=='') js-select-active @endif">
                                                <span>All</span>
                                            </button>
                                        </a>
                                    </li>
                                    @foreach ($date as $item)
                                        <li class="col-lg-2 col-md-3 col-sm-6 px-2">
                                            <a href="{{route('seller#moviePlan',[$movie->id,'date'=>$item])}}">
                                                <button class="js-select-date @if(request('date')==$item) js-select-active @endif">
                                                    <span>{{Carbon::parse($item)->format('j/ m/ Y (l)')}}</span>
                                                </button>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div id="select-time">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-clock me-2"></i>
                                        <h6 class="fw-medium mb-0">Select Time</h6>
                                    </div>
                                </div>
                                <ul class="row mt-2 p-1">
                                    @foreach ($moviePlan as $item)
                                        <li class="col-lg-3 col-md-4 col-sm-6 px-2">
                                            <button data-bs-toggle="modal" data-bs-target="#{{'select-seat-modal'.$item->id}}">
                                                <div class="mb-1">{{$item->hall_name}}</div>
                                                <div>{{Carbon::parse($item->start_time)->format('h:i A')}}</div>
                                            </button>
                                        </li>
                                        <!-- select seat modal  -->
                                        <div class="modal fade" id="{{'select-seat-modal'.$item->id}}" tabindex="-1" aria-labelledby="select-seat-modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content border-0 position-relative bg-transparent">
                                                    <div class="d-flex justify-content-end">
                                                        <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                                    </div>
                                                    <div class="row mx-auto">
                                                        <div class="col-lg-5 text-center mb-4">
                                                            <img src="{{Storage::url('poster/'.$movie->poster)}}" class="mx-auto rounded d-block w-50 img-thumbnail"  alt="">
                                                            <h5>{{$movie->name}}</h5>
                                                            <ul class="d-flex align-items-center justify-content-center gap-5 pe-3">
                                                                <li>{{ TimeHelper::convertMinutesToHoursMinutes($movie->run_time) }}</li>
                                                                <li>{{$movie->language}}</li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-lg-7 text-start pt-3 ps-lg-4">
                                                            <div class="mb-4">
                                                                <label>Cinema</label>
                                                                <h6 class="mt-1">{{$item->cinema_name}}</h6>
                                                            </div>
                                                            <div class="mb-4">
                                                                <label>Hall</label>
                                                                <h6 class="mt-1">{{$item->hall_name}}</h6>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Date & Time</label>
                                                                <h6 class="mt-1">{{$item->date}} , {{Carbon::parse($item->start_time)->format('h:i A')}}</h6>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

