@extends('main.customer.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <div class="d-none order-loader" id="order-loader">
        <div class="w-100 h-100 d-flex justify-content-center align-items-center">
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

    <!-- payment section start -->
    <section id="payment" class="mb-5">
        <div id="selected-movie-label" class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-4">
            <div class="d-flex align-items-center flex-wrap gap-1">
                <div>
                    <a href="{{ url()->previous() }}">
                        <button class="btn"><i class="fa-solid fa-chevron-left"></i></button>
                    </a>
                </div>
                <div class="ms-2 mb-2">
                    <span class="fs-4 fw-bold">Check Out</span>
                </div>
            </div>
            <div id="checkout-time" class="mb-2">
                <div class="alert alert-dark py-2 px-3 mb-0 border-secondary">
                    <i class="fa-regular fa-clock me-1 text-warning"></i>
                    <span>Time Left - <span id="timer" class="fw-bold text-warning">15:00</span></span>
                </div>
            </div>
        </div>

        <div id="order-location3" class="mb-4">
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
            <div class="row mx-auto g-4">
                <!-- Right Column on Desktop: Booking Summary (Sticky) -->
                <div class="col-lg-5 order-lg-2">
                    <div style="position: sticky; top: 20px;">
                        <div id="booking-summary" class="p-3 rounded shadow-sm border border-secondary">
                            <h5 class="mb-3 border-bottom pb-2">Booking Summary</h5>
                            <div class="px-1 summary-content">
                                <div class="item-right">
                                    <span class="up-border"></span>
                                    <span class="down-border"></span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="me-3" style="width: 90px; flex-shrink: 0;">
                                        <img src="{{Storage::url('poster/'.$moviePlan->poster)}}" class="d-block rounded w-100" alt="">
                                    </div>
                                    <div class="">
                                        <div class="d-flex align-items-center">
                                            <h5 class="fw-bold mb-1">{{$moviePlan->movie_name}}</h5>
                                        </div>
                                        <div class="mt-2">
                                            <label class="text-muted small">Cinema</label>
                                            <h6 class="mt-1 mb-0">{{$moviePlan->cinema_name}}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3 pt-2 border-top">
                                    <div class="col-sm-6 mb-2">
                                        <label class="text-muted small">Date</label>
                                        <h6 class="mt-1 mb-0">{{Carbon::parse($moviePlan->date)->format('l, F j, Y')}}</h6>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <label class="text-muted small">Time</label>
                                        <h6 class="mt-1 mb-0">{{Carbon::parse($moviePlan->start_time)->format('h:i A')}}</h6>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-6 mb-2">
                                        <label class="text-muted small">Hall</label>
                                        <h6 class="mt-1 mb-0">{{$moviePlan->hall_name}}</h6>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <label class="text-muted small">Selected Seats</label>
                                        <h6 class="mt-1 mb-0 text-warning">
                                            @foreach ($seat as $item)
                                                {{$item->seat_name}}{{ !$loop->last ? ',' : '' }}
                                            @endforeach
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Left Column on Desktop: Payment & Forms -->
                <div class="col-lg-7 order-lg-1">

                    <!-- Amount Payable -->
                    <div id="booking-total" class="mb-4 p-3 rounded shadow-sm border border-secondary">
                        <h5 class="mb-3 border-bottom pb-2">Amount Payable</h5>
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mt-2 booking-total-content">
                                <div class="d-flex align-items-center pe-3">
                                    <span class="me-1">{{count($seat)}}</span>
                                    <label>Ticket(s)</label>
                                </div>
                                <label class="d-flex align-items-center"><span id="seatTotalPrice">{{$totalPrice}}</span> Kyats</label>
                            </div>
                            @if ($website->payment_type)
                                <div id="transaction_fee_section" class="d-flex align-items-center justify-content-between mt-2 booking-total-content">
                                    <div class="text-muted">Transaction Fee</div>
                                    <div class="transaction_fee">0 Kyats</div>
                                </div>
                            @endif
                            <div id="promotion" class="m-0"></div>
                        </div>
                        <hr class="border-secondary">
                        <div class="d-flex align-items-center justify-content-between mt-2" id="total-price">
                            <label class="fw-bold fs-6">Total</label>
                            <label class="fw-bold fs-6 text-warning"><span id="subTotal" class="me-1">{{$totalPrice}}</span> Kyats</label>
                        </div>
                    </div>

                    <!-- Promotion Code -->
                    <div id="promo-code" class="mb-4 p-3 rounded shadow-sm border border-secondary">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Promotion Code</h5>
                            <i class="fa-solid fa-circle-plus promo-icon fs-5 text-warning" role="button"></i>
                        </div>
                        <div id="promo-form" class="mt-3">
                            <hr class="border-secondary">
                            <div id="promo_alert"></div>
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <div class="form-group w-75">
                                    <input type="text" id="promo_code" class="form-control" placeholder="Enter promo code">
                                </div>
                                <button class="btn btn-warning w-25" id="add_promotion">Apply</button>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div id="payment-option" class="mb-4 p-3 rounded shadow-sm border border-secondary">
                        <h5 class="mb-3 border-bottom pb-2">Payment Method</h5>
                        <div class="payment_alert"></div>
                        @if ($moviePlan->online_buy==1)
                            @if ($website->payment_type)
                                @if ($moviePlan->online_book==1)
                                    <div class="px-2 mt-3 parents">
                                        <div class="payment">
                                            <div class="custom-radio d-flex align-items-center mb-1">
                                                <input type="radio" id="paymentType" name="paymentType" value="booking" class="payment-radio" checked>
                                                <label class="ms-3" for="radio1">Booking</label>
                                            </div>
                                        </div>
                                        <hr class="border-secondary">
                                    </div>
                                @endif
                                <div class="px-2 mt-3 parents">
                                    <div class="payment">
                                        <div class="custom-radio d-flex align-items-center mb-1">
                                            <input type="radio" id="paymentType" name="paymentType" value="mmqr" class="payment-radio">
                                            <label class="ms-3" for="radio1">MMQR ( Kpay , Wave Pay , AYA Pay , uabpay ,..... )</label>
                                        </div>
                                    </div>
                                    <hr class="border-secondary">
                                </div>
                                <div class="px-2 mt-3 parents">
                                    <div class="payment">
                                        <div class="custom-radio d-flex align-items-center mb-1">
                                            <input type="radio" id="paymentType" name="paymentType" value="visa_master" class="payment-radio">
                                            <label class="ms-3" for="radio1">Visa and Master Card</label>
                                        </div>
                                    </div>
                                    <hr class="border-secondary">
                                </div>
                                <div class="px-2 mt-3 parents">
                                    <div class="payment">
                                        <div class="custom-radio d-flex align-items-center mb-1">
                                            <input type="radio" id="paymentType" name="paymentType" value="upi" class="payment-radio">
                                            <label class="ms-3" for="radio1">UPI</label>
                                        </div>
                                    </div>
                                    <hr class="border-secondary">
                                </div>
                                <div class="px-2 mt-3 parents">
                                    <div class="payment">
                                        <div class="custom-radio d-flex align-items-center mb-1">
                                            <input type="radio" id="paymentType" name="paymentType" value="mpu" class="payment-radio">
                                            <label class="ms-3" for="radio1">MPU</label>
                                        </div>
                                    </div>
                                    <hr class="border-secondary">
                                </div>
                                <div class="px-2 mt-3 parents">
                                    <div class="payment">
                                        <div class="custom-radio d-flex align-items-center mb-1">
                                            <input type="radio" id="paymentType" name="paymentType" value="uabpay" class="payment-radio">
                                            <label class="ms-3" for="radio1">uabpay</label>
                                        </div>
                                    </div>
                                    <hr class="border-secondary">
                                </div>
                            @else
                                @foreach ($payment as $item)
                                    @if ($item->id==2 && $moviePlan->online_book==1)
                                        <div class="px-2 mt-3 parents">
                                            <div class="payment">
                                                <div class="custom-radio d-flex align-items-center mb-1">
                                                    <input type="radio" id="paymentMethod" name="paymentMethod" value="{{$item->id}}" class="payment-radio" data-target=".payment-content-{{$item->id}}" @if ($loop->index==0) checked @endif>
                                                    <label class="ms-3">{{$item->name}}</label>
                                                </div>
                                            </div>
                                            <hr class="border-secondary">
                                        </div>
                                    @elseif($item->id!=2)
                                        <div class="px-2 mt-3 parents">
                                            <div class="payment">
                                                <div class="custom-radio d-flex align-items-center mb-1">
                                                    <input type="radio" id="paymentMethod" name="paymentMethod" value="{{$item->id}}" class="payment-radio" data-target=".payment-content-{{$item->id}}">
                                                    <label class="ms-3">{{$item->name}}</label>
                                                </div>
                                                <div class="payment-content payment-content-{{$item->id}} mt-2 p-2 bg-dark rounded">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <strong>{{$item->name}} : </strong>
                                                        <label class="mx-3 mb-0">{{$item->number}}</label>
                                                        <div>
                                                            <button class="btn btn-sm btn-outline-primary py-0 px-2" id="copyBtn" onclick="copyToClipboard('{{$item->number}}')">Copy</button>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <strong>Name : </strong>
                                                        <label class="ms-3 mb-0">{{$item->username}}</label>
                                                    </div>
                                                    @if ($item->image!=null)
                                                        <div class="d-flex justify-content-center py-2 align-items-center">
                                                            <img src="{{Storage::url('payment/'.$item->image)}}" class="w-50 img-thumbnail" alt="">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <hr class="border-secondary">
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        @else
                            <div class="px-2 py-2 text-white" style="font-size:10pt;">This Movie Time is available for booking only.</div>
                        @endif
                    </div>

                    <!-- Contact Information & Verification -->
                    <div id="user-contact-info" class="mb-4 p-3 rounded shadow-sm border border-secondary">
                        <h5 class="mb-3 border-bottom pb-2">Contact Information</h5>
                        <div class="d-none justify-content-center mt-4 text-center" id="verifyLoading">
                            <div id="loading-content">
                                <div class="spinner-border text-warning" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2" style="font-size: 9pt;">Please Wait</p>
                            </div>
                        </div>
                        <div id="user_alert" class='d-none px-3'></div>
                        <form action="" class="mt-3" id="send_form">
                            <div class="">
                                <div class="row form-group">
                                    <div class="col-sm-6 mb-3">
                                        <label for="">Name*</label>
                                        <input type="text" id="userName" class="form-control" value="{{old('user_name')}}" placeholder="Enter name">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label for="">Phone*</label>
                                        <input type="text" id="userPhone" class="form-control" value="{{old('user_phone')}}" placeholder="Enter Phone - 09 *********">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="">Email*</label>
                                    <input type="email" id="userEmail" class="form-control" value="{{old('user_email')}}" placeholder="Enter email">
                                </div>
                            </div>
                            <button class="btn btn-warning w-100 mt-2 fw-bold" type="button" id="verify">Get OTP <span id="otp_count"></span></button>
                        </form>

                        <div id="OTP" class="card bg-dark text-light border-secondary shadow-sm p-4 mb-3">
                            <h5 class="mb-3 border-bottom border-secondary pb-2">Verify Contact Info</h5>
                            <div class="d-none justify-content-center mt-3 text-center" id="orderLoading">
                                <div id="loading-content">
                                    <div class="spinner-border text-warning" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2" style="font-size: 8pt;">Please Wait</p>
                                </div>
                            </div>

                            <form method="post" action="@if ($website->payment_type) {{route('customer#order')}} @else {{route('customer#manualOrder')}} @endif" enctype="multipart/form-data" id="order-form">
                                @csrf
                                @if ($moviePlan->online_buy==1 && $website->payment_type!=1)
                                    <div id="payment_slip" class="@if($moviePlan->online_book==1) d-none @endif mb-4 p-3 bg-secondary bg-opacity-10 rounded border border-secondary">
                                        <div class="d-flex align-items-center mb-3">
                                            <label class="mb-0">To confirm your payment, please upload a picture of your payment receipt.</label>
                                            <i class="fa-regular fa-hand ms-2 text-warning"></i>
                                        </div>
                                        <div class="payment_alert"></div>

                                        <div class="mb-3 text-center">
                                            <img src="{{asset('image/default.png')}}" id="output" class="img-fluid rounded border border-secondary" style="max-height: 180px; object-fit: contain;">
                                        </div>

                                        <div class="d-grid">
                                            <label for="file" class="btn btn-outline-light btn-sm cursor-pointer mb-0">
                                                <span><i class="fa-solid fa-camera me-1 text-warning"></i></span>
                                                <span>Upload Receipt Image</span>
                                            </label>
                                            <input type="file" name="slip" id="file" class="d-none" accept="image/*" onchange="loadFile(event);">
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

                                <div id="otp_alert" class="px-0"></div>

                                <div class="otp-form mb-3">
                                    <label for="otp_input" class="form-label">OTP*</label>
                                    <input type="text" class="form-control bg-dark text-light border-secondary" id="otp_input" placeholder="Enter OTP number">
                                </div>

                                <section class="mb-3">
                                    @if($errors->has('g-recaptcha-response'))
                                        <div class="alert alert-danger alert-dismissible fade show my-2" style="font-size: 10pt;" role="alert">
                                            {{ $errors->first('g-recaptcha-response') }}
                                        </div>
                                    @endif

                                    {!! NoCaptcha::renderJs() !!}

                                    <div class="custom-recaptcha-wrapper d-flex justify-content-center my-2">
                                        {!! NoCaptcha::display() !!}
                                    </div>
                                </section>
                            </form>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                @if ($moviePlan->online_buy==1)
                                    <button class="btn btn-warning fw-bold px-4 d-none" type="button" id="order">Buy Ticket</button>
                                @endif
                                @if ($moviePlan->online_book==1)
                                    <button class="btn btn-warning fw-bold px-4" type="button" id="book">Book Ticket</button>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- payment section end -->

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
                    $('#promotion').html(
                        `<div class="d-flex align-items-center justify-content-between mt-2" id="booking-total-content">
                            <div class="d-flex align-items-center text-success">
                                <span>${promotion.name}</span>
                            </div>
                            <label class="text-success"><span id="seatTotalPrice">- ${promotion_price}</span> Kyats</label>
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
                            Your promo code is wrong or promotion period is over. Please check and try again.
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
                                    ! Please check OTP code in Gmail. Thank you. (If not notified check in spam mail). It can take 1 min. Thank you for your patience.
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
                    Please Fill User Info Completely
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

    const twoMinutesThirtySeconds = 1 * 60;
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
                $('#subTotal').html($price+$transactionfee);
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
