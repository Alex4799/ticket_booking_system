@extends('main.admin.layout.master')
@section('title')
    Product
@endsection
@section('content')
<main class="content px-4 py-3 mb-5">
    <div>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{route('admin#purchaseList')}}">List</a></li>
              <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </nav>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="shadow p-2 border-0 shadow">
                    <h4 class="text-center py-2 mb-4">View Purchase</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{Storage::url('product/'.$purchase->product_image)}}" class="w-100 img-thumbnail" alt="">
                        </div>
                        <div class="col-md-8">
                            <div class="py-2">
                                <h5>Product Name - {{$purchase->product_name}}</h5>
                            </div>
                            <div class="py-2">
                                <h5>Product QTY - {{$purchase->qty}}</h5>
                            </div>
                            <div class="py-2">
                                <h5>Product Price - {{$purchase->price}} MMK</h5>
                            </div>
                            <div class="py-2">
                                <h5>Total Price - {{$purchase->total_price}} MMK</h5>
                            </div>
                            <div class="py-2">
                                <h5>Supplier Name - {{$purchase->supplier_name}}</h5>
                            </div>
                            <div class="py-2">
                                <h5>Store Name - {{$purchase->store_name}}</h5>
                            </div>
                            <div class="py-2">
                                <h5>Date - {{$purchase->created_at}}</h5>
                            </div>
                            <div class="py-2">
                                <h5>Status - @if ($purchase->status==0)
                                    <span class="text-warning">Pending</span>
                                @elseif($purchase->status==1)
                                    <span class="text-success">Success</span>
                                @else
                                    <span class="text-danger">Reject</span>
                                @endif</h5>
                            </div>
                            <div class="py-2 d-flex justify-content-end gap-1">
                                @if (Auth::user()->purchase)
                                    <div>
                                        <div class="dropdown">
                                            <button class="btn @if ($purchase->status==0) btn-warning  @elseif ($purchase->status==1) btn-success @elseif ($purchase->status==2) btn-danger @endif dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            @if ($purchase->status==0)
                                                Pending
                                            @elseif ($purchase->status==1)
                                                Success
                                            @elseif ($purchase->status==2)
                                                Reject
                                            @endif
                                            </button>
                                            <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{route('admin#purchaseChangeStatus',[$purchase->id,0])}}">Pending</a></li>
                                            <li><a class="dropdown-item" href="{{route('admin#purchaseChangeStatus',[$purchase->id,1])}}">Success</a></li>
                                            <li><a class="dropdown-item" href="{{route('admin#purchaseChangeStatus',[$purchase->id,2])}}">Reject</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    @if ($purchase->status==0)
                                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#edit-purchase">Edit</button>
                                    @endif
                                    @if ($purchase->status!=1)
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-product">Delete</button>
                                    @endif
                                @else
                                    <button class="btn @if ($purchase->status==0) btn-warning  @elseif ($purchase->status==1) btn-success @elseif ($purchase->status==2) btn-danger @endif" type="button">
                                        @if ($purchase->status==0)
                                            Pending
                                        @elseif ($purchase->status==1)
                                            Success
                                        @elseif ($purchase->status==2)
                                            Reject
                                        @endif
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($purchase->status==0)
            <div class="modal fade" id="edit-purchase" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-purchaseLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title d-block w-100 text-center" id="edit-purchaseLabel">Edit Purchase</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#purchaseUpdate')}}" method="post" class="mt-1">
                                @csrf
                                @method('PATCH')
                                <div class="form-group mb-4">
                                    <label class="mb-1">Product</label>
                                    <select name="product" id="" class="form-control @error('product') is-invalid @enderror">
                                        <option value="">Choose Product</option>
                                        @foreach ($product as $item)
                                            <option value="{{json_encode(['id'=>$item->id,'name'=>$item->name])}}" @if ($item->name==$purchase->product_name) selected @endif>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('product')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-4">
                                    <label class="mb-1">Supplier</label>
                                    <select name="supplier_id" id="" class="form-control @error('supplier_id') is-invalid @enderror">
                                        <option value="">Choose Supplier</option>
                                        @foreach ($supplier as $item)
                                            <option value="{{$item->id}}" @if ($item->id==$purchase->supplier_id) selected @endif>{{$item->name}}</option>
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
                                            <option value="{{$item->id}}" @if ($item->id==$purchase->store_id) selected @endif>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('store_id')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-4">
                                    <label class="mb-1">Qty</label>
                                    <input id="" name="qty" type="number" value="{{$purchase->qty}}" class="form-control @error('qty') is-invalid @enderror" placeholder="Enter product qty">
                                    @error('qty')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-4">
                                    <label class="mb-1">Price (For one item)</label>
                                    <input id="" name="price" type="number" value="{{$purchase->price}}" class="form-control @error('price') is-invalid @enderror" placeholder="Enter product price">
                                    @error('price')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <input type="hidden" name="id" value="{{$purchase->id}}">

                                <button type="submit" class="btn btn-primary w-100 my-2">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if ($purchase->status!=1)
            <div class="modal fade" id="delete-product" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-productLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title d-block w-100 text-center text-danger" id="delete-productLabel">Delete Purchase</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#purchaseDelete')}}" method="post" class="mt-1">
                                @csrf
                                @method('DELETE')
                                <div class="py-1">
                                    <p class="text-danger">"Are you sure to delete this."</p>
                                </div>
                                <div class="py-1">
                                    <h6>Verify you are admin. Please enter the password.</h6>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="mb-1">Password</label>
                                    <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password." required>
                                    @error('password')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                <input type="hidden" name="id" value="{{$purchase->id}}">

                                <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</main>
@endsection
