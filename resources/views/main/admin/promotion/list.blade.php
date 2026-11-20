@extends('main.admin.layout.master')
@section('title')
    Promotion
@endsection
@section('content')
<main class="content px-3 pt-2 mb-5">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
                <h5>Total - {{$promotion->total()}}</h5>
            </div>
            <form action="" id="search-form">
                <div class="search-cinema">
                    <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                    <input placeholder="Search" name="search_key" value="{{request('search_key')}}" type="search" class="input">
                </div>
            </form>
            <div class="form-group">
                <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#add">Create</button>
            </div>
        </div>
        <form action="" id="search-form-small">
            <div class="search-cinema">
                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                <input placeholder="Search" name="search_key" value="{{request('search_key')}}" type="search" class="input">
            </div>
        </form>
        <div id="table-bg" class="mt-3">
            <div class="my-3">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Seat Plan</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Active</th>
                                <th>Feature</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($promotion)!=0)
                                @foreach ($promotion as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>
                                        @if ($item->cinema_id!=0)
                                            {{$item->cinema_name}}
                                        @else
                                            All Cinema
                                        @endif
                                    </td>
                                    <td>{{$item->start_date}}</td>
                                    <td>{{$item->end_date}}</td>
                                    <td>
                                        @if ($item->active==1)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Not Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->feature==1)
                                            <span class="text-success">True</span>
                                        @else
                                            <span class="text-danger">False</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="table-data-feature">
                                            <a href="{{route('admin#promotionView',$item->id)}}" class="btn btn-secondary" id="detail"><i class="fa-solid fa-eye"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div>
                        {{$promotion->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="addLabel">Add Promotion</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form action="{{route('admin#promotionCreate')}}" method="POST" enctype="multipart/form-data" class="mt-1 card-body">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="mb-1">Name</label>
                            <input name="name" id="" type="text" class="form-control" placeholder="Enter promotion name">
                            @error('name')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1">Description</label>
                            <textarea name="description" id="editor" class="form-control" cols="30" rows="6"></textarea>
                            @error('description')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1">Image</label>
                            <div class="row">
                                <div class="col-md-6 py-2">
                                    <img src="{{asset('image/placeholder.webp')}}" alt="default image" class="w-100 img-thumbnail" id="image">
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
                                <option value="0">All Cinema</option>
                                @foreach ($cinema as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('cinema_id')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1">Hall</label>
                            <select name="hall_id" id="" class="form-control">
                                <option value="">Choose hall..</option>
                                <option value="0">All Hall</option>
                                @foreach ($hall as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('hall_id')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1">Movie</label>
                            <select name="movie_id" id="" class="form-control">
                                <option value="">Choose movie..</option>
                                <option value="0">All Movie</option>
                                @foreach ($movie as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('movie_id')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1">Percentage ( % )</label>
                            <input type="number" name="percentage" id="" class="form-control" placeholder="Enter percentage">
                            @error('percentage')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1">Amount ( MMK )</label>
                            <input type="number" name="amount" id="" class="form-control" placeholder="Enter amount">
                            @error('amount')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1">Promo Code</label>
                            <input type="text" name="promo_code" id="" class="form-control" placeholder="Enter promo code">
                            @error('promo_code')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1">Max Count</label>
                            <input type="number" name="max_count" id="" class="form-control" placeholder="Enter max count">
                            @error('max_count')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1">Start Date</label>
                            <input id="" name="start_date" type="date" class="form-control">
                            @error('start_date')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1">End Date</label>
                            <input id="" name="end_date" type="date" class="form-control">
                            @error('end_date')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6 mb-4">
                                <div class="">
                                    <label for="name" class="form-label mb-1">Active</label>
                                    <select name="active" id="" class="form-control">
                                        <option value="0">Not Active</option>
                                        <option value="1">Active</option>
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
                                        <option value="0">False</option>
                                        <option value="1">True</option>
                                    </select>
                                    @error('feature')
                                        <small class=" text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 my-2">Create</button>
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
@endsection
