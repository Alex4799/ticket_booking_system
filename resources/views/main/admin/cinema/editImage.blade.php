@extends('main.admin.layout.master')
@section('title')
    Cinema
@endsection
@section('content')
    <main class="content p-2 mb-5">
        <div class="container-fluid">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin#cinemaList')}}">List</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin#cinemaView',$cinema->id)}}">View</a></li>
                        <li class="breadcrumb-item active">Edit Image</li>
                    </ol>
                </nav>
            </div>

            <div class="shadow-container-box">
                <div class="title-page">
                    <h5>Cinema Images</h5>
                    <div>
                        <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#add"><i class="fa-solid fa-plus-square me-2"></i>Add Image</button>
                    </div>
                </div>
                <div class="row cinema-images">
                    @foreach ($cinema->image as $key=>$item)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="image-cards">
                                <form action="{{route('admin#cinemaImageUpdate')}}" method="post" enctype="multipart/form-data" class="parents">
                                    @csrf
                                    @method('PATCH')
                                    <div class="img-content">
                                        <a href="{{route('admin#cinemaImageDelete',[$cinema->id,$key])}}" class="btn btn-sm btn-danger py-0" style="font-size: 9pt;"><i class="fa-solid fa-xmark"></i></a>
                                        <img src="{{Storage::url('cinema/'.$item)}}" alt="" id="image" class="rounded img-thumbnail">
                                    </div>
                                    <div>
                                        <input type="hidden" name="id" value="{{$cinema->id}}">
                                        <input type="hidden" name="index" value="{{$key}}">
                                    </div>
                                    <div class="mt-2">
                                       <input type="file" name="image" class="input_image form-control" style="font-size: 11pt;">
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="action-btn w-100" disabled>Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- modal  --}}
            <div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="addLabel">Add Image</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#cinemaImageCreate')}}" method="post" enctype="multipart/form-data" class="parents">
                                @csrf
                                <div class="row form-group mb-4 align-items-center">
                                    <div class="col-sm-6">
                                        <label for="" class="mb-1">Image</label>
                                        <div>
                                            <img src="{{asset('image/placeholder.webp')}}" alt="image" class="img-thumbnail" id="image">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="cover_image" class="file-pen-logo mt-3">
                                            <span><i class="fa-solid fa-upload me-1"></i> Upload Image</span>
                                        </label>
                                        <input type="file" name="image" class="d-none" id="cover_image">
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="id" value="{{$cinema->id}}">
                                </div>
                                <div class="d-flex align-items-center my-2">
                                    <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Add</button>
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
            });

            $('#cover_image').change(function(){
                $parents=$(this).parents('.parents');
                $parents.find('#image').attr("src",window.URL.createObjectURL(this.files[0]));
            });
        });
        

        // updatebtn disabled check
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
