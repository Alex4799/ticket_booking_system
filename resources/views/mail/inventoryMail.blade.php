@extends('mail.layout')
@section('title')
    Inventory
@endsection
@section('content')
    <div class="p-3">
        <h3>Inventory {{$mailData['status']}}</h3>
        <p>{{$mailData['body']}}</p>
    </div>
@endsection
