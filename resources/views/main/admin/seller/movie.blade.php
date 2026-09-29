@extends('main.admin.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <div class="d-flex justify-content-end p-2">
        <a href="#" class="btn btn-primary">Late Ticket</a>
    </div>

    <div>
        @foreach ($movie as $item)
            <div class="p-4 shadow rounded mt-3 mb-5 mx-1">
                <div class="row" id="movie-seller">
                    <div class="col-lg-3 col-md-4 col-sm-5">
                        <img src="{{Storage::url('poster/'.$item['poster'])}}" class="d-block img-thumbnail w-100" alt="">
                    </div>
                    <div class="col-lg-9 col-md-8 col-sm-7 ps-4">
                        <div class="row">
                            <div class="col-lg-7">
                                <div>
                                    <label for="">Name</label>
                                    <h4>{{$item['name']}}</h4>
                                </div>
                                <div class="mt-3">
                                    <label for="">Category</label>
                                    <p class="">
                                        @if ($item['genre'])
                                            @foreach (json_decode($item['genre']) as $genreItem)
                                                {{$genreItem}} |
                                            @endforeach
                                        @endif
                                    </p>
                                </div>
                                <div class="mt-3">
                                    <label for="">Release Date</label>
                                    <p class="">{{Carbon::parse($item['release_date'])->format('l, F j, Y')}}</p>
                                </div>
                                <div class="mt-3">
                                    <label for="">Duration</label>
                                    <p class="">{{ TimeHelper::convertMinutesToHoursMinutes($item['run_time']) }}</p>
                                </div>
                                <div class="mb-5">
                                    <a href="{{route('seller#moviePlan',$item['movie_id'])}}">
                                        <button class="btn btn-success">Book Ticket</button>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <label for="" class="mb-2">Today Date & Show Time</label>
                                <div style="font-size: 11pt">
                                    <i class="fa-solid fa-calendar"></i>
                                    <span class="ms-2">{{Carbon::now()->format('d/m/Y (D)')}}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2 row-gap-2 flex-wrap today-select mt-2">
                                    @foreach ($item['show_time'] as $moviePlanItem)
                                        <div>
                                            <a href="{{route('seller#selectSeat',$moviePlanItem['id'])}}">
                                                <button>
                                                    <div>{{$moviePlanItem['name']}}</div>
                                                    <div>{{Carbon::parse($moviePlanItem['start_time'])->format('h:i:A')}}</div>
                                                </button>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
    <!-- movie list end  -->

@endsection
