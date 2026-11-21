@extends('main.admin.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <div class="content px-2 py-3">
        <div class="container-fluid">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Seller</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payment</li>
                    </ol>
                </nav>
            </div>
            <div id="payment">
                <div class="row mb-3">
                    <div class="col-lg-5 order-lg-2">
                        <div id="booking-total">
                            <div class="title mb-3">
                                <div>
                                    <i class="fa-solid fa-wallet me-2"></i>
                                    <span class="fw-bold">Payment Summary</span>
                                </div>
                                <div id="promo-code">
                                    <button class="btn" data-bs-toggle="modal" data-bs-target="#promo">
                                        <i class="fa-solid fa-circle-plus promo-icon me-2"></i>Promo
                                    </button>
                                    <div class="modal fade" id="promo" tabindex="-1" aria-labelledby="promo-modalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title d-block w-100 text-center" id="add-PaymentLabel">Add Promotion</h5>
                                                    <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="from-group mb-4">
                                                        <input type="text" id="promo_code" class="form-control" placeholder="Enter promo code">
                                                    </div>
                                                    <div class="d-flex align-items-center my-2">
                                                        <button class="action-btn w-100" id="add_promotion" data-bs-dismiss="modal"><i class="fa-solid fa-square-plus me-2"></i>Apply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <small>Total Items ( <span>{{ count((array) $cart) }}</span> )</small>
                            <div class="ticket-content mt-3">
                                <div class="mx-4">
                                    @foreach ($cart as $item)
                                        <div class="d-flex align-items-center justify-content-between gap-5 mb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="summary-item-qty">{{$item->qty}}</span>
                                                <i class="fa-solid fa-xmark summary-item-x"></i>
                                                <label class="summary-item-product">{{$item->product_name}}</label>
                                            </div>
                                            <label class="text-nowrap">
                                                <span>{{number_format($item->product_price * $item->qty)}}</span> Ks
                                            </label>
                                        </div>
                                    @endforeach
                                    <div id="promotion"></div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between px-4 pb-1" id="total-price">
                                    <label for="">Total</label>
                                    <label><span id="subTotal">{{number_format($totalPrice)}}</span> Ks</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 pe-lg-4 order-lg-1">
                        <div>
                            <div id="promo_alert"></div>
                            <div id="payment_alert"></div>
                            <div id="payment-option">
                                <div class="title mb-3">
                                    <div>
                                        <i class="fa-solid fa-credit-card me-2"></i>
                                        <span class="fw-bold">Payment Method</span>
                                    </div>
                                </div>
                                <div class="payment-grid">
                                    @foreach ($payment as $item)
                                        @if ($item->id!=2 && ($item->cinema_id==null || $item->cinema_id==$cinema_id))
                                            <label class="payment-card parents">
                                                <div class="payment">
                                                    <input type="radio" id="paymentMethod" name="paymentMethod" value="{{$item->id}}" class="payment-radio" data-target="#payment-{{$item->id}}" @if($loop->first) checked @endif>
                                                    <div class="payment-card-body">
                                                        @if($item->id == 1)
                                                            <i class="fa-solid fa-money-bill-wave payment-icon"></i>
                                                        @else
                                                            <i class="fa-solid fa-mobile-screen-button payment-icon"></i>
                                                        @endif
                                                        <h6>{{$item->name}}</h6>
                                                        <span class="check">
                                                            <i class="fa-solid fa-circle-check"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="payment-details mt-4">
                                    @foreach ($payment as $item)
                                        @if ($item->id!=2 && ($item->cinema_id==null || $item->cinema_id==$cinema_id))
                                            <div class="payment-info @if(!$loop->first) d-none @endif" id="payment-{{$item->id}}">
                                                <div class="row align-items-center">
                                                    <div class="col-md-7">
                                                        <div class="info-item">
                                                            <label>Payment Type</label>
                                                            <h6>{{$item->name}}</h6>
                                                        </div>
                                                        @if($item->id != 1)
                                                            <div class="info-item">
                                                                <label>Phone Number</label>
                                                                <h6>{{$item->number}}</h6>
                                                            </div>
                                                            <div class="info-item">
                                                                <label>Account Name</label>
                                                                <h6>{{$item->username}}</h6>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @if($item->image)
                                                        <div class="col-md-5 text-center">
                                                            <img src="{{Storage::url('payment/'.$item->image)}}" class="payment-qr">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div id="paying-amount">
                                <div class="title mb-3">
                                    <div class="pay_amount">
                                        <i class="fa-solid fa-money-bill me-2"></i>
                                        <span class="fw-bold">Pay Amount</span>
                                    </div>
                                </div>
                                <div class="content">
                                    <form method="POST" action="{{route('seller#orderProduct')}}" enctype="multipart/form-data" id="edit-photo">
                                        @csrf
                                        <div class="pay_amount">
                                            <div>
                                                <input type="number" class="form-control" name="pay_amount" placeholder="Enter customer payment amount">
                                            </div>
                                        </div>
                                        <span>
                                            <input type="hidden" name="cinema_id" value="{{$cinema_id}}">
                                            <input type="hidden" name="payment_method" id="payment_method">
                                            <input type="hidden" name="promotion_id" id="promotion_id">
                                            <input type="hidden" name="promotion_price" id="promotion_price">
                                            <input type="hidden" name="total_price" id="total_price" value="{{$totalPrice}}">
                                            <input type="hidden" name="product" id="product">
                                        </span>
                                    </form>
                                    <div class="my-3 d-flex justify-content-end gap-1">
                                        <button class="btn" type="button" id="seller_order">
                                            <i class="fa-regular fa-circle-check me-1"></i>
                                            <span>Confirm Order</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    $(document).ready(function(){
        let promotion_id=null;
        let orderProduct=@json($cart);
        let total_price={{$totalPrice}};
        let promotion_price=0;
        let paymentMethod=1;

    $('#add_promotion').click(function(){
        let promo_code=$('#promo_code').val();
        let data={
                promoCode:promo_code,
                cinema_id:`{{$cinema_id}}`,
                hall_id:0,
                movie_id:0
            }
        $.ajax({
            type:'get',
            data:data,
            dataType:'json',
            url:`{{route('checkPromotion')}}`,
            success:function(data){
                if(data.status=='success'){
                    let promotion=data.data;
                    if (promotion.percentage!=null) {
                        promotion_price=total_price*(promotion.percentage/100);
                        if (promotion.amount!=null) {
                            promotion_price=promotion_price>promotion.amount?promotion.amount:promotion_price;
                        }
                    }else{
                        promotion_price=promotion.amount;
                    }
                    total_price=total_price-promotion_price;
                    $('#promotion').html(`
                        <div class="d-flex align-items-center justify-content-between gap-3 mt-3 border-top pt-3" id="taxes">
                            <label>${promotion.name}</label>
                            <label class="text-nowrap"> - ${Number(promotion_price).toLocaleString()} Ks</label>
                        </div>`
                    );
                    $('#subTotal').html(total_price.toLocaleString());
                    $('#promo-code').hide();
                    $('#promo_code').val('');
                    $('#promotion_id').val(promotion.id);
                    $('#promotion_price').val(promotion_price);
                    $('#total_price').val(total_price);
                }else{
                    $('#promo_alert').html(`
                        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="font-size: 10pt;">
                            Your promo code is wrong or promotion period is over. Please check and try again.
                            <button type="button" class="btn-close border-none shadow-none btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            }
        })
    })

        $('.payment').click(function(){
            let parents=$(this).parents('.parents');
            paymentMethod=parents.find('#paymentMethod').val();
            if (paymentMethod==1) {
                $('#pay_amount').show();
            }else{
                $('#pay_amount').hide();
            }

        })

        $('#seller_order').click(function(){
            paymentMethod=$('input[name="paymentMethod"]:checked').val();
            $(this).prop('disabled', true); // Disable the button
            $(this).text('Processing...');
            localStorage.setItem('cart','{}');
            sendForm();
        })

        function sendForm(){
            $('#payment_method').val(paymentMethod),
            $('#product').val(JSON.stringify(orderProduct)),
            $('#edit-photo').submit();
        }


    })
</script>
@endsection
