@extends('main.admin.layout.master')
@section('title')
    Seat Plan
@endsection
@section('content')
    <!-- seat start  -->
    <main class="content px-3 pt-3 mb-5">
        <div class="container-fluid">
            <div id="table-bg">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                    <h4 class="py-2">Seat Plan</h4>
                    <div class="py-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-SeatPlan">Add Seat Plan</button>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
                            <h5>Total - {{$seatPlan->total()}}</h5>
                        </div>
                        <form action="" id="search-form">
                            <div class="search-cinema">
                                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                                <input placeholder="Search" value="{{request('search_key')}}" name="search_key" type="text" class="input">
                            </div>
                        </form>
                    </div>
                    <form action="" id="search-form-small">
                        <div class="search-cinema">
                            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                            <input placeholder="Search" value="{{request('search_key')}}" name="search_key" type="text" class="input">
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Active</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($seatPlan as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->price}} MMK</td>
                                        <td>
                                            @if ($item->active==1)
                                                <span class="text-success">Active</span>
                                            @else
                                                <span class="text-danger">Not Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('admin#seatPlanView',$item->id)}}" class="btn btn-outline-primary">See More</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$seatPlan->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </main>
        <!-- seat end  -->

    {{-- model start --}}
    <div class="modal fade" id="add-SeatPlan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-SeatPlanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title d-block w-100 text-center" id="add-SeatPlanLabel">Add Seat Plan</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin#seatPlanCreate')}}" method="post" class="mt-1" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="mb-1">Seat Plan Image</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{asset('image/placeholder.webp')}}" alt="Default" class="w-100 img-thumbnail" id="image">
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
                            <input id="" name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter seat plan name">
                            @error('name')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1">Price (MMK)</label>
                            <input id="" name="price" type="number" class="form-control @error('price') is-invalid @enderror" placeholder="Enter seat plan price">
                            @error('price')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <div>
                                <label class="mb-1"> Color</label>
                            </div>
                            <input id="" name="seat_color" type="color" class=" @error('seat_color') is-invalid @enderror" placeholder="Enter cinema name">
                            @error('seat_color')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100 my-2">Create</button>
                    </form>
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
