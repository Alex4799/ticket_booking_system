@extends('mail.layout')
@section('title')
    {{$mailData['subject']}}
@endsection
@section('content')
    <div class="p-3">
        <h3>{{$mailData['subject']}}</h3>
        <p>{!!$mailData['body']!!}</p>
      </div>
@endsection
