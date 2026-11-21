@extends('main.admin.layout.master')
@section('title')
    Purchase
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
                            <h5 class="mb-0">Total - {{$purchase->total()}}</h5>
                        </div>
                        @if (Auth::user()->purchase)
                            <div class="ms-3">
                                <button class="add-btn" data-bs-toggle="modal" data-bs-target="#create-purchase">
                                    <i class="fa-solid fa-square-plus me-2"></i>Add Purchase
                                </button>
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
                        <a href="{{route('admin#purchaseList')}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                    </div>
                </div>
                <form method="get" action="" class="mt-3 bg-body-secondary rounded px-3 pb-2">
                    <div class="py-2 d-flex align-items-center gap-2 flex-wrap">
                        <div class="me-2">
                            <label class="label-small mb-0">Status</label>
                            <select name="status" class="form-select" style="font-size: 11pt;">
                                <option value="">All</option>
                                <option value="0" @if (request('status')==='0') selected @endif>Pending</option>
                                <option value="1" @if (request('status')==1) selected @endif>Success</option>
                                <option value="2" @if (request('status')==2) selected @endif>Reject</option>
                            </select>
                        </div>
                        <div class="me-2">
                            <label class="label-small mb-0">Product</label>
                            <select name="product_name" class="form-select" style="font-size: 11pt;">
                                <option value="">All</option>
                                @foreach ($productFilter as $item)
                                    <option value="{{$item}}" @if (request('product_name')===$item) selected @endif>{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex gap-3 flex-wrap">
                            <div>
                                <label class="label-small mb-0">Start Date</label>
                                <input type="date" style="font-size: 11pt;" name="start_date" class="form-control" value="{{request('start_date')}}">
                            </div>
                            <div>
                                <label class="label-small mb-0">End Date</label>
                                <input type="date" style="font-size: 11pt;" name="end_date" class="form-control" value="{{request('end_date')}}">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-end w-100">
                        <button class="d-flex align-items-center btn btn-primary me-2" style="font-size: 10pt;"><i class="fa-solid fa-filter me-2" style="font-size: 8pt;"></i>Filter</button>
                    </div>
                </form>
                <div class="summary-cards mt-4">
                    <div class="row">
                        @foreach ($summary as $item)
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="card-content @if ($item->status==0) pending @elseif($item->status==1) success @elseif($item->status==3) cancel @elseif($item->status==4) booking @else reject @endif">
                                    <label class="@if ($item->status==0) pending @elseif($item->status==1) success @elseif($item->status==3) cancel @elseif($item->status==4) booking @else reject @endif">Total Purchase @if ($item->status==0) Pending @elseif($item->status==1) Success @else Reject @endif</label>
                                    <div class="d-flex align-items-center justify-content-between gap-1 flex-wrap">
                                        <h5>{{number_format($item->total_price)}}<label class="label-small ms-1">Ks</label></h5>
                                        @if ($item->status==0)
                                            <p class="rep-pending">{{$item->total_count}}</p>
                                        @elseif($item->status==1)
                                            <p class="rep-success">{{$item->total_count}}</p>
                                        @elseif($item->status==3)
                                            <p class="rep-cancel">{{$item->total_count}}</p>
                                        @elseif($item->status==4)
                                            <p class="rep-booking">{{$item->total_count}}</p>
                                        @else
                                            <p class="rep-reject">{{$item->total_count}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>PID</th>
                                <th>Name</th>
                                <th class="text-center">QTY</th>
                                <th class="text-end">Total Price</th>
                                <th class="text-center">Date & Time</th>
                                <th class="text-center">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>PID - {{$item->id}}</td>
                                <td>{{$item->product_name}}</td>
                                <td class="text-center">{{$item->qty}}</td>
                                <td class="text-end">{{number_format($item->total_price)}}</td>
                                <td class="text-center">{{Carbon::parse($item->created_at)->format('d/m/Y - h:i A')}}</td>
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
                                    <a href="{{route('admin#purchaseView',$item->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
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

        <div>
            <div class="modal fade" id="create-purchase" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create-purchaseLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="create-purchaseLabel">Add Purchase</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#purchaseCreate')}}" method="post" class="mt-1">
                                @csrf
                                <div class="form-group mb-4">
                                    <label class="mb-1">Product</label>
                                    <select name="product" id="" class="form-select @error('product') is-invalid @enderror" required>
                                        <option value="">Choose Product</option>
                                        @foreach ($product as $item)
                                            <option value="{{json_encode(['id'=>$item->id,'name'=>$item->name])}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('product')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Supplier</label>
                                        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
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
                                        <label class="mb-1">Store</label>
                                        <select name="store_id" class="form-select @error('store_id') is-invalid @enderror" required>
                                            <option value="">Choose Store</option>
                                            @foreach ($store as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('store_id')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Qty</label>
                                        <input name="qty" type="number" class="form-control @error('qty') is-invalid @enderror" required>
                                        @error('qty')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Price (For one item)</label>
                                        <input name="price" type="number" class="form-control @error('price') is-invalid @enderror" required>
                                        @error('price')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
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
