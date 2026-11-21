@extends('main.admin.layout.master')
@section('style')
<style>
    /* Full-screen overlay */
    #loadingOverLay {
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
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <!-- select seat start  -->
    <section class="position-relative">
        <div id="selected-movie-label" class="row mx-auto align-items-center">
            <div class="col-lg-4 col-md-3">
                <a href="{{route('seller#moviePlan',$moviePlan->movie_id)}}">
                    <button class="btn"><i class="fa-solid fa-circle-left me-2"></i>Back</button>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 text-center">
                <h5>{{$moviePlan->movie_name}}</h5>
                    <ul class="d-flex align-items-center justify-content-center">
                        <li>{{Carbon::parse($moviePlan->date)->format('l, F j, Y')}} , {{Carbon::parse($moviePlan->start_time)->format('h:i:A')}}</li>
                    </ul>
            </div>
        </div>
        <div class="container-lg" id="seat-design">
            <div class="text-center">
                <h5>{{$moviePlan->cinema_name}}</h5>
                <label class="mt-3 fw-medium">{{$moviePlan->hall_name}}</label>
            </div>
        </div>
        <div>
            <div class="row container-fluid" id="seatPlan">
                @foreach ($seatPlan as $index=>$item)
                <div class="col">
                    <div class="rounded shadow p-2" style="border: 5px solid {{$item['seat_color']}}">
                        <p>{{$item['seat_plan_name']}} - {{$item['price']}} MMK</p>
                        <p>Avaliable - {{$item['avaliable']}}</p>
                        <p>Sold - {{$item['sold']}}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div id="theater-seat-content" class="mt-5">
            <div>
                <div id="seat-alert">

                </div>
                <div class="d-flex justify-content-center">
                    <div id="loadingOverLay" class="d-none">
                        <div class="spinner-border text-primary" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="cinema-seating p-3">
                    <div class="screen-container">
                        <div class="screen">Screen</div>
                    </div>

                    <div id="seat-plan-container">
                        <div>
                            <div class=" d-grid" style="grid-template-columns: repeat({{$moviePlan->seat_column}}, auto);" id="seat-plan">
                                @foreach ($movieSeat as $item)
                                <div class="mx-auto">
                                    <div class="parents seat @if($item->status==1) selected @elseif ($item->status==0) avaliable @else sold @endif" style="@if($item->status==0) color:{{$item->seat_color}} @endif" id="seat{{$item->id}}">
                                        @if ($item->active!=0)
                                            @if ($item->seat_type==1)
                                                <div class="box arrow-bottom">{{$item->price}} Kyats</div>
                                                <div class="seat-content seat_class">
                                                    <text x="12" y="20" text-anchor="middle" class="svg-number">{{$item->seat_name}}</text>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.6" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-armchair">
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
                                                <div class="box arrow-bottom">{{$item->price}} Kyats</div>
                                                <div class="seat-content seat_class">
                                                    <text x="12" y="20" text-anchor="middle" class="svg-number">{{$item->seat_name}}</text>
                                                    <svg class="lucide lucide-couple-armchair" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                                        <!-- Mirrored and shortened Left backrest -->
                                                        <path d="M22 12V10a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v8" />

                                                        <!-- Shortened Right backrest -->
                                                        <path d="M26 12V10a3 3 0 0 1 3-3h11a3 3 0 0 1 3 3v8" />

                                                        <!-- Shared seat base -->
                                                        <path d="M2 30a3 3 0 0 0 3 3h38a3 3 0 0 0 3-3v-8a3 3 0 0 0-6 0v2a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2a3 3 0 0 0-6 0z" />

                                                        <!-- Legs -->
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
                            <!-- add other row  -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-lg" id="check-out">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-8">
                        <h5>Selected Seats</h5>
                        <div class="row mt-3 ms-1 me-3" id="seat-number">
                            <p class="text-warning">There is no select</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-3 col-sm-4">
                        <h5>Total Price</h5>
                        <p class="mt-3"><span id="total_price">0</span> Kyats</p>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <form action="{{route('seller#checkOut')}}" method="get">
                            @csrf
                            <input type="hidden" name="seats" id="inputSeat" value="[]">
                            <input type="hidden" name="moviePlanId" value="{{$moviePlan->id}}">
                            <button type="submit" class="btn btn-outline-primary">Check Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- select seat end  -->

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
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            Unfortunately this seat is selected by someone. Please choose another seat.
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                        $list+=`<div class="col-lg-2 col-md-3 col-sm-3 parents">
                                    <div class="selected_seat">
                                        <h6>${element.seat_name}</h6>
                                        <input type="hidden" id="seat_id" value="${element.seat_id}">
                                        <input type="hidden" id="index" value="${index}">
                                    </div>
                                </div>`
                        $totalPrice+=parseInt(element.seat_price);
                    });
                } else {
                    $list=`<p class="text-warning">There is no select</p>`;
                    $totalPrice=0
                }
                $stringData=JSON.stringify(selected_seat);
                $('#inputSeat').val($stringData);
                $('#seat-number').html($list);
                $('#total_price').html($totalPrice);
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
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            An error occurred. Please try again.
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
