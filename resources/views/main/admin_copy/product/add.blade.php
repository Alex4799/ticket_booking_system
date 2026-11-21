@extends('main.admin.layout.master')
@section('title')
    Product
@endsection
@section('content')
<main class="content px-4 pt-4 mb-5">
    <div class="px-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{session('success')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{session('warning')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('danger'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{session('danger')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{$error}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
        @endif
    </div>
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('admin#productList')}}">List</a></li>
                  <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <form method="POST" action="{{route('admin#productAdd')}}" enctype="multipart/form-data" class="card border-0 shadow rounded p-3">
                    @csrf
                    <h4 class="text-center mb-4">Add Product</h4>
                    <div class="row">
                        <div class="col-md-4 py-2">
                            <label for="">Image</label>
                            <div class="py-2">
                                <img src="{{asset('image/placeholder.webp')}}" class="w-100 img-thumbnail" id="image" alt="">
                            </div>
                            <div>
                                <input type="file" name="image" accept="image/*" class="form-control" id="image_input">
                            </div>
                        </div>
                        <div class="col-md-8 py-2">
                            <div class="mb-4">
                                <div>
                                    <label for="" class="mb-1">Name</label>
                                    <input type="text" name="name" placeholder="Enter product name" class="form-control" id="">
                                </div>
                            </div>
                            <div class="mb-4">
                                <div>
                                    <label for="" class="mb-1">Category</label>
                                    <select name="category_id" id="" class="form-control">
                                        <option value="">Choose category</option>
                                        @foreach ($category as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div>
                                    <label for="" class="mb-1">Description</label>
                                    <textarea name="description" id="editor" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <button class="btn btn-primary w-100">Create</button>
                    </div>
                </form>
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
