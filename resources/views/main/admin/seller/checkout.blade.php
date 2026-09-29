@extends('main.admin.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <div id="selected-movie-label" class="row mx-auto align-items-center">
        <div class="col-lg-4">
            <a href="{{ url()->previous() }}">
                <button class="btn"><i class="fa-solid fa-circle-left me-2"></i>Change Seat</button>
            </a>
        </div>
    </div>

    <!-- payment section start  -->
    <section id="payment" class="mt-3 pb-5">
        <div class="mx-4">
            <div class="row">
                <div class="col-lg-5 order-lg-2">
                   <div id="booking-summary" class="shadow p-2 rounded">
                        <h5>Booking Summary</h5>
                        <div class="px-1 summary-content">
                            <div class="item-right">
                                <span class="up-border"></span>
                                <span class="down-border"></span>
                            </div>
                            <div>
                                @foreach (json_decode($moviePlan->cover) as $item)
                                    @if ($loop->index==0)
                                        <img src="{{Storage::url('cover/'.$item)}}" class="d-block rounded" alt="">
                                    @endif
                                @endforeach
                            </div>
                            <div class="my-4">
                                <div class="d-flex align-items-center">
                                    <h5 class="fw-bold">{{$moviePlan->movie_name}}</h5>
                                </div>
                                <div class="mt-2">
                                    <label>Cinema</label>
                                    <h6 class="mt-1">{{$moviePlan->cinema_name}}</h6>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-6 mb-2">
                                    <label>Date</label>
                                    <h6 class="mt-1">{{Carbon::parse($moviePlan->date)->format('l, F j, Y')}}</h6>
                                </div>
                                <div class="col-sm-6 ps-sm-5 mb-2">
                                    <label>Time</label>
                                    <h6 class="mt-1">{{Carbon::parse($moviePlan->start_time)->format('h:i A')}}</h6>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-6 mb-3">
                                    <label>Hall</label>
                                    <h6 class="mt-1">{{$moviePlan->hall_name}}</h6>
                                </div>
                                <div class="col-sm-6 ps-sm-5 mb-3">
                                    <label>Selected Seats</label>
                                    <h6 class="mt-1">
                                        @foreach ($seat as $item)
                                            {{$item->seat_name}} ,
                                        @endforeach
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 order-lg-1">

                    <div class="py-3">
                        <div id="booking-total">
                            <h5>Amount Payable</h5>
                            <div class="px-5 mb-4">
                                <div class="d-flex align-items-center justify-content-between mt-3" id="booking-total-content">
                                    <div class="d-flex align-items-center">
                                        <span>{{count($seat)}}</span>
                                        <label>Ticket(s)</label>
                                    </div>
                                    <label><span id="seatTotalPrice">{{$totalPrice}}</span> Kyats</label>
                                </div>
                                <div id="promotion">

                                </div>
                            </div>
                            <hr>
                            <div class="d-flex align-items-center justify-content-between mt-3 px-5" id="total-price">
                                <label for="">Total</label>
                                <label><span id="subTotal">{{$totalPrice}}</span> Kyats</label>
                            </div>
                        </div>
                    </div>

                    <div class="py-3">
                        <div id="promo-code">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5>Promotion Code</h5>
                                <i class="fa-solid fa-circle-plus promo-icon"></i>
                            </div>
                            <div id="promo-form">
                                <hr>
                                <div id="promo_alert">

                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="from-group w-75">
                                        <input type="text" id="promo_code" class="form-control" placeholder="Enter promo code">
                                    </div>
                                    <button class="btn btn-primary" id="add_promotion">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="py-3">
                        <div id="payment-option">
                            <h5>Payment Method</h5>
                            @foreach ($payment as $item)
                                @if (($item->cinema_id==null || $item->cinema_id==$moviePlan->cinema_id) && $item->id!=2)
                                    <div class="px-4 mt-3 parents">
                                        <div class="payment">
                                            <div class="custom-radio d-flex align-items-center mb-1">
                                                <input type="radio" id="paymentMethod" name="paymentMethod" value="{{$item->id}}" class="payment-radio" data-target=".payment-content-{{$item->id}}">
                                                <label class="ms-3" for="radio1">{{$item->name}}</label>
                                            </div>
                                            <div class="payment-content payment-content-{{$item->id}}">
                                                <div class="d-flex align-items-center mb-3">
                                                    <strong>{{$item->name}} : </strong>
                                                    <label class="ms-3">{{$item->number}}</label>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <strong>Name : </strong>
                                                    <label class="ms-3">{{$item->username}}</label>
                                                </div>
                                                @if ($item->image!=null)
                                                    <div class="d-flex justify-content-center py-2 align-items-center">
                                                        <img src="{{Storage::url('payment/'.$item->image)}}" class="w-50 img-thumbnail" alt="">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div id="" class="">
                        <div id="payment_alert">

                        </div>
                        <form method="POST" action="{{route('seller#order')}}" enctype="multipart/form-data" id="order_form">
                            @csrf
                            <div class="py-3" id="pay_amount">
                                <div>
                                    <label for="" class="py-2 fs-4">Pay Amount</label>
                                    <input type="number" class="form-control" name="pay_amount">
                                </div>
                            </div>
                            <div class="book d-none" id="book">
                                <div class="py-2">
                                    <label for="" class="py-1">User Name</label>
                                    <input type="hidden" name="user_name" id="user_name" class="form-control" placeholder="Enter user name" value="{{Auth::user()->name}}">
                                </div>
                                <div class="py-2">
                                    <label for="" class="py-1">User Email</label>
                                    <input type="hidden" name="user_email" id="user_email" class="form-control" placeholder="Enter user email" value="{{Auth::user()->email}}">
                                </div>
                                <div class="py-2">
                                    <label for="" class="py-1">User Phone</label>
                                    <input type="hidden" name="user_phone" id="user_phone" class="form-control" placeholder="Enter user phone" value="{{Auth::user()->email}}">
                                </div>
                            </div>
                            <span>
                                <input type="hidden" name="payment_method" id="payment_method">
                                <input type="hidden" name="promotion_id" id="promotion_id">
                                <input type="hidden" name="promotion_price" id="promotion_price">
                                <input type="hidden" name="total_price" id="total_price" value="{{$totalPrice}}">
                                <input type="hidden" name="movie_plan_id" id="movie_plan_id" value="{{$moviePlan->id}}">
                                <input type="hidden" name="seats" id="seats" value="{{json_encode($seat)}}">
                                <input type="hidden" name="seat_count" id="seat_count" value="{{count($seat)}}">
                                <input type="hidden" name="order_status" id="order_status" value="order">
                            </span>
                        </form>
                    </div>
                    <div class="py-2 d-flex justify-content-end gap-1">
                        <button class="btn text-white px-3 me-2" type="button" id="seller_order">Order</button>
                        <button class="btn text-white px-3" type="button" id="seller_booking">Booking</button>
                        <button class="btn btn-danger d-none px-3 ms-2" type="button" id="booking_cancel">Cancel</button>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- payment section end  -->

@endsection
@section('script')
<script>

    let total_price={{$totalPrice}};
    let promotion_price=0;
    let paymentMethod=1;

    $('#seller_booking').click(function(){
        $('#book').removeClass('d-none');
        $('#booking_cancel').removeClass('d-none');
        $('#seller_booking').addClass('d-none');
        $('#seller_order').html('Book');
        $("#user_name").attr("type", "text");
        $("#user_email").attr("type", "email");
        $("#user_phone").attr("type", "text");
        $('#order_status').val('book');
    })

    $('#booking_cancel').click(function(){
        $('#book').addClass('d-none');
        $('#booking_cancel').addClass('d-none');
        $('#seller_booking').removeClass('d-none');
        $('#seller_order').html('Order');
        $("#user_name").attr("type", "hidden");
        $("#user_email").attr("type", "hidden");
        $("#user_phone").attr("type", "hidden");
        $('#order_status').val('order');
    })

    $('#add_promotion').click(function(){
        let promo_code=$('#promo_code').val();
        let data={
                promoCode:promo_code,
                cinema_id:{{$moviePlan->cinema_id}},
                hall_id:{{$moviePlan->hall_id}},
                movie_id:{{$moviePlan->movie_id}}
            }
        $.ajax({
            type:'get',
            data:data,
            dataType:'json',
            url:`{{route('checkPromotion')}}`,
            success:function(data){
                if(data.status=='success'){
                    let promotion=data.data;
                    if (promotion.percentage!=null) {
                        promotion_price=total_price*(promotion.percentage/100);
                        if (promotion.amount!=null) {
                            promotion_price=promotion_price>promotion.amount?promotion.amount:promotion_price;
                        }
                    }else{
                        promotion_price=promotion.amount;
                    }
                    total_price=total_price-promotion_price;
                    $('#promotion').html(
                        `<div class="d-flex align-items-center justify-content-between mt-3" id="booking-total-content">
                                        <div class="d-flex align-items-center">
                                            <span>${promotion.name}</span>
                                        </div>
                                        <label><span id="seatTotalPrice">- ${promotion_price}</span> Kyats</label>
                                    </div>`
                    );
                    $('#subTotal').html(total_price);
                    $('#promo-code').hide();
                    $('#promo_code').val('');
                    $('#promotion_id').val(promotion.id);
                    $('#promotion_price').val(promotion_price);
                    $('#total_price').val(total_price);
                }else{
                    $('#promo_alert').html(`
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Your promo code is wrong or promotion period is over.Please check and try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            }
        })
    })

    $('.payment').click(function(){
        let parents=$(this).parents('.parents');
        paymentMethod=parents.find('#paymentMethod').val();
        $('#payment_method').val(paymentMethod);
        if (paymentMethod=='1') {
            $('#pay_amount').show();
        }else{
            $('#pay_amount').hide();
        }

    })

    $('#seller_order').click(function(){
        $(this).prop('disabled', true);
        $(this).text('Processing...');
       localStorage.removeItem('selected_seats');
        $('#order_form').submit();
    })

</script>
@endsection
