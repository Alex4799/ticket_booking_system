@extends('main.admin.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

    <div id="selected-movie-label" class="row mx-auto align-items-center mb-3">
        <div class="col-lg-4">
            <a href="{{ url()->previous() }}">
                <button class="btn"><i class="fa-solid fa-circle-left me-2"></i>Change Product</button>
            </a>
        </div>
    </div>

    <!-- payment section start  -->
    <section id="payment">
        <div class="row mx-2 mb-5">
            <div class="col-md-7">
                <div class="pb-3">
                    <div id="booking-total">
                        <h5>Amount Payable</h5>
                        <div class="px-4 mb-4">
                            @foreach ($cart as $item)
                                <div class="d-flex justify-content-between mt-3 payment-cart">
                                    <div class="d-flex">
                                        <div><i class="fa-solid fa-circle"></i></div>
                                        <label>{{$item->product_name}}</label>
                                        <span>{{$item->qty}}</span>
                                    </div>
                                    <label><span>{{$item->product_price*$item->qty}}</span> Kyats</label>
                                </div>
                            @endforeach
                            <div id="promotion">

                            </div>
                        </div>
                        <hr>
                        <div class="d-flex align-items-center justify-content-between mt-3 px-4" id="total-price">
                            <label for="">Total</label>
                            <label><span id="subTotal">{{$totalPrice}}</span> Kyats</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="pb-3">
                    <div id="promo-code">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5>Promotion Code</h5>
                            <i class="fa-solid fa-circle-plus promo-icon"></i>
                        </div>
                        <div id="promo-form">
                            <hr>
                            <div id="promo_alert">

                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="from-group w-75">
                                    <input type="text" id="promo_code" class="form-control" placeholder="Enter promo code">
                                </div>
                                <button class="btn btn-primary" id="add_promotion">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="">
                    <div id="payment-option">
                        <h5>Payment Method</h5>
                        @foreach ($payment as $item)
                            @if ($item->id!=2 && ($item->cinema_id==null || $item->cinema_id==$cinema_id))
                                <div class="px-4 mt-3 parents">
                                    <div class="payment">
                                        <div class="custom-radio d-flex align-items-center mb-1">
                                            <input type="radio" id="paymentMethod" name="paymentMethod" value="{{$item->id}}" class="payment-radio" data-target=".payment-content-{{$item->id}}" @if ($loop->index==0) checked @endif>
                                            <label class="ms-3" for="radio1">{{$item->name}}</label>
                                        </div>
                                    </div>
                                    <div class="payment-content payment-content-{{$item->id}}">
                                        <div class="d-flex align-items-center mb-3">
                                            <strong>{{$item->name}} : </strong>
                                            <label class="ms-3">{{$item->number}}</label>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <strong>Name : </strong>
                                            <label class="ms-3">{{$item->username}}</label>
                                        </div>
                                        @if ($item->image!=null)
                                            <div class="d-flex justify-content-center py-2 align-items-center">
                                                <img src="{{asset('storage/paymentMethod/'.$item->image)}}" class="w-50 img-thumbnail" alt="">
                                            </div>
                                        @endif
                                    </div>
                                    <hr>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div id="" class="">
                    <div id="payment_alert">

                    </div>
                    <form method="POST" action="{{route('seller#orderProduct')}}" enctype="multipart/form-data" id="edit-photo">
                        @csrf
                        <div class="py-2" id="pay_amount">
                            <div>
                                <label for="" class="py-2 fs-4">Pay Amount</label>
                                <input type="number" class="form-control" name="pay_amount">
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
                </div>
                <div class="py-2 d-flex justify-content-end">
                    <button class="btn btn-primary" type="button" id="seller_order">Order</button>
                </div>
            </div>
        </div>
    </section>
    <!-- payment section end  -->

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
                    $('#promotion').html(
                        `<div class="d-flex align-items-center justify-content-between mt-3" id="booking-total-content">
                                        <div class="d-flex align-items-center">
                                            <span>${promotion.name}</span>
                                        </div>
                                        <label><span id="seatTotalPrice">- ${promotion_price}</span> Kyats</label>
                                    </div>`
                    );
                    $('#subTotal').html(total_price);
                    $('#promo-code').hide();
                    $('#promo_code').val('');
                    $('#promotion_id').val(promotion.id);
                    $('#promotion_price').val(promotion_price);
                    $('#total_price').val(total_price);
                }else{
                    $('#promo_alert').html(`
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Your promo code is wrong or promotion period is over.Please check and try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
