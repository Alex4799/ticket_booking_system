@extends('main.admin.layout.master')
@section('title')
    Inventory
@endsection
@section('content')
<main class="content px-4 py-4 mb-5">
    <div class="d-flex align-items-center justify-content-between">
        <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
            <h5>Total - {{$inventory->total()}}</h5>
        </div>
        <form action="" id="search-form">
            <div class="search-cinema">
                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                <input placeholder="Search" type="search" name="search_key" value="{{request('search_key')}}" class="input">
            </div>
        </form>
        @if (in_array(Auth::user()->position,['store-manager','developer']))
            <div class="form-group">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-inventory">Create</button>
            </div>
        @endif
    </div>
    <form action="" id="search-form-small" class="mb-3">
        <div class="search-cinema">
            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
            <input placeholder="Search" type="search" name="search_key" value="{{request('search_key')}}" class="input">
        </div>
    </form>
    <div class="">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              @if (request('filter')==null)
                All
              @elseif (request('filter')==0)
                Pending
              @elseif (request('filter')==1)
                Success
              @elseif (request('filter')==2)
                Reject
              @endif
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{route('admin#inventoryList')}}">All</a></li>
              <li><a class="dropdown-item" href="{{route('admin#inventoryList',['filter'=>0])}}">Pending</a></li>
              <li><a class="dropdown-item" href="{{route('admin#inventoryList',['filter'=>1])}}">Success</a></li>
              <li><a class="dropdown-item" href="{{route('admin#inventoryList',['filter'=>2])}}">Reject</a></li>
            </ul>
        </div>
    </div>
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
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventory as $item)
                            <tr>
                                <td>IID - {{$item->id}}</td>
                                <td>{{$item->product_name}}</td>
                                <td>{{$item->qty}}</td>
                                <td>{{$item->total_price}} MMK</td>
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
                                    <a href="{{route('admin#inventoryView',$item->id)}}" class="btn btn-outline-primary">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{$inventory->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="create-inventory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create-inventoryLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="create-inventoryLabel">Inventory</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#inventoryCreate')}}" method="post" class="mt-1">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="mb-1">Product</label>
                                <select name="product_id" id="" class="form-control @error('product_id') is-invalid @enderror">
                                    <option value="">Choose Product</option>
                                    @foreach ($storeItem as $item)
                                        <option value="{{$item->id}}">{{$item->product_name}} ({{$item->store_name}}) (Max - {{$item->qty}})</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Store</label>
                                <select name="receive_store_id" id="" class="form-control @error('receive_store_id') is-invalid @enderror">
                                    <option value="">Choose Store</option>
                                    @foreach ($store as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('receive_store_id')
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

                            <button type="submit" class="btn btn-primary w-100 my-2">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
