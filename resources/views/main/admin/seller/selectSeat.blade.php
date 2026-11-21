@extends('main.admin.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp
    <div class="content py-1 px-3 mb-3">
        <div class="container-fluid">
            <div class="">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('seller#movie')}}">Seller</a></li>
                        <li class="breadcrumb-item"><a href="{{route('seller#moviePlan',$moviePlan->movie_id)}}">Booking</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Seat</li>
                    </ol>
                </nav>
            </div>
            <div class="select-seat">
                <div class="movie-info text-center">
                    <h5>{{$moviePlan->movie_name}}</h5>
                    <div class="movie-meta">
                        <div class="meta-item">
                            <i class="fa-solid fa-calendar-day"></i>
                            <span>{{Carbon::parse($moviePlan->date)->format('l, F j, Y')}}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fa-solid fa-clock"></i>
                            <span>{{Carbon::parse($moviePlan->start_time)->format('h:i A')}}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fa-solid fa-building"></i>
                            <span>{{$moviePlan->cinema_name}}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fa-solid fa-door-open"></i>
                            <span>{{$moviePlan->hall_name}}</span>
                        </div>
                    </div>
                </div>
                <div class="row g-3" id="seatPlan">
                    @foreach ($seatPlan as $item)
                        <div class="col-lg-2 col-md-3 col-sm-4 mb-2">
                            <div class="seat-type-card">
                                <div class="seat-top">
                                    <span class="seat-color" style="background: {{$item['seat_color']}};"></span>
                                    <div class="mt-1">
                                        <small>{{$item['seat_plan_name']}}</small>
                                        <h5>{{ number_format($item['price']) }} Ks</h5>
                                    </div>
                                </div>
                                <div class="seat-bottom">
                                    <div class="seat-stat">
                                        <span>Available</span>
                                        <strong>{{$item['avaliable']}}</strong>
                                    </div>
                                    <div class="seat-divider"></div>
                                    <div class="seat-stat">
                                        <span>Sold</span>
                                        <strong>{{$item['sold']}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div id="theater-seat-content">
                    <div id="loadingOverLay" class="d-none">
                        <div class="loading-card">
                            <div class="dot-loader">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <p>Please wait...</p>
                        </div>
                    </div>
                    <div class="cinema-seating">
                        @if ($moviePlan->seat_column > 10)
                            <div class="d-flex justify-content-end gap-2 me-4 mb-5">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="zoom-out">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="zoom-reset">
                                    <i class="fa-solid fa-expand"></i>
                                </button>
                            </div>
                        @endif
                        <div class="screen-wrapper">
                            <div class="screen">
                                <span>SCREEN</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 offset-lg-6">
                                <div id="seat-alert"></div>
                            </div>
                        </div>
                        <div id="seat-plan-container">
                            <div class="d-grid" style="grid-template-columns: repeat({{$moviePlan->seat_column}}, auto);" id="seat-plan">
                                @foreach ($movieSeat as $item)
                                <div class="mx-auto">
                                    <div class="parents seat @if($item->status==1) selected @elseif($item->status==0) avaliable @else sold @endif" style="@if($item->status==0) color:{{$item->seat_color}} @endif" id="seat{{$item->id}}">
                                        @if ($item->active!=0)
                                            @if ($item->seat_type==1)
                                                <div class="seat-content seat_class">
                                                    <text text-anchor="middle" class="svg-number">{{$item->seat_name}}</text>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-armchair">
                                                        <path d="M19 9V6a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3"></path>
                                                        <path d="M3 16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V11a2 2 0 0 0-4 0z"></path>
                                                        <path d="M5 18v2"></path>
                                                        <path d="M19 18v2"></path>
                                                    </svg>
                                                </div>
                                                <input type="hidden" id="seat_id" value="{{$item->id}}">
                                                <input type="hidden" id="seat_name" value="{{$item->seat_name}}">
                                                <input type="hidden" id="seat_price" value="{{$item->price}}">
                                                <input type="hidden" id="seat_plan" value="{{$item->seat_plan_name}}">
                                                <input type="hidden" id="seat_status_{{$item->id}}" value="{{$item->status}}">
                                            @else
                                                <div class="seat-content seat_class">
                                                    <text text-anchor="middle" class="svg-number">{{$item->seat_name}}</text>
                                                    <svg class="lucide lucide-couple-armchair" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M22 12V10a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v8" />
                                                        <path d="M26 12V10a3 3 0 0 1 3-3h11a3 3 0 0 1 3 3v8" />
                                                        <path d="M2 30a3 3 0 0 0 3 3h38a3 3 0 0 0 3-3v-8a3 3 0 0 0-6 0v2a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2a3 3 0 0 0-6 0z" />
                                                        <path d="M6 34v6" />
                                                        <path d="M24 34v6" />
                                                        <path d="M42 34v6" />
                                                    </svg>
                                                </div>
                                                <input type="hidden" id="seat_id" value="{{$item->id}}">
                                                <input type="hidden" id="seat_name" value="{{$item->seat_name}}">
                                                <input type="hidden" id="seat_price" value="{{$item->price}}">
                                                <input type="hidden" id="seat_plan" value="{{$item->seat_plan_name}}">
                                                <input type="hidden" id="seat_status_{{$item->id}}" value="{{$item->status}}">
                                            @endif

                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="check-out">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-8">
                        <h5>Selected Seats</h5>
                        <div id="seat-number">
                            <p>No seats selected.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-3 col-sm-4">
                        <h5>Total Price</h5>
                        <p class="mt-3"><span id="total_price">0</span> Ks</p>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <form action="{{route('seller#checkOut')}}" method="get">
                            @csrf
                            <input type="hidden" name="seats" id="inputSeat" value="[]">
                            <input type="hidden" name="moviePlanId" value="{{$moviePlan->id}}">
                            <button type="submit" class="btn">Check Out <i class="fa-solid fa-arrow-right ms-1"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('script')
    <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = false;

        var pusher = new Pusher('2b433f999a354310803a', {
          cluster: 'ap1'
        });

        var channel = pusher.subscribe('seat-status-channel');
        channel.bind('seat-status-event', function(data) {

            if (data.movie_plan_id=={{$moviePlan->id}}) {
                let statusClass = 'avaliable';
                if (data.status == 1) statusClass = 'selected';
                if (data.status == 2) statusClass = 'sold';

                data.seat_id.forEach(element => {
                    $(`#seat_status_${element}`).val(data.status);
                    $(`#seat${element}`).removeClass('avaliable');
                    $(`#seat${element}`).removeClass('selected');
                    $(`#seat${element}`).removeClass('sold');
                    $(`#seat${element}`).addClass(statusClass);
                });
            }
        });
    </script>
    <script>
        $(document).ready(function(){
            const SeatStorage = {
                setSeats(seatsArray, ttlInMinutes) {
                    const data = {
                        seats: seatsArray,
                        expiry: Date.now() + (ttlInMinutes * 60 * 1000)
                    };
                    localStorage.setItem('selected_seats', JSON.stringify(data));
                },

                getSeats() {
                    const itemStr = localStorage.getItem('selected_seats');
                    if (!itemStr) return [];
                    try {
                        const data = JSON.parse(itemStr);

                        if (Date.now() > data.expiry) {
                            localStorage.removeItem('selected_seats');
                            return [];
                        }

                        return data.seats;
                    } catch (e) {
                        localStorage.removeItem('selected_seats');
                        return [];
                    }
                },

                clearSeats() {
                    localStorage.removeItem('selected_seats');
                }
            };

            summary();

            $('.seat_class').click(function(){
                $('#loadingOverLay').removeClass('d-none');
                let parent=$(this).parents('.parents');
                let seat_id=parent.find('#seat_id').val();
                let seat_name=parent.find('#seat_name').val();
                let seat_price=parent.find('#seat_price').val();
                let seat_status=$(`#seat_status_${seat_id}`).val();
                let seat_plan=parent.find('#seat_plan').val();
                let selected_seat=SeatStorage.getSeats();
                $this=$(this);
                let temp={
                    'seat_id': seat_id,
                    'seat_name':seat_name,
                    'seat_price':seat_price,
                    'seat_plan':seat_plan,
                };
                $status = !selected_seat.some(element => element.seat_id == temp.seat_id);

                if ($status && seat_status==0) {
                    if ($this.data('processing')) {
                        return;
                    }
                    $this.data('processing', true);
                    $.ajax({
                        type:'get',
                        url:`{{route('changeStatus')}}`,
                        data:{id:seat_id,status:'select_seat'},
                        dataType:'json',
                        success:function(data){
                            if(data.status=='success'){
                                selected_seat.push(temp);
                                SeatStorage.setSeats(selected_seat,15);
                                summary();
                            }else{
                                $('#seat-alert').html(`
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="font-size: 10pt;">
                                        Unfortunately this seat is selected by someone. Please choose another seat.
                                        <button type="button" class="btn-close border-none shadow-none btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                `);
                            }
                            $('#loadingOverLay').addClass('d-none');
                        },
                        complete: function() {
                            $this.data('processing', false);
                        },
                    })
                }else{
                    $('#loadingOverLay').addClass('d-none');
                }

            })

            function summary(){
                let selected_seat=SeatStorage.getSeats();
                $list='';
                $totalPrice=0;
                if (selected_seat.length!=0) {
                    selected_seat.forEach(function(element,index) {
                        $list+=`<div class="parents">
                                    <div class="selected_seat">
                                        <h6>${element.seat_name}</h6>
                                        <input type="hidden" id="seat_id" value="${element.seat_id}">
                                        <input type="hidden" id="index" value="${index}">
                                    </div>
                                </div>`
                        $totalPrice+=parseInt(element.seat_price);
                    });
                } else {
                    $list=`<p>No seats selected.</p>`;
                    $totalPrice=0
                }
                $stringData=JSON.stringify(selected_seat);
                $('#inputSeat').val($stringData);
                $('#seat-number').html($list);
                $('#total_price').html($totalPrice.toLocaleString());
            }

            $(document).on("click",".selected_seat",function(){
                $('#loadingOverLay').removeClass('d-none');
                let parents=$(this).parents('.parents')
                let seat_id=parents.find('#seat_id').val();
                let index=parents.find('#index').val();
                let selected_seat=SeatStorage.getSeats();
                $this=$(this);
                if ($this.data('processing')) {
                    return;
                }
                $this.data('processing', true);
                $.ajax({
                        type:'get',
                        url:`{{route('changeStatus')}}`,
                        data:{id:seat_id,status:'unselect_seat'},
                        dataType:'json',
                        success:function(data){
                            if(data.status=='success'){
                                selected_seat.splice(index,1);
                                SeatStorage.setSeats(selected_seat,15);
                                $(`#seat${seat_id}`).removeClass('selected');
                                summary();
                            }else{
                                $('#seat-alert').html(`
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="font-size: 10pt;">
                                        An error occurred. Please try again.
                                        <button type="button" class="btn-close border-none shadow-none btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                `);
                            }
                            $('#loadingOverLay').addClass('d-none');
                        },
                        complete: function() {
                            $this.data('processing', false);
                        },
                })
            })


        })
    </script>
@endsection
@if(session('clear_seats'))
    <script>
        localStorage.removeItem('selected_seats');
    </script>
@endif
