@extends('main.admin.layout.master')
@section('title')
    Order
@endsection
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp
<main class="content px-4 pt-3 mb-5">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
        <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
            <h5>Total - {{$order->total()}}</h5>
        </div>
        <div>
            <form action="" method="get">
                <div class="d-flex">
                    <input type="text" value="PID" disabled class="w-25 form-control">
                    <input type="text" name="search_key" value="{{request('search_key')}}" class="form-control">
                    <div>
                        <input type="submit" value="Search" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- <div class="py-2 d-flex justify-content-between gap-1 flex-wrap">
        <div>
            <label for="">Filter By Order Stauts</label>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  @if (request('status')==null)
                  All
                  @elseif (request('status')==0)
                    Pending
                  @elseif (request('status')==1)
                    Success
                  @elseif (request('status')==2)
                    Reject
                  @elseif (request('status')==3)
                    Cancel
                  @elseif (request('status')==4)
                    Booking
                  @endif
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{route('admin#orderList')}}">All</a></li>
                  <li><a class="dropdown-item" href="{{route('admin#orderList',['status'=>0])}}">Pending</a></li>
                  <li><a class="dropdown-item" href="{{route('admin#orderList',['status'=>1])}}">Success</a></li>
                  <li><a class="dropdown-item" href="{{route('admin#orderList',['status'=>2])}}">Reject</a></li>
                  <li><a class="dropdown-item" href="{{route('admin#orderList',['status'=>3])}}">Cancel</a></li>
                  <li><a class="dropdown-item" href="{{route('admin#orderList',['status'=>4])}}">Booking</a></li>
                </ul>
              </div>
        </div>
        <div>
            <label for="">Filter By Order From</label>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  @if (request('from')==null)
                  All
                  @elseif (request('from')==0)
                    Seller
                  @elseif (request('from')==1)
                    Online
                  @endif
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{route('admin#orderList')}}">All</a></li>
                  <li><a class="dropdown-item" href="{{route('admin#orderList',['from'=>0])}}">Seller</a></li>
                  <li><a class="dropdown-item" href="{{route('admin#orderList',['from'=>1])}}">Online</a></li>
                </ul>
              </div>
        </div>
    </div> --}}
    <div class="">
        <div id="table-bg">
            <div class="">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>PID</th>
                                <th>Total Price</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>PID - {{$item->id}}</td>
                                <td>{{$item->total_price}}</td>
                                <td>{{Carbon::parse($item->created_at)->format('d/m/Y')}}</td>
                                <td>
                                    @if ($item->status==0)
                                        <span class="text-warning">Pending</span>
                                    @elseif($item->status==1)
                                        <span class="text-success">Success</span>
                                    @elseif($item->status==2)
                                        <span class="text-danger">Reject</span>
                                    @elseif($item->status==3)
                                        <span class="text-danger">Cancel</span>
                                    @elseif($item->status==4)
                                        <span class="text-warning">Booking</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('admin#orderViewProduct',$item->id)}}" class="btn btn-outline-primary">See More</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{$order->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('script')
<script>
    Pusher.logToConsole = false;
    var pusher = new Pusher('4eba68c06c6803d7204c', {
    cluster: 'ap1'
    });
    var channel = pusher.subscribe('order-alert-channel');
    channel.bind('order-alert-event', function(data) {
        // location.reload();
        $order=`
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <a href="{{route('admin#orderList')}}" class=" text-decoration-none">
                New order PID - ${data.order.id}.
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
        $('#order-alert').html($order);
    });
</script>
@endsection
