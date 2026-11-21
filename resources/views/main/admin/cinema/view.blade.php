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
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-4 mb-5">
                    <div class="shadow-container-box">
                        <div class="cinema-detail px-1">
                            <div class="d-flex align-items-center justify-content-end mb-3">
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-cinema" style="font-size: 9pt;"><i class="fa-solid fa-trash"></i></button>
                            </div>
                            <div class="mb-3">
                                @if (count($cinema->image)!=0)
                                    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach ($cinema->image as $item)
                                                <div class="carousel-item @if ($loop->index==0) active @endif">
                                                    <img src="{{Storage::url('cinema/'.$item)}}" class="d-block img-thumbnail" alt="Cinema Photo">
                                                </div>
                                            @endforeach
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
                                @else
                                    <img src="{{asset('image/placeholder.webp')}}" alt="Profile" class="w-100 img-thumbnail">
                                @endif
                            </div>
                            <div class="mt-4 px-1">
                                <h5>{{$cinema->name}}</h5>
                                <div class="mt-4">
                                    <label>Location</label>
                                    <h6>{{$cinema->location}}</h6>
                                </div>
                                <div class="mt-4">
                                    <label>Contact</label>
                                    <h6>{!!$cinema->contact!!}</h6>
                                </div>
                                <div class="mt-4">
                                    <label>Status</label>
                                    <h6>
                                        @if ($cinema->active==1)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Not Active</span>
                                        @endif
                                    </h6>
                                </div>
                                <div class="mt-4">
                                    <button type="button" class="change-btn border-none w-100" data-bs-toggle="modal" data-bs-target="#edit-cinema"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Cinema</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="table-bg">
                        <div>
                            <div class="total-count-title">
                                <div class="d-flex align-items-center">
                                    <div class="count-content">
                                        <h5 class="mb-0">Total - {{$hall->total()}}</h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <form action="" method="get" id="search-form">
                                        <div class="search-cinema">
                                            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                                            <input placeholder="Search" value="{{request('hall_search')}}" name="hall_search" type="text" class="input">
                                        </div>
                                    </form>
                                    <a href="{{route('admin#cinemaView',$cinema->id)}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                                </div>
                            </div>
                            @if (count($hall)!=0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Hall Name</th>
                                                <th class="text-center">Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($hall as $item)
                                                <tr>
                                                    <td>{{$loop->index+1}}</td>
                                                    <td>{{$item->name}}@if ($item->du_id!=null) (Copy) @endif</td>
                                                    <td class="text-center">
                                                        @if ($item->active==1)
                                                            <span class="text-success">Active</span>
                                                        @else
                                                            <span class="text-danger">Not Active</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{route('admin#hallView',$item->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div>
                                        {{$hall->appends(request()->query())->links()}}
                                    </div>
                                </div>
                            @else
                                <h6 class="text-center mt-5 mb-4 text-secondary" style="font-size: 11pt;">There is no hall.</h6>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="modal fade" id="edit-cinema" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-cinemaLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="edit-cinemaLabel">Edit Cinema</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#cinemaUpdate')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="update-btn-group">
                                        <div class="row">
                                            <div class="col-sm-12 form-group mb-4">
                                                <label class="mb-1">Cinema Name</label>
                                                <input name="name" value="{{$cinema->name}}" type="text" class="form-control @error('name') is-invalid @enderror" required>
                                                @error('name')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group mb-4">
                                                    <label class="mb-1">Location</label>
                                                    <textarea name="location" id="" cols="30" rows="3" class="form-control @error('location') is-invalid  @enderror" required>{{$cinema->location}}</textarea>
                                                    @error('location')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="mb-1">Contact</label>
                                                    <textarea name="contact" id="editor" cols="30" rows="10">{{$cinema->contact}}</textarea>
                                                    @error('contact')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group mb-4">
                                                    <label class="mb-1">Status</label>
                                                    <select name="active" id="" class="form-select">
                                                        <option value="0" @if ($cinema->active==0) selected @endif>Not Active</option>
                                                        <option value="1" @if ($cinema->active==1) selected @endif>Active</option>
                                                    </select>
                                                    @error('active')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="mb-1">Cinema Image</label>
                                                    <div>
                                                        @if (count($cinema->image)!=0)
                                                            <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                                                                <div class="carousel-inner">
                                                                    @foreach ($cinema->image as $item)
                                                                        <div class="carousel-item @if ($loop->index==0) active @endif">
                                                                            <img src="{{Storage::url('cinema/'.$item)}}" class="d-block img-thumbnail" alt="Cinema Photo">
                                                                        </div>
                                                                    @endforeach
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
                                                        @else
                                                            <img src="{{asset('image/placeholder.webp')}}" alt="Profile" class="w-100 img-thumbnail">
                                                        @endif
                                                        <div class="mt-3">
                                                            <a href="{{route('admin#cinemaImageEdit',$cinema->id)}}" class="file-pen-logo text-decoration-none" style="font-size: 10pt;">
                                                                <span><i class="fa-solid fa-pen-to-square me-2"></i> Edit Image</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="{{$cinema->id}}">
                                        <div class="d-flex align-items-center my-2">
                                            <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="delete-cinema" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-cinemaLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="delete-cinemaLabel">Delete Cinema</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#cinemaDelete')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <div class="delete-confirmation">
                                        <div class="delete-warning">
                                            <div class="delete-icon">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                            </div>
                                            <div class="delete-message">
                                                <h6>Are you sure to delete this?</h6>
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

                                    <input type="hidden" name="id" value="{{$cinema->id}}">
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
