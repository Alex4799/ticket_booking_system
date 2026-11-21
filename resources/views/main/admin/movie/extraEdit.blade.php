@extends('main.admin.layout.master')
@section('title')
    Extra Edit
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('admin#movieList')}}">List</a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin#movieView',$movie->id)}}">View</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Extra</li>
                </ol>
            </nav>
        </div>
        <div class="shadow-container-box">
            <div>
                <h5 class="box-title">Extra Edit</h5>
            </div>
            <div class="genre extra">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <h5 class="extra-title">Genre List</h5>
                    <div>
                        <button type="button" class="add-btn" data-bs-toggle="modal" data-bs-target="#add-genre"><i class="fa-solid fa-square-plus me-2"></i>Add Genre</button>
                    </div>
                </div>
                <div class="row mt-3 genre-row">
                    @if ($movie->genre!=null)
                        @foreach ($movie->genre as $key=>$item)
                        <div class="col-lg-2 col-sm-3 mb-3">
                            <div class="border border-secondary-subtle rounded">
                                <div class=" position-relative p-2">
                                    <span style="font-size: 11pt;">{{$item}}</span>
                                    <div class="position-absolute top-0 end-0">
                                        <a href="{{route('admin#movieGenreDelete',[$movie->id,$key])}}" class="btn btn-sm btn-danger py-0" style="font-size: 9pt;"><i class="fa-solid fa-xmark"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="cast extra">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <h5 class="extra-title">Cast List</h5>
                    <div>
                        <button type="button" class="add-btn" data-bs-toggle="modal" data-bs-target="#add-cast"><i class="fa-solid fa-square-plus me-2"></i>Add Cast</button>
                    </div>
                </div>
                <div class="row mt-3 extra-images">
                    @if ($movie->cast!=null)
                        @foreach ($movie->cast as $key=>$item)
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                                <div class="image-cards">
                                    <form action="{{route('admin#movieCastUpdate')}}" method="post" enctype="multipart/form-data" class="parents">
                                        @csrf
                                        @method('PATCH')
                                         <div class="img-content">
                                            <a href="{{route('admin#movieCastdelete',[$movie->id,$key])}}" class="btn btn-sm btn-danger py-0" style="font-size: 9pt;"><i class="fa-solid fa-xmark"></i></a>
                                            <img src="{{Storage::url('cast/'.$item['image'])}}" alt="" id="image" class="rounded img-thumbnail">
                                        </div>
                                        <div class="mt-2">
                                            <label for="" style="font-size: 9pt;">Name</label>
                                            <input type="text" name="name" id="" value="{{$item['name']}}" class="form-control" style="font-size: 11pt;">
                                        </div>
                                        <div>
                                            <input type="hidden" name="id" value="{{$movie->id}}">
                                            <input type="hidden" name="index" value="{{$key}}">
                                        </div>
                                        <div class="mt-2">
                                            <label for="" style="font-size: 9pt;">Image</label>
                                            <input type="file" name="image" class="input_image form-control" style="font-size: 9pt;">
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" class="action-btn w-100" disabled>Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h6 class="text-center mt-5 mb-4 text-secondary" style="font-size: 11pt;">There is no cast.</h6>
                    @endif
                </div>
            </div>
            <div class="cover extra">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <h5 class="extra-title">Cover List</h5>
                    <div>
                        <button type="button" class="add-btn" data-bs-toggle="modal" data-bs-target="#add-cover"><i class="fa-solid fa-square-plus me-2"></i>Add Cover</button>
                    </div>
                </div>
                <div class="row mt-3 cover-images">
                    @if ($movie->cover!=null)
                        @foreach ($movie->cover as $key=>$item)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="image-cards">
                                    <form action="{{route('admin#movieCoverupdate')}}" class="parents" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                        <div class="img-content">
                                            <a href="{{route('admin#movieCoverDelete',[$movie->id,$key])}}" class="btn btn-sm btn-danger py-0" style="font-size: 9pt;"><i class="fa-solid fa-xmark"></i></a>
                                            <img src="{{Storage::url('cover/'.$item)}}" alt="" id="image" class="rounded img-thumbnail">
                                        </div>
                                        <div>
                                            <input type="hidden" name="id" value="{{$movie->id}}">
                                            <input type="hidden" name="index" value="{{$key}}">
                                        </div>
                                        <div class="mt-2">
                                            <input type="file" name="image" class="input_image form-control" style="font-size: 9pt;">
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" class="action-btn w-100" disabled>Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h6 class="text-center mt-5 mb-4 text-secondary" style="font-size: 11pt;">There is no cover.</h6>
                    @endif
                </div>
            </div>
        </div>
        <div>
            <div class="modal fade" id="add-genre" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-genreLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="add-genreLabel">Add Genre</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#movieGenreAdd')}}" method="post" class="mt-1">
                                @csrf
                                <div class="form-group mb-4">
                                    <label class="mb-1">Genre</label>
                                    <select name="genre" id="" class="form-select @error('genre') is-invalid @enderror">
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
                                <div class="d-flex align-items-center my-2">
                                    <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="add-cast" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-castLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="add-castLabel">Add Cast</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#movieCastAdd')}}" class="mt-1 parents" method="post" enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Name</label>
                                        <input type="text" name="name" id="" class="form-control" required>
                                    </div>
                                    <div class="form-group mb-5">
                                        <label class="mb-1">Image</label>
                                        <div class="row align-items-center">
                                            <div class="col-sm-6 text-center">
                                                <img src="{{asset('image/placeholder.webp')}}" alt="image" class="img-thumbnail mx-auto" id="image">
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="input_image" class="file-pen-logo mt-3">
                                                    <span><i class="fa-solid fa-upload me-1"></i> Upload Image</span>
                                                </label>
                                                <input type="file" name="image" class="d-none" id="input_image">
                                            </div>
                                        </div>
                                        @error('image')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{$movie->id}}">
                                <div class="d-flex align-items-center my-2">
                                    <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Create</button>
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
                            <h5 class="modal-title d-block w-100 text-center" id="add-coverLabel">Add Cover</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#movieCoverAdd')}}" class="mt-1 parents" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row form-group mb-5 align-items-center">
                                    <div class="col-sm-6">
                                        <label for="" class="mb-1">Image</label>
                                        <div class="text-center">
                                            <img src="{{asset('image/placeholder.webp')}}" alt="image" class="img-thumbnail mx-auto" id="image">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="cover_image" class="file-pen-logo mt-3">
                                            <span><i class="fa-solid fa-upload me-1"></i> Upload Image</span>
                                        </label>
                                        <input type="file" name="image[]" class="d-none" id="cover_image">
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{$movie->id}}">
                                <div class="d-flex align-items-center my-2">
                                    <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Create</button>
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
            $parents=$(this).parents('.parents');
            $parents.find('#image').attr("src",window.URL.createObjectURL(this.files[0]));
        });

        $('#input_image').change(function(){
            $parents=$(this).parents('.parents');
            $parents.find('#image').attr("src",window.URL.createObjectURL(this.files[0]));
        });

        $('#cover_image').change(function(){
            $parents=$(this).parents('.parents');
            $parents.find('#image').attr("src",window.URL.createObjectURL(this.files[0]));
        });


        const forms = document.querySelectorAll('.image-cards');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input');
            const button = form.querySelector('.action-btn');
            const initialValues = {};
            inputs.forEach(input => {
                initialValues[input.name] = input.value;
            });
            function checkUpdateValueForBtn() {
                let hasChanged = false;
                inputs.forEach(input => {
                    if (input.value != initialValues[input.name]) {
                        hasChanged = true;
                    }
                });
                button.disabled = !hasChanged;
            }
            inputs.forEach(input => {
                input.addEventListener('input', checkUpdateValueForBtn);
                input.addEventListener('change', checkUpdateValueForBtn);
            });
            checkUpdateValueForBtn();
        });

    </script>
@endsection
