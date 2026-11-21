@extends('main.admin.layout.master')
@section('title')
    Store Item
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin#storeList')}}">List</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin#storeView', $storeItem->store_id) }}">View</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Item</li>
                </ol>
            </nav>
        </div>
        <div class="shadow-container-box mb-4">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="product-view-image">
                        @if ($storeItem->image!=null)
                            <img src="{{Storage::url('product/'.$storeItem->image)}}" class="img-thumbnail" alt="image">
                        @else
                            <img src="{{asset('image/placeholder.webp')}}" alt="Default" class="img-thumbnail">
                        @endif
                    </div>
                </div>
                <div class="col-md-9 ps-md-5">
                    <div class="seat-view">
                        <div class="seat-detail-top">
                            <div class="seat-title">
                                <div>
                                    <label>Product Name</label>
                                    <h4>{{$storeItem->product_name}}</h4>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label class="mb-0">Instock</label>
                                <small class="bg-secondary px-2 py-1 rounded text-white">
                                    {{$storeItem->qty}}
                                </small>
                            </div>
                        </div>
                        <div class="seat-detail-footer">
                            <div>
                                <label>Purchase Price</label>
                                <h6>{{number_format($storeItem->purchase_price)}} Ks</h6>
                            </div>
                            <div>
                                <label>Selling Price</label>
                                <h6>{{number_format($storeItem->selling_price)}} Ks</h6>
                            </div>
                            <div>
                                <label>Profit</label>
                                <h6>{{number_format($storeItem->profit)}} Ks</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @if (!empty($purchase))
                <div class="col-sm-6 mb-4">
                    <div class="shadow-container-box">
                        <div class="view-qty">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 py-2 px-3 me-2">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Purchase</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2 text-center">
                                <div class="col-6">
                                    <div class="bg-light rounded-3 py-2 px-4">
                                        <label>Purchase QTY</label>
                                        <h6 class="fw-bold">{{ $purchase->total_qty }}</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-3 py-2 px-4">
                                        <label>Total Price</label>
                                        <h6 class="fw-bold">{{ number_format($purchase->total_price) }} Ks</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (!empty($orderItem))
                <div class="col-sm-6 mb-4">
                    <div class="shadow-container-box">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 text-success rounded-3 py-2 px-3 me-2">
                                        <i class="fa-solid fa-cash-register"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Selling</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2 text-center">
                                <div class="col-6">
                                    <div class="bg-light rounded-3 py-2 px-4">
                                        <label>Selling QTY</label>
                                        <h6 class="fw-bold">{{ $orderItem->total_qty }}</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-3 py-2 px-4">
                                        <label>Total Price</label>
                                        <h6 class="fw-bold">{{ number_format($orderItem->total_price) }} Ks</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
