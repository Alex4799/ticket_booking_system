@extends('main.admin.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <div class="d-flex justify-content-end p-2">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#late-ticket">Late Ticket</button>
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

    <div class="modal fade" id="late-ticket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="late-ticketLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title d-block w-100 text-center" id="late-ticketLabel">Late Ticket</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('seller#lateTicket')}}" method="post" class="mt-1">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="mb-1">Enter Movie Plan Id</label>
                            <input name="id" type="number" class="form-control @error('id') is-invalid @enderror" placeholder="Enter your movie plan id." required>
                            @error('id')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 my-2">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
