@extends('main.admin.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp
<div class="content p-2">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('seller#cart')}}">Seller</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Summary</li>
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
                            <div class="d-flex align-items-center border-bottom">
                                <h6>
                                    <i class="fa-solid fa-calculator me-2"></i>
                                    Summary
                                </h6>
                            </div>
                            <div class="p-2">
                                <div class="d-flex justify-content-between py-2">
                                    <label>Total Price</label>
                                    <strong>{{number_format($order->total_price)}} Ks</strong>
                                </div>
                                @if ($order->payment_method == 1)
                                    <div class="d-flex justify-content-between py-2">
                                        <label>Pay Amount</label>
                                        <strong>{{number_format($order->pay_amount)}} Ks</strong>
                                    </div>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 order-lg-1 mb-4">
                <div class="seller-order-detail pb-4">
                    <div class="title flex-wrap gap-3 mb-3">
                        <div>
                            <div>
                                <i class="fa-solid fa-shopping-cart me-2"></i>
                                <span class="fw-bold">Product Items</span>
                            </div>
                        </div>
                        <div>
                            @if ($order->ticket_status==1)
                                <button class="btn btn-secondary generate-success disabled">
                                    <i class="fa-solid fa-square-check me-1 text-white"></i>
                                    Invoice Generated
                                </button>
                            @else
                                <a href="{{route('admin#invoiceProduct',$order->id)}}"  target="_blank" class="generate-invoice">
                                    <i class="fa-regular fa-file-lines me-2 text-white"></i>Generate Invoice
                                </a>
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
                                    @foreach ($order->product as $item)
                                        <div class="row align-items-center p-2 @if (!$loop->last) border-bottom border-light @endif">
                                            <div class="col-7">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-square me-3" style="font-size: 4pt;"></i>
                                                    <div>
                                                        <h6 class="mb-0" style="max-width: 100%; font-size: 15px;">
                                                            {{$item['product_name']}}
                                                        </h6>
                                                        <div class="text-muted small">
                                                            {{number_format($item['product_price'])}} Ks
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <h6 class="mb-0" style="font-size: 15px;">
                                                    {{$item['qty']}}
                                                </h6>
                                            </div>
                                            <h6 class="col-3 text-end mb-0" style="font-size: 15px;">
                                                {{number_format( $item['product_price'] * $item['qty'] )}}
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
                                        @if ($order->promotion_amount==null)
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
