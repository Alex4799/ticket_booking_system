@extends('main.admin.layout.master')
@section('title')
    Genre
@endsection
@section('content')
<main class="content px-4 pt-4 mb-5">
    <div class="d-flex align-items-center justify-content-between">
        <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
            <h5>Total - {{$genre->total()}}</h5>
        </div>
        <form action="" id="search-form">
            <div class="search-cinema">
                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                <input placeholder="Search" value='{{request('search_key')}}' name="search_key" type="search" class="input">
            </div>
        </form>
        <div class="form-group">
            <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#add-genre">Add Genre</button>
        </div>
    </div>
    <form action="" id="search-form-small" class="mb-3">
        <div class="search-cinema">
            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
            <input placeholder="Search" value='{{request('search_key')}}' name="search_key" type="search" class="input">
        </div>
    </form>

    <div class="">
        <div id="table-bg">
            <div class="">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Category Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($genre as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit-genre{{$item->id}}">Edit</button>
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-hall{{$item->id}}">Delete</button>
                                    </td>
                                </tr>
                                <div class="modal fade" id="edit-genre{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-genre{{$item->id}}Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title d-block w-100 text-center" id="edit-genre{{$item->id}}Label">Edit Genre</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('admin#genreUpdate')}}" method="post" class="mt-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="form-group mb-4">
                                                        <label class="mb-1">Catergory Name</label>
                                                        <input id="" name="name" value="{{$item->name}}" type="text"class="form-control @error('name') is-invalid @enderror" placeholder="Enter category name">
                                                        @error('name')
                                                            <small class="text-danger">{{$message}}</small>
                                                        @enderror
                                                    </div>

                                                    <input type="hidden" name="id" value="{{$item->id}}">

                                                    <button type="submit" class="btn btn-primary w-100 my-2">Update</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="delete-hall{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-hall{{$item->id}}Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title d-block w-100 text-center" id="delete-hall{{$item->id}}Label">Delete Genre</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('admin#genreDelete')}}" method="post" class="mt-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="py-1">
                                                        <p class="text-danger">Are you sure to delete this."</p>
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

                                                    <input type="hidden" name="id" value="{{$item->id}}">

                                                    <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{$genre->appends(request()->query())->links()}}
                    </div>
                </div>

                <!--add genre modal -->
                <div class="modal fade" id="add-genre" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-genreLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="add-genreLabel">Add Genre</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#genreCreate')}}" method="post" class="mt-1">
                                    @csrf
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Catergory Name</label>
                                        <input id="" name="name" type="text"class="form-control @error('name') is-invalid @enderror" placeholder="Enter category name">
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 my-2">Create</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
