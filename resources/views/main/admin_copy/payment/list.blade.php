@extends('main.admin.layout.master')
@section('title')
    Payment
@endsection
@section('content')
<main class="content px-3 pt-2 mb-5">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
                <h5>Total - {{$payment->total()}}</h5>
            </div>
            <form action="" id="search-form">
                <div class="search-cinema">
                    <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                    <input placeholder="Search" name="search_key" value="{{request('search_key')}}" type="search" class="input">
                </div>
            </form>
            <div class="form-group">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-Payment">Add Payment</button>
            </div>
        </div>
        <form action="" id="search-form-small">
            <div class="search-cinema">
                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                <input placeholder="Search" name="search_key" value="{{request('search_key')}}" type="search" class="input">
            </div>
        </form>
        <div id="table-bg" class="mt-3">
            <div class="my-3">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Number</th>
                                <th>User Name</th>
                                <th>Cinema</th>
                                <th>Active</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($payment)!=0)
                                @foreach ($payment as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->number}}</td>
                                    <td>{{$item->username}}</td>
                                    <td>{{$item->cinema_name??'All'}}</td>
                                    <td>
                                        @if ($item->active==1)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Not Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="table-data-feature">
                                            <a href="{{route('admin#paymentView',$item->id)}}" class="btn btn-secondary" id="detail"><i class="fa-solid fa-eye"></i></a>
                                        </div>
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
                        <h4 class="modal-title d-block w-100 text-center" id="add-PaymentLabel">Add Payment</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#paymentCreate')}}" method="post" class="" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="mb-1">Method Name</label>
                                <input id="" name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter payment method name">
                                @error('name')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">User Name</label>
                                <input id="" name="username" type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Enter username">
                                @error('username')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Number</label>
                                <input id="" name="number" type="number" class="form-control @error('number') is-invalid @enderror" placeholder="Enter number">
                                @error('number')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">QR Image</label>
                                <div class="row">
                                    <div class="col-md-6 py-2">
                                        <img src="{{asset('image/placeholder.webp')}}" alt="Profile" class="w-100 img-thumbnail" id="image">
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
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('cinema_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="name" class="form-label @error('active') is-invalid @enderror">Active</label>
                                <select name="active" id="" class="form-control ">
                                    <option value="0">Not Active</option>
                                    <option value="1">Active</option>
                                </select>
                                @error('active')
                                    <small class=" text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 my-2">Create</button>
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
    })
</script>
@endsection
