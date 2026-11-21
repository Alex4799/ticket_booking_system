@extends('main.customer.layout.master')
@section('content')
@php
    use Carbon\Carbon;
@endphp
    <!-- time table view start  -->
    <section id="timetable-view">
        <div id="selected-movie-label" class="d-flex align-items-center justify-content-between flex-wrap gap-1">
            <div class="d-flex align-items-center flex-wrap gap-1">
                <div>
                    <a href="{{route('customer#timeTableCinema')}}">
                        <button class="btn"><i class="fa-solid fa-chevron-left"></i></button>
                    </a>
                </div>
                <div class="ms-2 mb-2">
                    <h5>{{$cinema->name}}</h5>
                </div>
            </div>
            <div class="mb-2">
                <div class="dropdown py-2 dropdown-menu-end">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa-solid fa-calendar me-2"></i>{{request('date')??Carbon::now()->toDateString()}}
                    </button>
                    <ul class="dropdown-menu">
                        @foreach ($date as $index=>$item)
                            <li><a class="dropdown-item" href="{{route('customer#timeTable',[$cinema->id,'date'=>$item])}}">{{$item}}</a></li>
                        @endforeach
                      </ul>
                </div>
            </div>
        </div>


        <div id="cinema-schedule">
            <div class="cinema-block">
                <div class="row">
                    @if (count($moviePlan)!=0)
                        @foreach ($moviePlan as $item)
                            <div class="col-sm-6 mb-4">
                                <div class="showtime-container">
                                    <h6>
                                        <span class="me-1">{{$loop->index+1}}.</span>
                                        {{$item->movie_name}}
                                    </h6>
                                    <div class="showtime">
                                        <div class="w-75">
                                            <a href="">
                                                <img src="{{Storage::url('poster/'.$item->poster)}}" alt="">
                                            </a>
                                        </div>
                                        <div class="w-100">
                                            <div>
                                                <label for="">Hall</label>
                                                <p>{{$item->hall_name}}</p>
                                            </div>
                                            <div>
                                                <label for="">Date</label>
                                                <p>{{$item->date}}</p>
                                            </div>
                                            <div>
                                                <label for="">Start Time & End Time</label>
                                                <p class="mt-1">
                                                    <a href="{{route('customer#movieSelectSeat',$item->id)}}">
                                                        <button class="btn" id="ticket-btn">
                                                            {{Carbon::parse($item->start_time)->format('h:i a')}} - {{Carbon::parse($item->end_time)->format('h:i a')}}
                                                        </button>
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="py-5 px-3 mt-5">
                            <h6 class="text-center text-secondary" style="font-size: 10pt;">Showtimes not available yet.</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- time table view end  -->

@endsection
@section('script')
@endsection
