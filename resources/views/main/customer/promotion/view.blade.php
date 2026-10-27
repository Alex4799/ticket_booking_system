@extends('main.customer.layout.master')
@section('content')
@php
    use Carbon\Carbon;
@endphp
    <!-- promotion details start  -->
    <section class="container-lg" id="promotion-details">
        <div id="promotion-content">
            <h4>{{$promotion->name}}</h5>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <img src="{{Storage::url('promotion/'.$promotion->image)}}" alt="" class="w-100 d-block rounded">
                </div>
                <div class="col-lg-8 col-md-8 px-5 order-md-first">
                    <div class="mb-4">
                        <strong>Promotion Period :</strong>
                        <p>{{Carbon::parse($promotion->start_date)->format('F j, Y')}} To {{Carbon::parse($promotion->end_date)->format('F j, Y')}} | while stocks last</p>
                    </div>
                    <div class="mb-4">
                        <strong>What's in the offer :</strong>
                        <p style="margin-top: -10px">{!!$promotion->description!!}</p>
                    </div>
                    <div class="mb-4">
                        <strong>Promotion :</strong>
                        @if ($promotion->amount!=null)
                            <p>{{$promotion->amount}} MMK off for @if ($promotion->cinema_id!=0)
                                {{$promotion->cinema_name}}
                            @else
                                All Cinema.
                            @endif</p>
                        @elseif ($promotion->percentage!=null)
                            <p>{{$promotion->percentage}}% off for @if ($promotion->cinema_id!=0)
                                {{$promotion->cinema_name}}
                            @else
                                All Cinema.
                            @endif</p>
                        @endif
                    </div>
                    <div class="mb-4">
                        <strong>Promo Code :</strong>
                        <p>{{$promotion->promo_code}}</p>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- promotion details end -->

@endsection
