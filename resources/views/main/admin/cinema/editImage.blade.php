@extends('main.admin.layout.master')
@section('title')
    Cinema
@endsection
@section('content')
    <main class="content px-3 pt-3 mb-5">
        <div class="container-fluid">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin#cinemaList')}}">List</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin#cinemaView',$cinema->id)}}">View</a></li>
                    <li class="breadcrumb-item active">Edit Image</li>
                    </ol>
                </nav>
            </div><!-- End Page Title -->
            <div class="card mt-4 border-0 shadow">
                <div class="rounded p-3">
                    <h4 class="card-title text-center">Edit Cinema Image</h4>
                    <div class=" d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add">Add Image</button>
                    </div>
                    <div class="row p-2">
                        @foreach ($cinema->image as $key=>$item)
                            <div class="col-md-3 nav-item p-2 mb-4">
                                <form action="{{route('admin#cinemaImageUpdate')}}" method="post" enctype="multipart/form-data" class="parents">
                                    @csrf
                                    @method('PATCH')
                                    <div class="py-2">
                                        <img src="{{Storage::url('cinema/'.$item)}}" alt="" id="image" class="w-100 rounded img-thumbnail">
                                    </div>
                                    <div>
                                        <input type="hidden" name="id" value="{{$cinema->id}}">
                                        <input type="hidden" name="index" value="{{$key}}">
                                    </div>
                                    <div class="py-2">
                                        <input type="file" name="image" class="form-control input_image">
                                    </div>
                                    <div class="d-flex justify-content-end gap-1">
                                        <button class="btn btn-outline-primary me-2">Update</button>
                                        <a href="{{route('admin#cinemaImageDelete',[$cinema->id,$key])}}" class="btn btn-outline-danger">Remove</a>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                        <div class="col-md-3 nav-item p-2 d-none" id="add_form">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title d-block w-100 text-center" id="addLabel">Add Image</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#cinemaImageCreate')}}" method="post" enctype="multipart/form-data" class="parents">
                                @csrf
                                <div class="py-2">
                                    <img src="{{asset('image/placeholder.webp')}}" alt="" id="image" class="w-100 rounded img-thumbnail">
                                </div>
                                <div>
                                    <input type="hidden" name="id" value="{{$cinema->id}}">
                                </div>
                                <div class="py-2">
                                    <input type="file" name="image" class="form-control input_image">
                                </div>
                                <div class="d-flex justify-content-end gap-1">
                                    <button type="submit" class="btn btn-outline-primary me-2">Add</button>
                                    <button type="button" id="close_form" class="btn btn-outline-danger">Close</button>
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
    <script>
        $(document).ready(function(){
            $('.input_image').change(function(){
                $parents=$(this).parents('.parents')
                $parents.find('#image').attr("src",window.URL.createObjectURL(this.files[0]));
                // document.getElementById('image').src = window.URL.createObjectURL(this.files[0]);
            })
        })
    </script>
@endsection
