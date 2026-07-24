@extends('main.admin.layout.master')
@section('title')
    Cinema
@endsection
@section('content')
            <!-- general details start  -->
            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <div>
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{route('admin#seatPlanList')}}">Seat Plan List</a></li>
                              <li class="breadcrumb-item active" aria-current="page">View</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <h4 class="text-center py-2">View Seat Plan</h4>
                    </div>
                    <div class="row gap-1">
                        <div class="col-md-8 offset-md-2 d-flex justify-content-center card px-3 py-4">
                            <div class="row">
                                <div class="col-md-5">
                                    @if ($seatPlan->image!=null)
                                        <img src="{{Storage::url('seat_plan/'.$seatPlan->image)}}" alt="seat-plan" class="w-100 img-thumbnail">
                                    @else
                                        <img src="{{asset('image/placeholder.webp')}}" alt="Default" class="w-100 img-thumbnail">
                                    @endif
                                </div>
                                <div class="col-md-7">
                                    <h5 class="py-2">Name - {{$seatPlan->name}}</h5>
                                    <h5 class="py-2">Price - {{$seatPlan->price}} MMK</h5>
                                    <h5 class="py-2">Seat Count - {{$seatPlan->seat_count}}</h5>
                                    <h5 class="py-2">Seat Color - <input type="color" value="{{$seatPlan->seat_color}}" disabled></h5>
                                    <h5 class="py-2">Acitve - @if ($seatPlan->active==0)
                                                    <span class="text-danger">Not Active</span>
                                                @else
                                                    <span class="text-success">Active</span>
                                                @endif</h5>

                                    <div class=" d-flex justify-content-end gap-1 py-2">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit-seatPlan">Edit seatPlan</button>
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-seatPlan">Delete seatPlan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- general details end  -->

            {{-- model start --}}
            <div>
                <div class="modal fade" id="edit-seatPlan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-seatPlanLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="edit-seatPlanLabel">Edit Seat Plan</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#seatPlanUpdate')}}" method="post" class="mt-1" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')

                                    <div class="form-group mb-4">
                                        <label class="mb-1">Seat Plan Image</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                @if ($seatPlan->image!=null)
                                                    <img src="{{Storage::url('seat_plan/'.$seatPlan->image)}}" alt="seat-plan" class="w-100 img-thumbnail" id="image">
                                                @else
                                                    <img src="{{asset('image/placeholder.webp')}}" alt="Default" class="w-100 img-thumbnail" id="image">
                                                @endif
                                            </div>
                                            <div class="col-md-6 m-auto">
                                                <input type="file" name="image" class="form-control @error('image') is-invalid  @enderror" id="input_image" accept="image/*">
                                                @error('image')
                                                    <small class=" text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="mb-1">Seat Plan Name</label>
                                        <input id="" name="name" type="text" value="{{$seatPlan->name}}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter cinema name">
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="mb-1"> Price (MMK)</label>
                                        <input id="" name="price" type="number" value="{{$seatPlan->price}}" class="form-control @error('price') is-invalid @enderror" placeholder="Enter cinema name">
                                        @error('price')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-4">
                                        <div>
                                            <label class="mb-1"> Color</label>
                                        </div>
                                        <input id="" name="seat_color" type="color" value="{{$seatPlan->seat_color}}" class=" @error('seat_color') is-invalid @enderror" placeholder="Enter cinema name">
                                        @error('seat_color')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="name" class="form-label @error('active') is-invalid @enderror">Active</label>
                                        <select name="active" id="" class="form-control ">
                                            <option value="">Choose Active Status</option>
                                            <option value="0" @if ($seatPlan->active==0) selected @endif>Not Active</option>
                                            <option value="1" @if ($seatPlan->active==1) selected @endif>Active</option>
                                        </select>
                                        @error('active')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="id" value="{{$seatPlan->id}}">

                                    <button type="submit" class="btn btn-primary w-100 my-2">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="delete-seatPlan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-seatPlanLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="delete-seatPlanLabel">Delete Seat Plan</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#seatPlanDelete')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <div class="py-1">
                                        <p class="text-danger">Are you sure to delete this. If you delete this, the seat of this seat plan will be change "deleted".</p>
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

                                    <input type="hidden" name="id" value="{{$seatPlan->id}}">

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
<script>
    $(document).ready(function(){
        $('#input_image').change(function(){
            document.getElementById('image').src = window.URL.createObjectURL(this.files[0]);
        })
    })
</script>
@endsection
