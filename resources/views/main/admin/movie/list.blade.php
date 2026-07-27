@extends('main.admin.layout.master')
@section('title')
    Movie
@endsection
@section('content')
<main class="content px-3 mb-5">
    <div class="container-fluid">
        <div id="table-bg">
            <div class="my-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
                        <h5>Total - {{$movie->total()}}</h5>
                    </div>
                    <form action="" id="search-form">
                        <div class="search-cinema">
                            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                            <input placeholder="Search" value='{{request('search_key')}}' name="search_key" type="search" class="input">
                        </div>
                    </form>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add"><i class="fa-solid fa-plus me-2"></i>Add Movie</button>
                    </div>
                </div>
                <form action="" id="search-form-small" class="mb-3">
                    <div class="search-cinema">
                        <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                            <input placeholder="Search" value='{{request('search_key')}}' name="search_key" type="search" class="input">
                    </div>
                </form>
                <div class="table-responsive">
                    <table id="movie-list" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th class="col-2">Cover</th>
                                <th>Name</th>
                                <th>Release Date</th>
                                <th>Rating</th>
                                <th>Active</th>
                                <th>Featured</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movie as $item)
                                <tr class="mx-auto">
                                    <td>{{$item->id}}</td>
                                    <td class="col-2">
                                        @if ($item->poster==null)
                                            <img src="{{asset('image/default.jpg')}}" id="image" class="w-100 img-thumbnail" alt="">
                                        @else
                                            <img src="{{Storage::url('poster/'.$item->poster)}}" class="w-100" alt="">
                                        @endif
                                    </td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->release_date}}</td>
                                    <td>{{$item->rating}}/10</td>
                                    <td>
                                        @if ($item->active==1)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Not Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->featured==1)
                                            <span class="text-success">True</span>
                                        @else
                                            <span class="text-danger">False</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('admin#movieView',$item->id)}}" class="btn btn-primary" title='see more'><i class="fa-solid fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{$movie->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title d-block w-100 text-center" id="addLabel">Add Movie</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin#movieCreate')}}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="" class="mb-1">Name</label>
                            <input type="text" name="name" id="" placeholder="Enter moive name" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <small class=" text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="genre" class="form-label mb-1">Genre</label>
                            <select name="genre" id="" class="form-control @error('genre') is-invalid @enderror">
                                <option value="">Choose Genre</option>
                                @foreach ($genre as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
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
                                    <img src="{{asset('image/placeholder.webp')}}" id="image" class="w-75 mx-auto img-thumbnail" alt="">
                                </div>
                                <div class="col-md-6">
                                    <input type="file" name="poster" class="form-control @error('poster') is-invalid @enderror input_image" id="image">
                                    @error('poster')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="" class="mb-1">Director</label>
                            <input type="text" name="director" placeholder="Enter director name" id="" class="form-control @error('director') is-invalid @enderror">
                            @error('director')
                                <small class=" text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="row mb-1">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="" class="mb-1">Release Date</label>
                                    <input type="date" name="release_date" id="" class="form-control @error('release_date') is-invalid @enderror">
                                    @error('release_date')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="" class="mb-1">Run Time (min)</label>
                                    <input type="text" placeholder="Enter run time" name="run_time" id="" class="form-control @error('run_time') is-invalid @enderror">
                                    @error('run_time')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="" class="mb-1">Subtitles</label>
                                    <input type="text" placeholder="Enter subtitle" name="subtitle" id="" class="form-control @error('subtitle') is-invalid @enderror">
                                    @error('subtitle')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="" class="mb-1">Language</label>
                                    <input type="text" placeholder="Enter language" name="language" id="" class="form-control @error('language') is-invalid @enderror">
                                    @error('language')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="" class="mb-1">Trailer</label>
                            <input type="text" placeholder="Enter trailer link" name="trailer" id="" class="form-control @error('trailer') is-invalid @enderror">
                            @error('trailer')
                                <small class=" text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="mb-2">
                                <label for="" class="">Rating</label>
                            </div>
                            <div class="row">
                                <span class="col-1">1</span>
                                <div class="col-8">
                                    <input type="range" name="rating" class="" min="1" max="10" id="range">
                                </div>
                                <span class="col-1">10</span>
                                <div class="col-2">
                                    <input type="number" class="form-control" readonly value="6" id="range_number">
                                </div>
                            </div>
                            @error('rating')
                                <small class=" text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="" class="mb-1">Summary</label>
                            <textarea name="summary" id="editor" class=" @error('summary') is-invalid @enderror"></textarea>
                            @error('summary')
                                <small class=" text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="row mb-1">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="" class="mb-1">Start Date</label>
                                    <input type="date" name="start_date" id="" class="form-control @error('run_time') is-invalid @enderror">
                                    @error('start_date')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="" class="mb-1">End Date</label>
                                    <input type="date" name="end_date" id="" class="form-control @error('end_date') is-invalid @enderror">
                                    @error('end_date')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="mb-4 col-md-6">
                                <label for="name" class="form-label mb-1">Active</label>
                                <select name="active" id="" class="form-control">
                                    <option value="0">Not Active</option>
                                    <option value="1">Active</option>
                                </select>
                                @error('active')
                                    <small class=" text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="name" class="form-label mb-1">Featured</label>
                                <select name="featured" id="" class="form-control">
                                    <option value="0">False</option>
                                    <option value="1">True</option>
                                </select>
                                @error('featured')
                                    <small class=" text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="py-2">
                            <input type="submit" value="Create" class="btn btn-primary w-100">
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
