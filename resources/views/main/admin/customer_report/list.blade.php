@extends('main.admin.layout.master')
@section('title')
    Customer Report
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div class="table-bg">
            <div>
                <div class="total-count-title">
                    <div class="d-flex align-items-center">
                        <div class="count-content">
                            <h5 class="mb-0">Total - {{$message->total()}}</h5>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Sender</th>
                                <th class="text-center">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($message as $item)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->subject}}</td>
                                    <td>{{$item->email}}</td>
                                    <td class="text-center">
                                        @if ($item->status==1)
                                            <span class="text-success">Seen</span>
                                        @else
                                            <span class="text-danger">Unread</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('admin#customerReportView',$item->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
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
