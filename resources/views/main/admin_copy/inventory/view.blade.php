@extends('main.admin.layout.master')
@section('title')
    Product
@endsection
@section('content')
<main class="content px-4 py-3 mb-5">
    <div>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{route('admin#inventoryList')}}">List</a></li>
              <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </nav>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card p-2 border-0 shadow">
                    <h4 class="text-center py-2 mb-4">View Purchase</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{Storage::url('product/'.$inventory->product_image)}}" class="w-100 img-thumbnail" alt="">
                        </div>
                        <div class="col-md-8">
                            <div class="py-2">
                                <h5>Product Name - {{$inventory->product_name}}</h5>
                            </div>
                            <div class="py-2">
                                <h5>Product QTY - {{$inventory->qty}}</h5>
                            </div>
                            <div class="py-2">
                                <h5>Product Price - {{$inventory->price}} MMK</h5>
                            </div>
                            <div class="py-2">
                                <h5>Total Price - {{$inventory->total_price}} MMK</h5>
                            </div>
                            <div class="py-2">
                                <h5>Send Store - {{$inventory->send_store_name}}</h5>
                            </div>
                            <div class="py-2">
                                <h5>Receive Store - {{$inventory->receive_store_name}}</h5>
                            </div>
                            <div class="py-2">
                                <h5>Status - @if ($inventory->status==0)
                                    <span class="text-warning">Pending</span>
                                @elseif($inventory->status==1)
                                    <span class="text-success">Success</span>
                                @else
                                    <span class="text-danger">Reject</span>
                                @endif</h5>
                            </div>
                            <div class="py-2 d-flex justify-content-end gap-1">
                                <div>
                                    @if ($inventory->status==0)
                                        <div class="dropdown">
                                            <button class="btn @if ($inventory->status==0) btn-warning  @elseif ($inventory->status==1) btn-success @elseif ($inventory->status==2) btn-danger @endif dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            @if ($inventory->status==0)
                                                Pending
                                            @elseif ($inventory->status==1)
                                                Success
                                            @elseif ($inventory->status==2)
                                                Reject
                                            @endif
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{route('admin#inventoryChangeStatus',[$inventory->id,1])}}">Success</a></li>
                                                <li><a class="dropdown-item" href="{{route('admin#inventoryChangeStatus',[$inventory->id,2])}}">Reject</a></li>
                                            </ul>
                                        </div>
                                    @elseif ($inventory->status==1)
                                        <button class="btn btn-outline-success">Success</button>
                                    @elseif ($inventory->status==2)
                                        <button class="btn btn-outline-danger">Reject</button>
                                    @endif

                                </div>
                                @if ($inventory->status!=1)
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-inventory">Delete</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="delete-inventory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-inventoryLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center text-danger" id="delete-inventoryLabel">Delete Purchase</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#inventoryDelete')}}" method="post" class="mt-1">
                            @csrf
                            @method('DELETE')
                            <div class="py-1">
                                <p class="text-danger">"Are you sure to delete this."</p>
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

                            <input type="hidden" name="id" value="{{$inventory->id}}">

                            <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('script')

@endsection
