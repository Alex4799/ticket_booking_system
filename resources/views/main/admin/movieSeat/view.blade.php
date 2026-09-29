@extends('main.admin.layout.master')
@section('title')
    Seat
@endsection
@section('content')
            <!-- general details start  -->
<main class="content px-3 py-4">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin#moviePlanView',$movieSeat->movie_plan_id)}}">Movie Plan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Seat</li>
                </ol>
            </nav>
        </div>
        <div>
            <h4 class="text-center py-2">View Movie Seat</h4>
        </div>
        <div class="row gap-1">
            <div class="col-md-8 offset-md-2 d-flex justify-content-center card p-3">
                <div class=" ">
                    <h5 class="py-2">Hall Name - {{$movieSeat->hall_name}}</h5>
                    <h5 class="py-2">Date - {{$movieSeat->date}}</h5>
                    <h5 class="py-2">Time - {{$movieSeat->start_time}}</h5>
                    <h5 class="py-2">Seat Name - {{$movieSeat->seat_name}}</h5>
                    <h5 class="py-2">Seat Plan - {{$movieSeat->seat_plan_name}}</h5>
                    <h5 class="py-2">Seat Price - {{$movieSeat->price}}</h5>
                    <h5 class="py-2">Seat Status - @if ($movieSeat->status==0) Avaliable @elseif ($movieSeat->status==1) Selected @elseif ($movieSeat->status==2) Sold @endif</h5>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit">Edit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title d-block w-100 text-center" id="editLabel">Edit Movie Seat</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin#updateMovieSeat')}}" method="post" enctype="multipart/form-data" class="parents">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="" class="mb-1">Seat Price</label>
                            <input type="number" value="{{$movieSeat->price}}" name="price" id="clean_time" class="form-control clean_time">
                        </div>
                        <div class="mb-4">
                            <label for="name" class="form-label mb-1 @error('status') is-invalid @enderror">Status</label>
                            <select name="status" class="form-control ">
                                <option value="0" @if ($movieSeat->status==0) selected @endif>Avaliable</option>
                                <option value="1" @if ($movieSeat->status==1) selected @endif>Selected</option>
                                <option value="1" @if ($movieSeat->status==2) selected @endif>Sold</option>
                            </select>
                            @error('status')
                                <small class=" text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div>
                            <input type="hidden" name="id" value="{{$movieSeat->id}}" id="id">
                        </div>
                        <div class="d-flex justify-content-end gap-1">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload me-2"></i>Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
            <!-- general details end  -->
@endsection
@section('script')

@endsection
