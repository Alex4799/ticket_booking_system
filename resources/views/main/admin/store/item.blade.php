@extends('main.admin.layout.master')
@section('title')
    Product
@endsection
@section('content')
<main class="content px-4 py-3 mb-5">
    <div>
        <div class="col-md-10 offset-md-1 py-1">
            <div class="card p-2 border-0 shadow">
                <h4 class=" text-center text-capitalize py-2 mb-4">Store Item View</h4>
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{Storage::url('product/'.$storeItem->image)}}" class="w-75 d-block mx-auto img-thumbnail" alt="">
                    </div>
                    <div class="col-md-8">
                        <h5 class="py-2">Product Name - {{$storeItem->product_name}}</h5>
                        <h5 class="py-2">Purchase Price - {{$storeItem->purchase_price}} MMK</h5>
                        <h5 class="py-2">Selling Price - {{$storeItem->selling_price}} MMK</h5>
                        <h5 class="py-2">Profit - {{$storeItem->profit}} MMK</h5>
                        <h5 class="py-2">Instock - {{$storeItem->qty}}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-1 row">
            @if (!empty($purchase))
                <div class="col-md-6 p-2">
                    <div class="card p-2">
                        <h5 class="text-center">Purchase</h5>
                        <div class="d-flex justify-content-around">
                            <div>
                                <div>Purchase QTY</div>
                                <div>{{$purchase->total_qty}}</div>
                            </div>
                            <div>
                                <div>Purchase Total Price</div>
                                <div>{{$purchase->total_price}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (!empty($orderItem))
                <div class="col-md-6 p-2">
                    <div class="card p-2">
                        <h5 class="text-center">Selling</h5>
                        <div class="d-flex justify-content-around">
                            <div>
                                <div>Selling QTY</div>
                                <div>{{$orderItem->total_qty}}</div>
                            </div>
                            <div>
                                <div>Selling Total Price</div>
                                <div>{{$orderItem->total_price}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
