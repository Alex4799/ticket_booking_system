@extends('mail.layout')
@section('title')
    {{$mailData['title']}}
@endsection
@section('content')
    <div class="p-3">
        <h3>{{$mailData['title']}} .</h3>
        <p>{!!$mailData['body']!!}</p>
        @if ($mailData['status']!=1)
            <a href="https://megaacecineplex.com/contact">You can contact to admin from here.</a>
        @else
            <a href="https://megaacecineplex.com/order/check">You can check from here.</a>
        @endif
    </div>
@endsection
