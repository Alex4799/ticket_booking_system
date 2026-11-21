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
                  <li class="breadcrumb-item"><a href="{{route('admin#websiteView')}}">View</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Extra Edit</li>
                </ol>
            </nav>
        </div>
        <div class="card mt-1 px-4 border-0 shadow">
            <h4 class=" text-center pt-3">Extra Edit</h4>
            <hr>

            <div class="cover my-5">
                <div class="d-flex justify-content-between">
                    <h5 class="py-2">Cover Image List</h5>
                    <div class="py-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-cover"><i class="fa-solid fa-plus me-2"></i>Add</button>
                    </div>
                </div>
                <div class="row">
                    @if ($website->cover_image!=null)
                        @foreach ($website->cover_image as $key=>$item)
                            <div class="col-md-3" id="">
                                <form action="{{route('admin#websiteCoverUpdate')}}" class="p-2 border parents bg-light rounded" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="py-1">
                                        <img src="{{Storage::url('website/'.$item)}}" id="image" class="w-100 img-thumbnail" alt="">
                                    </div>
                                    <div class="py-1">
                                        <input type="file" name="image" class="form-control input_image" id="image">
                                    </div>

                                    <input type="hidden" name="index" value="{{$key}}">

                                    <div class="py-1 d-flex justify-content-end  gap-1 mt-2">
                                        <input type="submit" class="btn btn-outline-primary" value="Update">
                                        <a href="{{route('admin#websiteCoverDelete',$key)}}" class="btn btn-outline-danger">Delete</a>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="cover my-5">
                <div class="d-flex justify-content-between">
                    <h5 class="py-2">About Us Image List</h5>
                    <div class="py-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-about"><i class="fa-solid fa-plus me-2"></i>Add</button>
                    </div>
                </div>
                <div class="row">
                    @if ($website->about_image!=null)
                        @foreach ($website->about_image as $key=>$item)
                            <div class="col-md-3" id="">
                                <form action="{{route('admin#websiteAboutUpdate')}}" class="p-2 border parents bg-light rounded" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="py-1">
                                        <img src="{{Storage::url('website/'.$item)}}" id="image" class="w-100 img-thumbnail" alt="">
                                    </div>
                                    <div class="py-1">
                                        <input type="file" name="image" class="form-control input_image" id="image">
                                    </div>

                                    <input type="hidden" name="index" value="{{$key}}">

                                    <div class="py-1 d-flex justify-content-end  gap-1 mt-2">
                                        <input type="submit" class="btn btn-outline-primary" value="Update">
                                        <a href="{{route('admin#websiteAboutDelete',$key)}}" class="btn btn-outline-danger">Delete</a>
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
            <div class="modal fade" id="add-cover" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-coverLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title d-block w-100 text-center" id="add-coverLabel">Add Cover</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#websiteCoverAdd')}}" class="parents" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label for="" class="mb-1">Image</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="{{asset('image/placeholder.webp')}}" id="image" class="w-100 img-thumbnail" alt="">
                                        </div>
                                        <div class="col-md-6 m-auto">
                                           <input type="file" name="image" class="form-control input_image @error('image') is-invalid  @enderror" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <div class="py-1 d-flex justify-content-end  gap-1">
                                    <input type="submit" class="btn btn-primary w-100" value="Add">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="add-about" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-aboutLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title d-block w-100 text-center" id="add-aboutLabel">Add About Us Image</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#websiteAboutAdd')}}" class="parents" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label for="" class="mb-1">Image</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="{{asset('image/placeholder.webp')}}" id="image" class="w-100 img-thumbnail" alt="">
                                        </div>
                                        <div class="col-md-6 m-auto">
                                           <input type="file" name="image" class="form-control input_image @error('image') is-invalid  @enderror" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{$website->id}}">

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
