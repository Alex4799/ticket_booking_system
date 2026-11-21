@extends('main.admin.layout.master')
@section('title')
    Website Info
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-end gap-2 mb-3">
            <a href="{{route('admin#websiteExtraEdit')}}" class="text-decoration-none">
                <button class="add-btn">
                    <i class="fa-solid fa-pen-to-square me-2"></i>Extra Edit
                </button>
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit" style="font-size: 11pt;"><i class="fa-solid fa-pen-to-square"></i></button>
        </div>
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="shadow-container-box">
                    <div class="website-info-section">
                        <div class="website-info-title">
                            <i class="fa-solid fa-globe"></i>
                            <span>Website Name</span>
                        </div>
                        <div class="mt-3 mb-0">{{ $website->name }}</div>
                    </div>
                    <div class="website-info-section">
                        <div class="website-info-title">
                            <i class="fa-solid fa-address-card"></i>
                            <span>Contact Information</span>
                        </div>
                        <div class="website-content mt-3">{!! $website->contact !!}</div>
                    </div>
                    <div class="website-info-section">
                        <div class="website-info-title">
                            <i class="fa-solid fa-clock"></i>
                            <span>Order Booking Reject Time</span>
                        </div>
                        <div class="website-content mt-3">{{$website->default_order_reject_min}} min</div>
                    </div>
                    {{-- <div class="website-info-section">
                        <div class="website-info-title">
                            <i class="fa-solid fa-file-lines"></i>
                            <span>Cover Letter</span>
                        </div>
                        <div class="website-content mt-3">{!! $website->cover_letter !!}</div>
                    </div> --}}
                    <div class="website-info-section">
                        <div class="website-info-title">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>About Us</span>
                        </div>
                        <div class="website-content mt-3">{!! $website->aboutus_letter !!}</div>
                    </div>
                    {{-- <div class="website-info-section">
                        <div class="website-info-title">
                            <i class="fa-solid fa-align-left"></i>
                            <span>Footer</span>
                        </div>
                        <div class="website-content mt-3">{!! $website->footer_letter !!}</div>
                    </div> --}}
                </div>
            </div>
            <div class="col-md-4">
                <div class="shadow-container-box">
                    <div class="website-info-section">
                        <div class="website-info-title mb-3">
                            <i class="fa-solid fa-image"></i>
                            <span>Logo</span>
                        </div>
                        <div class="logo-preview">
                            @if ($website->logo == null)
                                <img src="{{ asset('image/placeholder.webp') }}" alt="Logo" class="img-thumbnail">
                            @else
                                <img src="{{Storage::url('website/'.$website->logo)}}" alt="Logo" class="img-thumbnail">
                            @endif
                        </div>
                    </div>
                    <div class="website-info-section">
                        <div class="website-info-title mb-3">
                            <i class="fa-solid fa-images"></i>
                            <span>Cover Images</span>
                        </div>
                        @if (count($website->cover_image) != 0)
                            <div class="cover-preview">
                                <div id="websiteCoverCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner rounded">
                                        @foreach ($website->cover_image as $item)
                                            <div class="carousel-item @if ($loop->index==0) active @endif">
                                                <img src="{{Storage::url('website/'.$item)}}" alt="Cover Image" class="img-thumbnail">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if (count($website->cover_image) > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#websiteCoverCarousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#websiteCoverCarousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="empty-cover">
                                <img src="{{ asset('image/placeholder.webp') }}" alt="cover" class="img-thumbnail">
                            </div>
                        @endif
                    </div>
                    <div class="website-info-section">
                        <div class="website-info-title mb-3">
                            <i class="fa-solid fa-images"></i>
                            <span>About Us Images</span>
                        </div>
                        @if (count($website->about_image) != 0)
                            <div class="cover-preview">
                                <div id="websiteAboutCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner rounded">
                                        @foreach ($website->about_image as $item)
                                            <div class="carousel-item @if ($loop->index==0) active @endif">
                                                <img src="{{Storage::url('website/'.$item)}}" alt="About Image"  class="img-thumbnail">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if (count($website->about_image) > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#websiteAboutCarousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#websiteAboutCarousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="empty-cover">
                                <img src="{{ asset('image/placeholder.webp') }}" alt="cover" class="img-thumbnail">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal  --}}
    <div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-block w-100 text-center" id="edit-label">Edit Information</h5>
                    <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin#websiteUpdate')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="update-btn-group">
                            <div>
                                <div class="form-group mb-4">
                                    <label class="mb-1">Website Name</label>
                                    <input name="name" value="{{$website->name}}" type="text"class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-4">
                                    <label class="mb-1">Contact Info</label>
                                    <textarea name="contact" class="editor" cols="30" rows="10">{{$website->contact}}</textarea>
                                    @error('contact')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-5">
                                    <label class="mb-1">Order Booking Reject Time ( min )</label>
                                    <input name="default_order_reject_min" value="{{$website->default_order_reject_min}}" type="text" class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-5">
                                    <label class="mb-1">About Us</label>
                                    <textarea name="aboutus_letter" class="editor" cols="30" rows="10">{{$website->aboutus_letter}}</textarea>
                                    @error('aboutus_letter')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                {{-- <div class="form-group mb-5 d-none">
                                    <label class="mb-1">Cover Letter</label>
                                    <textarea name="cover_letter" class="editor" cols="30" rows="10">{{$website->cover_letter}}</textarea>
                                    @error('cover_letter')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-5 d-none">
                                    <label class="mb-1">Footer</label>
                                    <textarea name="footer_letter" class="editor" cols="30" rows="10">{{$website->footer_letter}}</textarea>
                                    @error('footer_letter')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div> --}}
                                <div class="form-group mb-5">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-3">
                                        <label>Logo</label>
                                        <div>
                                            <label for="input_image" class="file-pen-logo">
                                                <span><i class="fa-solid fa-upload me-1"></i> Upload Image</span>
                                            </label>
                                            <input type="file" name="logo" class="d-none @error('logo') is-invalid  @enderror" id="input_image" accept="image/*">
                                        </div>
                                    </div>
                                    <div>
                                        @if ($website->logo==null)
                                            <img src="{{asset('image/placeholder.webp')}}" alt="image" class="img-thumbnail d-block w-75 mx-auto" id="image">
                                        @else
                                            <img src="{{Storage::url('website/'.$website->logo)}}" class="img-thumbnail d-block w-75 mx-auto" alt="" id="image">
                                        @endif
                                        @error('logo')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center my-2">
                                <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                            </div>
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
        $(document).ready(function(){
            $('#input_image').change(function(){
                document.getElementById('image').src = window.URL.createObjectURL(this.files[0]);
            });
        });
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

        document.querySelectorAll('.editor').forEach(function(editorElement) {
            const form = editorElement.closest('.update-btn-group');
            if (!form) {return;}
            const button = form.querySelector('.action-btn');
            ClassicEditor
                .create(editorElement, {
                    plugins: [
                        Essentials,
                        Bold,
                        Italic,
                        Font,
                        Paragraph
                    ],
                    toolbar: {
                        items: [
                            'undo',
                            'redo',
                            '|',
                            'bold',
                            'italic',
                            '|',
                            'fontSize',
                            'fontFamily',
                            'fontColor',
                            'fontBackgroundColor'
                        ]
                    }
                })
                .then(editor => {
                    const originalEditorValue = editor.getData();
                    const originalValues = {};
                    form.querySelectorAll('input, select, textarea').forEach(input => {
                        if (input !== editorElement) {
                            originalValues[input.name] = input.value;
                        }
                    });
                    function checkChanges() {
                        let hasChanged = false;
                        form.querySelectorAll('input, select, textarea').forEach(input => {
                            if (input === editorElement) { return; }
                            if (input.value !== originalValues[input.name]) {
                                hasChanged = true;
                            }
                        });
                        if (editor.getData() !== originalEditorValue) {
                            hasChanged = true;
                        }
                        button.disabled = !hasChanged;
                    }

                    form.querySelectorAll('input, select, textarea').forEach(input => {
                        if (input === editorElement) {
                            return;
                        }
                        input.addEventListener('input', checkChanges);
                        input.addEventListener('change', checkChanges);
                    });
                    editor.model.document.on('change:data', checkChanges);
                    checkChanges();
                })
                .catch(error => {
                    console.error(error);
                });

        });
    </script>
@endsection
