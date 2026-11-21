@extends('main.admin.layout.master')
@section('title')
    Movies
@endsection
@section('content')

<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div class="table-bg">
            <div>
                <div class="total-count-title">
                    <div class="d-flex align-items-center">
                        <div class="count-content">
                            <h5 class="mb-0">Total - {{$movie->total()}}</h5>
                        </div>
                        <div class="ms-3">
                            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#add">
                                <i class="fa-solid fa-square-plus me-2"></i>Add Movie
                            </button>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <form action="" method="get" id="search-form">
                            <div class="search-cinema">
                                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                                <input placeholder="Search" value="{{request('search_key')}}" name="search_key" type="text" class="input">
                            </div>
                        </form>
                        <a href="{{route('admin#movieList')}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                    </div>
                </div>
               @if (count($movie)!=0)
                    <div class="table-responsive">
                        <table id="movie-list" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th class="col-2">Poster</th>
                                    <th>Name</th>
                                    <th class="text-center">Release Date</th>
                                    <th class="text-center">Rating</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Feature</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movie as $item)
                                    <tr class="mx-auto">
                                        <td>{{$loop->index+1}}</td>
                                        <td class="col-2">
                                            @if ($item->poster==null)
                                                <img src="{{asset('image/placeholder.webp')}}" id="image" class="img-thumbnail" alt="">
                                            @else
                                                <img src="{{Storage::url('poster/'.$item->poster)}}" alt="">
                                            @endif
                                        </td>
                                        <td>{{$item->name}}</td>
                                        <td class="text-center">{{$item->release_date}}</td>
                                        <td class="text-center">{{$item->rating}}/10</td>
                                        <td class="text-center">
                                            @if ($item->active==1)
                                                <span class="text-success">Active</span>
                                            @else
                                                <span class="text-danger">Not Active</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->featured==1)
                                                <span class="text-success">True</span>
                                            @else
                                                <span class="text-danger">False</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('admin#movieView',$item->id)}}" class="btn btn-outline-primary btn-sm me-2"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            {{$movie->appends(request()->query())->links()}}
                        </div>
                    </div>
               @else
                    <h6 class="text-center mt-5 mb-4 text-secondary" style="font-size: 11pt;">There is no movie.</h6>
               @endif
            </div>
        </div>

        {{-- model--}}
        <div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-block w-100 text-center" id="add-Label">Add Movie</h5>
                        <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#movieCreate')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 form-group mb-4">
                                    <label class="mb-1">Name</label>
                                    <input type="text" name="name" id="" class="form-control @error('name') is-invalid @enderror" required>
                                    @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Genre</label>
                                    <select name="genre" id="" class="form-select @error('genre') is-invalid @enderror" required>
                                        <option value="">Choose Genre</option>
                                        @foreach ($genre as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('genre')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Release Date</label>
                                    <input type="date" name="release_date" class="form-control @error('release_date') is-invalid @enderror" required>
                                    @error('release_date')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Director</label>
                                    <input type="text" name="director" class="form-control @error('director') is-invalid @enderror" required>
                                    @error('director')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Trailer Link</label>
                                    <input type="text" name="trailer" class="form-control @error('trailer') is-invalid @enderror" required>
                                    @error('trailer')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4 form-group mb-4">
                                    <label class="mb-1">Run Time (min)</label>
                                    <input type="text" name="run_time" class="form-control @error('run_time') is-invalid @enderror" required>
                                    @error('run_time')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4 form-group mb-4">
                                    <label class="mb-1">Language</label>
                                    <input type="text" name="language" id="" class="form-control @error('language') is-invalid @enderror" required>
                                    @error('language')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4 form-group mb-4">
                                    <label class="mb-1">Subtitle</label>
                                    <input type="text" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" required>
                                    @error('subtitle')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12 form-group mb-4">
                                    <label class="mb-1">Rating</label>
                                    <div class="row align-items-center">
                                        <div class="col-4">
                                            <input type="number" class="form-control text-center" value="6" id="range_number" readonly>
                                        </div>
                                        <div class="col-8 d-flex align-items-center justify-content-center">
                                            <span>1</span>
                                            <div>
                                                <input type="range" name="rating" class="form-control" min="1" max="10" id="range">
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
                                        <img src="{{asset('image/placeholder.webp')}}" alt="image" class="img-thumbnail" id="image">
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
                                    <textarea name="summary" id="editor" cols="30" rows="10" class="@error('summary') is-invalid @enderror"></textarea>
                                    @error('summary')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Start Date</label>
                                    <input type="date" name="start_date" class="form-control @error('run_time') is-invalid @enderror" required>
                                    @error('start_date')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">End Date</label>
                                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" required>
                                    @error('end_date')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Status</label>
                                    <select name="active" id="" class="form-select">
                                        <option value="0">Not Active</option>
                                        <option value="1">Active</option>
                                    </select>
                                    @error('active')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Feature</label>
                                    <select name="featured" id="" class="form-select">
                                        <option value="0">False</option>
                                        <option value="1">True</option>
                                    </select>
                                    @error('featured')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex align-items-center my-2">
                                <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Create</button>
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
            $('#range').change(function(){
                $('#range_number').val($(this).val());
            });

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
