@extends('main.admin.layout.master')
@section('title')
    Order
@endsection
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp
<main class="content px-3 py-3 mb-5">
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
            <div class="col-md-10 offset-md-1 card border-0 shadow p-2">
                <div class="card-body">
                    <h4 class="py-2 text-center">Order Detail</h4>
                    <div class="d-flex justify-content-around flex-wrap gap-1">
                        <div class="py-2">
                            <div class="py-2">
                                Order ID - {{$order->id}}
                            </div>
                            <div class="py-2">
                                Total Price - {{$order->total_price}} MMK
                            </div>
                            <div class="py-2">
                                Payment Method - {{$order->payment_name}}
                            </div>
                            @if ($order->payment_method==1)
                                <div class="py-2">
                                    Pay Amount - {{$order->pay_amount}} Kyats
                                </div>
                                <div class="py-2">
                                    Change - {{$order->change}} Kyats
                                </div>
                            @endif
                        </div>
                        <div class="py-2">
                            {{-- <div class="py-2">
                                User Name - {{$order->user_name}}
                            </div>
                            <div class="py-2">
                                User Email - {{$order->user_email}}
                            </div>
                            <div class="py-2">
                                User Phone - {{$order->user_phone}}
                            </div> --}}
                            <div class="py-2">
                                Cinema - {{$order->cinema_name}}
                            </div>
                            <div class="py-2">
                                Order Date - {{$order->created_at}}
                            </div>
                            <div class="py-2">
                                Status - <span style="font-size: 11pt;" class="@if ($order->status==0)
                                                text-dark bg-warning px-3 py-2
                                            @elseif ($order->status==1)
                                                text-white bg-success px-3 py-2
                                            @elseif ($order->status==2)
                                                text-white bg-danger px-3 py-2
                                            @elseif ($order->status==3)
                                                text-white bg-danger px-3 py-2
                                            @elseif ($order->status==4)
                                                text-dark bg-warning px-3 py-2
                                            @endif">
                                            @if ($order->status==0)
                                                Pending
                                            @elseif ($order->status==1)
                                                Success
                                            @elseif ($order->status==2)
                                                Reject
                                            @elseif ($order->status==3)
                                                Cancel
                                            @elseif ($order->status==4)
                                                Booking
                                            @endif
                                        </span>
                            </div>
                            <div class="d-flex gap-1 py-2">
                                @if ($order->status!=2)
                                    <div class="ms-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#finishPayment">Change</button>
                                    </div>
                                @endif
                                @if ($order->status==1)
                                    @if (Auth::user()->position=='product-seller')
                                        @if ($order->ticket_status==1)
                                            <div class="">
                                                <button class="btn btn-secondary">Invoice Generated</button>
                                            </div>
                                        @else
                                            <div class="">
                                                <a href="{{route('admin#invoiceProduct',$order->id)}}" target="_blank" class="btn btn-success">Generate Invoice</a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="">
                                            <a href="{{route('admin#invoiceProduct',$order->id)}}" target="_blank" class="btn btn-success">Generate Invoice</a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="py-2">
                        <div class=" table-responsive">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">QTY</th>
                                    <th scope="col">Price</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productItem as $item)
                                        <tr>
                                            <td>{{$item->product_name}}</td>
                                            <td>{{$item->qty}}</td>
                                            <td>{{$item->product_price*$item->qty}} MMK</td>
                                        </tr>
                                    @endforeach
                                    @if ($order->promotion_id!=null)
                                        <tr>
                                            <td colspan="2">{{$order->promotion_name}}</td>
                                            @if ($order->amount==null)
                                                <td>- {{$order->promotion_percentage}} %</td>
                                            @else
                                                <td>- {{$order->promotion_amount}} MMK</td>
                                            @endif
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2">Total</td>
                                        <td>{{$order->total_price}} MMK</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        @if ($order->payment_slip!=null)
                            <div class="py-2 col-md-4 offset-md-4">
                                <img src="{{asset('storage/slip/'.$order->payment_slip)}}" class="w-100" alt="">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="finishPayment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="finishPaymentLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title d-block w-100 text-center" id="finishPaymentLabel">Finish Payment</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin#orderChangeStatusProduct')}}" method="post" enctype="multipart/form-data" class="parents">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="form-label mb-1">Stauts</label>
                            <select name="status" id="" class="form-control">
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
                        <div>
                            <input type="hidden" name="id" value="{{$order->id}}">
                        </div>
                        <div class="d-flex justify-content-end gap-1">
                            <button type="submit" class="btn btn-primary w-100">Finish</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
