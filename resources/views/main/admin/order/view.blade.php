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
                  <li class="breadcrumb-item"><a href="{{route('admin#orderList')}}">List</a></li>
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
                            @if ($order->payment_name=='UAB')
                                <div class="py-2">
                                    Payment Type - {{$order->payment_type}}
                                </div>
                                <div class="py-2">
                                    Transaction Id - {{$order->transaction_id}}
                                </div>
                            @endif
                            <div class="py-2">
                                Movie - {{$order->movie_name}}
                            </div>
                            <div class="py-2">
                                Date - {{Carbon::parse($order->date)->format('l, j, m, Y')}}
                            </div>
                            <div class="py-2">
                                Time - {{Carbon::parse($order->start_time)->format('h:i:A')}}
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
                            <div class="py-2">
                                User Name - {{$order->user_name}}
                            </div>
                            <div class="py-2">
                                User Email - {{$order->user_email}}
                            </div>
                            <div class="py-2">
                                User Phone - {{$order->user_phone}}
                            </div>
                            <div class="py-2">
                                Cinema - {{$order->cinema_name}}
                            </div>
                            <div class="py-2">
                                Hall - {{$order->hall_name}}
                            </div>
                            <div class="py-2">
                                Order Date - {{$order->created_at}}
                            </div>
                            <div class="py-2">
                                Update Date - {{$order->updated_at}}
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
                            <div class="d-flex align-items-center justify-content-end mt-5">
                                @if ($order->status==1)
                                    @if (Auth::user()->position=='ticket-seller')
                                        @if ($order->ticket_status==1)
                                            <div class="py-2 d-flex justify-content-end">
                                                <button class="btn btn-secondary">Invoice Generated</button>
                                            </div>
                                        @else
                                            <div class="py-2 d-flex justify-content-end">
                                                <a href="#" target="_blank" class="btn btn-success">Generate Invoice</a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="py-2 d-flex justify-content-end">
                                            <a href="#" target="_blank" class="btn btn-success">Generate Invoice</a>
                                        </div>
                                    @endif
                                @endif
                                @if ($order->status!=2)
                                    <div class="d-flex ms-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#finishPayment">Change</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class=" table-responsive">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th scope="col">Seat Name</th>
                                    <th scope="col">Plan</th>
                                    <th scope="col">Price</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach (json_decode($order->seats) as $item)
                                        <tr>
                                            <td>{{$item->seat_name??''}}</td>
                                            <td>{{$item->seat_plan??''}}</td>
                                            <td>{{$item->seat_price}} MMK</td>
                                        </tr>
                                    @endforeach
                                    @if ($order->promotion_id!=null)
                                        <tr>
                                            <td colspan="2">{{$order->promotion_name}}</td>
                                            <td>{{$order->promotion_price}} MMK</td>
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
                <div class="modal fade" id="finishPayment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="finishPaymentLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="finishPaymentLabel">Finish Payment</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#orderChangeStatus')}}" method="post" enctype="multipart/form-data" class="parents">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="name" class="form-label mb-1">Payment Method</label>
                                        <select name="payment_method" id="" class="form-control">
                                            @foreach ($payment as $item)
                                                @if ($item->id!=2)
                                                    <option value="{{$item->id}}" @if ($order->payment_method==$item->id) selected @endif>{{$item->name}} ({{$item->username}})</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
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
