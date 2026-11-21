@extends('main.admin.layout.master')
@section('title')
    Recycle Bin
@endsection
@section('content')
<main class="content px-3 mb-5">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('admin#recycle')}}">List</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Seat Plan</li>
                </ol>
            </nav>
        </div>
        <div id="table-bg seat_plan">
            <h4 class="py-2 text-center">Seat Plan</h4>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
                    <h5>Total - {{$seatPlan->total()}}</h5>
                </div>
                <form action="" id="">
                    <div class="search-cinema">
                        <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                        <input placeholder="Search" type="search" name="search_key" value="{{request('search_key')}}" class="input">
                    </div>
                </form>
            </div>
            <div class="my-3">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th class="col-2">Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Deleted At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($seatPlan as $item)
                                <tr class="mx-auto">
                                    <td>{{$item->id}}</td>
                                    <td class="col-2">
                                        @if ($item->poster==null)
                                            <img src="{{asset('image/default.jpg')}}" id="image" class="w-100 img-thumbnail" alt="">
                                        @else
                                            <img src="{{Storage::url('seat_plan/'.$item->image)}}" class="w-100" alt="">
                                        @endif
                                    </td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->price}}</td>
                                    <td>{{$item->deleted_at}}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{route('admin#recycleSeatPlanRestore',$item->id)}}" style="font-size: 11pt;" class="btn btn-outline-primary">Restore</a>
                                            <a href="{{route('admin#recycleSeatPlanDelete',$item->id)}}" style="font-size: 11pt;" class="btn btn-outline-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{$seatPlan->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
