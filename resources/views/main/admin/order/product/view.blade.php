@extends('main.admin.layout.master')
@section('title')
    Product Order
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
                  <li class="breadcrumb-item"><a href="{{route('admin#orderListProduct')}}">List</a></li>
                  <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-4 order-lg-2 mb-4">
                <div class="seller-order-detail">
                    <div class="title">
                        <div>
                            <div>
                                <i class="fa-solid fa-info-circle me-2"></i>
                                <span class="fw-bold">Order Details</span>
                            </div>
                            <span class="order-id">PID - {{$order->id}}</span>
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
                        <div class="movie-profile mb-4">
                            <div>
                                <i class="fa-solid fa-building me-2"></i>
                                {{$order->cinema_name}}
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
                            <div class="d-flex align-items-center border-bottom">
                                <h6>
                                    <i class="fa-solid fa-calculator me-2"></i>
                                    Summary
                                </h6>
                            </div>
                            <div class="p-2">
                                @if ($order->payment_method == 1)
                                    <div class="d-flex justify-content-between py-2">
                                        <label>Pay Amount</label>
                                        <strong>{{number_format($order->pay_amount)}} Ks</strong>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between py-2">
                                    <label>Total Price</label>
                                    <strong>{{number_format($order->total_price)}} Ks</strong>
                                </div>
                                @if ($order->payment_method == 1)
                                    <div class="d-flex justify-content-between py-2">
                                        <label>Change</label>
                                        <strong>{{number_format($order->change)}} Ks</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="method-box">
                            <div class="p-2">
                                <div class="mb-1">
                                    <label>Order Date</label>
                                    <p>{{Carbon::parse($order->created_at)->format('F j, Y - h:i A')}}</p>
                                </div>
                                <div>
                                    <label>Update Date</label>
                                    <p>{{Carbon::parse($order->updated_at)->format('F j, Y - h:i A')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 order-lg-1">
                <div class="seller-order-detail pb-4">
                    <div class="title flex-wrap gap-3 mb-3">
                        <div>
                            <div>
                                <i class="fa-solid fa-shopping-cart me-2"></i>
                                <span class="fw-bold">Product Items</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end gap-3">
                            @if ($order->status==1)
                                @if (Auth::user()->position=='ticket-seller')
                                    @if ($order->ticket_status==1)
                                        <div>
                                            <button class="btn btn-secondary generate-success disabled">
                                                <i class="fa-solid fa-square-check me-1 text-white"></i>
                                                Invoice Generated
                                            </button>
                                        </div>
                                    @else
                                        <div>
                                            <a href="{{route('admin#invoiceProduct',$order->id)}}" target="_blank" class="generate-invoice">
                                                <i class="fa-regular fa-file-lines me-2 text-white"></i>Generate Invoice
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div>
                                        <a href="{{route('admin#invoiceProduct',$order->id)}}" target="_blank" class="generate-invoice">
                                            <i class="fa-regular fa-file-lines me-2 text-white"></i>Generate Invoice
                                        </a>
                                    </div>
                                @endif
                            @endif
                            @if($order->status==0)
                                <button class="action-btn" data-bs-toggle="modal" data-bs-target="#finishPayment">
                                    <i class="fa-solid fa-pen-to-square me-2 text-white"></i>Change
                                </button>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div>
                            <div class="px-2">
                                <div class="row text-muted small fw-semibold px-2 pb-2 border-bottom">
                                    <div class="col-7">Products</div>
                                    <div class="col-2 text-center">Qty</div>
                                    <div class="col-3 text-end">Amount (Ks)</div>
                                </div>
                                <div class="my-2">
                                    @php
                                        $productItem = collect($productItem);
                                    @endphp

                                    @foreach ($productItem as $item)
                                        <div class="row align-items-center p-2 {{ !$loop->last ? 'border-bottom border-light' : '' }}">
                                            <div class="col-7">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-square me-3" style="font-size: 4pt;"></i>
                                                    <div>
                                                        <h6 class="mb-0" style="max-width: 100%; font-size: 15px;">
                                                            {{$item->product_name}}
                                                        </h6>
                                                        <div class="text-muted small">
                                                            {{number_format($item->product_price)}} Ks
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <h6 class="mb-0" style="font-size: 15px;">
                                                    {{$item->qty}}
                                                </h6>
                                            </div>
                                            <h6 class="col-3 text-end text-nowrap mb-0" style="font-size: 15px;">
                                                {{number_format( $item->product_price * $item->qty )}}
                                            </h6>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if ($order->promotion_id!=null)
                                <div class="discount-card">
                                    <div class="promo-name">
                                        <i class="fa-solid fa-tag me-1"></i> {{$order->promotion_name}}
                                    </div>
                                    <div class="promo-price">
                                        @if ($order->amount==null)
                                            - {{$order->promotion_percentage}} %
                                        @else
                                            - {{number_format($order->promotion_amount)}} Ks
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                                <strong>Total Price</strong>
                                <h5 class="mt-1 total-success" style="font-size: 13pt;">{{number_format($order->total_price)}} Ks</h5>
                            </div>
                        </div>
                        @if ($order->payment_slip!=null)
                            <div class="row mt-3">
                                <div class="col-sm-6 offset-sm-3">
                                    <img src="{{asset('storage/slip/'.$order->payment_slip)}}" class="w-100 img-thumbnail" alt="">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal fade" id="finishPayment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="finishPaymentLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="finishPaymentLabel">Update Order</h5>
                                <button type="button"  class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#orderChangeStatusProduct')}}" method="post" enctype="multipart/form-data" class="parents">
                                    @csrf
                                    <div class="update-btn-group">
                                        <div class="row">
                                            <div class="col-sm-12 form-group mb-4">
                                                <label class="mb-1">Status</label>
                                                <select name="status" id="" class="form-select">
                                                    @if (Auth::user()->position!='order-reject')
                                                        <option value="1" @if ($order->status==1) selected @endif>Success</option>
                                                        @if (Auth::user()->position=='developer')
                                                            <option value="2" @if ($order->status==2) selected @endif>Reject</option>
                                                            <option value="3" @if ($order->status==3) selected @endif>Cancel</option>
                                                        @endif
                                                    @else
                                                        <option value="2" @if ($order->status==2) selected @endif>Reject</option>
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
