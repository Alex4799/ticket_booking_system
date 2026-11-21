@extends('main.admin.layout.master')
@section('title')
    Store
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin#storeList')}}">List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="shadow-container-box">
            <div class="seat-view">
                <div class="seat-detail-top">
                    <div class="seat-title">
                        <div>
                            <i class="fa-solid fa-shop"></i>
                        </div>
                        <div>
                            <label>Store</label>
                            <h4>{{$store->name}}</h4>
                        </div>
                    </div>
                    <div>
                        <h6>
                            @if ($store->active==0)
                                <span class="status reject fw-bold">Not Active</span>
                            @else
                                <span class="status success fw-bold">Active</span>
                            @endif
                        </h6>
                    </div>
                </div>
                <div class="store-detail-footer">
                    <div>
                        <label>Cinema</label>
                        <h6>{{$store->cinema_name}}</h6>
                    </div>
                    <div>
                        <label>Manager</label>
                        <h6>{{$store->manager_name}}</h6>
                    </div>
                    <div>
                        <label>Contact</label>
                        <h6>{!!$store->contact!!}</h6>
                    </div>
                    <div>
                        <label>Location</label>
                        <h6>{{$store->location}}</h6>
                    </div>
                </div>
                <div class=" d-flex justify-content-end flex-wrap gap-2">
                    <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#edit-store"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Store</button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-store" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        </div>
        <div class="mt-5 bg-body-secondary rounded px-3 py-4">
            <div class="total-count-title">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div class="count-content bg-white me-1">
                        <h5 class="mb-0">Store Item</h5>
                    </div>
                    @if (Auth::user()->stock)
                        <div>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <button class="add-btn" data-bs-toggle="modal" data-bs-target="#create-storeItem">
                                    <i class="fa-solid fa-square-plus me-2"></i>Add
                                </button>
                                <button class="add-btn" data-bs-toggle="modal" data-bs-target="#reduce-storeItem">
                                    <i class="fa-solid fa-square-minus me-2"></i>Reduce
                                </button>
                                <button class="add-btn" data-bs-toggle="modal" data-bs-target="#damage-reduce-storeItem">
                                    <i class="fa-solid fa-triangle-exclamation me-2"></i>Damage Reduce
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="d-flex align-items-center">
                    <form action="" method="get" id="search-form">
                        <div class="search-cinema">
                            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                            <input placeholder="Search" value="{{request('search_key')}}" name="search_key" type="text" class="input">
                        </div>
                    </form>
                    <a href="{{route('admin#storeView',$store->id)}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                </div>
            </div>
        </div>
        <div class="table-bg mt-4">
            <div class="hall-view mt-2">
                <h5 class="mb-1 px-2"><i class="fa-solid fa-boxes-packing me-2"></i> In Stock</h5>
            </div>
            @if ($storeItem->where('type', 0)->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Product Name</th>
                                <th class="text-center">QTY</th>
                                <th class="text-end">Purchase Price</th>
                                <th class="text-end">Selling Price</th>
                                <th class="text-end">Profit</th>
                                <th class="text-center">Instock Level</th>
                                <th class="text-center">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($storeItem as $item)
                                @if ($item->type==0)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td><a href="{{route('admin#storeItemView',$item->id)}}" class=" text-decoration-none">{{$item->product_name}}</a></td>
                                        <td class="text-center">{{$item->qty}} {{$item->unit}} </td>
                                        <td class="text-end">{{number_format($item->purchase_price)}}</td>
                                        <td class="text-end">{{number_format($item->selling_price)}}</td>
                                        <td class="text-end">{{number_format($item->profit)}}</td>
                                        <td class="text-center">{{$item->instock_level}}</td>
                                        <td class="text-center">
                                            @if ($item->active==0)
                                                <span class="text-danger">Not Active</span>
                                            @else
                                                <span class="text-success">Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (Auth::user()->stock)
                                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#update-storeItem{{$item->id}}"><i class="fa-solid fa-pen-to-square"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="update-storeItem{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="update-storeItem{{$item->id}}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title d-block w-100 text-center" id="update-storeItem{{$item->id}}Label">Update Item</h5>
                                                    <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('admin#storeItemUpdate')}}" method="post" class="mt-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="update-btn-group">
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Selling Price</label>
                                                                <input id="" name="selling_price" type="text" value="{{$item->selling_price}}" class="form-control @error('selling_price') is-invalid @enderror">
                                                                @error('selling_price')
                                                                    <small class="text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <input type="checkbox" name="type" @if ($item->type) checked @endif>
                                                                <label class="ms-2">No Stock Required</label>
                                                            </div>
                                                            <div class="row">
                                                                @if (Auth::user()->position!='store-manager')
                                                                    <div class="col-sm-6 form-group mb-4">
                                                                        <label class="mb-1">Instock</label>
                                                                        <input id="" name="qty" type="text" value="{{$item->qty}}" class="form-control @error('qty') is-invalid @enderror">
                                                                        @error('qty')
                                                                            <small class="text-danger">{{$message}}</small>
                                                                        @enderror
                                                                    </div>
                                                                @endif
                                                                <div class="col-sm-6 form-group mb-4">
                                                                    <label class="mb-1">Unit</label>
                                                                    <input id="" name="unit" type="text" value="{{$item->unit}}" class="form-control @error('unit') is-invalid @enderror">
                                                                    @error('unit')
                                                                        <small class="text-danger">{{$message}}</small>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-sm-6 form-group mb-4">
                                                                    <label class="mb-1">Barcode</label>
                                                                    <input id="" name="barcode" type="text" value="{{$item->barcode}}" class="form-control @error('barcode') is-invalid @enderror">
                                                                    @error('barcode')
                                                                        <small class="text-danger">{{$message}}</small>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-sm-6 form-group mb-4">
                                                                    <label class="mb-1">Instock Level</label>
                                                                    <input id="" name="instock_level" type="text" value="{{$item->instock_level}}" class="form-control @error('instock_level') is-invalid @enderror">
                                                                    @error('instock_level')
                                                                        <small class="text-danger">{{$message}}</small>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <input type="checkbox" name="selling_type" @if ($item->selling_type) checked @endif>
                                                                <label class="ms-2">No Selling Item</label>
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Status</label>
                                                                <select name="active" id="" class="form-select">
                                                                    <option value="0" @if ($item->active==0) selected @endif>Not Active</option>
                                                                    <option value="1" @if ($item->active==1) selected @endif>Active</option>
                                                                </select>
                                                                @error('active')
                                                                    <small class=" text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <input type="hidden" name="id" value="{{$item->id}}">
                                                            <div class="d-flex align-items-center my-3">
                                                                <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                                            </div>
                                                        </div>
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
            @else
                <h6 class="text-center mt-5 mb-4 text-secondary" style="font-size: 11pt;">No items available.</h6>
            @endif
        </div>
        <div class="table-bg mt-5">
            <div class="hall-view mt-2">
                <h5 class="mb-1 px-2"><i class="fa-solid fa-ban me-2"></i> No Stock Required</h5>
            </div>
            @if ($storeItem->where('type', 1)->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Product Name</th>
                                <th class="text-end">Purchase Price</th>
                                <th class="text-end">Selling Price</th>
                                <th class="text-end">Profit</th>
                                <th class="text-center">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($storeItem as $item)
                                @if ($item->type==1)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$item->product_name}}</td>
                                        <td class="text-end">{{number_format($item->purchase_price)}}</td>
                                        <td class="text-end">{{number_format($item->selling_price)}}</td>
                                        <td class="text-end">{{number_format($item->profit)}}</td>
                                        <td class="text-center">
                                            @if ($item->active==0)
                                                <span class="text-danger">Not Active</span>
                                            @else
                                                <span class="text-success">Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (Auth::user()->stock)
                                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#update-storeItem{{$item->id}}"><i class="fa-solid fa-pen-to-square"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="update-storeItem{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="update-storeItem{{$item->id}}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title d-block w-100 text-center" id="update-storeItem{{$item->id}}Label">Update Item</h5>
                                                    <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('admin#storeItemUpdate')}}" method="post" class="mt-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="update-btn-group">
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Selling Price</label>
                                                                <input id="" name="selling_price" type="text" value="{{$item->selling_price}}" class="form-control @error('selling_price') is-invalid @enderror">
                                                                @error('selling_price')
                                                                    <small class="text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <input type="checkbox" name="type" @if ($item->type) value="on" checked @endif>
                                                                <label class="ms-2">No Stock Required</label>
                                                            </div>
                                                            <div class="row">
                                                                @if (Auth::user()->position!='store-manager')
                                                                    <div class="col-sm-6 form-group mb-4">
                                                                        <label class="mb-1">Instock</label>
                                                                        <input id="" name="qty" type="text" value="{{$item->qty}}" class="form-control @error('qty') is-invalid @enderror">
                                                                        @error('qty')
                                                                            <small class="text-danger">{{$message}}</small>
                                                                        @enderror
                                                                    </div>
                                                                @endif
                                                                <div class="col-sm-6 form-group mb-4">
                                                                    <label class="mb-1">Unit</label>
                                                                    <input id="" name="unit" type="text" value="{{$item->unit}}" class="form-control @error('unit') is-invalid @enderror">
                                                                    @error('unit')
                                                                        <small class="text-danger">{{$message}}</small>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-sm-6 form-group mb-4">
                                                                    <label class="mb-1">Barcode</label>
                                                                    <input id="" name="barcode" type="text" value="{{$item->barcode}}" class="form-control @error('barcode') is-invalid @enderror">
                                                                    @error('barcode')
                                                                        <small class="text-danger">{{$message}}</small>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-sm-6 form-group mb-4">
                                                                    <label class="mb-1">Instock Level</label>
                                                                    <input id="" name="instock_level" type="text" value="{{$item->instock_level}}" class="form-control @error('instock_level') is-invalid @enderror">
                                                                    @error('instock_level')
                                                                        <small class="text-danger">{{$message}}</small>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <input type="checkbox" name="selling_type" @if ($item->selling_type) value="on" checked @endif>
                                                                <label class="ms-2">No Selling Item</label>
                                                            </div>
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Status</label>
                                                                <select name="active" id="" class="form-select">
                                                                    <option value="0" @if ($item->active==0) selected @endif>Not Active</option>
                                                                    <option value="1" @if ($item->active==1) selected @endif>Active</option>
                                                                </select>
                                                                @error('active')
                                                                    <small class=" text-danger">{{$message}}</small>
                                                                @enderror
                                                            </div>
                                                            <input type="hidden" name="id" value="{{$item->id}}">
                                                            <div class="d-flex align-items-center my-3">
                                                                <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                                            </div>
                                                        </div>
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
            @else
                <h6 class="text-center mt-5 mb-4 text-secondary" style="font-size: 11pt;">No items available.</h6>
            @endif
        </div>


        {{-- model  --}}
        <div>
            <div class="modal fade" id="create-storeItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create-storeItemLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="create-storeItemLabel">Add Item</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#storeItemCreate')}}" method="post" class="mt-1">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Product</label>
                                        <select name="product" class="form-select @error('product') is-invalid @enderror">
                                            <option value="">Choose Product</option>
                                            @foreach ($product as $item)
                                                <option value="{{json_encode(['id'=>$item->id,'name'=>$item->name])}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('product')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Supplier</label>
                                        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                                            <option value="">Choose Supplier</option>
                                            @foreach ($supplier as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Purchase Price</label>
                                        <input name="purchase_price" type="text" class="form-control @error('purchase_price') is-invalid @enderror">
                                        @error('purchase_price')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Selling Price</label>
                                        <input name="selling_price" type="text" class="form-control @error('selling_price') is-invalid @enderror">
                                        @error('selling_price')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-12 form-group mb-4">
                                        <input type="checkbox" name="type" value="1" id="no_instock">
                                        <label class="ms-2">No Stock Required</label>
                                    </div>
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Instock</label>
                                        <input name="qty" type="number" class="form-control @error('qty') is-invalid @enderror">
                                        @error('qty')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Unit</label>
                                        <input name="unit" type="text" class="form-control @error('unit') is-invalid @enderror">
                                        @error('unit')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Barcode</label>
                                        <input name="barcode" type="text" class="form-control @error('barcode') is-invalid @enderror">
                                        @error('barcode')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Instock Level</label>
                                        <input name="instock_level" type="number" class="form-control @error('instock_level') is-invalid @enderror">
                                        @error('instock_level')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-12 form-group mb-4">
                                        <input type="checkbox" name="selling_type" value="1" id="no_selling">
                                        <label class="ms-2">No Selling Item</label>
                                    </div>
                                    <div class="col-sm-12 form-group mb-4">
                                        <label class="mb-1">Status</label>
                                        <select name="active" id="" class="form-select">
                                            <option value="1">Active</option>
                                            <option value="0">Not Active</option>
                                        </select>
                                        @error('active')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>

                                <input type="hidden" name="store_id" value="{{$store->id}}">
                                <div class="d-flex align-items-center mb-3">
                                    <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="damage-reduce-storeItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create-storeItemLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="create-storeItemLabel">Damage Reduce Item</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#storeItemDamage')}}" method="post" class="mt-1">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Product</label>
                                        <select name="store_item_id" class="form-select @error('store_item_id') is-invalid @enderror" required>
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
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">QTY</label>
                                        <input name="qty" type="number" min="1" class="form-control @error('qty') is-invalid @enderror" required>
                                        @error('qty')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex align-items-center my-2">
                                    <button type="submit" class="action-btn w-100"><i class="fa-solid fa-triangle-exclamation me-2"></i>Damage Reduce</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="reduce-storeItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create-storeItemLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="create-storeItemLabel">Reduce Item</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#storeItemReduce')}}" method="post" class="mt-1">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Product</label>
                                        <select name="store_item_id" class="form-select @error('store_item_id') is-invalid @enderror" required>
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
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">QTY</label>
                                        <input name="qty" type="number" min="1" class="form-control @error('qty') is-invalid @enderror" required>
                                        @error('qty')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex align-items-center my-2">
                                    <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-minus me-2"></i>Reduce</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="edit-store" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-storeLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="edit-storeLabel">Edit Store</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#storeUpdate')}}" method="post" class="mt-1">
                                @csrf
                                @method('PATCH')
                                <div class="update-btn-group">
                                    <div class="row">
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Name</label>
                                            <input name="name" type="text" value="{{$store->name}}" class="form-control @error('name') is-invalid @enderror" required>
                                            @error('name')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Status</label>
                                            <select name="active" class="form-select">
                                                <option value="0" @if ($store->active==0) selected @endif>Not Active</option>
                                                <option value="1" @if ($store->active==1) selected @endif>Active</option>
                                            </select>
                                            @error('active')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Store Manager</label>
                                            <select name="manager" class="form-select @error('name') is-invalid @enderror" required>
                                                @foreach ($storeManager as $item)
                                                    <option value="{{$item->id}}" @if ($item->id==$store->manager) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('manager')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Cinema</label>
                                            <select name="cinema_id" class="form-select @error('cinema_id') is-invalid @enderror" required>
                                                @foreach ($cinema as $item)
                                                    <option value="{{$item->id}}" @if ($item->id==$store->cinema_id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('cinema_id')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Contact</label>
                                            <textarea name="contact" cols="30" rows="5" class="form-control">{!!$store->contact!!}</textarea>
                                            @error('contact')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Location</label>
                                            <textarea name="location" cols="30" rows="5" class="form-control">{{$store->location}}</textarea>
                                            @error('location')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$store->id}}">
                                    <div class="d-flex align-items-center my-2">
                                        <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="delete-store" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-storeLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="delete-storeLabel">Delete Store</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#storeDelete')}}" method="post" class="mt-1">
                                @csrf
                                @method('DELETE')
                                <div class="delete-confirmation">
                                    <div class="delete-warning">
                                        <div class="delete-icon">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                        </div>
                                        <div class="delete-message">
                                            <h6>Are you sure to delete this? If you delete this, the count of product in this store must be "0".</h6>
                                            <small>Verify you are admin. Please enter the password.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group my-3">
                                    <label class="mb-1">Password</label>
                                    <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                <input type="hidden" name="id" value="{{$store->id}}">
                                <div class="d-flex align-items-center justify-content-end">
                                    <button type="submit" class="btn btn-danger">Delete</button>
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


@section('script')
<script>
    // updatebtn disabled check
    const forms = document.querySelectorAll('.update-btn-group');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, select, textarea');
        const button = form.querySelector('.action-btn');
        const initialValues = {};

        inputs.forEach(input => {
            if (input.type === 'checkbox' || input.type === 'radio') {
                initialValues[input.name] = input.checked;
            } else {
                initialValues[input.name] = input.value;
            }
        });

        function checkUpdateValueForBtn() {
            let hasChanged = false;
            inputs.forEach(input => {
                let currentValue;
                if (input.type === 'checkbox' || input.type === 'radio') {
                    currentValue = input.checked;
                } else {
                    currentValue = input.value;
                }
                if (currentValue !== initialValues[input.name]) {
                    hasChanged = true;
                }
            });
            button.disabled = !hasChanged;
        }

        inputs.forEach(input => {
            input.addEventListener('input', checkUpdateValueForBtn);
            input.addEventListener('change', checkUpdateValueForBtn);
        });

        checkUpdateValueForBtn();
    });
</script>
@endsection
