@extends('main.admin.layout.master')
@section('title')
    Movie Plan
@endsection
@section('style')
    <style>
            #loading {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
                z-index: 100000; /* High z-index to cover other elements */
                display: flex;
                justify-content: center;
                align-items: center;
            }
    </style>
@endsection
@section('content')
            <!-- general details start  -->
            <main class="content px-3 py-4 mb-5">
                <div class="d-flex justify-content-center">
                    <div id="loading" class="d-none">
                        <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div>
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin#moviePlanCinemaList')}}">Cinema</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin#moviePlanList',$moviePlan->hall_id)}}">List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">View Plan</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <h4 class="text-center py-2">View Movie Plan</h4>
                    </div>
                    <div class="row gap-1">
                        <div class="col-md-5 offset-md-1 d-flex justify-content-center card p-3">
                            <div class=" ">
                                <h5 class="py-2">Movie Name - {{$moviePlan->movie_name}}</h5>
                                <h5 class="py-2">Movie Run Time - {{$moviePlan->run_time}} min</h5>
                                <h5 class="py-2">Show Date - {{$moviePlan->date}}</h5>
                                <h5 class="py-2">
                                    <span>Online Buy -</span>
                                    @if ($moviePlan->online_buy==0)
                                        <span class="text-danger">False</span>
                                    @else
                                        <span class="text-success">True</span>
                                    @endif
                                </h5>
                                <h5 class="py-2">
                                    <span>Online Book -</span>
                                    @if ($moviePlan->online_book==0)
                                        <span class="text-danger">False</span>
                                    @else
                                        <span class="text-success">True</span>
                                    @endif
                                </h5>
                                <h5 class="">Acitve - @if ($moviePlan->active==0)
                                    <span class="text-danger">Not Active</span>
                                @else
                                    <span class="text-success">Active</span>
                                @endif</h5>
                            </div>
                        </div>
                        <div class="col-md-5 d-flex justify-content-center card p-2">
                            <h5 class="py-2">Start Time - {{$moviePlan->start_time}}</h5>
                            <h5 class="py-2">Clean Time - {{$moviePlan->clean_time}} min</h5>
                            <h5 class="py-2">End Time - {{$moviePlan->end_time}}</h5>
                            @if (isset($moviePlan->note))
                                <p>Note {!!$moviePlan->note!!}</p>
                            @endif
                            <div class=" d-flex justify-content-end gap-1 flex-wrap">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#hall-rental">Hall Rental</button>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit-plan">Edit Plan</button>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#replace-price">Replace Seat Price</button>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-plan">Delete Plan</button>
                            </div>
                        </div>
                    </div>
                    <div class="py-2 mt-3">
                        <div class="row container-fluid">
                            @foreach ($seatPlan as $index=>$item)
                            <div class="col">
                                <div class="rounded shadow p-2" style="border: 5px solid {{$item['seat_color']}}">
                                    <p>{{$item['name']}} - {{$item['price']}} MMK ({{$item['seat_count']}})</p>
                                </div>
                            </div>
                            @endforeach
                            <div>

                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="">
                            <h5 class="py-3">Seat List</h5>
                        </div>
                        <div class="cinema-seating">
                            <div class="screen-container">
                                <div class="screen">Screen</div>
                            </div>

                            <div id="seat-plan-container">
                                <div id="seat-plan">
                                    <!-- Row A -->
                                    <div class=" d-grid" style="grid-template-columns: repeat({{ $moviePlan->seat_column }}, auto);">
                                        @if (count($movieSeat)!=0)
                                            @foreach ($movieSeat as $item)
                                                @if ($item->active==1)
                                                    @if ($item->seat_type==1)
                                                         <a href="{{route('admin#viewMovieSeat',$item->id)}}" title="{{$item->name}},@if ($item->active==0) Not Active @else Active @endif,{{$item->seat_plan_name}}" class="mx-auto">
                                                            <div class="seat @if($item->status==1) selected @elseif ($item->status==0) 'avaliable' @else sold @endif" style="@if($item->status==0) color:{{$item->seat_color}} @endif">
                                                                <div class="seat-content">
                                                                    <text class="svg-number">{{$item->name}}</text>
                                                                    <svg class="lucide-armchair" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.6" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path d="M19 9V6a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3"/>
                                                                        <path d="M3 16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V11a2 2 0 0 0-4 0z"/>
                                                                        <path d="M5 18v2"/>
                                                                        <path d="M19 18v2"/>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @else
                                                         <a href="{{route('admin#viewMovieSeat',$item->id)}}" title="{{$item->name}},@if ($item->active==0) Not Active @else Active @endif,{{$item->seat_plan_name}}" class="mx-auto">
                                                            <div class="seat @if($item->status==1) selected @elseif ($item->status==0) 'avaliable' @else sold @endif" style="@if($item->status==0) color:{{$item->seat_color}} @endif">
                                                                <div class="seat-content">
                                                                    <text class="svg-number">{{$item->name}}</text>
                                                                    <svg class="lucide-couple-armchair" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
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

                                    <!-- add other row  -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="table-bg" class="mt-5">
                        <div>
                            <h5>Order List</h5>
                        </div>
                        <div class="">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>OID</th>
                                            <th>Total Price</th>
                                            <th>Seat</th>
                                            <th>Seat Count</th>
                                            <th>Status</th>
                                            <th></th>
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
                                            <td>OID - {{$item->id}}</td>
                                            <td>{{$item->total_price}} MMK</td>
                                            <td>
                                                @foreach (json_decode($item->seats) as $seatItem)
                                                {{$seatItem->seat_name}},
                                                @endforeach
                                            </td>
                                            <td>{{$item->seat_count}}</td>
                                            <td>
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
                                            <td>
                                                <a href="{{route('admin#orderView',$item->id)}}" class="btn btn-outline-primary">See More</a>
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
                    <div id="table-bg" class="mt-5">
                        <div>
                            <h5>Order Summary</h5>
                        </div>
                        <div class="">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Seat Plan</th>
                                            <th>Seat Price</th>
                                            <th>Sold Seat Count</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sellingSeat as $index=>$item)
                                        <tr>
                                            <td>
                                                {{$item['seat_plan']}}
                                            </td>
                                            <td class="text-end">
                                                {{$item['seat_price']}}
                                            </td>
                                            <td class="text-end">
                                                {{$item['sold_seat']}}
                                            </td>
                                            <td class="text-end">
                                                {{$item['sold_seat']*$item['seat_price']}} MMK
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3">Promotion Price</td>
                                            <td class="text-end">- {{$sellingDetail['totalPromoPrice']}} MMK</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Total Income
                                            </td>
                                            <td colspan="2" class="text-end">
                                                {{$sellingDetail['totalSoldSeat']}}
                                            </td>
                                            <td class="text-end">
                                                {{$sellingDetail['totalSoldPrice']}} MMK
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- general details end  -->

            {{-- model start --}}
            <div>
                <div class="modal fade" id="hall-rental" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="hall-rentalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="hall-rentalLabel">Create Hall Rental</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('seller#orderHallRental')}}" method="post" enctype="multipart/form-data" class="parents">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Choose Movie</label>
                                        <select name="movie" class="form-control">
                                            <option value="">Choose Movie</option>
                                            <option value="0">Hall Rental</option>
                                            @foreach ($movie as $movie_item)
                                                <option value="{{$movie_item->id}}" @if ($movie_item->id==$moviePlan->movie_id) selected @endif >{{$movie_item->name}} ({{$movie_item->run_time}} min)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">User Name</label>
                                        <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter user name">
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">User Email</label>
                                        <input type="email" name="user_email" id="user_email" class="form-control" placeholder="Enter user email">
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">User Phone</label>
                                        <input type="text" name="user_phone" id="user_phone" class="form-control" placeholder="Enter user phone">
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Price</label>
                                        <input type="text" name="price" id="price" class="form-control" placeholder="Enter price">
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Payment Method</label>
                                        <select name="payment_method" id="payment_method" class="form-control">
                                            <option value="">Choose Payment Method</option>
                                            @foreach ($payment as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <input type="hidden" name="movie_plan_id" value="{{$moviePlan->id}}" id="id">
                                        <input type="hidden" name="hall_id" value="{{$moviePlan->hall_id}}">
                                    </div>
                                    <div class="d-flex justify-content-end gap-1">
                                        <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-upload me-2"></i>Create</button>
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
                                <h4 class="modal-title d-block w-100 text-center" id="edit-planLabel">Edit Movie Plan</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#moviePlanUpdate')}}" method="post" enctype="multipart/form-data" class="parents">
                                    @csrf
                                    @method('PATCH')
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Choose Movie</label>
                                        <select name="movie_id" id="movie" class="form-control movie">
                                            <option value="">Choose Movie</option>
                                            @foreach ($movie as $movie_item)
                                                <option value="{{$movie_item->id}}" data-run_time="{{$movie_item->run_time}}" @if ($movie_item->id==$moviePlan->movie_id) selected @endif >{{$movie_item->name}} ({{$movie_item->run_time}} min)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Show Date</label>
                                        <input type="date" name="date" value="{{$moviePlan->date}}" class="form-control">
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Start Time</label>
                                        <input type="time" value="{{$moviePlan->start_time}}" name="start_time" class="form-control start_time" id="start_time">
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Clean Time (MIN)</label>
                                        <input type="number" value="{{$moviePlan->clean_time}}" name="clean_time" id="clean_time" class="form-control clean_time">
                                    </div>
                                    <div class="mb-4" id="end_time">
                                        <label for="" class="mb-1">End Time</label>
                                        <input type="time" readonly name="end_time" value='{{$moviePlan->end_time}}' class="form-control">
                                    </div>
                                    <div class="row mb-1">
                                        <div class="mb-4 col-md-6">
                                            <label for="name" class="form-label mb-1">Online Booking</label>
                                            <select name="online_book" id="" class="form-control">
                                                <option value="">Choose Online Booking</option>
                                                <option value="0" @if ($moviePlan->online_book==0) selected @endif>False</option>
                                                <option value="1" @if ($moviePlan->online_book==1) selected @endif>True</option>
                                            </select>
                                            @error('online_book')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-4 col-md-6">
                                            <label for="name" class="form-label mb-1">Online Buy</label>
                                            <select name="online_buy" id="" class="form-control">
                                                <option value="">Choose Online Buy</option>
                                                <option value="0" @if ($moviePlan->online_buy==0) selected @endif>False</option>
                                                <option value="1" @if ($moviePlan->online_buy==1) selected @endif>True</option>
                                            </select>
                                            @error('online_buy')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="name" class="form-label mb-1 @error('active') is-invalid @enderror">Active</label>
                                        <select name="active" class="form-control ">
                                            <option value="">Choose Active Status</option>
                                            <option value="0" @if ($moviePlan->active==0) selected @endif>Not Active</option>
                                            <option value="1" @if ($moviePlan->active==1) selected @endif>Active</option>
                                        </select>
                                        @error('active')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Note</label>
                                        <textarea name="note" id="editor" cols="30" rows="10" class=" @error('note') is-invalid @enderror">{!!$moviePlan->note!!}</textarea>
                                    </div>
                                    <div>
                                        <input type="hidden" name="id" value="{{$moviePlan->id}}" id="id">
                                        <input type="hidden" name="hall_id" value="{{$moviePlan->hall_id}}">
                                    </div>
                                    <div class="d-flex justify-content-end gap-1">
                                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload me-2"></i>Update</button>
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
                                <h4 class="modal-title d-block w-100 text-center" id="replace-priceLabel">Replace Seat Plan</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#replacePriceMovieSeat')}}" method="post" class="mt-1">
                                    @csrf

                                    <div class="mb-4">
                                        <label for="" class="mb-1">Choose Seat Plan</label>
                                        <select name="seat_plan" class="form-control">
                                            @foreach ($seatPlan as $item)
                                                <option value="{{$item->id}}" >{{$item->name}} ({{$item->price}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">New Price</label>
                                        <input type="number" name="price"  class="form-control" required>
                                    </div>
                                    <input type="hidden" name="id" value="{{$moviePlan->id}}">

                                    <button type="submit" class="btn btn-primary w-100 my-2">Replace</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal fade" id="delete-plan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-planLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="delete-planLabel">Delete Movie Plan</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#deleteMoviePlan')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <div class="py-1">
                                        <p class="text-danger">Are you sure to delete this.</p>
                                    </div>
                                    <div class="py-1">
                                        <h6>Verify you are admin. Please enter the password.</h6>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Password</label>
                                        <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password." required>
                                        @error('password')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="id" id="plan_id" value="{{$moviePlan->id}}">

                                    <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            {{-- model end --}}
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
                <label for="" class="py-2">End Time</label>
                <input type="time" readonly name="end_time" value='${$endTime}' class="form-control">
            `);
            $('#loading').addClass('d-none');
        }

    })
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

    ClassicEditor
        .create( document.querySelector( '#editor' ), {
            plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
            toolbar: {
                items: [
                    'undo', 'redo', '|', 'bold', 'italic', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                ]
            }
        } )
        .then( /* ... */ )
        .catch( /* ... */ );
</script>
@endsection
