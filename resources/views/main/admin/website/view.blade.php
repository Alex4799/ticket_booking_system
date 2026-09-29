@extends('main.admin.layout.master')
@section('title')
    Website
@endsection
@section('content')
<main class="content px-3 py-4 mb-5">
    <div class="container-fluid">
        <div class="card border-0 shadow py-3 px-4">
            <!-- details start  -->
            <div class="mt-3">
                <div class="mb-4">
                    <div class="d-flex align-items-center" id="web-info-text">
                        <i class="fa-solid fa-circle-info me-2"></i><h6 class="pt-2">Website Name</h6>
                    </div>
                    <h4 class="mt-2">{{$website->name}}</h4>
                </div>
                <hr>
                <div class="mb-4">
                    <div class="d-flex align-items-center" id="web-info-text">
                        <i class="fa-solid fa-circle-info me-2"></i><h6 class="pt-2">Contact Info</h6>
                    </div>
                    <p>{!!$website->contact!!}</p>
                </div>
                <hr>
                <div class="mb-4">
                    <div class="d-flex align-items-center" id="web-info-text">
                        <i class="fa-solid fa-circle-info me-2"></i><h6 class="pt-2">Logo</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-4 offset-md-4">
                            @if ($website->logo==null)
                                <img src="{{asset('image/placeholder.webp')}}" class="w-100 img-thumbnail" alt="Logo">
                            @else
                                <img src="{{Storage::url('website/'.$website->logo)}}" class="w-100 img-thumbnail" alt="Logo">
                            @endif
                        </div>
                    </div>
                <hr>
                <div class="mb-4">
                    <div class="d-flex align-items-center" id="web-info-text">
                        <i class="fa-solid fa-circle-info me-2"></i><h6 class="pt-2">Cover Letter</h6>
                    </div>
                    <p>{!!$website->cover_letter!!}</p>
                </div>
                <hr>
                <div class="mb-4">
                    <div class="d-flex align-items-center" id="web-info-text">
                        <i class="fa-solid fa-circle-info me-2"></i><h6 class="pt-2">Cover Image</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-4 offset-md-4">
                            <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @if (count($website->cover_image)!=0)
                                        @foreach ($website->cover_image as $item)
                                            <div class="carousel-item @if ($loop->index==0) active @endif">
                                                <img src="{{Storage::url('website/'.$item)}}" class="w-100" alt="Cover Photo">
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="carousel-item active">
                                            <img src="{{asset('image/default.png')}}" class="w-100" alt="Cover Image">
                                        </div>
                                    @endif
                                </div>
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
                </div>
                <hr>
                <div class="mb-4">
                    <div class="d-flex align-items-center" id="web-info-text">
                        <i class="fa-solid fa-circle-info me-2"></i><h6 class="pt-2">About Us Letter</h6>
                    </div>
                    <p>{!!$website->aboutus_letter!!}</p>
                </div>
                <hr>
                <div class="mb-4">
                    <div class="d-flex align-items-center" id="web-info-text">
                        <i class="fa-solid fa-circle-info me-2"></i><h6 class="pt-2">Footer Letter</h6>
                    </div>
                    <p>{!!$website->footer_letter!!}</p>
                </div>
                <hr>
                <div class="mb-4">
                    <div class="d-flex align-items-center" id="web-info-text">
                        <i class="fa-solid fa-circle-info me-2"></i><h6 class="pt-2">Main Info</h6>
                    </div>
                    <p>Order Booking Reject Mnute - {{$website->default_order_reject_min}} min</p>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <div class="">
                    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#update">Edit</button>
                    <a href="{{route('admin#websiteExtraEdit')}}" class="btn btn-primary mt-3">Extra Edit</a>
                </div>
            </div>
            <!-- details end  -->
        </div>
        <div class="modal fade" id="update" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="updateLabel">Add Social Details</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#websiteUpdate')}}" method="post" class="mt-4" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="form-group mb-4">
                                <label class="mb-1">Website Name</label>
                                <input name="name" value="{{$website->name}}" type="text"class="form-control @error('name') is-invalid @enderror" placeholder="Enter cinema name">
                                @error('name')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Contact</label>
                                <textarea name="contact" class="editor" cols="30" rows="10" placeholder="Enter content info">{{$website->contact}}</textarea>
                                @error('contact')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-5">
                                <label class="mb-1">Logo</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        @if ($website->logo==null)
                                            <img src="{{asset('image/placeholder.webp')}}" alt="Cover" class="w-75 mx-auto img-thumbnail" id="image">
                                        @else
                                            <img src="{{Storage::url('website/'.$website->logo)}}" alt="Cover" class="w-75 mx-auto img-thumbnail" id="image">
                                        @endif
                                    </div>
                                    <div class="col-md-6 m-auto">
                                        <div>
                                            <input type="file" name="logo" class="form-control" id="input_image">
                                            @error('logo')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-5">
                                <label class="mb-1">Cover Letter</label>
                                <textarea name="cover_letter" class="editor" cols="30" rows="10" placeholder="Enter content info">{{$website->cover_letter}}</textarea>
                                @error('cover_letter')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">About Us Letter</label>
                                <textarea name="aboutus_letter" class="editor" cols="30" rows="10" placeholder="Enter content info">{{$website->aboutus_letter}}</textarea>
                                @error('aboutus_letter')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Footer Letter</label>
                                <textarea name="footer_letter" class="editor" cols="30" rows="10" placeholder="Enter content info">{{$website->footer_letter}}</textarea>
                                @error('footer_letter')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Order Booking Reject Min</label>
                                <input name="default_order_reject_min" value="{{$website->default_order_reject_min}}" type="text"class="form-control @error('name') is-invalid @enderror" placeholder="Enter cinema name">
                                @error('name')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mt-3">Update</button>
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

        let editor=document.querySelectorAll('.editor');
        editor.forEach(element => {
            ClassicEditor
            .create(element, {
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
        });
    </script>
@endsection
