@extends('main.admin.layout.master')
@section('title')
    View Account
@endsection
@section('content')
<main class="content px-3 py-4">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('admin#list')}}">Admin</a></li>
                  <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-title text-center fs-4 bold pt-2">View Account</div>
                    <div class="card-body">
                        <div class="row">
                            <h5 class="col-md-6 py-2">Name - {{$admin->name}}</h5>
                            <h5 class="col-md-6 py-2">Email - {{$admin->email}}</h5>
                            <h5 class="col-md-6 py-2">Position - {{$admin->position}}</h5>
                            <h5 class="col-md-6 py-2">Location - {{$admin->cinema_name??'All'}}</h5>
                        </div>
                        <div>
                            <h4 class="py-2">Permission</h4>
                            <div class="row">
                                <h6 class="col-md-6 text-center p-1">Order Reject</h6>
                                <div class="col-md-6">
                                    @if ($admin->can_reject)
                                        <h6 class="text-success text-center p-1 border border-black">Granted</h6>
                                    @else
                                        <h6 class="text-danger text-center p-1 border border-black">Denied</h6>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <h6 class="col-md-6 text-center p-1">Ticket Report</h6>
                                <div class="col-md-6">
                                    @if ($admin->ticket_report)
                                        <h6 class="text-success text-center p-1 border border-black">Granted</h6>
                                    @else
                                        <h6 class="text-danger text-center p-1 border border-black">Denied</h6>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <h6 class="col-md-6 text-center p-1">Product Report</h6>
                                <div class="col-md-6">
                                    @if ($admin->product_report)
                                        <h6 class="text-success text-center p-1 border border-black">Granted</h6>
                                    @else
                                        <h6 class="text-danger text-center p-1 border border-black">Denied</h6>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <h6 class="col-md-6 text-center p-1">Purchase Product</h6>
                                <div class="col-md-6">
                                    @if ($admin->purchase)
                                        <h6 class="text-success text-center p-1 border border-black">Granted</h6>
                                    @else
                                        <h6 class="text-danger text-center p-1 border border-black">Denied</h6>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <h6 class="col-md-6 text-center p-1">Stock Change</h6>
                                <div class="col-md-6">
                                    @if ($admin->stock)
                                        <h6 class="text-success text-center p-1 border border-black">Granted</h6>
                                    @else
                                        <h6 class="text-danger text-center p-1 border border-black">Denied</h6>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        @if (in_array(Auth::user()->position,['developer','CEO','manager','admin']))
                            @if (Auth::user()->position=='manager')
                                @if (!in_array($admin->position,['developer','CEO','admin','manager']))
                                    <div class="py-2 d-flex justify-content-end gap-1">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#edit">Edit</button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete">Delete</button>
                                    </div>
                                @endif
                            @else
                                <div class="py-2 d-flex justify-content-end gap-1">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#edit">Edit</button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete">Delete</button>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- model  --}}
        <div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="deleteLabel">Edit Admin</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#update')}}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="">
                                <div>
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Name</label>
                                        <input name="name" value="{{old('name',$admin->name)}}" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter name">
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Email</label>
                                        <input name="email" value="{{old('email',$admin->email)}}" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email">
                                        @error('email')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Position</label>
                                        <select name="position" id="" class="form-control @error('position') is-invalid @enderror">
                                            <option value="">Choose Position</option>
                                            @if (Auth::user()->position=='CEO'||Auth::user()->position=='developer')
                                                <option value="admin" @if ($admin->position=='admin') selected @endif>Admin</option>
                                                <option value="manager" @if ($admin->position=='manager') selected @endif>Manager</option>
                                            @elseif(Auth::user()->position=='admin')
                                                <option value="manager" @if ($admin->position=='manager') selected @endif>Manager</option>
                                            @endif
                                            <option value="ticket-seller" @if ($admin->position=='ticket-seller') selected @endif>Ticket Seller</option>
                                            <option value="movie-planner" @if ($admin->position=='movie-planner') selected @endif>Movie Planner</option>
                                            <option value="product-seller" @if ($admin->position=='product-seller') selected @endif>Product Seller</option>
                                            <option value="store-manager" @if ($admin->position=='store-manager') selected @endif>Store Manager</option>
                                        </select>
                                        @error('position')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Location</label>
                                        <select name="location" id="" class="form-control @error('location') is-invalid @enderror">
                                            <option value="">Choose Cinema Location</option>
                                            <option value="admin">Admin</option>
                                            @foreach ($cinema as $item)
                                                <option value="{{$item->id}}" @if ($admin->location==$item->id) selected @endif>{{$item->name}}({{$item->location}})</option>
                                            @endforeach
                                        </select>
                                        @error('location')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    @if ($admin->position=='ticket-seller' || $admin->position=='product-seller')
                                        <div class="form-group mb-3">
                                            <label class="mb-1">Order Reject</label>
                                            <select name="can_reject" id="" class="form-control @error('can_reject') is-invalid @enderror">
                                                <option value="0" @if ($admin->can_reject==0) selected @endif>Denied</option>
                                                <option value="1" @if ($admin->can_reject==1) selected @endif>Granted</option>
                                            </select>
                                            @error('can_reject')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="mb-1">Ticket Report</label>
                                            <select name="ticket_report" id="" class="form-control @error('ticket_report') is-invalid @enderror">
                                                <option value="0" @if ($admin->ticket_report==0) selected @endif>Denied</option>
                                                <option value="1" @if ($admin->ticket_report==1) selected @endif>Granted</option>
                                            </select>
                                            @error('ticket_report')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="mb-1">Product Report</label>
                                            <select name="product_report" id="" class="form-control @error('product_report') is-invalid @enderror">
                                                <option value="0" @if ($admin->product_report==0) selected @endif>Denied</option>
                                                <option value="1" @if ($admin->product_report==1) selected @endif>Granted</option>
                                            </select>
                                            @error('product_report')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="mb-1">Purchase</label>
                                            <select name="purchase" id="" class="form-control @error('purchase') is-invalid @enderror">
                                                <option value="0" @if ($admin->purchase==0) selected @endif>Denied</option>
                                                <option value="1" @if ($admin->purchase==1) selected @endif>Granted</option>
                                            </select>
                                            @error('purchase')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="mb-1">Stock Change</label>
                                            <select name="stock" id="" class="form-control @error('stock') is-invalid @enderror">
                                                <option value="0" @if ($admin->stock==0) selected @endif>Denied</option>
                                                <option value="1" @if ($admin->stock==1) selected @endif>Granted</option>
                                            </select>
                                            @error('stock')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    @endif
                                    <input type="hidden" name="id" value="{{$admin->id}}">
                                    <div class="py-2 d-flex justify-content-end">
                                        <input type="submit" class="btn btn-primary" value="Update">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center text-danger" id="delete-inventoryLabel">Delete Purchase</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#delete')}}" method="post" class="mt-1">
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

                            <input type="hidden" name="id" value="{{$admin->id}}">

                            <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>


@endsection
