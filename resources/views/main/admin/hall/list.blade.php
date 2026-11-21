@extends('main.admin.layout.master')
@section('title')
    Hall
@endsection

@section('content')
    <main class="content p-2 mb-5">
        <div class="container-fluid">
            <div class="table-bg">
                <div>
                    <div class="total-count-title">
                        <div class="d-flex align-items-center">
                            <div class="count-content">
                                <h5 class="mb-0">Total - {{$hall->total()}}</h5>
                            </div>
                            @if (Auth::user()->position=='developer')
                                <div class="ms-3">
                                    <button class="add-btn" data-bs-toggle="modal" data-bs-target="#add-hall">
                                        <i class="fa-solid fa-square-plus me-2"></i>Add Hall
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex align-items-center">
                            <form action="" method="get" id="search-form">
                                <div class="search-cinema">
                                    <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                                    <input placeholder="Search" value="{{request('search_key')}}" name="search_key" type="text" class="input">
                                </div>
                            </form>
                            <a href="{{route('admin#hallList')}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
                    @if (count($hall)!=0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Hall Name</th>
                                        <th>Cinema Name</th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hall as $item)
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{$item->name}}@if ($item->du_id!=null) (Copy) @endif</td>
                                            <td>{{$item->cinema_name}}</td>
                                            <td class="text-center">
                                                @if ($item->active==1)
                                                    <span class="text-success">Active</span>
                                                @else
                                                    <span class="text-danger">Not Active</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('admin#hallView',$item->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div>
                                {{$hall->appends(request()->query())->links()}}
                            </div>
                        </div>
                    @else
                        <h6 class="text-center mt-5 mb-4 text-secondary" style="font-size: 11pt;">There is no hall.</h6>
                    @endif
                </div>
            </div>

            {{-- modal  --}}
            <div class="modal fade" id="add-hall" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addHallLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="addHallLabel">Add Hall</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#hallCreate')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Hall Name</label>
                                        <input id="" name="name" type="text" class="form-control @error('name') is-invalid @enderror" required>
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Hall Seat Column</label>
                                        <input id="" name="seat_column" type="number" class="form-control @error('seat_column') is-invalid @enderror" required>
                                        @error('seat_column')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Cinema</label>
                                        <select name="cinema_id" id="" class="form-select @error('cinema_id') is-invalid @enderror" required>
                                            <option value="">Choose Cinema</option>
                                            @foreach ($cinema as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('cinema_id')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 form-group mb-4">
                                        <label for="mb-1">Status</label>
                                        <select name="active" id="" class="form-select @error('active') is-invalid @enderror" required>
                                            <option value="">Choose Status</option>
                                            <option value="0">Not Active</option>
                                            <option value="1">Active</option>
                                        </select>
                                        @error('active')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex align-items-center my-2">
                                    <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
