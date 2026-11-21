@extends('main.admin.layout.master')
@section('title')
    Movie
@endsection
@section('content')
<main class="content px-3 py-4">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('admin#movieList')}}">List</a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin#movieView',$movie->id)}}">View</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Extra Edit</li>
                </ol>
            </nav>
        </div>
        <div class="card mt-1 px-4 border-0 shadow">
            <h4 class=" text-center pt-3">Extra Edit</h4>
            <hr>
            <div class="genre mt-2 mb-5">
                <div class="d-flex justify-content-between">
                    <h5 class="py-2">Genre List</h5>
                    <div class="py-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-genre"><i class="fa-solid fa-plus me-2"></i>Add Genre</button>
                    </div>
                </div>
                <div class="row">
                    @if ($movie->genre!=null)
                        @foreach ($movie->genre as $key=>$item)
                        <div class="col-3 col-md-2">
                            <div class="border border-dark-subtle rounded">
                                <div class=" position-relative p-1">
                                    <button class="btn btn-transparent">{{$item}}</button>
                                    <div class=" position-absolute top-0 end-0">
                                        <a href="{{route('admin#movieGenreDelete',[$movie->id,$key])}}" class=" text-danger"><i class="fa-solid fa-xmark"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="cast mt-3 mb-5">
                <div class="d-flex justify-content-between">
                    <h5 class="py-2">Cast List</h5>
                    <div class="py-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-cast"><i class="fa-solid fa-plus me-2"></i>Add Cast</button>
                    </div>
                </div>
                <div class="row">
                    @if ($movie->cast!=null)
                        @foreach ($movie->cast as $key=>$item)
                            <div class="col-md-3">
                                <form action="{{route('admin#movieCastUpdate')}}" class="p-2 border parents bg-light rounded" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="py-1">
                                        <img src="{{Storage::url('cast/'.$item['image'])}}" id="image" class="w-100 border-0 img-thumbnail" alt="">
                                    </div>
                                    <div class="mb-3">
                                        <input type="file" name="image" class="form-control input_image" id="image">
                                    </div>
                                    <div class="py-1">
                                        <label for="">Name</label>
                                        <input type="text" name="name" id="" value="{{$item['name']}}" class="form-control">
                                    </div>

                                    <input type="hidden" name="id" value="{{$movie->id}}">
                                    <input type="hidden" name="index" value="{{$key}}">

                                    <div class="py-1 d-flex justify-content-end  gap-1 mt-2">
                                        <input type="submit" class="btn btn-outline-primary" value="Update">
                                        <a href="{{route('admin#movieCastdelete',[$movie->id,$key])}}" class="btn btn-outline-danger">Delete</a>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>


            <div class="cover my-5">
                <div class="d-flex justify-content-between">
                    <h5 class="py-2">Cover List</h5>
                    <div class="py-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-cover"><i class="fa-solid fa-plus me-2"></i>Add Cover</button>
                    </div>
                </div>
                <div class="row">
                    @if ($movie->cover!=null)
                        @foreach ($movie->cover as $key=>$item)
                            <div class="col-md-3" id="">
                                <form action="{{route('admin#movieCoverupdate')}}" class="p-2 border parents bg-light rounded" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="py-1">
                                        <img src="{{Storage::url('cover/'.$item)}}" id="image" class="w-100 img-thumbnail" alt="">
                                    </div>
                                    <div class="py-1">
                                        <input type="file" name="image" class="form-control input_image" id="image">
                                    </div>

                                    <input type="hidden" name="id" value="{{$movie->id}}">
                                    <input type="hidden" name="index" value="{{$key}}">

                                    <div class="py-1 d-flex justify-content-end  gap-1 mt-2">
                                        <input type="submit" class="btn btn-outline-primary" value="Update">
                                        <a href="{{route('admin#movieCoverDelete',[$movie->id,$key])}}" class="btn btn-outline-danger">Delete</a>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- mordel start  --}}

        <div>
            <div class="modal fade" id="add-genre" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-genreLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title d-block w-100 text-center" id="add-genreLabel">Add Genre</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#movieGenreAdd')}}" method="post">
                                @csrf

                                <div class="mb-4">
                                    <label for="genre" class="form-label mb-1">Genre</label>
                                    <select name="genre" id="" class="form-control @error('genre') is-invalid @enderror">
                                        <option value="">Choose Genre</option>
                                        @foreach ($genre as $item)
                                            <option value="{{$item->name}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('genre')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                <input type="hidden" name="id" value="{{$movie->id}}">

                                <button type="submit" class="btn btn-primary w-100 my-2">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="add-cast" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-castLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title d-block w-100 text-center" id="add-castLabel">Add Cast</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#movieCastAdd')}}" class=" parents" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label for="" class="mb-1">Image</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="{{asset('image/placeholder.webp')}}" id="image" class="w-100 img-thumbnail" alt="">
                                        </div>
                                        <div class="col-md-6 m-auto">
                                            <input type="file" name="image" class="form-control input_image" id="image">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Name</label>
                                    <input type="text" name="name" id="" placeholder="Enter name" class="form-control">
                                </div>

                                <input type="hidden" name="id" value="{{$movie->id}}">

                                <div class="py-1 d-flex justify-content-end  gap-1">
                                    <input type="submit" class="btn btn-primary w-100" value="Add">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="add-cover" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-coverLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title d-block w-100 text-center" id="add-coverLabel">Add Cover</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#movieCoverAdd')}}" class="parents" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label for="" class="mb-1">Image</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="{{asset('image/placeholder.webp')}}" id="image" class="w-100 img-thumbnail" alt="">
                                        </div>
                                        <div class="col-md-6 m-auto">
                                           <input type="file" name="image[]" class="form-control input_image @error('image') is-invalid  @enderror" multiple accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{$movie->id}}">

                                <div class="py-1 d-flex justify-content-end  gap-1">
                                    <input type="submit" class="btn btn-primary w-100" value="Add">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
@section('script')
    <script>
        $('.input_image').change(function(){
            $parents=$(this).parents('.parents')
            $parents.find('#image').attr("src",window.URL.createObjectURL(this.files[0]));
        })

    </script>
@endsection
