@extends('main.admin.layout.master')
@section('title')
    Product
@endsection
@section('content')
<main class="content px-4 pt-4 mb-5">
    <div class="d-flex align-items-center justify-content-between">
        <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
            <h5>Total - {{$product->total()}}</h5>
        </div>
        <form action="" id="search-form">
            <div class="search-cinema">
                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                <input placeholder="Search" type="search" name="search_key" value="{{request('search_key')}}" class="input">
            </div>
        </form>
        <div class="form-group me-2">
            <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#add-product">Add</button>
        </div>
    </div>
    <form action="" id="search-form-small" class="mb-3">
        <div class="search-cinema">
            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
            <input placeholder="Search" type="search" name="search_key" value="{{request('search_key')}}" class="input">
        </div>
    </form>
    <div class="">
        <div id="table-bg">
            <div class="">
                <div class="table-responsive">
                    <table class="table" id="product-list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th class="col-2">Image</th>
                                <th>Name</th>
                                <th>Active</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td class="col-2">
                                    <img src="{{Storage::url('product/'.$item->image)}}" class="w-100 img-thumbnail" alt="">
                                </td>
                                <td>{{$item->name}}</td>
                                <td>
                                    @if ($item->active==0)
                                        <span class="text-warning">Not Active</span>
                                    @else
                                        <span class="text-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('admin#productView',$item->id)}}" class="btn btn-outline-primary">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{$product->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-product" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-productLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title d-block w-100 text-center" id="add-productLabel">Add Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{route('admin#productCreate')}}" enctype="multipart/form-data" class="card border-0 shadow rounded p-3">
                        @csrf
                        <div class="">
                            <div class=" py-2">
                                <div class="mb-4">
                                    <div>
                                        <label for="" class="mb-1">Name</label>
                                        <input type="text" name="name" placeholder="Enter product name" class="form-control" id="">
                                    </div>
                                </div>
                                <div class=" mb-4">
                                    <label for="">Image</label>
                                    <div class="py-2">
                                        <img src="{{asset('image/placeholder.webp')}}" class="w-100 img-thumbnail" id="image" alt="">
                                    </div>
                                    <div>
                                        <input type="file" name="image" accept="image/*" class="form-control" id="image_input">
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
                                <div class="mb-4">
                                    <label for="name" class="form-label mb-1">Active</label>
                                    <select name="active" id="" class="form-control">
                                        <option value="0">Not Active</option>
                                        <option value="1">Active</option>
                                    </select>
                                    @error('active')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
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