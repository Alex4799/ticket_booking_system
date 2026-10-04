@extends('main.admin.layout.master')
@section('title')
    Store
@endsection
@section('content')
<main class="content px-4 py-3 mb-5">
    <div>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{route('admin#storeList')}}">List</a></li>
              <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </nav>
    </div>
    <div class="container-fluid">
        <div>
            <div class="col-md-10 offset-md-1">
                <div class="card p-2 border-0 shadow">
                    <h4 class=" text-center text-capitalize py-2 mb-4">{{$store->name}}</h4>
                    <div class=" m-auto">
                        <h5 class="py-2">Store Manager - {{$store->manager_name}}</h5>
                        <h5 class="py-2">Cinema - {{$store->cinema_name}}</h5>
                        <h5 class="py-2">Location - {{$store->location}}</h5>
                        <h5 class="py-2">{!!$store->contact!!}</h5>
                        <h5 class="py-2">Acitve - @if ($store->active==0)
                                        <span class="text-danger">Not Active</span>
                                    @else
                                        <span class="text-success">Active</span>
                                    @endif</h5>
                    </div>
                    <div class=" d-flex justify-content-end gap-1 flex-wrap">
                        <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#update-store">Edit</button>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-store">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <div id="table-bg">
                <div class="d-flex justify-content-between">
                    <h4>Store Item</h4>
                    <div class="gap-1">
                        @if (Auth::user()->stock)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-storeItem">Add</button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reduce-storeItem">Reduce</button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#damage-reduce-storeItem">Damage Reduce</button>
                        @endif
                    </div>
                </div>
                <div class="py-3">
                    <div>
                        <h5>Instock Item</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Product Name</th>
                                    <th>QTY</th>
                                    <th>Purchase Price</th>
                                    <th>Selling Price</th>
                                    <th>Profit</th>
                                    <th>Instock Level</th>
                                    <th>Active</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($storeItem as $item)
                                    @if ($item->type==0)
                                        <tr>
                                            <td>{{$item->id}}</td>
                                            <td><a href="{{route('admin#storeItemView',$item->id)}}" class=" text-decoration-none">{{$item->product_name}}</a></td>
                                            <td>{{$item->qty}} {{$item->unit}} </td>
                                            <td>{{$item->purchase_price}} MMK</td>
                                            <td>{{$item->selling_price}} MMK</td>
                                            <td>{{$item->profit}} MMK</td>
                                            <td>{{$item->instock_level}}</td>
                                            <td>
                                                @if ($item->active==0)
                                                    <span class="text-warning">Not Active</span>
                                                @else
                                                    <span class="text-success">Active</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (Auth::user()->stock)
                                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#update-storeItem{{$item->id}}">Edit</button>
                                                @endif
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="update-storeItem{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="update-storeItem{{$item->id}}Label" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title d-block w-100 text-center" id="update-storeItem{{$item->id}}Label">Update Store</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('admin#storeItemUpdate')}}" method="post" class="mt-1">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Purchase Price</label>
                                                                <input id="" name="purchase_price" type="text" value="{{$item->purchase_price}}" class="form-control @error('purchase_price') is-invalid @enderror" placeholder="Enter product selling price" readonly>
                                                                @error('purchase_price')
                                                                    <small class="text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Selling Price</label>
                                                                <input id="" name="selling_price" type="text" value="{{$item->selling_price}}" class="form-control @error('selling_price') is-invalid @enderror" placeholder="Enter product selling price">
                                                                @error('selling_price')
                                                                    <small class="text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <input type="checkbox" name="type" @if ($item->type) value="1" checked @endif>
                                                                <label for="">No Need Instock</label>
                                                            </div>
                                                            @if (Auth::user()->position!='store-manager')
                                                                <div class="form-group mb-4">
                                                                    <label class="mb-1">Instock</label>
                                                                    <input id="" name="qty" type="text" value="{{$item->qty}}" class="form-control @error('qty') is-invalid @enderror" placeholder="Enter product selling price">
                                                                    @error('qty')
                                                                        <small class="text-danger">{{$message}}</small>
                                                                    @enderror
                                                                </div>
                                                            @endif
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Unit</label>
                                                                <input id="" name="unit" type="text" value="{{$item->unit}}" class="form-control @error('unit') is-invalid @enderror" placeholder="Enter product unit">
                                                                @error('unit')
                                                                    <small class="text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Barcode</label>
                                                                <input id="" name="barcode" type="text" value="{{$item->barcode}}" class="form-control @error('barcode') is-invalid @enderror" placeholder="Enter product barcode">
                                                                @error('barcode')
                                                                    <small class="text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Instock Level</label>
                                                                <input id="" name="instock_level" type="text" value="{{$item->instock_level}}" class="form-control @error('instock_level') is-invalid @enderror" placeholder="Enter product selling price">
                                                                @error('instock_level')
                                                                    <small class="text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <input type="checkbox" name="selling_type" @if ($item->selling_type) checked @endif>
                                                                <label for="">No Selling</label>
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <label for="name" class="form-label mb-1">Active</label>
                                                                <select name="active" id="" class="form-control">
                                                                    <option value="">Choose Active Status</option>
                                                                    <option value="0" @if ($item->active==0) selected @endif>Not Active</option>
                                                                    <option value="1" @if ($item->active==1) selected @endif>Active</option>
                                                                </select>
                                                                @error('active')
                                                                    <small class=" text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <input type="hidden" name="id" value="{{$item->id}}">
                                                            <button type="submit" class="btn btn-primary w-100 my-2">Update</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="py-3">
                    <div>
                        <h5>No Need Instock Item</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Product Name</th>
                                    <th>Purchase Price</th>
                                    <th>Selling Price</th>
                                    <th>Profit</th>
                                    <th>Active</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($storeItem as $item)
                                    @if ($item->type==1)
                                        <tr>
                                            <td>{{$item->id}}</td>
                                            <td>{{$item->product_name}}</td>
                                            <td>{{$item->purchase_price}} MMK</td>
                                            <td>{{$item->selling_price}} MMK</td>
                                            <td>{{$item->profit}} MMK</td>
                                            <td>
                                                @if ($item->active==0)
                                                    <span class="text-warning">Not Active</span>
                                                @else
                                                    <span class="text-success">Active</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (Auth::user()->stock)
                                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#update-storeItem{{$item->id}}">Edit</button>
                                                @endif
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="update-storeItem{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="update-storeItem{{$item->id}}Label" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title d-block w-100 text-center" id="update-storeItem{{$item->id}}Label">Update Store</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('admin#storeItemUpdate')}}" method="post" class="mt-1">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Purchase Price</label>
                                                                <input id="" name="purchase_price" type="text" value="{{$item->purchase_price}}" class="form-control @error('purchase_price') is-invalid @enderror" placeholder="Enter product selling price" readonly>
                                                                @error('purchase_price')
                                                                    <small class="text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Selling Price</label>
                                                                <input id="" name="selling_price" type="text" value="{{$item->selling_price}}" class="form-control @error('selling_price') is-invalid @enderror" placeholder="Enter product selling price">
                                                                @error('selling_price')
                                                                    <small class="text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <input type="checkbox" name="type" @if ($item->type) value="1" checked @endif>
                                                                <label for="">No Need Instock</label>
                                                            </div>
                                                            @if (Auth::user()->position!='store-manager')
                                                                <div class="form-group mb-4">
                                                                    <label class="mb-1">Instock</label>
                                                                    <input id="" name="qty" type="text" value="{{$item->qty}}" class="form-control @error('qty') is-invalid @enderror" placeholder="Enter product selling price">
                                                                    @error('qty')
                                                                        <small class="text-danger">{{$message}}</small>
                                                                    @enderror
                                                                </div>
                                                            @endif
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Unit</label>
                                                                <input id="" name="unit" type="text" value="{{$item->unit}}" class="form-control @error('unit') is-invalid @enderror" placeholder="Enter product unit">
                                                                @error('unit')
                                                                    <small class="text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Barcode</label>
                                                                <input id="" name="barcode" type="text" value="{{$item->barcode}}" class="form-control @error('barcode') is-invalid @enderror" placeholder="Enter product barcode">
                                                                @error('barcode')
                                                                    <small class="text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Instock Level</label>
                                                                <input id="" name="instock_level" type="text" value="{{$item->instock_level}}" class="form-control @error('instock_level') is-invalid @enderror" placeholder="Enter product selling price">
                                                                @error('instock_level')
                                                                    <small class="text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <input type="checkbox" name="selling_type" @if ($item->selling_type) checked @endif>
                                                                <label for="">No Selling</label>
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <label for="name" class="form-label mb-1">Active</label>
                                                                <select name="active" id="" class="form-control">
                                                                    <option value="">Choose Active Status</option>
                                                                    <option value="0" @if ($item->active==0) selected @endif>Not Active</option>
                                                                    <option value="1" @if ($item->active==1) selected @endif>Active</option>
                                                                </select>
                                                                @error('active')
                                                                    <small class=" text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <input type="hidden" name="id" value="{{$item->id}}">
                                                            <button type="submit" class="btn btn-primary w-100 my-2">Update</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- model  --}}
        <div class="modal fade" id="create-storeItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create-storeItemLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="create-storeItemLabel">Add Store Item</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#storeItemCreate')}}" method="post" class="mt-1">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="mb-1">Product</label>
                                <select name="product" id="" class="form-control @error('product') is-invalid @enderror">
                                    @foreach ($product as $item)
                                        <option value="{{json_encode(['id'=>$item->id,'name'=>$item->name])}}">{{$item->name}}</option>
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
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Purchase Price</label>
                                <input id="" name="purchase_price" type="text" class="form-control @error('purchase_price') is-invalid @enderror" placeholder="Enter product purchase price">
                                @error('purchase_price')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Selling Price</label>
                                <input id="" name="selling_price" type="text" class="form-control @error('selling_price') is-invalid @enderror" placeholder="Enter product selling price">
                                @error('selling_price')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <input type="checkbox" name="type" value="1" id="no_instock">
                                <label for="">No Need Instock</label>
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Instock</label>
                                <input id="" name="qty" type="text" class="form-control @error('qty') is-invalid @enderror" placeholder="Enter product instock">
                                @error('qty')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Unit</label>
                                <input id="" name="unit" type="text" class="form-control @error('unit') is-invalid @enderror" placeholder="Enter product unit">
                                @error('unit')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Barcode</label>
                                <input id="" name="barcode" type="text" class="form-control @error('barcode') is-invalid @enderror" placeholder="Enter product barcode">
                                @error('barcode')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Instock Level</label>
                                <input id="" name="instock_level" type="text" class="form-control @error('instock_level') is-invalid @enderror" placeholder="Enter product instock level">
                                @error('instock_level')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <input type="checkbox" name="selling_type" value="1" id="no_selling">
                                <label for="">No Selling Item</label>
                            </div>
                            <div class="form-group mb-4">
                                <label for="name" class="form-label mb-1">Active</label>
                                <select name="active" id="" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Not Active</option>
                                </select>
                                @error('active')
                                    <small class=" text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <input type="hidden" name="store_id" value="{{$store->id}}">
                            <button type="submit" class="btn btn-primary w-100 my-2">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="damage-reduce-storeItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create-storeItemLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="create-storeItemLabel">Reduce Damage Store Item</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#storeItemDamage')}}" method="post" class="mt-1">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="mb-1">Product</label>
                                <select name="store_item_id" id="" class="form-control @error('store_item_id') is-invalid @enderror">
                                    <option value="">Choose Product</option>
                                    @foreach ($storeItem as $item)
                                        @if ($item->qty>0 && $item->type!=1)
                                            <option value="{{$item->id}}">{{$item->product_name}} (Max - {{$item->qty}})</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('store_item_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">QTY</label>
                                <input id="" name="qty" type="number" min="1" class="form-control @error('qty') is-invalid @enderror" placeholder="Enter product qty">
                                @error('qty')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 my-2">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="reduce-storeItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create-storeItemLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="create-storeItemLabel">Reduce Store Item</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#storeItemReduce')}}" method="post" class="mt-1">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="mb-1">Product</label>
                                <select name="store_item_id" id="" class="form-control @error('store_item_id') is-invalid @enderror">
                                    <option value="">Choose Product</option>
                                    @foreach ($storeItem as $item)
                                        @if ($item->selling_type==1)
                                            <option value="{{$item->id}}">{{$item->product_name}} (Max - {{$item->qty}})</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('store_item_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">QTY</label>
                                <input id="" name="qty" type="number" min="1" class="form-control @error('qty') is-invalid @enderror" placeholder="Enter product qty">
                                @error('qty')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 my-2">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="update-store" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="update-storeLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="update-storeLabel">Update Store</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#storeUpdate')}}" method="post" class="mt-1">
                            @csrf
                            @method('PATCH')
                            <div class="form-group mb-4">
                                <label class="mb-1"> Name</label>
                                <input id="" name="name" type="text" value="{{$store->name}}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter store name">
                                @error('name')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Store Manager</label>
                                <select name="manager" class="form-control @error('name') is-invalid @enderror"  id="">
                                    <option value="">Choose Store Manager</option>
                                    @foreach ($storeManager as $item)
                                        <option value="{{$item->id}}" @if ($item->id==$store->manager) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('manager')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Cinema</label>
                                <select name="cinema_id" class="form-control @error('cinema_id') is-invalid @enderror"  id="">
                                    <option value="">Choose Cinema</option>
                                    @foreach ($cinema as $item)
                                        <option value="{{$item->id}}" @if ($item->id==$store->cinema_id) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('cinema_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Contact</label>
                                <textarea name="contact" id="editor" cols="30" rows="10" placeholder="Enter content info">{!!$store->contact!!}</textarea>
                                @error('contact')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Location</label>
                                <input name="location" value="{{$store->location}}" type="text"class="form-control @error('location') is-invalid  @enderror" placeholder="Enter cinema location">
                                @error('location')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label for="name" class="form-label mb-1">Active</label>
                                <select name="active" id="" class="form-control">
                                    <option value="">Choose Active Status</option>
                                    <option value="0" @if ($store->active==0) selected @endif>Not Active</option>
                                    <option value="1" @if ($store->active==1) selected @endif>Active</option>
                                </select>
                                @error('active')
                                    <small class=" text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <input type="hidden" name="id" value="{{$store->id}}">
                            <button type="submit" class="btn btn-primary w-100 my-2">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="delete-store" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-storeLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="delete-storeLabel">Delete Store</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#storeDelete')}}" method="post" class="mt-1">
                            @csrf
                            @method('DELETE')
                            <div class="py-1">
                                <p class="text-danger">Are you sure to delete this. If you delete this, the count of product in this store must be 0."</p>
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

                            <input type="hidden" name="id" value="{{$store->id}}">

                            <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('script')
        <script type="module">
            import {
                ClassicEditor,
                Essentials,
                Bold,
                Italic,
                Font,
                Paragraph
            } from 'ckeditor5';

            ClassicEditor
                .create( document.querySelector( '#editor' ), {
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: {
                        items: [
                            'undo', 'redo', '|', 'bold', 'italic', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                        ]
                    }
                } )
                .then( /* ... */ )
                .catch( /* ... */ );
        </script>
@endsection
