@extends('main.customer.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

<section class="container-lg">
    <div class="py-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="font-size: 10pt;" role="alert">
                {!!session('success')!!}
            <button type="button" class="btn-close border-0 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" style="font-size: 10pt;" role="alert">
                {{session('warning')}}
            <button type="button" class="btn-close border-0 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('danger'))
            <div class="alert alert-danger alert-dismissible fade show" style="font-size: 10pt;" role="alert">
                {{session('danger')}}
            <button type="button" class="btn-close border-0 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger py-3 alert-dismissible fade show" style="font-size: 10pt;" role="alert">
                    {{ $error }}
                    <button type="button" class="btn-close border-0 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
        @endif
    </div>
    <div class="row">
        <div class="d-flex justify-content-end mt-2 mb-4">
            <button id="download-screenshot" class="btn px-2 py-1"><i class="fa-solid fa-download me-2"></i>Download Screenshot</button>
        </div>
        <div class="col-md-6 offset-md-3" id="capture-area">
            <div id="booking-summary">
                <h5>Ticket Summary</h5>
                <div class="px-1 summary-content">
                    <div class="item-right">
                        <span class="up-border"></span>
                        <span class="down-border"></span>
                    </div>
                    <div class="">
                        <div class="mb-4">
                            <img src="{{Storage::url('poster/'.$order->poster)}}" class="d-block rounded" alt="image">
                            <h5 class="fw-bold text-center mt-3">{{$order->movie_name}}</h5>
                        </div>
                        <div class="border-dotted">
                            <span>Customer Information</span>
                            <div class="mt-2">
                                <div class="py-1">
                                    <label>Name</label>
                                    <h6 class="mt-1">{{$order->user_name}}</h6>
                                </div>
                                <div class="py-1">
                                    <label>Email</label>
                                    <h6 class="mt-1">{{$order->user_email}}</h6>
                                </div>
                                <div class="py-1">
                                    <label>Phone</label>
                                    <h6 class="mt-1">{{$order->user_phone}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="border-dotted">
                            <span>Ticket Information</span>
                            <div class="mt-2">
                                <div class="py-2">
                                    <label class="me-3">Order Date</label>
                                    <h6 class="mt-1">{{$order->created_at}}</h6>
                                </div>
                                <div class="py-2">
                                    <label class="me-3">Cinema</label>
                                    <h6 class="mt-1">{{$order->cinema_name}}</h6>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-6 mb-2">
                                        <label>Movie Date</label>
                                        <h6 class="mt-1">{{Carbon::parse($order->date)->format('l, F j, Y')}}</h6>
                                    </div>
                                    <div class="col-sm-6 ps-sm-5 mb-2">
                                        <label>Time</label>
                                        <h6 class="mt-1">{{Carbon::parse($order->start_time)->format('h:i A')}}</h6>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label>Hall</label>
                                    <h6 class="mt-1">{{$order->hall_name}}</h6>
                                </div>
                                <div class="pt-2">
                                    <label>Selected Seats</label>
                                    <div class=" d-flex gap-2 flex-wrap mt-1">
                                        <table class="table table-primary" style="font-size: 10pt;">
                                            <thead>
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Plan</th>
                                                <th scope="col" class="text-center">Price(Kyats)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (json_decode($order->seats) as $item)
                                                    <tr>
                                                        <td>{{$item->seat_name??''}}</td>
                                                        <td>{{$item->seat_plan??''}}</td>
                                                        <td class="text-end">{{number_format($item->seat_price)}}</td>
                                                    </tr>
                                                @endforeach
                                                @if ($order->promotion_id!=null)
                                                    <tr>
                                                        <td colspan="2">{{$order->promotion_name}}</td>
                                                        @if ($order->amount==null)
                                                            <td class="text-end">- {{$order->percentage}} %</td>
                                                        @else
                                                            <td class="text-end">- {{number_format($order->amount)}}</td>
                                                        @endif
                                                    </tr>
                                                @endif
                                                <tr class="table-active">
                                                    <td colspan="2">Total</td>
                                                    <td class="text-end fw-bold">{{number_format($order->total_price)}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 py-2">
                                        <label>Cost</label>
                                        <h6 class="mt-1">{{number_format($order->total_price)}} Kyats</h6>
                                    </div>
                                    <div class="col-sm-8 py-2">
                                        <div class="d-flex align-items-center">
                                            <div class="me-5">
                                                <label>Booking Code</label>
                                                <h6 class="mt-1">OID - {{$order->id}}</h6>
                                            </div>
                                            <div class="hall-sm ms-5">
                                                <label>Status</label>
                                                <h6 class="mt-1">
                                                     @if ($order->status==0)
                                                        <span class=" text-warning">Pending</span>
                                                    @elseif ($order->status==1)
                                                        <span class=" text-success">Success</span>
                                                    @elseif ($order->status==2)
                                                        <span class=" text-danger">Reject</span>
                                                    @elseif ($order->status==3)
                                                        <span class=" text-danger">Cancel</span>
                                                    @elseif ($order->status==4)
                                                        <span class=" text-warning">Booking</span>
                                                    @endif
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($order->status==0)
                                    {{-- <div class="py-2 d-flex justify-content-end">
                                        <button class="btn btn-danger" type="button" id="verify">Order Cancel</button>
                                    </div> --}}
                                @endif
                                @if ($order->status==4)
                                    @if ($order->online_buy==1)
                                        <div class="mt-4 d-flex justify-content-end">
                                            <button class="btn btn-success" style="font-size: 11pt;" type="button" data-bs-toggle="modal" data-bs-target="#payment">
                                                <i class="fa-solid fa-mobile-screen-button me-1"></i>
                                                Finish Payment
                                            </button>
                                        </div>
                                    @endif
                                    <div class="modal fade" id="payment" tabindex="-1" aria-labelledby="paymentLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-nav-bar">
                                                <div class="modal-header d-flex align-items-center justify-content-between">
                                                    <h1 class="modal-title fs-6" id="paymentLabel">Finish Payment</h1>
                                                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                                </div>
                                                <form action="{{route('customer#orderFinish')}}" method="post" class="p-3" enctype="multipart/form-data">
                                                    @csrf
                                                    @if ($website->payment_type)
                                                        <div>
                                                            <label class="mb-1">Payment Method</label>
                                                            <select name="payment_type" class="form-select">
                                                                <option value="">Choose Payment</option>
                                                                <option value="mmqr">MMQR ( Kpay , Wave Pay , AYA Pay , UAB Pay ,..... )</option>
                                                                <option value="visa_master">Visa and Master Card</option>
                                                                <option value="upi">UPI</option>
                                                                <option value="mpu">MPU</option>
                                                                <option value="uabpay">UAB Pay</option>
                                                            </select>
                                                        </div>
                                                    @else
                                                        <div id="finish-payment-select">
                                                            <div>
                                                                <label class="mb-2">Payment Method</label>
                                                                <select name="payment_method" id="payment_method" class="form-select">
                                                                    <option value="">Choose Payment</option>
                                                                    @foreach ($payment as $item)
                                                                        @if ($item->id!=2)
                                                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="payment-details mt-2 mb-5" id="payment_container">
                                                                @foreach  ($payment as $item)
                                                                    <div class="payment_info d-none" id="{{$item->id}}info">
                                                                        <div class="row align-items-center">
                                                                            <div class="col-md-6">
                                                                                <div class="info-item">
                                                                                    <label>Payment Type</label>
                                                                                    <h6>{{$item->name}}</h6>
                                                                                </div>
                                                                                <div class="info-item">
                                                                                    <label>Phone Number</label>
                                                                                    <h6>{{$item->number}}</h6>
                                                                                </div>
                                                                                <div class="info-item">
                                                                                    <label>Account Name</label>
                                                                                    <h6>{{$item->username}}</h6>
                                                                                </div>
                                                                            </div>
                                                                            @if($item->image!=null)
                                                                                <div class="col-md-6 text-center">
                                                                                    <img src="{{Storage::url('payment/'.$item->image)}}" class="payment-qr">
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="d-none" id="payment_slip">
                                                        <label for="" >Payment Slip <br> (ငွေပေးချေမှု အတည်ပြုနိုင်ရန် ငွေလွှဲပြေစာပုံကို ဤနေရာတွင် တင်ပေးပါ။)</label>
                                                        <div id="payment-picture">
                                                            <div class="w-100">
                                                                <img src="{{asset('image/placeholder.webp')}}" id="output" alt="image">
                                                            </div>
                                                            <label for="file">
                                                                <span><i class="fa-solid fa-cloud-arrow-up me-1"></i></span>
                                                                <span>Upload Image</span>
                                                            </label>
                                                            <input type="file" name="slip" id="file" accept="image/*" onchange="loadFile(event);">
                                                        </div>
                                                    </div>

                                                    <div class="mt-5">
                                                        {!! NoCaptcha::renderJs() !!}

                                                        <div class="custom-recaptcha-wrapper">
                                                            {!! NoCaptcha::display() !!}
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="id" value="{{$order->id}}">

                                                    <div class="my-3">
                                                        <button type="submit" class="btn w-100" id="ticket-btn">Pay</button>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-center mx-0 my-5" id="selected-movie-label">
            <a href="{{route('customer#home')}}">
                <button class="btn py-2" style="font-size: 10pt;"><i class="fa-solid fa-house me-1"></i>Return Home</button>
            </a>
            <span class="mx-3 mb-2">  > </span>
            <a href="{{route('customer#checkOrderPage')}}">
                <button class="btn py-2" style="font-size: 10pt;"><i class="fa-solid fa-ticket me-1"></i>Ticket Status Check</button>
            </a>
        </div>
    </div>
</section>

@endsection
@section('script')
    @if ($order->status==4 && $website->payment_type==0)
        <script>
            $(document).ready(function(){
               $('#payment_method').change(function(){
                    let id = $(this).val();
                    $('.payment_info').addClass('d-none');
                    $('#payment_slip').addClass('d-none');

                    if(id != ''){
                        $(`#${id}info`).removeClass('d-none');
                        $('#payment_slip').removeClass('d-none');
                    }
                });
            })
        </script>
    @endif

    <script>
        $('#download-screenshot').click(function () {;
            const element = document.getElementById('capture-area');
            html2canvas(element).then(function (canvas) {
                const dataURL = canvas.toDataURL('image/png');
                const link = document.createElement('a');
                link.href = dataURL;
                link.download = 'orderscreenshot.png';
                link.click();
            });
        });
        const element = document.getElementById('capture-area');
        html2canvas(element).then(function (canvas) {
            const dataURL = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.href = dataURL;
            link.download = 'orderscreenshot.png';
            link.click();
        });
    </script>
@endsection
