@extends('main.admin.layout.master')
@section('title')
    Movie Plan
@endsection
@section('style')
<style>
    #loading{
        position: fixed;
        inset: 0;
        background-color: #1419238c;
        z-index: 99999;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .plan-loading{
        background-color: #fff;
        padding: 30px 40px;
        border-radius: 18px;
        text-align: center;
        box-shadow: 0 20px 50px rgba(0,0,0,.18);
    }
</style>
@endsection
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp
    <div id="loading" class="d-none">
        <div class="plan-loading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <main class="content p-2 mb-5">
        <div class="container-fluid">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin#moviePlanCinemaList')}}">Cinemas</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin#moviePlanList',$moviePlan->hall_id)}}">List Plan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Plan</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-8 d-flex mb-4">
                    <div class="seller-order-detail">
                        <div class="title">
                            <div>
                                <div>
                                    <i class="fa-solid fa-calendar-check me-2"></i>
                                    <span class="fw-bold">Movie Plan Details</span>
                                </div>
                            </div>
                            <div>
                                <h6>
                                    @if ($moviePlan->active==0)
                                        <span class="status reject fw-bold">Not Active</span>
                                    @else
                                        <span class="status success fw-bold">Active</span>
                                    @endif
                                </h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-4">
                                <div class="movie-plan-view px-0">
                                    <div class="details-grid">
                                        <div class="detail-item">
                                            <span>
                                                <i class="fa-solid fa-clapperboard me-2"></i>Name
                                            </span>
                                            <strong>{{ $moviePlan->movie_name }}</strong>
                                        </div>
                                        <div class="detail-item">
                                            <span>
                                                <i class="fa-solid fa-clock me-2"></i>Run Time
                                            </span>
                                            <strong>{{ $moviePlan->run_time }} min</strong>
                                        </div>
                                        <div class="detail-item">
                                            <span>
                                                <i class="fa-solid fa-calendar me-2"></i>Show Date
                                            </span>
                                            <strong>{{ $moviePlan->date }}</strong>
                                        </div>
                                        <div class="detail-item">
                                            <span>
                                                <i class="fa-solid fa-play me-2"></i>Start Time
                                            </span>
                                            <strong>{{Carbon::parse($moviePlan->start_time)->format('h:i A')}}</strong>
                                        </div>
                                        <div class="detail-item">
                                            <span>
                                                <i class="fa-solid fa-broom me-2"></i>Clean Time
                                            </span>
                                            <strong>{{ $moviePlan->clean_time }} min</strong>
                                        </div>
                                        <div class="detail-item">
                                            <span>
                                                <i class="fa-solid fa-flag-checkered me-2"></i>End Time
                                            </span>
                                            <strong>{{Carbon::parse($moviePlan->end_time)->format('h:i A')}}</strong>
                                        </div>
                                        <div class="detail-item">
                                            <span>
                                                <i class="fa-solid fa-ticket me-2"></i>
                                                Online Booking
                                            </span>
                                            @if ($moviePlan->online_book == 0)
                                                <strong class="text-danger">
                                                    <i class="fa-solid fa-circle-xmark me-1"></i>
                                                    False
                                                </strong>
                                            @else
                                                <strong class="text-success">
                                                    <i class="fa-solid fa-circle-check me-1"></i>
                                                    True
                                                </strong>
                                            @endif
                                        </div>
                                        <div class="detail-item">
                                            <span>
                                                <i class="fa-solid fa-cart-shopping me-2"></i>
                                                Online Buy
                                            </span>
                                            @if ($moviePlan->online_buy == 0)
                                                <strong class="text-danger">
                                                    <i class="fa-solid fa-circle-xmark me-1"></i>
                                                    False
                                                </strong>
                                            @else
                                                <strong class="text-success">
                                                    <i class="fa-solid fa-circle-check me-1"></i>
                                                    True
                                                </strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 ps-md-3 d-flex">
                                <div>
                                    <label class="mb-2">Note</label>
                                    @if(isset($moviePlan->note))
                                        <div style="font-size: 10pt;">
                                            {!! $moviePlan->note !!}
                                        </div>
                                    @else
                                        <div style="font-size: 9pt;" class="mb-3 text-body-tertiary">No notes have been added yet.</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="border-top mt-2 pt-3 d-flex align-items-center justify-content-end flex-wrap gap-2">
                            <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#hall-rental"> <i class="fa-solid fa-door-open me-2"></i>Hall Rental</button>
                            <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#replace-price"><i class="fa-solid fa-couch me-2"></i>Replace Seat Price</button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit-plan" style="font-size: 11pt;"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-plan" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-flex mb-4">
                    <div class="seller-order-detail">
                        <div class="title">
                            <div>
                                <div>
                                    <i class="fa-solid fa-couch me-2"></i>
                                    <span class="fw-bold">Seat Plan Details</span>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3" id="seatPlan">
                            @foreach ($seatPlan as $index=>$item)
                                <div class="col-lg-6 col-md-3 col-sm-4">
                                    <div class="seat-type-card">
                                        <div class="seat-top">
                                            <div class="d-flex align-items-center">
                                                <span class="seat-color" style="background: {{$item['seat_color']}};"></span>
                                                <h6 class="ms-3 mb-0" style="font-size: 11pt;"> ( {{$item['seat_count']}} )</h6>
                                            </div>
                                            <div class="mt-1">
                                                <small>{{$item['name']}}</small>
                                                <h6 class="mb-0 mt-1">{{number_format($item['price'])}} Ks</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-bg mb-4">
                <div class="mt-1">
                    <div class="cinema-seating">
                        <div class="d-flex justify-content-end gap-2 mb-5">
                            @if ($moviePlan->seat_column > 10)
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="zoom-out">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="zoom-reset">
                                    <i class="fa-solid fa-expand"></i>
                                </button>
                            @endif
                        </div>
                        <div class="screen-wrapper">
                            <div class="screen">
                                <span>SCREEN</span>
                            </div>
                        </div>
                        <div id="seat-plan-container">
                             <div class="d-grid" style="grid-template-columns: repeat({{ $moviePlan->seat_column }}, auto);" id="seat-plan">
                                @if (count($movieSeat)!=0)
                                    @foreach ($movieSeat as $item)
                                        @if ($item->active==1)
                                            @if ($item->seat_type==1)
                                                <a href="{{route('admin#viewMovieSeat',$item->id)}}" title="{{$item->name}},@if ($item->active==0) Not Active @else Active @endif,{{$item->seat_plan_name}}" class="mx-auto text-decoration-none">
                                                    <div class="seat @if($item->status==1) selected @elseif($item->status==0) avaliable @else sold @endif" style="@if($item->status==0) color:{{$item->seat_color}} @endif">
                                                        <div class="seat-content">
                                                            <text class="svg-number">{{$item->name}}</text>
                                                            <svg class="lucide-armchair" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M19 9V6a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3"/>
                                                                <path d="M3 16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V11a2 2 0 0 0-4 0z"/>
                                                                <path d="M5 18v2"/>
                                                                <path d="M19 18v2"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </a>
                                            @else
                                                <a href="{{route('admin#viewMovieSeat',$item->id)}}" title="{{$item->name}},@if ($item->active==0) Not Active @else Active @endif,{{$item->seat_plan_name}}" class="mx-auto text-decoration-none">
                                                    <div class="seat @if($item->status==1) selected @elseif($item->status==0) avaliable @else sold @endif" style="@if($item->status==0) color:{{$item->seat_color}} @endif">
                                                        <div class="seat-content">
                                                            <text class="svg-number">{{$item->name}}</text>
                                                            <svg class="lucide-couple-armchair" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M22 12V10a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v8" />
                                                                <path d="M26 12V10a3 3 0 0 1 3-3h11a3 3 0 0 1 3 3v8" />
                                                                <path d="M2 30a3 3 0 0 0 3 3h38a3 3 0 0 0 3-3v-8a3 3 0 0 0-6 0v2a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2a3 3 0 0 0-6 0z" />
                                                                <path d="M6 34v6" />
                                                                <path d="M24 34v6" />
                                                                <path d="M42 34v6" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endif

                                        @else
                                            <div></div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="table-bg">
                        <div class="hall-view mt-2">
                            <h5 class="px-2"><i class="fa-solid fa-ticket me-2"></i>Ticket List</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>OID</th>
                                        <th>Seat</th>
                                        <th class="text-center">Seat Count</th>
                                        <th class="text-center">Total Price</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalSoldPrice=0;
                                        $totalSoldSeat=0;
                                        $totalPromoPrice=0;
                                    @endphp
                                    @foreach ($order as $item)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td><a href="{{route('admin#orderView',$item->id)}}" class="text-decoration-none ps-0">OID - {{$item->id}}</a></td>
                                        <td>
                                            @foreach (json_decode($item->seats) as $seatItem)
                                                {{ $seatItem->seat_name }} @if (!$loop->last), @endif
                                            @endforeach
                                        </td>
                                        <td class="text-center">{{$item->seat_count}}</td>
                                        <td class="text-end">{{number_format($item->total_price)}}</td>
                                        <td class="text-center">
                                            @if ($item->status==0)
                                                <span class="text-warning">Pending</span>
                                            @elseif($item->status==1)
                                                <span class="text-success">Success</span>
                                            @elseif($item->status==2)
                                                <span class="text-danger">Reject</span>
                                            @elseif($item->status==3)
                                                <span class="text-danger">Cancel</span>
                                            @elseif($item->status==4)
                                                <span class="text-warning">Booking</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        if ($item->status==1) {
                                            $totalSoldPrice+=$item->total_price;
                                            $totalSoldSeat+=$item->seat_count;
                                        };
                                        if ($item->promotion_price!=null) {
                                            $totalPromoPrice+=$item->promotion_price;
                                        }
                                    @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="table-bg pb-4">
                        <div class="hall-view mt-2">
                            <h5 class="mb-1 px-2"><i class="fa-solid fa-circle-info me-2"></i>Order Summary</h5>
                        </div>
                        <div class="px-1">
                            @foreach($sellingSeat as $index=>$item)
                                <div class="d-flex justify-content-between align-items-center gap-4 py-3 border-bottom">
                                    <div>
                                        <div style="font-size: 11pt;">{{ $item['seat_plan'] }}</div>
                                        <small class="text-muted">
                                            {{ $item['sold_seat'] }} <i class="fa-solid fa-xmark" style="font-size: 7pt;"></i> {{ number_format($item['seat_price']) }} Ks
                                        </small>
                                    </div>

                                    <strong>
                                        {{ number_format($item['sold_seat'] * $item['seat_price']) }}
                                    </strong>
                                </div>
                            @endforeach
                            <div class="d-flex justify-content-between pt-3">
                                <div style="font-size: 11pt;">Promotion</div>
                                <span class="text-danger">
                                    - {{ number_format($sellingDetail['totalPromoPrice']) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <div style="font-size: 11pt;">Sold Seats</div>
                                <span>{{ $sellingDetail['totalSoldSeat'] }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold" style="font-size: 13pt;">
                                <span>Total</span>
                                <span class="text-success">
                                    {{ number_format($sellingDetail['totalSoldPrice']) }} Ks
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="modal fade" id="hall-rental" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="hall-rentalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="hall-rentalLabel">Create Hall Rental</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('seller#orderHallRental')}}" method="post" enctype="multipart/form-data" class="parents">
                                    @csrf
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Choose Movie</label>
                                        <select name="movie" class="form-select">
                                            <option value="0">Hall Rental</option>
                                            @foreach ($movie as $movie_item)
                                                <option value="{{$movie_item->id}}" @if ($movie_item->id==$moviePlan->movie_id) selected @endif >{{$movie_item->name}} ({{$movie_item->run_time}} min)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Payment Method</label>
                                        <select name="payment_method" id="payment_method" class="form-select" required>
                                            <option value="">Choose Payment</option>
                                            @foreach ($payment as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">User Name</label>
                                            <input type="text" name="user_name" id="user_name" class="form-control" required>
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">User Email</label>
                                            <input type="email" name="user_email" id="user_email" class="form-control" required>
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">User Phone</label>
                                            <input type="text" name="user_phone" id="user_phone" class="form-control" required>
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Price</label>
                                            <input type="text" name="price" id="price" class="form-control" required>
                                        </div>
                                    </div>
                                    <div>
                                        <input type="hidden" name="movie_plan_id" value="{{$moviePlan->id}}" id="id">
                                        <input type="hidden" name="hall_id" value="{{$moviePlan->hall_id}}">
                                    </div>
                                    <div class="d-flex align-items-center my-2">
                                        <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="edit-plan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-planLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="edit-planLabel">Edit Plan</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#moviePlanUpdate')}}" method="post" enctype="multipart/form-data" class="parents">
                                    @csrf
                                    @method('PATCH')
                                    <div class="update-btn-group">
                                        <div class="row">
                                            <div class="col-sm-12 form-group mb-4">
                                                <label class="mb-1">Choose Movie</label>
                                                <select name="movie_id" id="movie" class="form-select movie">
                                                    @foreach ($movie as $movie_item)
                                                        <option value="{{$movie_item->id}}" data-run_time="{{$movie_item->run_time}}" @if ($movie_item->id==$moviePlan->movie_id) selected @endif >{{$movie_item->name}} ({{$movie_item->run_time}} min)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-12 form-group mb-4">
                                                <label class="mb-1">Show Date</label>
                                                <input type="date" name="date" value="{{$moviePlan->date}}" class="form-control">
                                            </div>
                                            <div class="col-sm-6 form-group mb-4">
                                                <label class="mb-1">Start Time</label>
                                                <input type="time" value="{{$moviePlan->start_time}}" name="start_time" class="form-control start_time" id="start_time">
                                            </div>
                                            <div class="col-sm-6 form-group mb-4">
                                                <label class="mb-1">Clean Time (Min)</label>
                                                <input type="number" value="{{$moviePlan->clean_time}}" name="clean_time" class="form-control clean_time">
                                            </div>
                                            <div class="col-sm-12 form-group mb-4" id="end_time">
                                                <label class="mb-1">End Time</label>
                                                <input type="time" readonly name="end_time" value='{{$moviePlan->end_time}}' class="form-control">
                                            </div>
                                            <div class="col-sm-6 form-group mb-4">
                                                <label class="mb-1">Online Booking</label>
                                                <select name="online_book" id="" class="form-select">
                                                    <option value="0" @if ($moviePlan->online_book==0) selected @endif>False</option>
                                                    <option value="1" @if ($moviePlan->online_book==1) selected @endif>True</option>
                                                </select>
                                                @error('online_book')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6 form-group mb-4">
                                                <label class="mb-1">Online Buy</label>
                                                <select name="online_buy" id="" class="form-select">
                                                    <option value="0" @if ($moviePlan->online_buy==0) selected @endif>False</option>
                                                    <option value="1" @if ($moviePlan->online_buy==1) selected @endif>True</option>
                                                </select>
                                                @error('online_buy')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                            <div class="col-sm-12 form-group mb-4">
                                                <label class="mb-1">Status</label>
                                                <select name="active" class="form-select">
                                                    <option value="0" @if ($moviePlan->active==0) selected @endif>Not Active</option>
                                                    <option value="1" @if ($moviePlan->active==1) selected @endif>Active</option>
                                                </select>
                                                @error('active')
                                                    <small class=" text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                            <div class="col-sm-12 form-group mb-4">
                                                <label class="mb-1">Note</label>
                                                <textarea name="note" id="editor" cols="30" rows="10" class=" @error('note') is-invalid @enderror">{!!$moviePlan->note!!}</textarea>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="hidden" name="id" value="{{$moviePlan->id}}" id="id">
                                            <input type="hidden" name="hall_id" value="{{$moviePlan->hall_id}}">
                                        </div>
                                        <div class="d-flex align-items-center my-2">
                                            <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="replace-price" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="replace-priceLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="replace-priceLabel">Replace Seat Price</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#replacePriceMovieSeat')}}" method="post" class="parents">
                                    @csrf
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Choose Seat Plan</label>
                                        <select name="seat_plan" class="form-select">
                                            @foreach ($seatPlan as $item)
                                                <option value="{{$item->id}}" >{{$item->name}} ({{$item->price}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="mb-1">New Price</label>
                                        <input type="number" name="price"  class="form-control" required>
                                    </div>
                                    <input type="hidden" name="id" value="{{$moviePlan->id}}">

                                    <div class="d-flex align-items-center my-2">
                                        <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Replace</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal fade" id="delete-plan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-planLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="delete-planLabel">Delete Movie Plan</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#deleteMoviePlan')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <div class="delete-confirmation">
                                        <div class="delete-warning">
                                            <div class="delete-icon">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                            </div>
                                            <div class="delete-message">
                                                <h6>Are you sure to delete this?</h6>
                                                <small>Verify you are admin. Please enter the password.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group my-3">
                                        <label class="mb-1">Password</label>
                                        <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="id" id="plan_id" value="{{$moviePlan->id}}">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </main>
@endsection


@section('script')
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#movie').change(function(){
            calculateRunTime();
        });

        $('#start_time').change(function(){
            calculateRunTime();
        })

        $('#clean_time').change(function(){
            calculateRunTime();
        })

        function calculateRunTime(){
            $('#loading').removeClass('d-none');
            $runTime=parseInt($('#movie option:selected').data('run_time'))||0;
            $startTime=$('#start_time').val()|| "00:00";
            $cleanTime=parseInt($('#clean_time').val())||0;
            $startTimeMin=$startTime.split(':');
            $startHour=parseInt($startTimeMin[0],10);
            $startMin=parseInt($startTimeMin[1],10);
            let totalEndMinutes=($startHour*60)+$startMin+$runTime+$cleanTime;
            totalEndMinutes = totalEndMinutes % 1440;
            let endHours = Math.floor(totalEndMinutes / 60);
            let endMinutes = totalEndMinutes % 60;
            let formattedHours = String(endHours).padStart(2, '0');
            let formattedMinutes = String(endMinutes).padStart(2, '0');
            $endTime=`${formattedHours}:${formattedMinutes}`;
            $('#end_time').html(`
                <label class="mb-1">End Time</label>
                <input type="time" readonly name="end_time" value='${$endTime}' class="form-control">
            `);
            $('#loading').addClass('d-none');
        }

    });
</script>

<script type="module">
    import {
        ClassicEditor,
        Essentials,
        Bold,
        Italic,
        Font,
        Paragraph
    } from 'ckeditor5';

    const editorElement = document.querySelector('#editor');
    const form = document.querySelector('.update-btn-group');
    const button = form.querySelector('.action-btn');

    ClassicEditor
        .create(editorElement, {
            plugins: [
                Essentials,
                Bold,
                Italic,
                Font,
                Paragraph
            ],
            toolbar: {
                items: [
                    'undo',
                    'redo',
                    '|',
                    'bold',
                    'italic',
                    '|',
                    'fontSize',
                    'fontFamily',
                    'fontColor',
                    'fontBackgroundColor'
                ]
            }
        })
        .then(editor => {
            const originalEditorValue = editor.getData();
            const originalValues = {};
            form.querySelectorAll('input, select, textarea').forEach(input => {
                if (input.id !== 'editor') {
                    originalValues[input.name] = input.value;
                }
            });
            function checkChanges() {
                let hasChanged = false;
                form.querySelectorAll('input, select, textarea').forEach(input => {
                    if (input.id === 'editor') {
                        return;
                    }
                    if (input.value !== originalValues[input.name]) {
                        hasChanged = true;
                    }
                });
                if (editor.getData() !== originalEditorValue) {
                    hasChanged = true;
                }
                button.disabled = !hasChanged;
            }
            form.querySelectorAll('input, select, textarea').forEach(input => {
                if (input.id !== 'editor') {
                    input.addEventListener('input', checkChanges);
                    input.addEventListener('change', checkChanges);
                }
            });
            editor.model.document.on('change:data', checkChanges);
            checkChanges();
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection
