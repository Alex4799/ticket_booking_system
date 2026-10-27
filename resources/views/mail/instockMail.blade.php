@extends('mail.layout')
@section('title')
    Instock Alert
@endsection
@section('content')
    <div class="p-3">
        <h3>Instock Alert</h3>
        <p>{{$mailData['body']}}</p>
    </div>
@endsection
