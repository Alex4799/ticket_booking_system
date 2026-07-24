@extends('main.admin.layout.master')
@section('title')
    Admin List
@endsection
@section('content')
<main class="content px-3 pt-2 mb-5">
    <div class="container-fluid">
        <div id="table-bg">
            <div class="d-flex align-items-center justify-content-end flex-wrap gap-1">
                {{-- <h4 class="py-2">Admin List</h4> --}}
                @if (in_array(Auth::user()->position,['CEO','manager','developer']))
                    <div class="py-2">
                        <button type="button" class="btn btn-success add" data-bs-toggle="modal" data-bs-target="#add">Add Account</button>
                    </div>
                @endif
            </div>
            <div class="my-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                    <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
                        <h5>Total - {{$admin->total()}}</h5>
                    </div>
                    <form action="" method="get" id="search-form">
                        <div class="search-cinema">
                            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                            <input placeholder="Search" value="{{request('search_key')}}" name="search_key" type="text" class="input">
                        </div>
                    </form>
                </div>
                <form action="" id="search-form-small">
                    <div class="search-cinema">
                        <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                        <input placeholder="Search" value="{{request('search_key')}}" name="search_key" type="text" class="input">
                    </div>
                </form>
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
                                            <a href="#" class="btn btn-secondary"><i class="fa-solid fa-message"></i></a>
                                            @if (in_array(Auth::user()->position,['CEO','manager','admin','developer']))
                                                <a href="{{route('admin#view',$item->id)}}" class="btn btn-secondary"><i class="fa-solid fa-eye"></i></a>
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
            <div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title d-block w-100 text-center" id="deleteLabel">Create Admin</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#add')}}" method="post">
                                @csrf
                                <div class="mt-4">
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Name</label>
                                        <input name="name" value="{{old('name')}}" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter name">
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Email</label>
                                        <input name="email" value="{{old('email')}}" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email">
                                        @error('email')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Position</label>
                                        <select name="position" id="" class="form-control @error('position') is-invalid @enderror">
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
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Location</label>
                                        <select name="location" id="" class="form-control @error('location') is-invalid @enderror">
                                            <option value="">Choose Cinema Location</option>
                                            <option value="admin">Admin</option>
                                            @foreach ($cinema as $item)
                                                <option value="{{$item->id}}">{{$item->name}}({{$item->location}})</option>
                                            @endforeach
                                        </select>
                                        @error('location')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Password</label>
                                        <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password">
                                        @error('password')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Confirm Password</label>
                                        <input name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Enter Confirm Password">
                                        @error('password_confirmation')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="py-2 d-flex justify-content-end">
                                        <input type="submit" class="btn btn-primary" value="Register">
                                    </div>
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

@endsection
