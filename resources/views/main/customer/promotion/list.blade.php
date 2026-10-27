@extends('main.customer.layout.master')
@section('content')
    <!-- promotion start  -->
    @if (count($promotion)!=0)
        <section id="promotion-page">
            <div class="mb-5" id="promotion-list">
                <div class="row mt-4">
                    @foreach ($promotion as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <a href="{{route('customer#promotionView',$item->id)}}">
                            <img src="{{Storage::url('promotion/'.$item->image)}}" class="w-100 d-block rounded" alt="">
                            <h5 class="mt-3">{{$item->name}}</h5>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <div class="py-5 px-3 mt-5">
            <h6 class="text-center text-secondary" style="font-size: 10pt;">No promotions available at the moment.</h6>
        </div>
    @endif
    <!-- promotion end -->

@endsection
