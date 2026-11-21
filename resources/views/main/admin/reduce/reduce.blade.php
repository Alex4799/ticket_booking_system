@extends('main.admin.layout.master')
@section('title')
    Reduce
@endsection
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div class="table-bg">
            <div>
                <div class="total-count-title">
                    <div class="d-flex align-items-center">
                        <div class="count-content">
                            <h5 class="mb-0">Total - {{$reduce->total()}} <span class="mx-2">( {{number_format($total_price)}} Ks )</span></h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <form action="" method="get" id="search-form">
                            <div class="search-cinema">
                                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                                <input placeholder="Search" value="{{request('search_key')}}" name="search_key" type="text" class="input">
                            </div>
                        </form>
                        <a href="{{route('admin#reduceList')}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                    </div>
                </div>
                <form method="get" action="" class="mt-3 bg-body-secondary rounded px-3 pb-2">
                    <div class="py-2 d-flex align-items-center gap-2 flex-wrap">
                        <div class="me-2">
                            <label class="label-small mb-0">Product</label>
                            <select name="product" class="form-select" style="font-size: 11pt;">
                                <option value="">All</option>
                                @foreach ($product as $item)
                                    <option value="{{$item}}" @if ($item==request('product')) selected @endif>{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex gap-3 flex-wrap">
                            <div>
                                <label class="label-small mb-0">Start Date</label>
                                <input type="date" style="font-size: 11pt;" name="start_date" class="form-control" value="{{request('start_date')}}">
                            </div>
                            <div>
                                <label class="label-small mb-0">End Date</label>
                                <input type="date" style="font-size: 11pt;" name="end_date" class="form-control" value="{{request('end_date')}}">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-end w-100">
                        <button class="d-flex align-items-center btn btn-primary me-2" style="font-size: 10pt;"><i class="fa-solid fa-filter me-2" style="font-size: 8pt;"></i>Filter</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Reduce By</th>
                                <th class="text-center">QTY</th>
                                <th class="text-end">Total Price</th>
                                <th class="text-center">Date & Time</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reduce as $item)
                            <tr>
                                <td>PID - {{$item->id}}</td>
                                <td>{{$item->product_name}}</td>
                                <td>{{$item->user_name}}</td>
                                <td class="text-center">{{$item->qty}}</td>
                                <td class="text-end">{{number_format($item->total_price)}}</td>
                                <td class="text-center">{{Carbon::parse($item->created_at)->format('d/m/Y - h:i A')}}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm delete" data-bs-toggle="modal" data-bs-target="#delete-reduce{{$item->id}}"><i class="fa-solid fa-trash"></i></button>
                                </td>
                                <div class="modal fade" id="delete-reduce{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-reduceLabel{{$item->id}}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title d-block w-100 text-center" id="delete-reduceLabel{{$item->id}}">Delete Reduce</h5>
                                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('admin#reduceDelete')}}" method="post" class="mt-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="delete-confirmation">
                                                        <div class="delete-warning">
                                                            <div class="delete-icon">
                                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                            </div>
                                                            <div class="delete-message">
                                                                <h6>Are you sure to delete PID - {{$item->id}}?</h6>
                                                                <small>Verify you are admin. Please enter the password.</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group my-3">
                                                        <label class="mb-1">Password</label>
                                                        <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                                                        @error('password')
                                                            <small class="text-danger">{{$message}}</small>
                                                        @enderror
                                                    </div>

                                                    <input type="hidden" name="id" value="{{$item->id}}">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{$reduce->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
