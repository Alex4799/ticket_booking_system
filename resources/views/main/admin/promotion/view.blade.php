@extends('main.admin.layout.master')
@section('title')
    Promotion
@endsection
@section('content')
<main class="content px-3 py-4 mb-5">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('admin#promotionList')}}">List</a></li>
                  <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-10 offset-md-1 card border-0 shadow p-4">
                <h4 class="mb-5 text-center ">{{$promotion->name}}</h4>
                <div class="row">
                    <div class="col-md-4">
                        @if ($promotion->image==null)
                            <img src="{{asset('image/placeholder.webp')}}" alt="default image" class="w-100 img-thumbnail" >
                        @else
                            <img src="{{Storage::url('promotion/'.$promotion->image)}}" alt="promotion image" class="w-100 img-thumbnail">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="py-2">
                            {!!$promotion->description!!}
                        </div>
                        <div class="py-2">
                            <div>
                                Cinema - @if ($promotion->cinema_id==0)
                                    All Cinema
                                @else
                                {{$promotion->cinema_name}}
                                @endif
                            </div>
                        </div>
                        <div class="py-2">
                            <div>
                                Hall - @if ($promotion->hall_id==0)
                                    All Hall
                                @else
                                {{$promotion->hall_name}}
                                @endif
                            </div>
                        </div>
                        <div class="py-2">
                            <div>
                                Movie - @if ($promotion->movie_id==0)
                                    All Movie
                                @else
                                {{$promotion->movie_name}}
                                @endif
                            </div>
                        </div>
                        <div class="py-2">
                            Promo Code - {{$promotion->promo_code}}
                        </div>
                        <div class="py-2">
                            Max Count - {{$promotion->max_count}}
                        </div>
                        <div class="py-2">
                            Use Count - {{$promotion->used_count}}
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="py-2">
                                    <div>Start Date - {{$promotion->start_date}}</div>
                                </div>
                                <div class="py-2">
                                    <div>
                                        Acitve - @if ($promotion->active==0)
                                                            <span class="text-danger">Not Active</span>
                                                        @else
                                                            <span class="text-success">Active</span>
                                                        @endif
                                    </div>
                                </div>
                                @if ($promotion->percentage!=null)
                                    <div class="py-2">
                                        <div>Percentage - {{$promotion->percentage}} %</div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="py-2">
                                    <div>End Date - {{$promotion->end_date}}</div>
                                </div>
                                <div class="py-2">
                                    <div>
                                        Feature - @if ($promotion->feature==0)
                                                            <span class="text-danger">False</span>
                                                        @else
                                                            <span class="text-success">True</span>
                                                        @endif
                                    </div>
                                </div>
                                @if ($promotion->amount!=null)
                                    <div class="py-2">
                                        <div>Amount - {{$promotion->amount}}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="py-2 d-flex justify-content-end gap-1">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPromotion">Edit</button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletePromotion">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editPromotion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editPromotionLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="editPromotionLabel">Edit Promotion</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#promotionUpdate')}}" method="POST" enctype="multipart/form-data" class="mt-1 card-body">
                            @csrf
                            @method('PATCH')
                            <div class="form-group mb-4">
                                <label class="mb-1">Name</label>
                                <input name="name" value="{{$promotion->name}}" type="text" class="form-control" placeholder="Enter promotion name">
                                @error('name')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Description</label>
                                <textarea name="description" id="editor" class="form-control" cols="30" rows="6">{{$promotion->description}}</textarea>
                                @error('description')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Image</label>
                                <div class="row">
                                    <div class="col-md-6 py-2">
                                        @if ($promotion->image==null)
                                            <img src="{{asset('image/placeholder.webp')}}" alt="default image" class="w-100 img-thumbnail" id="image">
                                        @else
                                            <img src="{{Storage::url('promotion/'.$promotion->image)}}" alt="promotion image" class="w-100 img-thumbnail" id="image">
                                        @endif
                                    </div>
                                    <div class="col-md-6 py-2 m-auto">
                                        <input type="file" name="image" class="form-control @error('image') is-invalid  @enderror" id="input_image" accept="image/*">
                                        @error('image')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Cinema</label>
                                <select name="cinema_id" id="" class="form-control">
                                    <option value="">Choose cinema..</option>
                                    <option value="0" @if ($promotion->cinema_id==0) selected @endif>All Cinema</option>
                                    @foreach ($cinema as $item)
                                        <option value="{{$item->id}}" @if ($item->id==$promotion->cinema_id) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('cinema_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Hall</label>
                                <select name="hall_id" id="" class="form-control">
                                    <option value="0">All Hall</option>
                                    @foreach ($hall as $item)
                                        <option value="{{$item->id}}" @if ($item->id==$promotion->hall_id) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('hall_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Movie</label>
                                <select name="movie_id" id="" class="form-control">
                                    <option value="0">All Movie</option>
                                    @foreach ($movie as $item)
                                        <option value="{{$item->id}}" @if ($item->id==$promotion->movie_id) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('movie_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Percentage ( % )</label>
                                <input type="number" name="percentage" value="{{$promotion->percentage}}" class="form-control">
                                @error('percentage')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Amount ( MMK )</label>
                                <input type="number" name="amount" id="" value="{{$promotion->amount}}" class="form-control">
                                @error('amount')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Promo Code</label>
                                <input type="text" name="promo_code" id="" value="{{$promotion->promo_code}}" class="form-control">
                                @error('promo_code')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Max Count</label>
                                <input type="number" name="max_count" id="" value="{{$promotion->max_count}}" class="form-control" placeholder="Enter max count">
                                @error('max_count')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">Start Date</label>
                                <input id="" name="start_date" type="date" value="{{$promotion->start_date}}" class="form-control">
                                @error('start_date')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="mb-1">End Date</label>
                                <input id="" name="end_date" type="date" value="{{$promotion->end_date}}" class="form-control">
                                @error('end_date')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="row mb-1">
                                <div class="col-md-6 mb-4">
                                    <div class="">
                                        <label for="name" class="form-label mb-1">Active</label>
                                        <select name="active" id="" class="form-control">
                                            <option value="">Choose Active Status</option>
                                            <option value="0" @if ($promotion->active==0) selected @endif>Not Active</option>
                                            <option value="1" @if ($promotion->active==1) selected @endif>Active</option>
                                        </select>
                                        @error('active')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="">
                                        <label for="name" class="form-label mb-1">Feature</label>
                                        <select name="feature" id="" class="form-control">
                                            <option value="">Choose Feature Status</option>
                                            <option value="0" @if ($promotion->feature==0) selected @endif>False</option>
                                            <option value="1" @if ($promotion->feature==1) selected @endif>True</option>
                                        </select>
                                        @error('feature')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="id" value="{{$promotion->id}}">

                            <button type="submit" class="btn btn-primary w-100 my-2">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deletePromotion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deletePromotionLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="deletePromotionLabel">Delete Promotion</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#promotionDelete')}}" method="post" class="mt-1">
                            @csrf
                            @method('DELETE')
                            <div class="py-1">
                                <p class="text-danger">Are you sure to delete this ?"</p>
                            </div>
                            <div class="py-1">
                                <h6>Verify you are admin. Please enter the password.</h6>
                            </div>
                            <div class="form-group mb-3">
                                <label class="mb-1">Password</label>
                                <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password." required>
                                @error('password')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <input type="hidden" name="id" value="{{$promotion->id}}">

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
