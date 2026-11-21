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
                    <li class="breadcrumb-item"><a href="{{route('admin#websiteView')}}">View</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Extra</li>
                </ol>
            </nav>
        </div>
        <div class="shadow-container-box">
            <div>
                <h5 class="box-title">Extra Edit</h5>
            </div>
            <div class="cover extra">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <h5 class="extra-title">Cover Images</h5>
                    <div>
                        <button type="button" class="add-btn" data-bs-toggle="modal" data-bs-target="#add-cover"><i class="fa-solid fa-square-plus me-2"></i>Add Image</button>
                    </div>
                </div>
                <div class="row mt-3 cover-images">
                    @if ($website->cover_image!=null)
                        @foreach ($website->cover_image as $key=>$item)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="image-cards">
                                    <form action="{{route('admin#websiteCoverUpdate')}}" class="parents" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                        <div class="img-content">
                                            <a href="{{route('admin#websiteCoverDelete',$key)}}" class="btn btn-sm btn-danger py-0" style="font-size: 9pt;"><i class="fa-solid fa-xmark"></i></a>
                                            <img src="{{Storage::url('website/'.$item)}}" alt="" id="image" class="rounded img-thumbnail">
                                        </div>
                                        <div>
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
                        <h6 class="text-center mt-5 mb-4 text-secondary" style="font-size: 11pt;">There is no cover images.</h6>
                    @endif
                </div>
            </div>
            <div class="cover extra">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <h5 class="extra-title">About Us Images</h5>
                    <div>
                        <button type="button" class="add-btn" data-bs-toggle="modal" data-bs-target="#add-about"><i class="fa-solid fa-square-plus me-2"></i>Add Image</button>
                    </div>
                </div>
                <div class="row mt-3 cover-images">
                    @if ($website->about_image!=null)
                        @foreach ($website->about_image as $key=>$item)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="image-cards">
                                    <form action="{{route('admin#websiteAboutUpdate')}}" class="parents" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                        <div class="img-content">
                                            <a href="{{route('admin#websiteAboutDelete',$key)}}" class="btn btn-sm btn-danger py-0" style="font-size: 9pt;"><i class="fa-solid fa-xmark"></i></a>
                                            <img src="{{Storage::url('website/'.$item)}}" alt="" id="image" class="rounded img-thumbnail">
                                        </div>
                                        <div>
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
                        <h6 class="text-center mt-5 mb-4 text-secondary" style="font-size: 11pt;">There is no about us images.</h6>
                    @endif
                </div>
            </div>
        </div>
        <div>
            <div class="modal fade" id="add-cover" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-coverLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="add-coverLabel">Add Cover Image</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#websiteCoverAdd')}}" class="mt-1 parents" method="post" enctype="multipart/form-data">
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
                                        <input type="file" name="image" class="d-none" id="cover_image">
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{$website->id}}">
                                <div class="d-flex align-items-center my-2">
                                    <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Create</button>
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
                            <h5 class="modal-title d-block w-100 text-center" id="add-aboutLabel">Add About Us Image</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#websiteAboutAdd')}}" class="mt-1 parents" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row form-group mb-5 align-items-center">
                                    <div class="col-sm-6">
                                        <label for="" class="mb-1">Image</label>
                                        <div class="text-center">
                                            <img src="{{asset('image/placeholder.webp')}}" alt="image" class="img-thumbnail mx-auto" id="image">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="about_image" class="file-pen-logo mt-3">
                                            <span><i class="fa-solid fa-upload me-1"></i> Upload Image</span>
                                        </label>
                                        <input type="file" name="image" class="d-none" id="about_image">
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{$website->id}}">
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

        $('#cover_image').change(function(){
            $parents=$(this).parents('.parents');
            $parents.find('#image').attr("src",window.URL.createObjectURL(this.files[0]));
        });

        $('#about_image').change(function(){
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
