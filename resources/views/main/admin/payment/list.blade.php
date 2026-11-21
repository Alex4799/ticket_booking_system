@extends('main.admin.layout.master')
@section('title')
    Payment
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div class="table-bg">
            <div>
                <div class="total-count-title">
                    <div class="d-flex align-items-center">
                        <div class="count-content">
                            <h5 class="mb-0">Total - {{$payment->total()}}</h5>
                        </div>
                        <div class="ms-3">
                            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#add-Payment">
                                <i class="fa-solid fa-square-plus me-2"></i>Add Payment
                            </button>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <form action="" method="get" id="search-form">
                            <div class="search-cinema">
                                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                                <input placeholder="Search" value="{{request('search_key')}}" name="search_key" type="text" class="input">
                            </div>
                        </form>
                        <a href="{{route('admin#paymentList')}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Number</th>
                                <th>User Name</th>
                                <th>Cinema</th>
                                <th class="text-center">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($payment)!=0)
                                @foreach ($payment as $item)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->number}}</td>
                                    <td>{{$item->username}}</td>
                                    <td>{{$item->cinema_name??'All'}}</td>
                                    <td class="text-center">
                                        @if ($item->active==1)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Not Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('admin#paymentView',$item->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div>
                        {{$payment->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="add-Payment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-PaymentLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-block w-100 text-center" id="add-PaymentLabel">Add Payment</h5>
                        <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#paymentCreate')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-7 form-group mb-4">
                                    <label class="mb-1">Cinema</label>
                                    <select name="cinema_id" id="" class="form-select @error('cinema_id') is-invalid @enderror">
                                        <option value="">Choose Cinema</option>
                                        @foreach ($cinema as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('cinema_id')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-5 form-group mb-4">
                                    <label class="mb-1">Status</label>
                                    <select name="active" id="" class="form-select">
                                        <option value="0">Not Active</option>
                                        <option value="1">Active</option>
                                    </select>
                                    @error('active')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Method Name</label>
                                        <input id="" name="name" type="text" class="form-control @error('name') is-invalid @enderror" required>
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="mb-1">User Name</label>
                                        <input id="" name="username" type="text" class="form-control @error('username') is-invalid @enderror" required>
                                        @error('username')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Number</label>
                                        <input id="" name="number" type="number" class="form-control @error('number') is-invalid @enderror" required>
                                        @error('number')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-5 ps-md-4">
                                    <div class="form-group mb-4">
                                        <label class="mb-1">QR Image</label>
                                        <div>
                                            <img src="{{asset('image/placeholder.webp')}}" alt="image" class="img-thumbnail" id="image">
                                            <div>
                                                <label for="input_image" class="file-pen-logo mt-3">
                                                    <span><i class="fa-solid fa-upload me-1"></i> Upload Image</span>
                                                </label>
                                                <input type="file" name="image" class="d-none @error('image') is-invalid  @enderror" id="input_image" accept="image/*">
                                            </div>
                                            @error('image')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
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
@section('script')
<script>
    $(document).ready(function(){
        $('#input_image').change(function(){
            document.getElementById('image').src = window.URL.createObjectURL(this.files[0]);
        })
    });
</script>
@endsection
