@extends('mail.layout')
@section('title')
    Purchase
@endsection
@section('content')
    <div class="p-3">
        <h3>Purchase {{$mailData['status']}}</h3>
        <p>{{$mailData['body']}}</p>
    </div>
@endsection
