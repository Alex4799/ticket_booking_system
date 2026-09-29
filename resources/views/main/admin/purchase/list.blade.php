@extends('main.admin.layout.master')
@section('title')
    Purchase
@endsection
@section('content')
<main class="content px-4 py-4 mb-5">
    <div class="row p-4 mb-4">
        @foreach ($summary as $item)
            <div class="col-md-6 py-2">
                <div class="shadow rounded p-4">
                    <h5 class=" py-1">Total @if ($item->status==0) Pending @elseif($item->status==1) Success @else Reject @endif Purchase Count</h5>
                    <div class="d-flex align-items-center gap-1">
                        <div class="shadow py-2 px-2 bg-primary rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-ticket fs-2 text-white"></i>
                        </div>
                        <h5 class="py-2">{{$item->order_count}}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6 py-2">
                <div class="shadow rounded p-4">
                    <h5 class=" py-1">Total @if ($item->status==0) Pending @elseif($item->status==1) Success @else Reject @endif Purchase Price</h5>
                    <div class="d-flex align-items-center gap-1">
                        <div class="shadow py-2 px-3 bg-primary rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-dollar-sign fs-2 text-white"></i>
                        </div>
                        <h5 class="py-2">{{number_format($item->total_price)}}</h5>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex align-items-center justify-content-between">
        <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
            <h5>Total - {{$purchase->total()}}</h5>
        </div>
        <form action="" id="search-form">
            <div class="search-cinema">
                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                <input placeholder="Search" type="search" name="search_key" value="{{request('search_key')}}" class="input" placeholder="Enter purchase ID">
            </div>
        </form>
        <div class="form-group">
            @if (Auth::user()->purchase)
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-purchase">Create Purchase</button>
            @endif
        </div>
    </div>
    <form action="" id="search-form-small" class="mb-3">
        <div class="search-cinema">
            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
            <input placeholder="Search" type="search" name="search_key" value="{{request('search_key')}}" class="input" placeholder="Enter purchase ID">
        </div>
    </form>
    <form method="get" action="" class="mt-2 bg-body-secondary rounded px-2">
        <div class="py-2 d-flex gap-1 flex-wrap py-2">
            <div>
                <select name="status" class="form-control" style="font-size: 11pt;">
                    <option value="">Filter By Purchase Stauts</option>
                    <option value="">All</option>
                    <option value="0" @if (request('status')==='0') selected @endif>Pending</option>
                    <option value="1" @if (request('status')==1) selected @endif>Success</option>
                    <option value="2" @if (request('status')==2) selected @endif>Reject</option>
                </select>
            </div>
            <div>
                <select name="product_name" class="form-control" style="font-size: 11pt;">
                    <option value="">Filter By Product</option>
                    <option value="">All</option>
                    @foreach ($purchaseHistory as $item)
                        <option value="{{$item->product_name}}" @if (request('product_name')===$item->product_name) selected @endif>{{$item->product_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap mt-1">
            <div class="d-flex gap-1 flex-wrap py-2">
                <div>
                    <label for="" style="font-size: 11pt;">Start Date</label>
                    <input type="date" style="font-size: 11pt;" name="start_date" class="form-control" value="{{request('start_date')}}">
                </div>
                <div>
                    <label for="" style="font-size: 11pt;">End Date</label>
                    <input type="date" style="font-size: 11pt;" name="end_date" class="form-control" value="{{request('end_date')}}">
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-end w-100 mb-2">
                <button class="btn btn-primary me-2" style="font-size: 11pt;">Filter</button>
                <a href="{{route('admin#purchaseList')}}" style="font-size: 11pt;" class="btn btn-danger">Clear</a>
            </div>
        </div>
    </form>
    <div class="">
        <div id="table-bg">
            <div class="">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>QTY</th>
                                <th>Total Price</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase as $item)
                            <tr>
                                <td>PID - {{$item->id}}</td>
                                <td>{{$item->product_name}}</td>
                                <td>{{$item->qty}}</td>
                                <td>{{$item->total_price}} MMK</td>
                                <td>{{$item->created_at}}</td>
                                <td>
                                    @if ($item->status==0)
                                        <span class="text-warning">Pending</span>
                                    @elseif($item->status==1)
                                        <span class="text-success">Success</span>
                                    @else
                                        <span class="text-danger">Reject</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('admin#purchaseView',$item->id)}}" class="btn btn-outline-primary">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{$purchase->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="create-purchase" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create-purchaseLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="create-purchaseLabel">Create Purchase</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#purchaseCreate')}}" method="post" class="mt-1">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="mb-1">Product</label>
                                <select name="product_name" id="" class="form-control @error('product_name') is-invalid @enderror">
                                    <option value="">Choose Product</option>
                                    @foreach ($product as $item)
                                        <option value="{{$item->name}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('product_name')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Supplier</label>
                                <select name="supplier_id" id="" class="form-control @error('supplier_id') is-invalid @enderror">
                                    <option value="">Choose Supplier</option>
                                    @foreach ($supplier as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Store</label>
                                <select name="store_id" id="" class="form-control @error('store_id') is-invalid @enderror">
                                    <option value="">Choose Store</option>
                                    @foreach ($store as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('store_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Qty</label>
                                <input id="" name="qty" type="number" class="form-control @error('qty') is-invalid @enderror" placeholder="Enter product qty">
                                @error('qty')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Price (For one item)</label>
                                <input id="" name="price" type="number" class="form-control @error('price') is-invalid @enderror" placeholder="Enter product price">
                                @error('price')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 my-2">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
