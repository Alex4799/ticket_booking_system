@extends('main.admin.layout.master')
@section('title')
    Hall
@endsection
@section('content')
            <!-- hall start  -->
            <main class="content px-3 pt-3 mb-5">
                <div class="container-fluid">
                    <div id="table-bg">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                            <h4 class="py-2">Hall</h4>
                            @if (Auth::user()->position=='developer')
                                <div class="py-2">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-hall">Add Hall</button>
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                                <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
                                    <h5>Total - {{$hall->total()}}</h5>
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
                                            <th>Hall Name</th>
                                            <th>Active</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($hall))
                                            @foreach ($hall as $item)
                                                <tr>
                                                    <td>{{$item->id}}</td>
                                                    <td>{{$item->name}}@if ($item->du_id!=null) (Copy) @endif</td>
                                                    <td>
                                                        @if ($item->active==1)
                                                            <span class="text-success">Active</span>
                                                        @else
                                                            <span class="text-danger">Not Active</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{route('admin#hallView',$item->id)}}" class="btn btn-outline-primary">See More</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4">There is no hall</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div>
                                    {{$hall->appends(request()->query())->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- hall end  -->

            {{-- model start --}}
            <div>
                <div class="modal fade" id="add-hall" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-hallLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="add-hallLabel">Add Hall</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#hallCreate')}}" method="post" class="mt-1">
                                    @csrf
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Hall Name</label>
                                        <input id="" name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter hall name">
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="mb-1">Hall Seat Column</label>
                                        <input id="" name="seat_column" type="number" class="form-control @error('seat_column') is-invalid @enderror" placeholder="Enter hall seat column">
                                        @error('seat_column')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
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

                                    <div class="form-group mb-4">
                                        <label for="name" class="form-label @error('active') is-invalid @enderror">Active</label>
                                        <select name="active" id="" class="form-control ">
                                            <option value="">Choose Active Status</option>
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
            {{-- model end --}}
@endsection
