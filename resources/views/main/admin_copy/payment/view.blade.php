@extends('main.admin.layout.master')
@section('title')
    Payment
@endsection
@section('content')
            <!-- general details start  -->
            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <div>
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{route('admin#paymentList')}}">List</a></li>
                              <li class="breadcrumb-item active" aria-current="page">View</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row gap-1">
                        <div class="col-md-10 offset-md-1">
                            <div class="card shadow border-0 p-4">
                                <h4 class="text-center mb-5">View Payment</h4>
                                <div class="row">
                                    @if ($payment->image!=null)
                                    <div class="col-md-6">
                                        <img src="{{Storage::url('payment/'.$payment->image)}}" class="w-100 mx-auto img-thumbnail" alt="">
                                    </div>
                                    @endif
                                    <div class=" col-md-6">
                                        <h5 class="py-2">Name - {{$payment->name}}</h5>
                                        <h5 class="py-2">User Name - {{$payment->username}}</h5>
                                        <h5 class="py-2">Number - {{$payment->number}}</h5>
                                        <h5 class="py-2">Cinema - {{$payment->cinema_name??'All'}}</h5>
                                        <h5 class="">Acitve - @if ($payment->active==0)
                                                        <span class="text-danger">Not Active</span>
                                                    @else
                                                        <span class="text-success">Active</span>
                                                    @endif
                                                </h5>
                                    </div>
                                </div>
                                <div class=" d-flex justify-content-end gap-1 mt-4">
                                    <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#edit-seat">Edit</button>
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-seat">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- general details end  -->

            {{-- model start --}}
            <div>
                <div class="modal fade" id="edit-seat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-seatLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="edit-seatLabel">Edit Payment </h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#paymentUpdate')}}" method="post" class="" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Method Name</label>
                                        <input id="" value="{{$payment->name}}" name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter payment method name">
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="mb-1">User Name</label>
                                        <input id="" value="{{$payment->username}}" name="username" type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Enter username">
                                        @error('username')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="mb-1">Number</label>
                                        <input id="" value="{{$payment->number}}" name="number" type="number" class="form-control @error('number') is-invalid @enderror" placeholder="Enter number">
                                        @error('number')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="mb-1">QR Image</label>
                                        <div class="row">
                                            <div class="col-md-6 py-2">
                                                @if ($payment->image)
                                                    <img src="{{asset('image/placeholder.webp')}}" alt="Profile" class="w-100 img-thumbnail" id="image">
                                                @else
                                                    <img src="{{Storage::url('payment/'.$payment->image)}}" class="w-100 img-thumbnail" alt="">
                                                @endif
                                            </div>
                                            <div class="col-md-6 py-2 m-auto">
                                                <input type="file" name="image" class="form-control @error('image') is-invalid  @enderror" id="input_image" accept="image/*">
                                                @error('image')
                                                    <small class=" text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="mb-1">Cinema</label>
                                        <select name="cinema_id" id="" class="form-control @error('cinema_id') is-invalid @enderror">
                                            <option value="">Choose Cinema</option>
                                            @foreach ($cinema as $item)
                                                <option value="{{$item->id}}" @if ($payment->cinema_id==$item->id) selected @endif>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('cinema_id')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="name" class="form-label @error('active') is-invalid @enderror">Active</label>
                                        <select name="active" id="" class="form-control ">
                                            <option value="">Choose Active Status</option>
                                            <option value="0" @if ($payment->active==0) selected @endif>Not Active</option>
                                            <option value="1" @if ($payment->active==1) selected @endif>Active</option>
                                        </select>
                                        @error('active')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="id" value="{{$payment->id}}">

                                    <button type="submit" class="btn btn-primary w-100 my-2">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="delete-seat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-seatLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="delete-seatLabel">Delete Payment</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#paymentDelete')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <div class="py-1">
                                        <p class="text-danger">Are you sure to delete this?</p>
                                    </div>
                                    <div class="py-1">
                                        <h6>Verify you are admin. Please enter the password.</h6>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Password</label>
                                        <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password." required>
                                        @error('password')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="id" value="{{$payment->id}}">

                                    <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- model end --}}
@endsection
@section('script')

@endsection
