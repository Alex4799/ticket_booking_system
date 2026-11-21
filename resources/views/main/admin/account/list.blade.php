@extends('main.admin.layout.master')
@section('title')
    Admin List
@endsection

@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div class="table-bg">
            <div>
                <div class="total-count-title">
                    <div class="d-flex align-items-center">
                        <div class="count-content">
                            <h5 class="mb-0">Total - {{$admin->total()}}</h5>
                        </div>
                        @if (in_array(Auth::user()->position,['CEO','manager','developer','admin']))
                            <div class="ms-3">
                                <button class="add-btn" data-bs-toggle="modal" data-bs-target="#add">
                                    <i class="fa-solid fa-square-plus me-2"></i>Add Account
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
                        <a href="{{route('admin#list')}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Position</th>
                                <th>Location</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admin as $item)
                                @if ($item->position!='developer')
                                    <tr class="parents">
                                        <td>{{$loop->index}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->email}}</td>
                                        <td>{{$item->position}}</td>
                                        <td>{{$item->cinema_name}}</td>
                                        <td>
                                            @if (Auth::user()->id!=$item->id)
                                                @if (in_array(Auth::user()->position,['CEO','manager','admin','developer']))
                                                    <a href="{{route('admin#view',$item->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- model  --}}
        <div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-block w-100 text-center" id="addLabel">Add Account</h5>
                        <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#add')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Name</label>
                                    <input name="name" value="{{old('name')}}" type="text" class="form-control @error('name') is-invalid @enderror" required>
                                    @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Email</label>
                                    <input name="email" value="{{old('email')}}" type="email" class="form-control @error('email') is-invalid @enderror" required>
                                    @error('email')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Position</label>
                                    <select name="position" id="" class="form-select @error('position') is-invalid @enderror">
                                        <option value="">Choose Position</option>
                                        @if (in_array(Auth::user()->position,['CEO','developer']))
                                            <option value="admin">Admin</option>
                                            <option value="manager">Manager</option>
                                        @elseif(Auth::user()->position=='admin')
                                            <option value="manager">Manager</option>
                                        @endif
                                        <option value="ticket-seller">Ticket Seller</option>
                                        <option value="movie-planner">Movie Planner</option>
                                        <option value="product-seller">Product Seller</option>
                                        <option value="store-manager">Store Manager</option>
                                    </select>
                                    @error('position')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label>Location</label>
                                    <select name="location" id="" class="form-select @error('location') is-invalid @enderror" required>
                                        <option value="">Choose Cinema</option>
                                        <option value="admin">Admin</option>
                                        @foreach ($cinema as $item)
                                            <option value="{{$item->id}}">{{$item->name}}({{$item->location}})</option>
                                        @endforeach
                                    </select>
                                    @error('location')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label>Password</label>
                                    <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Confirm Password</label>
                                    <input name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                                    @error('password_confirmation')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex align-items-center my-2">
                                <button type="submit" class="action-btn w-100"><i class="fa-solid fa-user-plus me-2"></i> Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
