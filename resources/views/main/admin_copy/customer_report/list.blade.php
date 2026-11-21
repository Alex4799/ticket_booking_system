@extends('main.admin.layout.master')
@section('title')
    Customer Report
@endsection
@section('content')
<main class="content px-3 py-2 mb-5">
    <div class="p-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{session('success')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{session('warning')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('danger'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{session('danger')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    <div class="container-fluid">
        <div id="table-bg">
            <div class="d-flex align-items-center justify-content-between">
                <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
                    <h5>Total - {{$message->total()}}</h5>
                </div>
            </div>
            <div class="my-3">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Send Email</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($message as $item)
                            <tr class="@if ($item->status!=1) bg-body-secondary @endif">
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->subject}}</td>
                                <td>{{$item->email}}</td>
                                <td>
                                    <a href="{{route('admin#customerReportView',$item->id)}}" class="btn btn-outline-primary">See More</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{$message->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
