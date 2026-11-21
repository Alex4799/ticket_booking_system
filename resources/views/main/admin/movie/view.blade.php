@extends('main.admin.layout.master')
@section('title')
    Movie
@endsection
@section('content')

<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('admin#movieList')}}">List</a></li>
                  <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="shadow-container-box">
            <div class="movie-view">
                <div class="row">
                    <div class="col-md-3 mb-5">
                        <div>
                            <div class="poster-content">
                                <img src="{{Storage::url('poster/'.$movie->poster)}}" alt="{{$movie->name}}" class="img-thumbnail">
                                <div class="mt-3">
                                    <button type="button" class="trailer-btn" data-bs-toggle="modal" data-bs-target="#show-trailer">
                                        <i class="fa-solid fa-circle-play me-2"></i>
                                        <span>Watch Trailer</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 ps-md-4">
                        <div>
                            <div class="title">
                                <div>
                                    <h4>{{$movie->name}}</h4>
                                    <div class="genre">
                                        @if ($movie->genre != null)
                                            @foreach ($movie->genre as $item)
                                                <span>{{$item}}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{route('admin#movieExtraEdit',$movie->id)}}" class="text-decoration-none">
                                        <button type="button" class="action-btn"><i class="fa-solid fa-pen-to-square me-2"></i>Extra Edit</button>
                                    </a>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit" style="font-size: 11pt;"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                            <div class="details">
                                <h6 class="section-label">Details</h6>
                                <div class="details-grid">
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-video me-2"></i>Director
                                        </span>
                                        <strong>{{$movie->director}}</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-calendar me-2"></i>Release Date
                                        </span>
                                        <strong>{{$movie->release_date}}</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-clock me-2"></i>Run Time
                                        </span>
                                        <strong>{{$movie->run_time}} min</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-star me-2"></i>Rating
                                        </span>
                                        <strong>{{$movie->rating}} / 10</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-language me-2"></i>Language
                                        </span>
                                        <strong>{{$movie->language}}</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-closed-captioning me-2"></i>Subtitle
                                        </span>
                                        <strong>{{$movie->subtitle}} </strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-play me-2"></i>Start Date
                                        </span>
                                        <strong>{{$movie->start_date}}</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-flag-checkered me-2"></i>End Date
                                        </span>
                                        <strong>{{$movie->end_date}}</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-circle me-2"></i>Feature
                                        </span>
                                        @if ($movie->featured == 0)
                                            <strong class="text-danger">
                                                <i class="fa-solid fa-circle-xmark me-1"></i>
                                                False
                                            </strong>
                                        @else
                                            <strong class="text-success">
                                                <i class="fa-solid fa-circle-check me-1"></i>
                                                True
                                            </strong>
                                        @endif
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-circle me-2"></i>Status
                                        </span>
                                        @if ($movie->active == 0)
                                            <strong class="text-danger">
                                                <i class="fa-solid fa-circle-xmark me-1"></i>
                                                Not Active
                                            </strong>
                                        @else
                                            <strong class="text-success">
                                                <i class="fa-solid fa-circle-check me-1"></i>
                                                Active
                                            </strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="summary">
                                <h6 class="section-label">Summary</h6>
                                <div class="movie-summary">
                                    {!!$movie->summary!!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row footer">
                    @if ($movie->cover != null)
                        <div class="col-md-7">
                            <div class="gallery">
                                <h6 class="section-label">Gallery</h6>
                                <div class="owl-carousel owl-theme movie-cover-carousel">
                                    @foreach ($movie->cover as $item)
                                        <div class="cover-item">
                                            <img src="{{Storage::url('cover/'.$item)}}" alt="Movie Photo" class="img-thumbnail">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($movie->cast != null)
                        <div class="col-md-5 ps-md-5">
                            <div class="cast">
                                <h6 class="section-label">Cast</h6>
                                <div class="owl-carousel owl-theme movie-cast-carousel">
                                    @foreach ($movie->cast as $item)
                                        <div>
                                            <div>
                                                <img src="{{Storage::url('cast/'.$item['image'])}}" alt="{{$item['name']}}" class="img-thumbnail">
                                            </div>
                                            <div class="cast-name">{{$item['name']}}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>


        {{-- model  --}}
        <div>
            <div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="edit-Label">Edit Movie</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#movieUpdate')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="update-btn-group">
                                    <div class="row">
                                        <div class="col-sm-12 form-group mb-4">
                                            <label class="mb-1">Name</label>
                                            <input type="text" name="name" id="" value="{{$movie->name}}" class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Genre</label>
                                            <select name="genre" id="" class="form-select @error('genre') is-invalid @enderror">
                                                @foreach ($genre as $item)
                                                    <option value="{{$item->id}}" @if ($item->id==$movie->main_genre) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('genre')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Release Date</label>
                                            <input type="date" name="release_date" id="" value="{{$movie->release_date}}" class="form-control @error('release_date') is-invalid @enderror">
                                            @error('release_date')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Director</label>
                                            <input type="text" name="director" id="" value="{{$movie->director}}" class="form-control @error('director') is-invalid @enderror">
                                            @error('director')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Trailer Link</label>
                                            <input type="text" name="trailer" id="" value="{{$movie->trailer}}" class="form-control @error('trailer') is-invalid @enderror">
                                            @error('trailer')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4 form-group mb-4">
                                            <label class="mb-1">Run Time (min)</label>
                                            <input type="text" name="run_time" id="" value="{{$movie->run_time}}" class="form-control @error('run_time') is-invalid @enderror">
                                            @error('run_time')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4 form-group mb-4">
                                            <label class="mb-1">Language</label>
                                            <input type="text" name="language" id="" value="{{$movie->language}}" class="form-control @error('language') is-invalid @enderror">
                                            @error('language')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4 form-group mb-4">
                                            <label class="mb-1">Subtitle</label>
                                            <input type="text" name="subtitle" id="" value="{{$movie->subtitle}}" class="form-control @error('subtitle') is-invalid @enderror">
                                            @error('subtitle')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 form-group mb-4">
                                            <label class="mb-1">Rating</label>
                                            <div class="row align-items-center">
                                                <div class="col-4">
                                                    <input type="number" class="form-control text-center" value="{{$movie->rating}}" id="range_number" readonly>
                                                </div>
                                                <div class="col-8 d-flex align-items-center justify-content-center">
                                                    <span>1</span>
                                                    <div>
                                                        <input type="range" value="{{$movie->rating}}" name="rating" class="form-control" min="1" max="10" id="range">
                                                    </div>
                                                    <span>10</span>
                                                </div>
                                            </div>
                                            @error('rating')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-5 form-group mb-5">
                                            <label class="mb-1">Poster Image</label>
                                            <div>
                                                @if ($movie->poster==null)
                                                    <img src="{{asset('image/placeholder.webp')}}" alt="image" class="img-thumbnail" id="image">
                                                @else
                                                    <img src="{{Storage::url('poster/'.$movie->poster)}}" id="image" class="img-thumbnail" alt="">
                                                @endif
                                                <div>
                                                    <label for="input_image" class="file-pen-logo mt-3">
                                                        <span><i class="fa-solid fa-upload me-1"></i> Upload Image</span>
                                                    </label>
                                                    <input type="file" name="poster" class="d-none @error('poster') is-invalid  @enderror" id="input_image">
                                                </div>
                                                @error('poster')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-7 ps-sm-3 form-group mb-5">
                                            <label class="mb-1">Summary</label>
                                            <textarea name="summary" id="editor" cols="30" rows="10" class=" @error('summary') is-invalid @enderror">{{$movie->summary}}</textarea>
                                            @error('summary')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Start Date</label>
                                            <input type="date" name="start_date" id="" value="{{$movie->start_date}}" class="form-control @error('run_time') is-invalid @enderror">
                                            @error('start_date')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">End Date</label>
                                            <input type="date" name="end_date" id="" value="{{$movie->end_date}}" class="form-control @error('end_date') is-invalid @enderror">
                                            @error('end_date')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Status</label>
                                            <select name="active" id="" class="form-select">
                                                <option value="0" @if ($movie->active==0) selected @endif>Not Active</option>
                                                <option value="1" @if ($movie->active==1) selected @endif>Active</option>
                                            </select>
                                            @error('active')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Feature</label>
                                            <select name="featured" id="" class="form-select">
                                                <option value="0" @if ($movie->featured==0) selected @endif>False</option>
                                                <option value="1" @if ($movie->featured==1) selected @endif>True</option>
                                            </select>
                                            @error('featured')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$movie->id}}">
                                    <div class="d-flex align-items-center my-2">
                                        <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="show-trailer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="show-trailerLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="show-trailerLabel">Trailer</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <iframe id="videoTrailer" class="w-100" height="315" src="{{$movie->trailer}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="deleteLabel">Delete Movie</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#movieDelete')}}" method="post" class="mt-1">
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

                                <input type="hidden" name="id" id="plan_id" value="{{$movie->id}}">

                                <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
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
            $('.owl-carousel').owlCarousel({
                loop:true,
                margin:10,
                nav: false,
                dots: true,
                responsive:{
                    0:{
                        items:2
                    },
                    991:{
                        items:3
                    }
                }
            })
        });

        $(document).ready(function(){
            $('#range').change(function(){
                $('#range_number').val($(this).val());
            });

            $('#input_image').change(function(){
                document.getElementById('image').src = window.URL.createObjectURL(this.files[0]);
            });
        });
    </script>
    <script>
        const modal = document.getElementById('show-trailer');
        const video = document.getElementById('videoTrailer');

        modal.addEventListener('hidden.bs.modal', function () {
            video.src = video.src;
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
