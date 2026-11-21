@extends('main.admin.layout.master')
@section('title')
    Order Log
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
                <div class="d-flex align-items-center justify-content-end">
                    <form action="" method="get" id="search-form">
                        <div class="search-cinema">
                            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                            <input placeholder="Search by OID" value="{{request('order_id')}}" name="order_id" type="text" class="input">
                        </div>
                    </form>
                    <a href="{{route('admin#orderLog')}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User Name</th>
                                <th>Order ID</th>
                                <th>Message</th>
                                <th class="text-center">Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderLog as $item)
                                <tr class="parents">
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->user_name}}</td>
                                    <td><a href="{{route('admin#orderView',$item->order_id)}}" class="text-decoration-none ps-0">OID - {{$item->order_id}}</a></td>
                                    <td>{{$item->message}}</td>
                                    <td class="text-center">{{Carbon::parse($item->created_at)->format('d/m/Y - h:i A')}}</td>
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
