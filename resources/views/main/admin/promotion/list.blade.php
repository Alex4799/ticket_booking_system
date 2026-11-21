@extends('main.admin.layout.master')
@section('title')
    Promotion
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div class="table-bg">
            <div>
                <div class="total-count-title">
                    <div class="d-flex align-items-center">
                        <div class="count-content">
                            <h5 class="mb-0">Total - {{$promotion->total()}}</h5>
                        </div>
                        <div class="ms-3">
                            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#add-promotion">
                                <i class="fa-solid fa-square-plus me-2"></i>Add Promotion
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
                        <a href="{{route('admin#promotionList')}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                    </div>
                </div>
                @if (count($promotion)!=0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Cinema</th>
                                    <th class="text-center">Start Date</th>
                                    <th class="text-center">End Date</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Feature</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($promotion as $item)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>
                                            @if ($item->cinema_id!=0)
                                                {{$item->cinema_name}}
                                            @else
                                                All Cinema
                                            @endif
                                        </td>
                                        <td class="text-center">{{$item->start_date}}</td>
                                        <td class="text-center">{{$item->end_date}}</td>
                                        <td class="text-center">
                                            @if ($item->active==1)
                                                <span class="text-success">Active</span>
                                            @else
                                                <span class="text-danger">Not Active</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->feature==1)
                                                <span class="text-success">True</span>
                                            @else
                                                <span class="text-danger">False</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('admin#promotionView',$item->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            {{$promotion->appends(request()->query())->links()}}
                        </div>
                    </div>
                @else
                    <h6 class="text-center mt-5 mb-4 text-secondary" style="font-size: 11pt;">There is no promotion.</h6>
                @endif
            </div>
        </div>
        <div class="modal fade" id="add-promotion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-promotionLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-block w-100 text-center" id="add-promotionLabel">Add Promotion</h5>
                        <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#promotionCreate')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 form-group mb-4">
                                    <label class="mb-1">Name</label>
                                    <input name="name" id="" type="text" class="form-control" required>
                                    @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12 form-group mb-4">
                                    <label class="mb-1">Cinema</label>
                                    <select name="cinema_id" id="" class="form-select">
                                        <option value="">Choose Cinema</option>
                                        <option value="0">All Cinema</option>
                                        @foreach ($cinema as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('cinema_id')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Hall</label>
                                    <select name="hall_id" id="" class="form-select">
                                        <option value="">Choose Hall</option>
                                        <option value="0">All Hall</option>
                                        @foreach ($hall as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('hall_id')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Movie</label>
                                    <select name="movie_id" id="" class="form-select">
                                        <option value="">Choose Movie</option>
                                        <option value="0">All Movie</option>
                                        @foreach ($movie as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('movie_id')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Promo Code</label>
                                    <input type="text" name="promo_code" id="" class="form-control" required>
                                    @error('promo_code')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Max Count</label>
                                    <input type="number" name="max_count" id="" class="form-control" required>
                                    @error('max_count')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Percentage ( % )</label>
                                    <input type="number" name="percentage" id="" class="form-control">
                                    @error('percentage')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Amount ( Ks )</label>
                                    <input type="number" name="amount" id="" class="form-control">
                                    @error('amount')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-5 form-group mb-5">
                                    <label class="mb-1">Image</label>
                                    <div>
                                        <img src="{{asset('image/placeholder.webp')}}" alt="image" class="img-thumbnail" id="image">
                                        <div>
                                            <label for="input_image" class="file-pen-logo mt-3">
                                                <span><i class="fa-solid fa-upload me-1"></i> Upload Image</span>
                                            </label>
                                            <input type="file" name="image" class="d-none @error('image') is-invalid  @enderror" id="input_image">
                                        </div>
                                        @error('image')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-7 ps-sm-4 form-group mb-5">
                                    <label class="mb-1">Description</label>
                                    <textarea name="description" id="editor" cols="30" rows="10" class="@error('description') is-invalid @enderror"></textarea>
                                    @error('description')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">Start Date</label>
                                    <input id="" name="start_date" type="date" class="form-control" required>
                                    @error('start_date')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label class="mb-1">End Date</label>
                                    <input id="" name="end_date" type="date" class="form-control" required>
                                    @error('end_date')
                                        <small class="text-danger">{{$message}}</small>
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
                                    <select name="feature" id="" class="form-select">
                                        <option value="0">False</option>
                                        <option value="1">True</option>
                                    </select>
                                    @error('feature')
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
            $('#input_image').change(function(){
                document.getElementById('image').src = window.URL.createObjectURL(this.files[0]);
            })
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
