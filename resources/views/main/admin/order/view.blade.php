@extends('main.admin.layout.master')
@section('title')
    Ticket Order
@endsection
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('admin#orderList')}}">List</a></li>
                  <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-5 order-lg-2 mb-4">
                <div class="seller-order-detail">
                    <div class="title">
                        <div>
                            <i class="fa-solid fa-clapperboard me-2"></i>
                            <span class="fw-bold">{{$order->movie_name}}</span>
                        </div>
                    </div>
                    <div class="">
                        <div class="movie-profile mb-4">
                            <div>
                                <i class="fa-solid fa-calendar-day me-2"></i>
                                {{Carbon::parse($order->date)->format('l, F j, Y')}}
                            </div>
                            <div>
                                <i class="fa-solid fa-clock me-2"></i>
                                {{Carbon::parse($order->start_time)->format('h:i A')}}
                            </div>
                            <div>
                                <i class="fa-solid fa-building me-2"></i>
                                {{$order->cinema_name}}
                            </div>
                            <div>
                                <i class="fa-solid fa-door-open me-2"></i>
                                {{$order->hall_name}}
                            </div>
                        </div>
                        <div class="method-box">
                            <div class="d-flex align-items-center">
                                <h6>
                                    <i class="fa-solid fa-credit-card me-1"></i>
                                    Payment Method
                                </h6>
                                <h6>
                                    <span class="mx-2"> - </span>
                                    {{$order->payment_name}}
                                </h6>
                            </div>
                            @if($order->payment_name=='UAB')
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div>
                                        <label>Type</label>
                                        <p>{{$order->payment_type}}</p>
                                    </div>
                                    <div class="me-4">
                                        <label>Transaction ID</label>
                                        <p>{{$order->transaction_id}}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="method-box">
                            <h6 class="">
                                <i class="fa-solid fa-user me-1"></i>
                                Customer
                            </h6>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between flex-wrap gap-2">
                                    <div>
                                        <label>Name</label>
                                        <p>{{$order->user_name}}</p>
                                    </div>
                                    <div class="mb-2 me-4">
                                        <label>Phone</label>
                                        <p>{{$order->user_phone}}</p>
                                    </div>
                                </div>
                                <div class="">
                                    <label>Email</label>
                                    <p>{{$order->user_email}}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 order-lg-1">
                <div class="seller-order-detail">
                    <div class="title">
                        <div>
                            <div>
                                <i class="fa-solid fa-ticket me-2"></i>
                                <span class="fw-bold">Ticket Details</span>
                            </div>
                            <span class="order-id">OID - {{$order->id}}</span>
                        </div>
                        <div>
                            <h6>
                                @if ($order->status==0)
                                    <span class="status pending">Pending</span>
                                @elseif ($order->status==1)
                                    <span class="status success">Success</span>
                                @elseif ($order->status==2)
                                    <span class="status reject">Reject</span>
                                @elseif ($order->status==3)
                                    <span class="status reject">Cancel</span>
                                @elseif ($order->status==4)
                                    <span class="status booking">Booking</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                    <div>
                        <div>
                            <label>Selected Seats</label>
                            <div class="seat-card-wrapper mt-2">
                                @foreach (json_decode($order->seats) as $item)
                                    <div class="seat-card">
                                        <div class="seat-name">
                                            {{$item->seat_name??''}}
                                        </div>
                                        <div class="seat-plan">
                                            {{$item->seat_plan??''}}
                                        </div>
                                        <div class="seat-price">
                                            {{number_format($item->seat_price)}} Ks
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($order->promotion_id!=null)
                                <div class="discount-card">
                                    <div class="promo-name">
                                        <i class="fa-solid fa-tag me-1"></i> {{$order->promotion_name}}
                                    </div>
                                    <div class="promo-price">
                                        @if ($order->promotion_price==null)
                                            - {{$order->promotion_percentage}} %
                                        @else
                                            - {{number_format($order->promotion_price)}} Ks
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="payment-check">
                            @if ($order->payment_method==1)
                                <div>
                                    <label>Pay Amount</label>
                                    <h6 class="mt-1">{{number_format($order->pay_amount)}} Ks</h6>
                                </div>
                            @endif
                            <div>
                                <label>Total Price</label>
                                <h6 class="mt-1 total-success">{{number_format($order->total_price)}} Ks</h6>
                            </div>
                            @if ($order->payment_method==1)
                                <div>
                                    <label>Change</label>
                                    <h6 class="mt-1">{{number_format($order->change)}} Ks</h6>
                                </div>
                            @endif
                        </div>
                        <div class="date-check">
                            <div>
                                <label>Order Date</label>
                                <h6 class="mt-1 date">{{Carbon::parse($order->created_at)->format('F j, Y - h:i A')}}</h6>
                            </div>
                            <div>
                                <label>Update Date</label>
                                <h6 class="mt-1 date">{{Carbon::parse($order->updated_at)->format('F j, Y - h:i A')}}</h6>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end gap-3 mt-3">
                            @if($order->status==1)
                                <a href="{{route('admin#invoice',$order->id)}}" target="_blank" class="generate-invoice">
                                    <i class="fa-regular fa-file-lines me-2 text-white"></i>Generate Invoice
                                </a>
                            @endif
                            @if($order->status!=2)
                                <button class="action-btn" data-bs-toggle="modal" data-bs-target="#finishPayment">
                                    <i class="fa-solid fa-pen-to-square me-2 text-white"></i>Change
                                </button>
                            @endif
                        </div>

                        @if ($order->payment_slip!=null)
                            <div class="row mt-5">
                                <div class="col-sm-6 offset-sm-3">
                                    <img src="{{asset('storage/slip/'.$order->payment_slip)}}" class="w-100 img-thumbnail" alt="">
                                </div>
                            </div>
                        @endif

                        <div class="modal fade" id="finishPayment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="finishPaymentLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title d-block w-100 text-center" id="finishPaymentLabel">Update Order</h5>
                                        <button type="button"  class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin#orderChangeStatus')}}" method="post" enctype="multipart/form-data" class="parents">
                                            @csrf
                                            <div class="update-btn-group">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group mb-4">
                                                        <label class="mb-1">Payment Method</label>
                                                        <select name="payment_method" id="" class="form-select">
                                                            <option value="">Choose Payment</option>
                                                            <option value="1" @if ($order->payment_method==1) selected @endif>Cash</option>
                                                            @foreach ($payment as $item)
                                                                <option value="{{$item->id}}" @if ($order->payment_method==$item->id) selected @endif>{{$item->name}} ({{$item->username}})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group mb-4">
                                                        <label class="mb-1">Status</label>
                                                        <select name="status" id="" class="form-select" required>
                                                            <option value="">Choose Status</option>
                                                            <option value="1" @if ($order->status==1) selected @endif>Success</option>
                                                            @if (Auth::user()->position=='ticket-seller')
                                                                @if (Auth::user()->can_reject==1)
                                                                    <option value="2" @if ($order->status==2) selected @endif>Reject</option>
                                                                    <option value="3" @if ($order->status==3) selected @endif>Cancel</option>
                                                                @endif
                                                            @else
                                                                <option value="2" @if ($order->status==2) selected @endif>Reject</option>
                                                                <option value="3" @if ($order->status==3) selected @endif>Cancel</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div>
                                                    <input type="hidden" name="id" value="{{$order->id}}">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection


@section('script')
    <script>
        // updatebtn disabled check
        const forms = document.querySelectorAll('.update-btn-group');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input,select');
            const button = form.querySelector('.action-btn');
            const initialValues = {};
            inputs.forEach(input => {
                initialValues[input.name] = input.value;
            });
            function checkUpdateValueForBtn() {
                let hasChanged = false;
                inputs.forEach(input => {
                    if (input.value != initialValues[input.name]) {
                        hasChanged = true;
                    }
                });
                button.disabled = !hasChanged;
            }
            inputs.forEach(input => {
                input.addEventListener('input', checkUpdateValueForBtn);
                input.addEventListener('change', checkUpdateValueForBtn);
            });
            checkUpdateValueForBtn();
        });
    </script>
@endsection
