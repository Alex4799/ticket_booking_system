@extends('main.customer.layout.master')
@section('content')

    <!-- time table start  -->
    <section id="time-table">
        <div class="header">
            <h5 class="text-center mx-auto">
                <span class="fw-bold">{{$website->name}}</span>
                <br>
                <span>Movie Show Time (ရုံများတွင် ပြသမည့်ရုပ်ရှင် နှင့် ပွဲချိန်များ)</span>
            </h5>
            <div class="my-5">
                @foreach ($cinema as $item)
                    <a href="{{route('customer#timeTable',$item->id)}}">
                        <span><i class="fa-solid fa-location-dot me-2"></i> {{$item->name}}</span>
                        <span class="ms-5"><i class="fa-solid fa-angle-right"></i></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    <!-- time table end  -->

@endsection
@section('script')
@endsection
