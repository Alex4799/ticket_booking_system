@extends('main.admin.layout.master')
@section('title')
    Order Log
@endsection
@section('content')
<main class="content px-3 py-4">
    <div class="container-fluid">
        <div id="table-bg">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                <h4 class="py-2">Order Log</h4>
                <form action="" method="get" class="d-flex gap-1">
                    <div>
                        <input type="text" class="form-control" value="{{request('order_id')}}" name="order_id" placeholder="Enter order id">
                    </div>
                    <div>
                        <button class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
            <div class="my-3">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User Name</th>
                                <th>Order Id</th>
                                <th>Message</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderLog as $item)
                                <tr class="parents">
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->user_name}}</td>
                                    <td><a href="{{route('admin#orderView',$item->order_id)}}">OID - {{$item->order_id}}</a></td>
                                    <td>{{$item->message}}</td>
                                    <td>{{$item->created_at}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{$orderLog->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
