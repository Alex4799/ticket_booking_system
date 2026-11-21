@extends('main.customer.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <div class="d-none order-loader" id="order-loader">
        <div class=" w-100 h-100 d-flex justify-content-center align-items-center">
            <div class="">
                <div class="d-flex justify-content-center py-2">
                    <div class="loader"></div>
                </div>
                <p class="mt-2 text-white" style="font-size: 9pt;">Please Wait</p>
                <h6 class="text-white mt-4" style="font-size: 10pt;">
                    Finalizing your payment, this should only take a few seconds...
                </h6>
            </div>
        </div>
    </div>

    <!-- payment section start  -->
    <section id="payment" class="mb-5">
        <div id="selected-movie-label" class="d-flex align-items-center justify-content-between flex-wrap gap-1">
            <div class="d-flex align-items-center flex-wrap gap-1">
                <div>
                    <a href="{{ url()->previous() }}">
                        <button class="btn"><i class="fa-solid fa-chevron-left"></i></button>
                    </a>
                </div>
                <div class="ms-2 mb-2">
                    <span>Check Out</span>
                </div>
            </div>
            <div id="checkout-time" class="mb-2">
                <div>
                    <i class="fa-regular fa-clock"></i>
                    <span>Time Left - <span id="timer">15:00</span> </span>
                </div>
            </div>
        </div>

        <div id="order-location3">
            <div class="d-flex align-items-center justify-content-center">
                <span></span>
                <span></span>
            </div>
            <div id="o-location-n">
                <div class="d-flex align-items-center justify-content-around">
                    <label for=""><i class="fa-solid fa-check"></i></label>
                    <label for=""><i class="fa-solid fa-check"></i></label>
                    <label for="">3</label>
                </div>
                <div class="d-flex align-items-center justify-content-around mt-1">
                    <h6>Select Showtime</h6>
                    <h6>Select Seats</h6>
                    <h6>Confirm Payment</h6>
                </div>
            </div>
        </div>

        <div class="checkout-content">
            <div class="row mx-auto">
                <div class="col-lg-5 order-lg-2 mb-5">
                    <div id="booking-summary">
                        <h5>Movie Details</h5>
                        <div class="px-1 summary-content">
                            <div class="item-right">
                                <span class="up-border"></span>
                                <span class="down-border"></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="me-4">
                                    <img src="{{Storage::url('poster/'.$moviePlan->poster)}}" class="d-block rounded" alt="image">
                                </div>
                                <div class="">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fw-bold">{{$moviePlan->movie_name}}</h5>
                                    </div>
                                    <div class="mt-2">
                                        <label>Cinema</label>
                                        <h6 class="mt-1">{{$moviePlan->cinema_name}}</h6>
                                    </div>
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
                                            {{$item->seat_name}}{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 order-lg-1">
                    <div id="promo_alert"></div>
                    <div id="payment_alert"></div>
                    <div id="booking-total">
                        <div class="title">
                            <div>
                                <i class="fa-solid fa-wallet me-2"></i>
                                <span>Payment Summary</span>
                            </div>
                            <div id="promo-code">
                                <button class="btn" data-bs-toggle="modal" data-bs-target="#promo">
                                    <i class="fa-solid fa-circle-plus promo-icon me-2"></i>Promo
                                </button>
                                <div class="modal fade" id="promo" tabindex="-1" aria-labelledby="promo-modalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content bg-nav-bar">
                                            <div class="modal-header d-flex align-items-center justify-content-between">
                                                <h1 class="modal-title fs-6" id="add-PaymentLabel">Add Promotion</h1>
                                                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            <div class="modal-body mt-2">
                                                <div class="from-group mb-4">
                                                    <input type="text" id="promo_code" class="form-control" placeholder="Enter promo code">
                                                </div>
                                                <button type="submit" class="btn mb-1 w-100" id="add_promotion" data-bs-dismiss="modal" aria-label="Close">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ticket-content mt-3">
                            <div class="mx-3">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center">
                                        <span>{{count($seat)}}</span>
                                        <label class="ms-2">Ticket(s)</label>
                                    </div>
                                    <label><span id="seatTotalPrice">{{ number_format($totalPrice) }}</span> Kyats</label>
                                </div>
                                @if ($website->payment_type)
                                    <div id="transaction_fee_section" class="d-flex align-items-center justify-content-between mt-3 booking-total-content">
                                        <label class="pe-3">Transaction Fee</label>
                                        <div class="transaction_fee text-nowrap">0 Kyats</div>
                                    </div>
                                @endif
                                <div id="promotion-text"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between px-3" id="total-price">
                                <label for="">Total</label>
                                <label><span id="subTotal">{{ number_format($totalPrice) }}</span> Kyats</label>
                            </div>
                        </div>
                    </div>
                    <div id="payment-option">
                        <div class="title mb-4">
                            <div>
                                <i class="fa-solid fa-credit-card me-2"></i>
                                <span>Payment Method</span>
                            </div>
                        </div>
                        <div>
                            @if ($moviePlan->online_buy==1)
                                @if ($website->payment_type)
                                    <div class="payment-types">
                                        @if ($moviePlan->online_book==1)
                                            <label class="payment-card parents">
                                                <div class="payment">
                                                    <input type="radio" id="paymentType" name="paymentType" value="booking" class="payment-radio" checked>
                                                    <div class="payment-card-body">
                                                        <i class="fa-solid fa-building payment-icon"></i>
                                                        <h6>Booking</h6>
                                                        <span class="check">
                                                            <i class="fa-solid fa-circle-check"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </label>
                                        @endif
                                        <label class="payment-card parents">
                                            <div class="payment">
                                                <input type="radio" id="paymentType" name="paymentType" value="mmqr" class="payment-radio">
                                                <div class="payment-card-body">
                                                    <i class="fa-solid fa-mobile-screen-button payment-icon"></i>
                                                    <h6>MMQR ( Kpay , Wave Pay , AYA Pay , uabpay ,..... )</h6>
                                                    <span class="check">
                                                        <i class="fa-solid fa-circle-check"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="payment-card parents">
                                            <div class="payment">
                                                <input type="radio" id="paymentType" name="paymentType" value="visa_master" class="payment-radio">
                                                <div class="payment-card-body">
                                                    <i class="fa-solid fa-mobile-screen-button payment-icon"></i>
                                                    <h6>Visa and Master Card</h6>
                                                    <span class="check">
                                                        <i class="fa-solid fa-circle-check"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="payment-card parents">
                                            <div class="payment">
                                                <input type="radio" id="paymentType" name="paymentType" value="upi" class="payment-radio">
                                                <div class="payment-card-body">
                                                    <i class="fa-solid fa-mobile-screen-button payment-icon"></i>
                                                    <h6>UPI</h6>
                                                    <span class="check">
                                                        <i class="fa-solid fa-circle-check"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="payment-card parents">
                                            <div class="payment">
                                                <input type="radio" id="paymentType" name="paymentType" value="mpu" class="payment-radio">
                                                <div class="payment-card-body">
                                                    <i class="fa-solid fa-mobile-screen-button payment-icon"></i>
                                                    <h6>MPU</h6>
                                                    <span class="check">
                                                        <i class="fa-solid fa-circle-check"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="payment-card parents">
                                            <div class="payment">
                                                <input type="radio" id="paymentType" name="paymentType" value="uabpay" class="payment-radio">
                                                <div class="payment-card-body">
                                                    <i class="fa-solid fa-mobile-screen-button payment-icon"></i>
                                                    <h6>uabpay</h6>
                                                    <span class="check">
                                                        <i class="fa-solid fa-circle-check"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @else
                                    @php
                                        $visiblePayments = collect($payment)->filter(function ($item) use ($moviePlan) {
                                            return ($item->id == 2 && $moviePlan->online_book == 1) || $item->id != 2;
                                        });
                                        $selectedPayment = $visiblePayments->first();
                                    @endphp

                                    <div class="payment-grid">
                                        @foreach ($visiblePayments as $item)
                                            <label class="payment-card parents">
                                                <div class="payment">
                                                    <input type="radio" id="paymentMethod" name="paymentMethod" value="{{$item->id}}" class="payment-radio" data-target="#payment-{{$item->id}}"  {{ $selectedPayment->id == $item->id ? 'checked' : '' }}>
                                                    <div class="payment-card-body">
                                                        @if($item->id == 2)
                                                            <i class="fa-solid fa-building payment-icon"></i>
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
                                        @endforeach
                                    </div>
                                    <div class="payment-details mt-4">
                                        @foreach ($visiblePayments as $item)
                                            <div class="payment-info {{ $selectedPayment->id == $item->id ? '' : 'd-none' }}" id="payment-{{$item->id}}">
                                                <div class="row align-items-center">
                                                    <div class="col-md-7">
                                                        <div class="info-item">
                                                            <label>Payment Type</label>
                                                            <h6>{{$item->name}}</h6>
                                                        </div>
                                                        @if($item->id!=2)
                                                            <div class="info-item">
                                                                <label>Phone Number</label>
                                                                <div class="d-flex align-items-center">
                                                                    <h6>{{$item->number}}</h6>
                                                                    <button class="btn btn-sm btn-outline-info py-0 px-2 ms-4 mb-2" id="copyBtn" onclick="copyToClipboard('{{$item->number}}')">Copy</button>
                                                                </div>
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
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="px-2 py-3 text-center text-secondary" style="font-size: 10pt;">This Movie Time is available for booking only.</div>
                            @endif
                        </div>
                    </div>

                    <div id="user-contact-info">
                        <div class="title mb-4">
                            <div>
                                <i class="fa-solid fa-address-card me-2"></i>
                                <span>Contact Information</span>
                            </div>
                        </div>
                        <div class="d-none justify-content-center mt-4 text-center" id="verifyLoading">
                            <div id="loading-content">
                                <div class="spinner-border text-warning" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2" style="font-size: 9pt;">Please Wait</p>
                            </div>
                        </div>
                        <div id="user_alert" class='d-none'></div>
                        <form action="" class="mt-3" id="send_form">
                            <div class="mb-3">
                                <div class="row form-group">
                                    <div class="col-sm-6 mb-3">
                                        <label for="">Name*</label>
                                        <input type="text" id="userName" class="form-control" value="{{old('user_name')}}" placeholder="Enter name">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label for="">Phone*</label>
                                        <input type="text" id="userPhone" class="form-control" value="{{old('user_email')}}" placeholder="09 *********">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="">Email*</label>
                                    <input type="email" id="userEmail" class="form-control" value="{{old('user_phone')}}" placeholder="Enter email">
                                </div>
                            </div>
                            <button class="btn w-100 mb-2" type="button" id="verify"><i class="fa-solid fa-envelope me-1" style="font-size: 11pt;"></i> Get OTP <span id="otp_count"></span></button>
                        </form>
                    </div>

                    <div id="OTP">
                        <div class="title mb-4">
                            <div>
                                <i class="fa-solid fa-address-card me-2"></i>
                                <span>Verify Contact Info</span>
                            </div>
                        </div>
                        <div class="d-none justify-content-center mt-4 text-center" id="orderLoading">
                            <div id="loading-content">
                                <div class="spinner-border text-warning" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2" style="font-size: 9pt;">Please Wait</p>
                            </div>
                        </div>
                        <div id="otp_alert"></div>
                        <form method="post" action="@if ($website->payment_type) {{route('customer#order')}} @else {{route('customer#manualOrder')}} @endif" enctype="multipart/form-data" class="mt-3" id="order-form">
                            @csrf
                            @if ($moviePlan->online_buy==1 && $website->payment_type!=1)
                                <div id="payment_slip" class="@if($moviePlan->online_book==1) d-none @endif mb-4">
                                    <div class="d-flex align-items-center mb-4">
                                        <label>ငွေပေးချေမှု အတည်ပြုနိုင်ရန် ငွေလွှဲပြေစာပုံကို ဤနေရာတွင် တင်ပေးပါ။*</label>
                                    </div>
                                    <div class="payment_alert"></div>
                                    <div id="payment-picture">
                                        <div>
                                            <img src="{{asset('image/placeholder.webp')}}" id="output" alt="image">
                                        </div>
                                        <label for="file">
                                            <span><i class="fa-solid fa-cloud-arrow-up me-1"></i></span>
                                            <span>Upload Image</span>
                                        </label>
                                        <input type="file" name="slip" id="file" accept="image/*" onchange="loadFile(event);">
                                    </div>
                                </div>
                            @endif

                            <span>
                                <input type="hidden" name="user_name" id="user_name">
                                <input type="hidden" name="user_email" id="user_email">
                                <input type="hidden" name="user_phone" id="user_phone">
                                <input type="hidden" name="payment_type" id="payment_type">
                                <input type="hidden" name="payment_method" id="payment_method">
                                <input type="hidden" name="promotion_id" id="promotion_id">
                                <input type="hidden" name="promotion_price" id="promotion_price">
                                <input type="hidden" name="total_price" id="total_price" value="{{$totalPrice}}">
                                <input type="hidden" name="movie_plan_id" id="movie_plan_id" value="{{$moviePlan->id}}">
                                <input type="hidden" name="seats" id="seats" value="{{json_encode($seat)}}">
                                <input type="hidden" name="seat_count" id="seat_count" value="{{count($seat)}}">
                                <input type="hidden" name="order_status" id="order_status" value="order">
                                <input type="hidden" name="transaction_fee" id="transaction_fee_input">
                            </span>

                            <div class="form-group mb-4">
                                <label for="otp_input">OTP*</label>
                                <input type="text" class="form-control" id="otp_input" placeholder="Enter OTP number">
                            </div>

                            <section>
                                @if($errors->has('g-recaptcha-response'))
                                    <div class="alert alert-danger alert-dismissible fade show my-2" style="font-size: 10pt;" role="alert">
                                        {{ $errors->first('g-recaptcha-response') }}
                                    </div>
                                @endif

                                {!! NoCaptcha::renderJs() !!}

                                <div class="custom-recaptcha-wrapper">
                                    {!! NoCaptcha::display() !!}
                                </div>
                            </section>
                        </form>

                        <div class="mt-3 d-flex justify-content-end gap-3">
                            @if ($moviePlan->online_buy==1)
                                <button class="btn" type="button" id="order">
                                    <i class="fa-solid fa-money-bill me-2"></i>Buy Ticket
                                </button>
                            @endif

                            @if ($moviePlan->online_book==1)
                                <button class="btn" type="button" id="book">
                                    <i class="fa-solid fa-ticket me-2"></i>Book Ticket
                                </button>
                            @endif
                        </div>
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
    let paymentMethod=2;
    let otp=null;

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
                    $('#promotion-text').html(
                        `<div class="d-flex align-items-center justify-content-between mt-3 booking-total-content">
                            <label class="pe-3">${promotion.name}</label>
                            <label class="text-nowrap"> - <span class="mx-1"> ${Number(promotion_price).toLocaleString()}</span> Kyats</label>
                        </div>`
                    );
                    $('#subTotal').html(total_price.toLocaleString());
                    $('#promo-code').hide();
                    $('#promo_code').val('');
                    $('#promotion_id').val(promotion.id);
                    $('#promotion_price').val(promotion_price);
                    $('#total_price').val(total_price);
                }else{
                    $('#promo_alert').html(`
                        <div class="alert alert-warning alert-dismissible fade show" style="font-size: 10pt;" role="alert">
                            Your promo code is wrong or promotion period is over.Please check and try again.
                            <button type="button" class="btn-close border-0 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
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
        if (paymentMethod==2) {
            $('#payment_slip').addClass('d-none');
            $('#order').addClass('d-none');
            $('#book').removeClass('d-none');
        }else{
            $('#payment_slip').removeClass('d-none');
            $('#order').removeClass('d-none');
            $('#book').addClass('d-none');
        }
    })

    $('#verify').click(function(){
        $('#verifyLoading').removeClass('d-none');
        $('#verifyLoading').addClass('d-flex');

        let username=$('#userName').val();
        let email=$('#userEmail').val();
        let phone=$('#userPhone').val();
        if (username!=''&& email!=''&& phone!='') {
            let emailInput = document.getElementById("userEmail");
            if (emailInput.checkValidity()) {
                otp=Math.floor(Math.random()*100001);
                let user_data={
                    username:username,
                    email:email,
                    phone:phone,
                    otp:otp,
                    status:'pending',
                };
                $.ajax({
                type:'get',
                url:`{{route('customer#verify')}}`,
                dataType:'json',
                data:Object.assign(user_data),
                success:function(data){
                        if (data.status=='successful') {
                            $('#user_alert').removeClass('d-none');
                            $('#user_alert').html(`
                                <div class="alert alert-success alert-dismissible fade show my-2" style="font-size: 10pt;" role="alert">
                                    Please check your Gmail inbox for the OTP code. If you don't receive it within 1 minute, please check your Spam folder. Thank you!
                                    <button type="button" class="btn-close border-0 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
                            $('#verifyText').html('Resend Mail');
                            $('#otp_count').removeClass('d-none');
                            $('#verify').prop('disabled', true);
                            restartOTP();
                            $('#user_name').val(username);
                            $('#user_email').val(email);
                            $('#user_phone').val(phone);
                        }
                        $('#verifyLoading').addClass('d-none');
                    }
                })
            } else {
                $('#user_alert').removeClass('d-none');
                $('#user_alert').html(`
                    <div class="alert alert-warning alert-dismissible fade show my-2" style="font-size: 10pt;" role="alert">
                        Please Fill Correct Email.
                        <button type="button" class="btn-close border-0 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                $('#verifyLoading').addClass('d-none');
            }

        }else{
            $('#user_alert').removeClass('d-none');
            $('#user_alert').html(`
                <div class="alert alert-warning alert-dismissible fade show my-2" style="font-size: 10pt;" role="alert">
                    Please Fill User Info Completely.
                    <button type="button" class="btn-close border-0 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            $('#verifyLoading').addClass('d-none');
        }
    })

    function startTimer(duration, display) {
        let timer = duration, minutes, seconds;
        const interval = setInterval(function(){
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            display.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(interval);
                alert('You have reached the time limit. You can try again.');
                localStorage.removeItem('selected_seats');
                window.location.assign("{{route('customer#movieBook',$moviePlan->movie_id)}}");
            }
        }, 1000);
    }

    const twoMinutesThirtySeconds = 20 * 60;
    const display = document.querySelector('#timer');
    startTimer(twoMinutesThirtySeconds, display);

    function restartOTP(){
        let timer = 90, minutes, seconds;
        let otpInterval = setInterval(function(){
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            $('#otp_count').html(" in "+minutes + ":" + seconds);

            if (--timer < 0) {
                clearInterval(otpInterval);
                $('#otp_count').addClass('d-none');
                $('#verify').prop('disabled', false);
            }
        }, 1000);
    }

    $('#order').click(function(){
        if (otp==$('#otp_input').val()) {
            $(this).prop('disabled', true);
            $(this).text('Processing...');
            $('#payment_method').val(paymentMethod);
            localStorage.removeItem('selected_seats');
            $('#order-form').submit();
        }else{
            $('#otp_alert').html(`
                <div class="alert alert-danger alert-dismissible fade show my-2" style="font-size: 10pt;" role="alert">
                    Wrong OTP code. Please try again.
                    <button type="button" class="btn-close border-0 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
        }
    });

    $('#book').click(function(){
        if (otp==$('#otp_input').val()) {
            $(this).prop('disabled', true);
            $(this).text('Processing...');
            $('#payment_method').val(2);
            $('#order_status').val('book');
            $('#order-form').attr('action', '/order/customer/book');
            localStorage.removeItem('selected_seats');
            $('#order-form').submit();
        }else{
            $('#otp_alert').html(`
                <div class="alert alert-danger alert-dismissible fade show my-2" style="font-size: 10pt;" role="alert">
                    Wrong OTP code. Please try again.
                    <button type="button" class="btn-close border-0 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
        }
    });

    function updateOrderButtons() {
        // Booking only
        if (@json($moviePlan->online_buy == 0 && $moviePlan->online_book == 1)) {
            $('#book').removeClass('d-none');
            $('#order').addClass('d-none');
            return;
        }

        let paymentType = $('.payment-radio:checked').val();
        if (!paymentType) {
            $('#book, #order').addClass('d-none');
            return;
        }
        if (paymentType == 'booking' || paymentType == '2') {
            $('#book').removeClass('d-none');
            $('#order').addClass('d-none');
        } else {
            $('#order').removeClass('d-none');
            $('#book').addClass('d-none');
        }
    }
    $(document).on('change', '.payment-radio', updateOrderButtons);
    $(document).ready(function () {
        updateOrderButtons();
    });

</script>
<script>
    function copyToClipboard($value) {
        navigator.clipboard.writeText($value).then(() => {
            $('#copyBtn').html('Copied');
        });
    }

    function loadFile(event) {
        let output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src)
        }
    }
</script>
@if ($website->payment_type)
    <script>
        let paymentType='mmqr';
        $price={{$totalPrice}};

        function calculateTransactionFee($type){
            if ($type=='booking') {
                $('#order').addClass('d-none');
                $('#book').removeClass('d-none');
                $transactionfee=0;
                $('.transaction_fee').html($transactionfee + ' Kyats');
                $('#subTotal').html($price+$transactionfee);
                $('#transaction_fee_input').val($transactionfee);
                $('#payment_type').val($type);
            }else{
                $rate={
                    'mmqr':0.5,
                    'visa_master':2,
                    'upi':2,
                    'mpu':500,
                    'uabpay':0,
                };
                $transactionfee=0;
                if ($type=='mpu') {
                    $transactionfee=500;
                }else if($type=='uabpay'){
                    $transactionfee=0;
                }else{
                    $transactionfee=$price*($rate[$type]/100);
                }
                $('.transaction_fee').html($transactionfee + ' Kyats');
                $('#subTotal').html(($price+$transactionfee).toLocaleString());
                $('#transaction_fee_input').val($transactionfee);
                $('#payment_type').val($type);
                $('#order').removeClass('d-none');
                $('#book').addClass('d-none');
                paymentMethod=3;
            }
        }

        $('input[name="paymentType"]').change(function(){
            paymentType=$('input[name="paymentType"]:checked').val();
            calculateTransactionFee(paymentType)
        })

        $('.payment').click(function(){
            parents=$(this).parents('.parents');
            paymentType=parents.find('#paymentType').val();
            calculateTransactionFee(paymentType);
        })
    </script>
@endif
@endsection
