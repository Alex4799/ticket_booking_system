@extends('main.customer.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <!-- book ticket start  -->
    <section id="book-ticket">
        <div>
            <div id="ticket-mc-poster">
                @if (!empty($movie->cover[0]))
                    <img src="{{Storage::url('cover/'.$movie->cover[0])}}" alt="">
                @else
                    <img src="{{asset('image/default.jpg')}}" alt="">
                @endif
                <span></span>
            </div>
            <div class="" id="ticket-m-poster">
                <img src="{{Storage::url('poster/'.$movie->poster)}}" class="mx-auto rounded d-block"  alt="">
                <h4>{{$movie->name}}</h4>
                <ul class="d-flex align-items-center justify-content-center">
                    <li class="me-5">{{ TimeHelper::convertMinutesToHoursMinutes($movie->run_time) }}</li>
                    <li>{{$movie->language}}</li>
                </ul>
            </div>
        </div>
        <div id="order-location1">
            <div class="d-flex align-items-center justify-content-center">
                <span></span>
                <span></span>
            </div>
            <div id="o-location-n">
                <div class="d-flex align-items-center justify-content-around">
                    <label for="">1</label>
                    <label for="">2</label>
                    <label for="">3</label>
                </div>
                <div class="d-flex align-items-center justify-content-around mt-1">
                    <h6>Select Showtime</h6>
                    <h6>Select Seats</h6>
                    <h6>Confirm Payment</h6>
                </div>
            </div>
        </div>
        <div id="show-time" class="mt-5">
            <div class="container-lg mt-4" id="select-date">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-caret-right me-3 mb-1" id="caret-right"></i>
                    <h5 class="fw-medium">Select Date</h5>
                </div>
                <ul class="row mt-2 mx-auto">
                    <li class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{route('customer#movieBook',$movie->id)}}">
                            <button id="first-date-button" class="js-select-date">
                                <span>All</span>
                            </button>
                        </a>
                    </li>
                    @foreach ($date as $item)
                        <li class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{route('customer#movieBook',[$movie->id,'date'=>$item])}}">
                                <button class="js-select-date">
                                    <span>{{Carbon::parse($item)->format('l, j, m, Y')}}</span>
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
                        <h5 class="fw-medium">Select Cinema & Time</h5>
                    </div>
                    <form action="">
                        <div class="group">
                            <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                            <input class="input" type="search" placeholder="Search location" name="location" value="{{request('location')}}"/>
                        </div>
                    </form>
                </div>
                <div id="cinema-time-content" class="mt-2">
                    @if (count($moviePlan)!=0)
                        @foreach ($moviePlan as $key=>$item)
                            @if (count($item)!=0)
                                <div class="cinema-section">
                                    <div class="d-flex align-items-center justify-content-between ct-toggle-btn">
                                        <div class="d-flex align-items-center" id="cinema-name">
                                            <h6 class="me-4">{{$item[0]->cinema_name}} - {{$item[0]->cinema_location}}</h6>

                                        </div>
                                        <span class="">
                                            <i class="fa-solid fa-chevron-down cinema-down"></i>
                                            <i class="fa-solid fa-chevron-up cinema-up"></i>
                                        </span>
                                    </div>
                                    <div class="select-time">
                                        <ul class="row mx-auto ps-0 ">
                                            @foreach ($item as $planItem)
                                                <li class="col-lg-2 col-md-3 col-sm-4">
                                                    <button data-bs-toggle="modal" data-bs-target="#{{'select-seat-modal'.$planItem->id}}">
                                                        <div>{{$planItem->date}}</div>
                                                        <div>{{Carbon::parse($planItem->start_time)->format('h:i:A')}}</div>
                                                    </button>
                                                </li>
                                                <!-- select seat modal  -->
                                                <div class="modal fade p-0 select-seat-modal" id="{{'select-seat-modal'.$planItem->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="select-seat-modalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                                        <div class="modal-content border-0 position-relative bg-transparent">
                                                            <div class="d-flex justify-content-end mb-2">
                                                                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                                            </div>
                                                            <div class="row">
                                                                @isset($planItem->note)
                                                                    <div class="px-3">
                                                                        <div class="alert alert-secondary pb-0" style="font-size: 9pt;" role="alert">
                                                                            {!!$planItem->note!!}
                                                                        </div>
                                                                    </div>
                                                                @endisset
                                                                <div class="col-lg-5 text-center">
                                                                    <img src="{{Storage::url('poster/'.$movie->poster)}}" class="mx-auto rounded d-block w-75"  alt="">
                                                                    <h5>{{$movie->name}}</h5>
                                                                    <ul class="d-flex align-items-center justify-content-center">
                                                                        <li class="me-5">{{ TimeHelper::convertMinutesToHoursMinutes($movie->run_time) }}</li>
                                                                        <li>{{$movie->language}}</li>
                                                                    </ul>
                                                                </div>
                                                                <div class="col-lg-7 text-start pt-4">
                                                                    <div class="mb-4">
                                                                        <label>Cinema</label>
                                                                        <h6 class="mt-1">{{$planItem->cinema_name}}</h6>
                                                                    </div>
                                                                    <div class="mb-4">
                                                                        <label>Hall</label>
                                                                        <h6 class="mt-1">{{$planItem->hall_name}}</h6>
                                                                    </div>
                                                                    <div class="mb-4">
                                                                        <label>Date & Time</label>
                                                                        <h6 class="mt-1">{{Carbon::parse($planItem->date)->format('j/m/Y')}} , {{Carbon::parse($planItem->start_time)->format('h:i A')}}</h6>
                                                                    </div>
                                                                    @if ($planItem->online_book==0 && $planItem->online_buy==0)
                                                                        <div></div>
                                                                    @else
                                                                        <a href="{{route('customer#movieSelectSeat',$planItem->id)}}" class="text-decoration-none">
                                                                            <button type="button" class="btn" id="ticket-btn">Select Seats</button>
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                         <div class="px-3">
                            <h6 class="text-center text-secondary" style="font-size: 10pt;">No showtimes available.<h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </section>
    <!-- book ticket end  -->

@endsection

