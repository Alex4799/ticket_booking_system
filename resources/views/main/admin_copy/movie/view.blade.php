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
                  <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="shadow py-4 px-5 rounded">
            <div class=" d-flex justify-content-between flex-wrap">
                <h5 class=" py-2 ">{{$movie->name}}</h5>
                <div class="py-2">
                    <span>Rating &#58;</span>
                   <span>
                        @for ($i = 0; $i < $movie->rating; $i++)
                            <i class="fa-solid fa-star text-warning"></i>
                        @endfor
                    </span>
                </div>
            </div>
            <div class="py-2">
                <div class="row">
                    <div class=" col-md-4">
                        <div>
                            <img src="{{Storage::url('poster/'.$movie->poster)}}" class="w-100 img-thumbnail rounded" alt="">
                        </div>
                        <div class="py-2 d-flex justify-content-center">
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#show-trailer">See Trailer</button>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div>
                            <div class="cover py-3">
                                <h6>Photos :</h6>
                                <div class="owl-carousel owl-theme">
                                    @if ($movie->cover!=null)
                                        @foreach ($movie->cover as $item)
                                            <div class="">
                                                <img src="{{Storage::url('cover/'.$item)}}" class="w-100" alt="">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="cast py-3">
                                <h6>Casts :</h6>
                                <div class="owl-carousel owl-theme">
                                    @if ($movie->cast!=null)
                                        @foreach ($movie->cast as $item)
                                            <div class="">
                                                <div class="bg-secondary-subtle rounded">
                                                    <img src="{{Storage::url('cast/'.$item['image'])}}" alt="">
                                                </div>
                                                <div class="text-center mt-2">
                                                    {{$item['name']}}
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="genre py-3">
                                <h6>Genre :</h6>
                                <div>
                                    @if ($movie->genre!=null)
                                        <div class=" d-flex flex-wrap gap-1">
                                            @foreach ($movie->genre as $item)
                                                <div class="py-1">
                                                    <button class="btn btn-outline-secondary text-dark" disabled>{{$item}}</button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="other-detail py-3">
                                <h6>Other Details</h6>
                                <hr>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="py-2 d-flex justify-content-between">
                                            <span>Director &#58;</span>
                                            <span>{{$movie->director}}</span>
                                        </div>
                                        <div class="py-2 d-flex justify-content-between">
                                            <span>Release Date &#58;</span>
                                            <span>{{$movie->release_date}}</span>
                                        </div>
                                        <div class="py-2 d-flex justify-content-between">
                                            <span>Run Time &#58;</span>
                                            <span>{{$movie->run_time}} min</span>
                                        </div>
                                        <div class="py-2 d-flex justify-content-between">
                                            <span>Language &#58;</span>
                                            <span>{{$movie->language}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="py-2 d-flex justify-content-between">
                                            <span>Start Date &#58;</span>
                                            <span>{{$movie->start_date}}</span>
                                        </div>
                                        <div class="py-2 d-flex justify-content-between">
                                            <span>End Date &#58;</span>
                                            <span>{{$movie->end_date}}</span>
                                        </div>
                                        <div class="py-2 d-flex justify-content-between">
                                            <span>Active &#58;</span>
                                            @if ($movie->active==0)
                                                <span class="text-danger">Not Active</span>
                                            @else
                                                <span class="text-success">Active</span>
                                            @endif
                                        </div>
                                        <div class="py-2 d-flex justify-content-between">
                                            <span>Subtitle &#58;</span>
                                            <span>{{$movie->subtitle}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="review">
                                <h6>Summary :</h6>
                                <div class="">
                                    <p>{!!$movie->summary!!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" d-flex justify-content-end gap-1">
                <a href="{{route('admin#movieExtraEdit',$movie->id)}}" class="btn btn-outline-primary me-2" title='edit'><i class="fa-solid fa-hammer me-2"></i>Extra Edit</a>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit"><i class="fa-solid fa-hammer me-2"></i>Edit</button>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete"><i class="fa-solid fa-trash me-2"></i>Delete</button>
            </div>
        </div>

        {{-- model start  --}}
        <div class="modal fade" id="show-trailer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="show-trailerLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="show-trailerLabel">Trailer</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <iframe id="videoTrailer" class="w-100" height="315" src="{{$movie->trailer}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="editLabel">Edit Movie</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#movieUpdate')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="mt-3">
                                <div class="mb-4">
                                    <label for="" class="mb-1">Name</label>
                                    <input type="text" name="name" id="" value="{{$movie->name}}" class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="genre" class="form-label mb-1">Genre</label>
                                <select name="genre" id="" class="form-control @error('genre') is-invalid @enderror">
                                    <option value="">Choose Genre</option>
                                    @foreach ($genre as $item)
                                        <option value="{{$item->id}}" @if ($item->id==$movie->main_genre) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('genre')
                                    <small class=" text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-1">Poster Image</label>
                                <div class="row parents">
                                    <div class="col-md-6">
                                        @if ($movie->poster==null)
                                                    <img src="{{asset('image/placeholder.webp')}}" id="image" class="w-75 mx-auto img-thumbnail" alt="">
                                                @else
                                                    <img src="{{Storage::url('poster/'.$movie->poster)}}" id="image" class="w-75 mx-auto img-thumbnail" alt="">
                                                @endif
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="poster" class="form-control @error('poster') is-invalid @enderror input_image">
                                        @error('poster')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div class="mb-4">
                                    <label for="" class="mb-1">Director</label>
                                    <input type="text" name="director" id="" value="{{$movie->director}}" class="form-control @error('director') is-invalid @enderror">
                                    @error('director')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Release Date</label>
                                        <input type="date" name="release_date" id="" value="{{$movie->release_date}}" class="form-control @error('release_date') is-invalid @enderror">
                                        @error('release_date')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Run Time (min)</label>
                                        <input type="text" name="run_time" id="" value="{{$movie->run_time}}" class="form-control @error('run_time') is-invalid @enderror">
                                        @error('run_time')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Subtitles</label>
                                        <input type="text" name="subtitle" id="" value="{{$movie->subtitle}}" class="form-control @error('subtitle') is-invalid @enderror">
                                        @error('subtitle')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Language</label>
                                        <input type="text" name="language" id="" value="{{$movie->language}}" class="form-control @error('language') is-invalid @enderror">
                                        @error('language')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div class="mb-4">
                                    <label for="" class="mb-1">Trailer</label>
                                    <input type="text" name="trailer" id="" value="{{$movie->trailer}}" class="form-control @error('trailer') is-invalid @enderror">
                                    @error('trailer')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="">
                                <div class="mb-4">
                                    <div>
                                        <label for="" class="mb-2">Rating</label>
                                    </div>
                                    <div class="row">
                                        <span class="col-1">1</span>
                                        <div class="col-8">
                                            <input type="range" name="rating" value="{{$movie->rating}}" class="" min="1" max="10" id="range">
                                        </div>
                                        <span class="col-1">10</span>
                                        <div class="col-2">
                                            <input type="number" value="{{$movie->rating}}" class="form-control" readonly value="6" id="range_number">
                                        </div>
                                    </div>
                                    @error('rating')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="">
                                <div class="mb-4">
                                    <label for="" class="mb-1">Summary</label>
                                    <textarea name="summary" id="editor" cols="30" rows="10" class=" @error('summary') is-invalid @enderror">{{$movie->summary}}</textarea>
                                    @error('summary')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Start Date</label>
                                        <input type="date" name="start_date" id="" value="{{$movie->start_date}}" class="form-control @error('run_time') is-invalid @enderror">
                                        @error('start_date')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="" class="mb-1">End Date</label>
                                        <input type="date" name="end_date" id="" value="{{$movie->end_date}}" class="form-control @error('end_date') is-invalid @enderror">
                                        @error('end_date')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-4 col-md-6">
                                    <label for="name" class="form-label mb-1">Active</label>
                                    <select name="active" id="" class="form-control">
                                        <option value="0" @if ($movie->active==0) selected @endif>Not Active</option>
                                        <option value="1" @if ($movie->active==1) selected @endif>Active</option>
                                    </select>
                                    @error('active')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="name" class="form-label mb-1">Featured</label>
                                    <select name="featured" id="" class="form-control">
                                        <option value="0" @if ($movie->featured==0) selected @endif>False</option>
                                        <option value="1" @if ($movie->featured==1) selected @endif>True</option>
                                    </select>
                                    @error('featured')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>

                            <input type="hidden" name="id" value="{{$movie->id}}">

                            <div class="py-2 d-flex justify-content-end gap-1">
                                <input type="submit" value="Update" class="btn btn-success">
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="deleteLabel">Delete Movie</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#movieDelete')}}" method="post" class="mt-1">
                            @csrf
                            @method('DELETE')
                            <div class="py-1">
                                <p class="text-danger">Are you sure to delete this.</p>
                            </div>
                            <div class="py-1">
                                <h6>Verify you are admin. Please enter the password.</h6>
                            </div>
                            <div class="form-group mb-3">
                                <label class="mb-1">Password</label>
                                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password." required>
                                @error('password')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <input type="hidden" name="id" id="id" value="{{$movie->id}}">

                            <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
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
            $('#range').change(function(){
                console.log('hello');
                $('#range_number').val($(this).val());
            })

            $('.input_image').change(function(){
                $parents=$(this).parents('.parents')
                $parents.find('#image').attr("src",window.URL.createObjectURL(this.files[0]));
            })
        })
    </script>
    <script>
        $(document).ready(function(){
            $('.owl-carousel').owlCarousel({
                loop:true,
                margin:10,
                nav:true,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:2
                    },
                    1000:{
                        items:3
                    }
                }
            })
        })
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

        ClassicEditor
            .create( document.querySelector( '#editor' ), {
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
    </script>
@endsection
