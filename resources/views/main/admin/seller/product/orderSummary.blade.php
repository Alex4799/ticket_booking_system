@extends('main.admin.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

<section class="container">
    <div class="pt-2">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{route('seller#productList')}}">List</a></li>
              <li class="breadcrumb-item active" aria-current="page">Summary</li>
            </ol>
        </nav>
    </div>
    <div class="row mb-5">
        <div class="col-md-6 offset-md-3">
            <div id="booking-summary" class="p-2 rounded">
                <h5>Order Summary</h5>
                <div class="px-1 summary-content">
                    <div class="py-2">
                        <h6 class="mt-1">Order Code : PID - {{$order->id}}</h6>
                    </div>
                    <div id="order-date">
                        <h6 class="mt-1">Order Date : PID - {{$order->created_at}}</h6>
                    </div>
                    <div class=" py-2">
                        <h6 class="mt-1" >Cinema : {{$order->cinema_name}}</h6>
                    </div>
                    <div class="mt-3">
                        <div class=" d-flex gap-2 flex-wrap mt-1">
                            <table class="table table-primary" style="font-size: 11pt;">
                                <thead class="text-dark">
                                  <tr>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Price</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->product as $item)
                                        <tr>
                                            <td>{{$item['product_name']}}</td>
                                            <td>{{$item['qty']}}</td>
                                            <td>{{$item['qty']*$item['product_price']}} MMK</td>
                                        </tr>
                                    @endforeach
                                    @if ($order->promotion_id!=null)
                                        <tr>
                                            <td colspan="2">{{$order->promotion_name}}</td>
                                            @if ($order->promotion_amount==null)
                                                <td>- {{$order->promotion_percentage}} %</td>
                                            @else
                                                <td>- {{$order->promotion_amount}} MMK</td>
                                            @endif
                                        </tr>
                                    @endif
                                    <tr class="table-active">
                                        <td colspan="2">Total</td>
                                        <td>{{$order->total_price}} MMK</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 py-2">
                            <label>Cost</label>
                            <h6 class="mt-1" style="font-size: 12pt;">{{$order->total_price}} Kyats</h6>
                        </div>
                        @if ($order->payment_method==1)
                            <div class="col-sm-4 py-2">
                                <label>Pay Amount</label>
                                <h6 class="mt-1" style="font-size: 12pt;">{{$order->pay_amount}} Kyats</h6>
                            </div>
                            <div class="col-sm-4 py-2">
                                <label>Change</label>
                                <h6 class="mt-1" style="font-size: 12pt;">{{$order->change}} Kyats</h6>
                            </div>
                        @endif
                    </div>
                    <div class="mt-2">
                        <label>Status</label>
                        <h6>
                            @if ($order->status==0)
                                <span class=" text-warning">Pending</span>
                            @elseif ($order->status==1)
                                <span class=" text-success">Success</span>
                            @elseif ($order->status==2)
                                <span class=" text-danger">Reject</span>
                            @elseif ($order->status==3)
                                <span class=" text-danger">Cancel</span>
                            @endif
                        </h6>
                    </div>
                    @if ($order->ticket_status==1)
                        <div class="py-2 d-flex justify-content-end">
                            <button class="btn btn-secondary">Invoice Generated</button>
                        </div>
                    @else
                        <div class="py-2 d-flex justify-content-end">
                            <a href="{{route('seller#productOrderGetInvoice',$order->id)}}" target="_blank" class="btn btn-success">Generate Invoice</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('script')

@endsection
