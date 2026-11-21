@extends('main.admin.layout.master')
@section('title')
    Inventory
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div class="table-bg">
            <div>
                <div class="total-count-title">
                    <div class="d-flex align-items-center">
                        <div class="count-content">
                            <h5 class="mb-0">Total - {{$inventory->total()}}</h5>
                        </div>
                        @if (in_array(Auth::user()->position,['store-manager','developer']))
                            <div class="ms-3">
                                <button class="add-btn" data-bs-toggle="modal" data-bs-target="#create-inventory">
                                    <i class="fa-solid fa-square-plus me-2"></i>Add Inventory
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <button class="action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-eye-dropper me-2 text-white"></i>
                                @if (request('filter')==null)
                                    All Status
                                @elseif (request('filter')==0)
                                    Pending
                                @elseif (request('filter')==1)
                                    Success
                                @elseif (request('filter')==2)
                                    Reject
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{route('admin#inventoryList')}}">All</a></li>
                                <li><a class="dropdown-item" href="{{route('admin#inventoryList',['filter'=>0])}}">Pending</a></li>
                                <li><a class="dropdown-item" href="{{route('admin#inventoryList',['filter'=>1])}}">Success</a></li>
                                <li><a class="dropdown-item" href="{{route('admin#inventoryList',['filter'=>2])}}">Reject</a></li>
                            </ul>
                        </div>
                        <div class="d-flex align-items-center">
                            <form action="" method="get" id="search-form">
                                <div class="search-cinema">
                                    <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                                    <input placeholder="Search" value="{{request('search_key')}}" name="search_key" type="text" class="input">
                                </div>
                            </form>
                            <a href="{{route('admin#inventoryList')}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>IID</th>
                                <th>Name</th>
                                <th class="text-center">QTY</th>
                                <th class="text-end">Total Price</th>
                                <th class="text-center">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventory as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>IID - {{$item->id}}</td>
                                <td>{{$item->product_name}}</td>
                                <td class="text-center">{{$item->qty}}</td>
                                <td class="text-end">{{number_format($item->total_price)}}</td>
                                <td class="text-center">
                                    @if ($item->status==0)
                                        <span class="text-warning">Pending</span>
                                    @elseif($item->status==1)
                                        <span class="text-success">Success</span>
                                    @else
                                        <span class="text-danger">Reject</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('admin#inventoryView',$item->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
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

        <div>
            <div class="modal fade" id="create-inventory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create-inventoryLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="create-inventoryLabel">Add Inventory</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#inventoryCreate')}}" method="post" class="mt-1">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Product</label>
                                        <select name="product_id" id="" class="form-select @error('product_id') is-invalid @enderror" required>
                                            <option value="">Choose Product</option>
                                            @foreach ($storeItem as $item)
                                                <option value="{{$item->id}}">{{$item->product_name}} ({{$item->store_name}}) (Max - {{$item->qty}})</option>
                                            @endforeach
                                        </select>
                                        @error('product_id')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Store</label>
                                        <select name="receive_store_id" id="" class="form-select @error('receive_store_id') is-invalid @enderror" required>
                                            <option value="">Choose Store</option>
                                            @foreach ($store as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('receive_store_id')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="mb-1">Qty</label>
                                    <input id="" name="qty" type="number" class="form-control @error('qty') is-invalid @enderror" required>
                                    @error('qty')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="d-flex align-items-center my-2">
                                    <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
