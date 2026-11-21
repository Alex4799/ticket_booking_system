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
        <div class="table-bg">
            <div>
                <div class="total-count-title">
                    <div class="d-flex align-items-center">
                        <div class="count-content">
                            <h5 class="mb-0">Total - {{$order->total()}}</h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <form action="" method="get" id="search-form">
                            <div class="search-cinema">
                                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                                <input placeholder="Search by OID" value="{{request('search_key')}}" name="search_key" type="text" class="input">
                            </div>
                        </form>
                        <a href="{{route('admin#orderList')}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                    </div>
                </div>
                <form method="get" action="" class="mt-3 bg-body-secondary rounded px-3">
                    <div class="pb-2 pt-3 d-flex gap-2 flex-wrap">
                        <div>
                            <select name="status" class="form-select" style="font-size: 11pt;">
                                <option value="">Order Status</option>
                                <option value="">All</option>
                                <option value="0" @if (request('status')==='0') selected @endif>Pending</option>
                                <option value="1" @if (request('status')==1) selected @endif>Success</option>
                                <option value="2" @if (request('status')==2) selected @endif>Reject</option>
                                <option value="3" @if (request('status')==3) selected @endif>Cancel</option>
                                <option value="4" @if (request('status')==4) selected @endif>Booking</option>
                            </select>
                        </div>
                        <div>
                            <select name="from" class="form-select" style="font-size: 11pt;">
                                <option value="">Order Source</option>
                                <option value="">All</option>
                                <option value="0" @if (request('from')==='0') selected @endif>Seller</option>
                                <option value="1" @if (request('from')==1) selected @endif>Online</option>
                            </select>
                        </div>
                        @if (Auth::user()->position=='CEO'||Auth::user()->position=='admin'||Auth::user()->position=='developer')
                            <div>
                                <select name="cinema" class="form-select" style="font-size: 11pt;">
                                    <option value="">Choose Cinema</option>
                                    <option value="">All</option>
                                    @foreach ($cinema as $item)
                                        <option value="{{$item->id}}"  @if (request('cinema')==$item->id) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div>
                            <select name="movie" class="form-select" style="font-size: 11pt;">
                                <option value="">Choose Movie</option>
                                <option value="">All</option>
                                @foreach ($movie as $item)
                                    <option value="{{$item}}" @if (request('movie')==$item) selected @endif>{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            <div>
                                <label class="label-small mb-0">Start Date</label>
                                <input type="date" style="font-size: 11pt;" name="start_date" class="form-control" value="{{request('start_date')}}">
                            </div>
                            <div>
                                <label class="label-small mb-0">End Date</label>
                                <input type="date" style="font-size: 11pt;" name="end_date" class="form-control" value="{{request('end_date')}}">
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end w-100 mb-2">
                            <button class="d-flex align-items-center btn btn-primary me-2" style="font-size: 10pt;"><i class="fa-solid fa-filter me-2" style="font-size: 8pt;"></i>Filter</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>OID</th>
                                <th>Movie</th>
                                <th class="text-center">Seat Count</th>
                                <th class="text-end">Total Price</th>
                                <th class="text-center">Date & Time</th>
                                <th class="text-center">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>OID - {{$item->id}}</td>
                                <td>{{$item->movie_name}}</td>
                                <td class="text-center">{{$item->seat_count}}</td>
                                <td class="text-end">{{number_format($item->total_price)}}</td>
                                <td class="text-center">{{Carbon::parse($item->created_at)->format('d/m/Y - h:i A')}}</td>
                                <td class="text-center">
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
                                    <a href="{{route('admin#orderView',$item->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
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
    var pusher = new Pusher('2b433f999a354310803a', {
    cluster: 'ap1'
    });
    var channel = pusher.subscribe('order-alert-channel');
    channel.bind('order-alert-event', function(data) {
        // location.reload();
        $order=`
        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="font-size: 11pt;">
            <a href="{{route('admin#orderList')}}" class="text-decoration-none">
                New order OID - ${data.order.id}.
            </a>
            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
        $('#order-alert').html($order);
    });
</script>
@endsection
