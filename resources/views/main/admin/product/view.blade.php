@extends('main.admin.layout.master')
@section('title')
    Product
@endsection
@section('content')
<main class="content px-4 pt-4 mb-5">
    <div>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{route('admin#productList')}}">List</a></li>
              <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </nav>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2 card border-0 shadow p-4">
                <div class="">
                    <h4 class="text-center py-2">View Product</h4>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <img src="{{Storage::url('product/'.$product->image)}}" class="w-75 d-block mx-auto img-thumbnail" alt="">
                        </div>
                        <div class="col-md-8">
                            <div class="py-2">
                                <h6>Name - {{$product->name}}</h6>
                            </div>
                            <div class="py-2">
                                <h6>Category - {{$product->category_name}}</h6>
                            </div>
                            <div class="py-2">
                                <h6>Active - @if ($product->active==1)
                                    <span class="text-success">Active</span>
                                @else
                                    <span class="text-danger">Not Active</span>
                                @endif</h6>
                            </div>
                            <div class="py-2">
                                <p>{!!$product->description!!}</p>
                            </div>
                            <div class="py-2 d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#edit-product">Edit</button>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-product">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit-product" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-productLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="edit-productLabel">Edit Product</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('admin#productUpdate')}}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="row">
                                <div class="">
                                    <div class="mb-4">
                                        <div>
                                            <label for="" class="mb-1">Name</label>
                                            <input type="text" name="name" value="{{$product->name}}" placeholder="Enter product name" class="form-control" id="">
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-2">Image</label>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <img src="{{Storage::url('product/'.$product->image)}}" class="w-75 d-block mx-auto img-thumbnail" id="image" alt="">
                                            </div>
                                            <div class="col-sm-6 m-auto">
                                                <input type="file" name="image" accept="image/*" class="form-control" id="image_input">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div>
                                            <label for="" class="mb-1">Category</label>
                                            <select name="category_id" id="" class="form-control">
                                                <option value="">Choose category</option>
                                                @foreach ($category as $item)
                                                    <option value="{{$item->id}}" @if ($item->id==$product->category_id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div>
                                            <label for="" class="mb-1">Description</label>
                                            <textarea name="description" id="editor" cols="30" rows="10">{{$product->description}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="name" class="form-label mb-1">Active</label>
                                        <select name="active" id="" class="form-control">
                                            <option value="">Choose Active Status</option>
                                            <option value="0" @if ($product->active==0) selected @endif>Not Active</option>
                                            <option value="1" @if ($product->active==1) selected @endif>Active</option>
                                        </select>
                                        @error('active')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="id" value="{{$product->id}}">
                                    <div class=" d-flex">
                                        <button class="btn btn-primary w-100">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="delete-product" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-productLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center text-danger" id="delete-productLabel">Delete Product</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#productDelete')}}" method="post" class="mt-1">
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

                            <input type="hidden" name="id" value="{{$product->id}}">

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
    <script>
        $('#image_input').change(function(){
            $('#image').attr("src",window.URL.createObjectURL(this.files[0]));
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
