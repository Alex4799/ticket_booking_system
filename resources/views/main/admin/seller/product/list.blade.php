@extends('main.admin.layout.master')
@section('body-class', 'product-page')

@section('title')
    Product
@endsection
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp
<main class="content px-3 mb-5">
    <div style="width: 0; height:0; overflow:hidden;">
        <input type="text" id="barcode" autocomplete="off">
    </div>
    <div class="row product-seller-container py-2">
        <div class="col-lg-8 col-md-6">
            <div class="d-flex justify-content-between pb-2">
                <div>
                    <select class="form-control" id="category_filter">
                        <option value="0">All</option>
                        @foreach ($category as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                @if (in_array(Auth::user()->position,['developer','admin','CEO']))
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @foreach ($cinema as $item)
                                @if (request('cinema_id')!=null)
                                    @if (request('cinema_id')==$item->id)
                                        {{$item->name}}
                                    @endif
                                @elseif($loop->index==0)
                                    {{$item->name}}
                                @endif
                            @endforeach
                        </button>
                        <ul class="dropdown-menu">
                            @foreach ($cinema as $item)
                                <li><a class="dropdown-item" href="{{route('seller#cart',['cinema_id'=>$item->id])}}">{{$item->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#barcodeInput">Barcode</button>
                </div>
            </div>
            <div class="">
                <div class="row mx-auto snack-list">
                    @foreach ($storeItem as $item)
                        <div class="col-lg-3 col-sm-6 mb-4">
                            <div class="product-parents">
                                <div class="snack-content">
                                    <button class="add-to-cart w-100">
                                        <div class="position-relative">
                                            <img src="{{Storage::url('product/'.$item->product_image)}}" class="w-100 img-thumbnail" alt="">
                                            <p>
                                                @if ($item->type)
                                                    &infin;
                                                @else
                                                    {{$item->qty}}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="mt-3">
                                            <h6 id="product-name">{{$item->product_name}} </h6>
                                            <span id="product-price">{{$item->selling_price}}</span> MMK
                                        </div>
                                    </button>
                                </div>
                                <input type="hidden" id="product_id" value="{{$item->id}}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 ps-0 pe-1">
            <div id="snack-cart">
                <div id="cart-detail-content">
                    <h5 class="text-center">Cart Item ( <span class="cart_count">0</span> )</h5>
                    <div class="cart-details shadow rounded position-relative">

                        <div id="no_cart_data">
                            <h6 class="text-center text-secondary" style="font-size: 10pt;"><i class="fa-solid fa-cart-shopping me-2"></i>Nothing in the cart yet.<h6>
                        </div>

                        <div id="cart"></div>
                        <div class="p-2">
                            <div class="d-flex justify-content-end my-2 me-2">
                                <label>Total:</label>
                                <h5 class="ms-3"><span id="sub-total">0</span> MMK</h5>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <button class="btn btn-danger w-100 me-2" id="clearCart">
                                    <span>Clear Cart</span>
                                </button>
                                <button class="btn btn-success w-100" id="sendOrder">
                                    <span>Confirm Order</span>
                                </button>
                            </div>
                        </div>
                        <form action="{{route('seller#checkOutProduct')}}" method="post" class="d-flex justify-content-end me-3" id="order">
                            @csrf
                            <input type="hidden" name="data" id="data">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="barcodeInput" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="barcodeInputLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title d-block w-100 text-center" id="barcodeInputLabel">Barcode</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="barcodeAlert"></div>
                    <div class="form-group mb-4">
                        <label class="mb-1">Barcode</label>
                        <input id="manuralBarcode" name="name" type="text" class="form-control" placeholder="Enter barcode">
                    </div>
                    <div>
                        <button id="manuralBarcodeButton" class="btn btn-primary w-100 my-2">Enter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@section('script')
<script>
    $(document).ready(function(){
        let product=[];
        let barCodeProduct=[];
        let cart=JSON.parse(localStorage.getItem('cart'))??{};

        function getData(){
            let storeItem=@json($storeItem);
            storeItem.forEach(element => {
                product[element.id]=element;
                if (element.barcode!=null) {
                    barCodeProduct[element.barcode]=element;
                }
            });
            let overQty=@json(session('overQty'));
            if (overQty) {
                cart[overQty.id].qty=overQty.max_qty;
            }
        }

        function renderCart(){
            if (Object.values(cart).length==0) {
                $('#no_cart_data').show();
            }else{
                $('#no_cart_data').hide();
            }
            let cartHTML = '';
            let totalPrice=0;

            Object.values(cart).forEach((item, index) => {
                cartHTML += `
                    <div class="cart-content mb-3 d-flex align-items-center cart-parents justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="ms-2">
                                <h5>${item.product_name}</h5>
                                <label>${item.product_price} Ks</label>
                                <input type="hidden" class="id" value="${item.id}">
                            </div>
                        </div>
                        <div class="d-flex align-items-center" style="width:130px;">
                            <div class="input-group quantity">
                                <button class="btn btn-warning btn-sm btn-minus"><i class="fa fa-minus"></i></button>
                                <input type="text" class="form-control bg-body-secondary border-0 text-center orderCount" value="${item.qty}">
                                <button class="btn btn-warning btn-sm btn-plus"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div><span>${item.product_price * item.qty}</span></div>
                        <div><button class="btn btn-remove btn-danger remove"><i class="fa fa-times"></i></button></div>
                    </div>
                `;
                totalPrice+=item.product_price * item.qty;
            });

            $('#cart').html(cartHTML);
            $('.cart_count').html(Object.values(cart).length);
            $('#sub-total').html(totalPrice);
            focusBarcodeInput();
            localStorage.setItem('cart',JSON.stringify(cart));
        }

        $(document).on("click",'.add-to-cart',function(){
            let id=$(this).parents('.product-parents').find('#product_id').val();
            let currentProduct=product[id];
            addCart(currentProduct,id);
        })

        $(document).on('click','.btn-plus',function(){
            let id=$(this).parents('.cart-parents').find('.id').val();
            let cartItem=cart[id];
            let cartProduct=product[id];
            if (cartProduct.type==0) {
                if (cartProduct.qty>cartItem.qty) {
                        cart[id].qty+=1;
                }
            }else{
                cart[id].qty+=1;
            }
            renderCart();
        })

        $(document).on('click','.btn-minus',function(){
            let id=$(this).parents('.cart-parents').find('.id').val();
            if (cart[id].qty>1) {
                cart[id].qty--;
            }
            renderCart();
        })

        $(document).on('click','.btn-remove',function(){
            let id=$(this).parents('.cart-parents').find('.id').val();
            delete cart[id];
            renderCart();
        })

        $('#clearCart').click(function(){
            cart={};
            renderCart();
        })

        $('#manuralBarcodeButton').click(function(){
            let barcode=$('#manuralBarcode').val();
            let currentProduct=barCodeProduct[barcode];
            if (currentProduct!=undefined) {
                addCart(currentProduct,currentProduct.id);
                $('#barcodeInput').modal('hide');
            }else{
                $('#barcodeAlert').html(`
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        No product found. Wrong barcode.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }
        })

        function addCart(product,id){
            if (cart[id]!=undefined) {
                if (product.type==0) {
                    if (cart[id].qty<product.qty) {
                        cart[id].qty+=1;
                    }
                }else{
                    cart[id].qty+=1;
                }
            }else{
                let temp={
                    'id':id,
                    'product_name':product.product_name,
                    'qty':1,
                    'product_price':product.selling_price,
                };
                cart[id]=temp;
            }
            renderCart();
        }

        $(document).on("click", function(e) {
            if (!$(e.target).is("input, textarea, select, button")) {
                focusBarcodeInput();
            }
        });

        function focusBarcodeInput() {
            let barcodeInput = $("#barcode");
            barcodeInput.val("").trigger("focus"); // Clear input & force focus
        }

        $('#category_filter').change(function(){
            let categoryId=$(this).val();
            if (categoryId!=0) {
                filterData=product.filter((element)=>categoryId==element.category_id);
                renderItem(filterData);
            }else{
                renderItem(product);
            }
        })

        function renderItem(items){
            let temp='';
            let imageUrl=`{{ env('AWS_URL', 'http://127.0.0.1:8000') }}`
            items.forEach(function(element){
                temp+=`
                        <div class="col-lg-3 col-sm-6 mb-4">
                            <div class="product-parents">
                                <div class="snack-content">
                                    <button class="add-to-cart w-100">
                                        <div class="position-relative">
                                            <img src="${imageUrl}/product/${element.product_image}" class="w-100 img-thumbnail" alt="">
                                            <p>
                                                ${element.type? '&infin;' : element.qty}
                                            </p>
                                        </div>
                                        <div class="mt-3">
                                            <h6 id="product-name">${element.product_name} </h6>
                                            <span id="product-price">${element.selling_price}</span> MMK
                                        </div>
                                    </button>
                                </div>
                                <input type="hidden" id="product_id" value="${element.id}">
                            </div>
                        </div>
                `
            });
            $('.snack-list').html(temp);
        }

        $('#sendOrder').click(function(){
            $('#data').val(JSON.stringify(cart));
            $('#order').submit();
        })

        getData();
        focusBarcodeInput();
        renderCart();
    })
</script>
@endsection
