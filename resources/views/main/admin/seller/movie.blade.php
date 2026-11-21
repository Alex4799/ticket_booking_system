@extends('main.admin.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <div class="d-flex justify-content-end p-3">
        <button class="add-btn" data-bs-toggle="modal" data-bs-target="#late-ticket">
            <i class="fa-solid fa-clock me-2" style="font-size: 10pt;"></i>
            Late Ticket
        </button>
    </div>

    <div class="mx-3 mb-5">
        @foreach ($movie as $item)
        <div class="movie-card">
            <div class="row">
                <div class="col-lg-3 col-sm-4">
                    <div class="poster-box">
                        <img src="{{Storage::url('poster/'.$item['poster'])}}" alt="">
                    </div>
                </div>
                <div class="col-lg-9 col-sm-8">
                    <div class="movie-content">
                        <div class="row">
                            <div class="col-lg-7">
                                <h4 class="movie-title">
                                    {{$item['name']}}
                                </h4>
                                <div class="genre-list">
                                    @if ($item['genre'])
                                        @foreach (json_decode($item['genre']) as $genreItem)
                                            <span>{{$genreItem}}</span>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="info-group">
                                    <span class="info-label">Release Date</span>
                                    <p>{{Carbon::parse($item['release_date'])->format('l, F j, Y')}}</p>
                                </div>
                                <div class="info-group">
                                    <span class="info-label">Duration</span>
                                    <p>{{ TimeHelper::convertMinutesToHoursMinutes($item['run_time']) }}</p>
                                </div>
                                <div>
                                    <a href="{{route('seller#moviePlan',$item['movie_id'])}}" class="book-btn">
                                       <i class="fa-solid fa-ticket me-2" style="font-size: 10pt;"></i>
                                       <span>Book Ticket</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="show-header">
                                    <h6>Today's Show Time</h6>
                                    <div class="today-date">
                                        <i class="fa-solid fa-calendar-day me-2"></i>
                                        {{Carbon::now()->format('d M Y (D)')}}
                                    </div>
                                </div>
                                <div class="show-times">
                                    @foreach ($item['show_time'] as $moviePlanItem)
                                        <a href="{{route('seller#selectSeat',$moviePlanItem['id'])}}" class="show-time-btn">
                                            <h6>{{$moviePlanItem['name']}}</h6>
                                            <span>
                                                {{Carbon::parse($moviePlanItem['start_time'])->format('h:i A')}}
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div>
        <div class="modal fade" id="late-ticket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="late-ticketLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-block w-100 text-center" id="late-ticketLabel">Late Ticket</h5>
                        <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('seller#lateTicket')}}" method="post" class="mt-1">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="mb-1">Movie Plan ID</label>
                                <input name="id" type="number" class="form-control @error('id') is-invalid @enderror" required>
                                @error('id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="d-flex align-items-center my-2">
                                <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
