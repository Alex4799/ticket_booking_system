@extends('main.admin.layout.master')
@section('title')
    Product
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin#productList')}}">List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="shadow-container-box">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="product-view-image">
                        @if ($product->image!=null)
                            <img src="{{Storage::url('product/'.$product->image)}}" alt="image" class="img-thumbnail">
                        @else
                            <img src="{{asset('image/placeholder.webp')}}" alt="Default" class="img-thumbnail">
                        @endif
                    </div>
                </div>
                <div class="col-md-9 ps-md-5">
                    <div class="seat-view">
                        <div class="seat-detail-top">
                            <div class="seat-title">
                                <div>
                                    <label>Name</label>
                                    <h4>{{$product->name}}</h4>
                                </div>
                            </div>
                            <div>
                                <h6>
                                    @if ($product->active==1)
                                        <span class="status success fw-bold">Active</span>
                                    @else
                                        <span class="status reject fw-bold">Not Active</span>
                                    @endif
                                </h6>
                            </div>
                        </div>
                        <div class="product-detail-footer">
                            <div>
                                <label>Category</label>
                                <h6>{{$product->category_name}}</h6>
                            </div>
                            <div>
                                <label>Description</label>
                                <h6>{!!$product->description!!}</h6>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end flex-wrap gap-2">
                            <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#edit-product"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Product</button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-product" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="modal fade" id="edit-product" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-productLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="edit-productLabel">Edit Product</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{route('admin#productUpdate')}}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="update-btn-group">
                                    <div class="row">
                                        <div class="col-sm-12 form-group mb-4">
                                            <label class="mb-1">Name</label>
                                            <input type="text" name="name" value="{{$product->name}}" class="form-control" required>
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Category</label>
                                            <select name="category_id" id="" class="form-select" required>
                                                @foreach ($category as $item)
                                                    <option value="{{$item->id}}" @if ($item->id==$product->category_id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Status</label>
                                            <select name="active" class="form-select">
                                                <option value="0" @if ($product->active==0) selected @endif>Not Active</option>
                                                <option value="1" @if ($product->active==1) selected @endif>Active</option>
                                            </select>
                                            @error('active')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Description</label>
                                            <textarea name="description" id="editor" cols="30" rows="10">{{$product->description}}</textarea>
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Product Image</label>
                                            <div>
                                                @if ($product->image!=null)
                                                    <img src="{{Storage::url('product/'.$product->image)}}" alt="product image" class="img-thumbnail" id="image">
                                                @else
                                                    <img src="{{asset('image/placeholder.webp')}}" alt="Default" class="img-thumbnail" id="image">
                                                @endif
                                                <div>
                                                    <label for="input_image" class="file-pen-logo mt-3">
                                                        <span><i class="fa-solid fa-upload me-1"></i> Upload Image</span>
                                                    </label>
                                                    <input type="file" name="image" class="d-none @error('image') is-invalid  @enderror" id="input_image" accept="image/*">
                                                </div>
                                                @error('image')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$product->id}}">
                                    <div class="d-flex align-items-center my-2">
                                        <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="delete-product" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-productLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="delete-productLabel">Delete Product</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#productDelete')}}" method="post" class="mt-1">
                                @csrf
                                @method('DELETE')
                                <div class="delete-confirmation">
                                    <div class="delete-warning">
                                        <div class="delete-icon">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                        </div>
                                        <div class="delete-message">
                                            <h6>Are you sure to delete this ?</h6>
                                            <small>Verify you are admin. Please enter the password.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group my-3">
                                    <label class="mb-1">Password</label>
                                    <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <input type="hidden" name="id" value="{{$product->id}}">

                                <div class="d-flex align-items-center justify-content-end">
                                    <button type="submit" class="btn btn-danger">Delete</button>
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

        const editorElement = document.querySelector('#editor');
        const form = document.querySelector('.update-btn-group');
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
                    if (input.id !== 'editor') {
                        originalValues[input.name] = input.value;
                    }
                });
                function checkChanges() {
                    let hasChanged = false;
                    form.querySelectorAll('input, select, textarea').forEach(input => {
                        if (input.id === 'editor') {
                            return;
                        }
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
                    if (input.id !== 'editor') {
                        input.addEventListener('input', checkChanges);
                        input.addEventListener('change', checkChanges);
                    }
                });
                editor.model.document.on('change:data', checkChanges);
                checkChanges();
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
