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
                              <li class="breadcrumb-item"><a href="{{route('admin#hallView',$seat->hall_id)}}">Hall</a></li>
                              <li class="breadcrumb-item active" aria-current="page">Seat</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <h4 class="text-center py-2">View Seat</h4>
                    </div>
                    <div class="row gap-1">
                        <div class="col-md-8 offset-md-2 d-flex justify-content-center card p-3">
                            <div class=" ">
                                <h5 class="py-2">Name - {{$seat->name}}</h5>
                                <h5 class="py-2">Seat Plan - {{$seat->seat_plan_name}}</h5>
                                <h5 class="py-2">Hall - {{$seat->hall_name}}</h5>
                                <h5 class="py-2">Seat Type - @if ($seat->seat_type==1)
                                                <span>Single</span>
                                            @elseif($seat->seat_type==2)
                                                <span>Couple</span>
                                            @elseif($seat->seat_type==3)
                                                <span>Bed</span>
                                            @endif</h5>
                                <h5 class="">Acitve - @if ($seat->active==0)
                                                <span class="text-danger">Not Active</span>
                                            @else
                                                <span class="text-success">Active</span>
                                            @endif</h5>

                                <div class=" d-flex justify-content-end gap-1">
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit-seat">Edit Seat</button>
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-seat">Delete Seat</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- general details end  -->

            {{-- model start --}}
            <div>
                <div class="modal fade" id="edit-seat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-seatLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title d-block w-100 text-center" id="edit-seatLabel">Edit Seat </h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#seatUpdate')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Seat Name</label>
                                        <input id="" name="name" value="{{$seat->name}}" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter seat name">
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="py-2">
                                        <label class="py-1">Seat plan</label>
                                        <select name="seat_plan_id" class="form-control @error('seat_plan_id') is-invalid @enderror">
                                            <option value="">Choose Seat Plan</option>
                                            @foreach ($seatPlan as $item)
                                                <option value="{{$item->id}}" @if ($seat->seat_plan_id==$item->id) selected @endif>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('seat_plan_id')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="py-2">
                                        <label for="name" class="form-label @error('seat_type') is-invalid @enderror">Seat Type</label>
                                        <select name="seat_type" id="" class="form-control ">
                                            <option value="1" @if ($seat->seat_type==1) selected @endif>Single</option>
                                            <option value="2" @if ($seat->seat_type==2) selected @endif>Couple</option>
                                            {{-- <option value="3" @if ($seat->seat_type==3) selected @endif>Bed</option> --}}
                                        </select>
                                        @error('seat_type')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="py-2">
                                        <label for="name" class="form-label @error('active') is-invalid @enderror">Active</label>
                                        <select name="active" id="" class="form-control ">
                                            <option value="">Choose Active Status</option>
                                            <option value="0" @if ($seat->active==0) selected @endif>Not Active</option>
                                            <option value="1" @if ($seat->active==1) selected @endif>Active</option>
                                        </select>
                                        @error('active')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="id" value="{{$seat->id}}">
                                    <input type="hidden" name="hall_id" value="{{$seat->hall_id}}">

                                    <button type="submit" class="btn btn-primary w-100 my-2">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="delete-seat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-seatLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title d-block w-100 text-center" id="delete-seatLabel">Delete Seat</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#seatDelete')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <div class="py-1">
                                        <p class="text-danger">Are you sure to delete this. If you delete this, the booking and movie plan of this seat will be change "deleted".</p>
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

                                    <input type="hidden" name="id" value="{{$seat->id}}">

                                    <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- model end --}}
@endsection
@section('script')

@endsection
