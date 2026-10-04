@extends('main.admin.layout.master')
@section('title')
    Cinema
@endsection
@section('content')
            <!-- general details start  -->
            <main class="content px-3 pt-3 mb-5">
                <div class="container-fluid">
                    <div>
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{route('admin#cinemaList')}}">List</a></li>
                              <li class="breadcrumb-item active" aria-current="page">View</li>
                            </ol>
                        </nav>
                    </div>
                    <div id="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="card border-0 shadow p-4">
                                <h4 class=" text-center text-capitalize mb-4">{{$cinema->name}}</h4>
                                <div class="row">
                                    <div class="col-md-6 d-flex justify-content-center mb-3">
                                        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                @foreach ($cinema->image as $item)
                                                    <div class="carousel-item @if ($loop->index==0) active @endif ">
                                                        <img src="{{Storage::url('cinema/'.$item)}}" class="d-block w-75 mx-auto img-thumbnail" alt="Cinema Photo">
                                                    </div>
                                                @endforeach
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                                              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                              <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                                              <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                              <span class="visually-hidden">Next</span>
                                            </button>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-center">
                                        <div class=" m-auto">
                                            <h5 class="py-2">Location - {{$cinema->location}}</h5>
                                            <h5>{!!$cinema->contact!!}</h5>
                                            <h5 class="">Acitve - @if ($cinema->active==0)
                                                            <span class="text-danger">Not Active</span>
                                                        @else
                                                            <span class="text-success">Active</span>
                                                        @endif</h5>
                                            <div class=" d-flex justify-content-end gap-1 flex-wrap mt-3">
                                                <a href="{{route('admin#cinemaImageEdit',$cinema->id)}}" class="btn btn-outline-primary">Edit Image</a>
                                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit-cinema">Edit</button>
                                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-cinema">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- hall start  -->
            <main class="content px-3 pt-3 mb-5">
                <div class="container-fluid">
                    <div id="table-bg">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                            <h4 class="py-2">Hall</h4>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                                <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
                                    <h5>Total - {{$hall->total()}}</h5>
                                </div>
                                <form action="" method="get" id="search-form">
                                    <div class="search-cinema">
                                        <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                                        <input placeholder="Search" value="{{request('hall_search')}}" name="hall_search" type="text" class="input">
                                    </div>
                                </form>
                            </div>
                            <form action="" id="search-form-small">
                                <div class="search-cinema">
                                    <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                                    <input placeholder="Search" value="{{request('hall_search')}}" name="hall_search" type="text" class="input">
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
                <div class="modal fade" id="edit-cinema" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-cinemaLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="delete-cinemaLabel">Delete Cinema</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#cinemaUpdate')}}" method="post" class="mt-4" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Cinema Name</label>
                                        <input name="name" value="{{$cinema->name}}" type="text"class="form-control @error('name') is-invalid @enderror" placeholder="Enter cinema name">
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-1">
                                        <div class=" d-flex justify-content-between flex-wrap gap-1 mb-2">
                                            <label class="mb-1">Cinema Image</label>
                                            <a href="#">Edit Image</a>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 offset-md-3 mb-4">
                                                @if (count($cinema->image)!=0)
                                                <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-inner">
                                                        @foreach ($cinema->image as $item)
                                                            <div class="carousel-item @if ($loop->index==0) active @endif">
                                                                <img src="{{Storage::url('cinema/'.$item)}}" class="d-block img-thumbnail w-100" alt="...">
                                                            </div>
                                                        @endforeach
                                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                        </button>
                                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                @else
                                                    <img src="{{asset('image/default.jpg')}}" alt="Profile" class="w-100" id="image">
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="mb-1">Location</label>
                                        <input name="location" value="{{$cinema->location}}" type="text"class="form-control @error('location') is-invalid  @enderror" placeholder="Enter cinema location">
                                        @error('location')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="mb-1">Contact</label>
                                        <textarea name="contact" id="editor" cols="30" rows="10" placeholder="Enter content info">{{$cinema->contact}}</textarea>
                                        @error('contact')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="name" class="form-label mb-1">Active</label>
                                        <select name="active" id="" class="form-control">
                                            <option value="">Choose Active Status</option>
                                            <option value="0" @if ($cinema->active==0) selected @endif>Not Active</option>
                                            <option value="1" @if ($cinema->active==1) selected @endif>Active</option>
                                        </select>
                                        @error('active')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="id" value="{{$cinema->id}}">

                                    <button type="submit" class="btn btn-primary mt-2 w-100">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="delete-cinema" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-cinemaLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="delete-cinemaLabel">Delete Cinema</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#cinemaDelete')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <div class="py-1">
                                        <p class="text-danger">Are you sure to delete this !!!</p>
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

                                    <input type="hidden" name="id" value="{{$cinema->id}}">

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
<script>
    $(document).ready(function(){
        $('#input_image').change(function(){
            document.getElementById('image').src = window.URL.createObjectURL(this.files[0]);
        })
    })
</script>
        <script type="module">
            import {
                ClassicEditor,
                Essentials,
                Bold,
                Italic,
                Font,
                Paragraph
            } from 'ckeditor5';

            ClassicEditor
                .create( document.querySelector( '#editor' ), {
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: {
                        items: [
                            'undo', 'redo', '|', 'bold', 'italic', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                        ]
                    }
                } )
                .then( /* ... */ )
                .catch( /* ... */ );
        </script>
@endsection
