@extends('main.admin.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <div class="content px-2 py-3">
        <div class="container-fluid">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('seller#movie')}}">Seller</a></li>
                        <li class="breadcrumb-item"><a href="{{route('seller#moviePlan',$moviePlan->movie_id)}}">Booking</a></li>
                        <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Seat</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payment</li>
                    </ol>
                </nav>
            </div>
            <div id="payment">
                <div class="row mb-3">
                    <div class="col-lg-5 order-lg-2 mb-4">
                        <div id="booking-summary">
                            <h6 class="fw-bold">Movie Details</h6>
                            <div class="summary-content">
                                <div>
                                    @if ($moviePlan->cover != null)
                                        @foreach (json_decode($moviePlan->cover) as $item)
                                            @if ($loop->index==0)
                                                <img src="{{Storage::url('cover/'.$item)}}" class="d-block" alt="image">
                                            @endif
                                        @endforeach
                                    @else
                                        <img src="{{asset('image/placeholder.webp')}}" alt="Default" class="d-block">
                                    @endif

                                </div>
                                <div class="my-4">
                                    <div class="d-flex align-items-center">
                                        <h5>{{$moviePlan->movie_name}}</h5>
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
                                                {{$item->seat_name}} @if (!$loop->last), @endif
                                            @endforeach
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 pe-lg-4 order-lg-1">
                        <div>
                            <div id="promo_alert"></div>
                            <div id="payment_alert"></div>
                            <div id="booking-total">
                                <div class="title">
                                    <div>
                                        <i class="fa-solid fa-wallet me-2"></i>
                                        <span class="fw-bold">Payment Summary</span>
                                    </div>
                                    <div id="promo-code">
                                        <button class="btn" data-bs-toggle="modal" data-bs-target="#promo">
                                            <i class="fa-solid fa-circle-plus promo-icon me-2"></i>Promo
                                        </button>
                                        <div class="modal fade" id="promo" tabindex="-1" aria-labelledby="promo-modalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title d-block w-100 text-center" id="add-PaymentLabel">Add Promotion</h5>
                                                        <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="from-group mb-4">
                                                            <input type="text" id="promo_code" class="form-control" placeholder="Enter promo code">
                                                        </div>
                                                        <div class="d-flex align-items-center my-2">
                                                            <button class="action-btn w-100" id="add_promotion" data-bs-dismiss="modal"><i class="fa-solid fa-square-plus me-2"></i>Apply</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ticket-content mt-3">
                                    <div class="mx-5">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div class="d-flex align-items-center">
                                                <span>{{count($seat)}}</span>
                                                <label class="ms-2">Ticket(s)</label>
                                            </div>
                                            <label><span id="seatTotalPrice">{{ number_format($totalPrice) }}</span> Kyats</label>
                                        </div>
                                        <div id="promotion"></div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between px-5" id="total-price">
                                        <label for="">Total</label>
                                        <label><span id="subTotal">{{ number_format($totalPrice) }}</span> Kyats</label>
                                    </div>
                                </div>
                            </div>
                            <div id="payment-option">
                                <div class="title mb-3">
                                    <div>
                                        <i class="fa-solid fa-credit-card me-2"></i>
                                        <span class="fw-bold">Payment Method</span>
                                    </div>
                                </div>
                                <div class="payment-grid">
                                    @foreach ($payment as $item)
                                        @if($item->id == 1 || $item->cinema_id == $moviePlan->cinema_id)
                                            <label class="payment-card parents">
                                                <div class="payment">
                                                    <input type="radio" id="paymentMethod" name="paymentMethod" value="{{$item->id}}" class="payment-radio" data-target="#payment-{{$item->id}}" @if($loop->first) checked @endif>
                                                    <div class="payment-card-body">
                                                        @if($item->id == 1)
                                                            <i class="fa-solid fa-money-bill-wave payment-icon"></i>
                                                        @else
                                                            <i class="fa-solid fa-mobile-screen-button payment-icon"></i>
                                                        @endif
                                                        <h6>{{$item->name}}</h6>
                                                        <span class="check">
                                                            <i class="fa-solid fa-circle-check"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="payment-details mt-4">
                                    @foreach ($payment as $item)
                                        @if($item->id == 1 || $item->cinema_id == $moviePlan->cinema_id)
                                            <div class="payment-info @if(!$loop->first) d-none @endif" id="payment-{{$item->id}}">
                                                <div class="row align-items-center">
                                                    <div class="col-md-7">
                                                        <div class="info-item">
                                                            <label>Payment Type</label>
                                                            <h6>{{$item->name}}</h6>
                                                        </div>
                                                        @if($item->id != 1)
                                                            <div class="info-item">
                                                                <label>Phone Number</label>
                                                                <h6>{{$item->number}}</h6>
                                                            </div>
                                                            <div class="info-item">
                                                                <label>Account Name</label>
                                                                <h6>{{$item->username}}</h6>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @if($item->image)
                                                        <div class="col-md-5 text-center">
                                                            <img src="{{Storage::url('payment/'.$item->image)}}" class="payment-qr">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div id="paying-amount">
                                <div class="title mb-3">
                                    <div class="pay_amount">
                                        <i class="fa-solid fa-money-bill me-2"></i>
                                        <span class="fw-bold">Pay Amount</span>
                                    </div>
                                </div>
                                <div class="content">
                                    <form method="POST" action="{{route('seller#order')}}" enctype="multipart/form-data" id="order_form">
                                        @csrf
                                        <div class="pay_amount">
                                            <div>
                                                <input type="number" class="form-control" name="pay_amount" placeholder="Enter customer payment amount">
                                            </div>
                                        </div>
                                        <div class="book d-none" id="book">
                                            <div class="row">
                                                <div class="col-sm-6 mb-3">
                                                    <div>
                                                        <label for="" class="py-1">User Name</label>
                                                        <input type="hidden" name="user_name" id="user_name" class="form-control" placeholder="Enter user name" value="{{Auth::user()->name}}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <div>
                                                        <label for="" class="py-1">User Phone</label>
                                                        <input type="hidden" name="user_phone" id="user_phone" class="form-control" placeholder="Enter user phone" value="{{Auth::user()->phone}}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div>
                                                        <label for="" class="py-1">User Email</label>
                                                        <input type="hidden" name="user_email" id="user_email" class="form-control" placeholder="Enter user email" value="{{Auth::user()->email}}">
                                                    </div>
                                                </div>
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
                                    <div class="my-3 d-flex justify-content-end gap-1">
                                        <button class="btn me-2" type="button" id="seller_booking"><i class="fa-solid fa-calendar-check me-2"></i>Booking</button>
                                        <button class="btn" type="button" id="seller_order">
                                            <i id="seller_order_icon" class="fa-regular fa-circle-check me-1"></i>
                                            <span id="seller_order_text">Confirm Order</span>
                                        </button>
                                        <button class="btn btn-danger d-none ms-2" type="button" id="booking_cancel"><i class="fa-solid fa-ban me-2"></i>Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        $('#seller_order_text').text('Booking');
        $('#seller_order_icon')
            .removeClass('fa-regular fa-circle-check')
            .addClass('fa-solid fa-calendar-check');
        $('#user_name').attr('type', 'text');
        $('#user_email').attr('type', 'email');
        $('#user_phone').attr('type', 'text');
        $('#order_status').val('book');
    })

    $('#booking_cancel').click(function(){
        $('#book').addClass('d-none');
        $('#booking_cancel').addClass('d-none');
        $('#seller_booking').removeClass('d-none');
        $('#seller_order_text').text('Confirm Order');
        $('#seller_order_icon')
            .removeClass('fa-solid fa-calendar-check')
            .addClass('fa-regular fa-circle-check');
        $('#user_name').attr('type', 'hidden');
        $('#user_email').attr('type', 'hidden');
        $('#user_phone').attr('type', 'hidden');
        $('#order_status').val('');
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
                    $('#promotion').html(`
                        <div class="d-flex align-items-center justify-content-between gap-3 mt-3" id="taxes">
                            <label>${promotion.name}</label>
                            <label class="text-nowrap"> - ${Number(promotion_price).toLocaleString()} Kyats</label>
                        </div>
                    `);
                    $('#subTotal').html(total_price);
                    $('#promo-code').hide();
                    $('#promo_code').val('');
                    $('#promotion_id').val(promotion.id);
                    $('#promotion_price').val(promotion_price);
                    $('#total_price').val(total_price);
                }else{
                    $('#promo_alert').html(`
                        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="font-size: 10pt;">
                            Your promo code is wrong or promotion period is over. Please check and try again.
                            <button type="button" class="btn-close border-none shadow-none btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
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
