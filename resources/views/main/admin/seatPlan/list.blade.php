@extends('main.admin.layout.master')
@section('title')
    Seat Plan
@endsection

@section('content')
    <main class="content p-2 mb-5">
        <div class="container-fluid">
            <div class="table-bg">
                <div>
                    <div class="total-count-title">
                        <div class="d-flex align-items-center">
                            <div class="count-content">
                                <h5 class="mb-0">Total - {{$seatPlan->total()}}</h5>
                            </div>
                            <div class="ms-3">
                                <button class="add-btn" data-bs-toggle="modal" data-bs-target="#add-SeatPlan">
                                    <i class="fa-solid fa-square-plus me-2"></i>Add Seat Plan
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
                            <a href="{{route('admin#seatPlanList')}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
                    @if (count($seatPlan)!=0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th class="text-end">Price (Ks)</th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($seatPlan as $item)
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{$item->name}}</td>
                                            <td class="text-end">{{number_format($item->price)}}</td>
                                            <td class="text-center">
                                                @if ($item->active==1)
                                                    <span class="text-success">Active</span>
                                                @else
                                                    <span class="text-danger">Not Active</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('admin#seatPlanView',$item->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div>
                                {{$seatPlan->appends(request()->query())->links()}}
                            </div>
                        </div>
                    @else
                        <h6 class="text-center mt-5 mb-4 text-secondary" style="font-size: 11pt;">There is no seat plan.</h6>
                    @endif
                </div>
            </div>

            {{-- model--}}
            <div class="modal fade" id="add-SeatPlan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-SeatPlanLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="add-SeatPlanLabel">Add Seat Plan</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#seatPlanCreate')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-4">
                                    <label class="mb-1">Seat Plan Name</label>
                                    <input id="" name="name" type="text" class="form-control @error('name') is-invalid @enderror" required>
                                    @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="mb-1">Status</label>
                                            <select name="active" id="" class="form-select @error('active') is-invalid @enderror" required>
                                                <option value="">Choose Status</option>
                                                <option value="0">Not Active</option>
                                                <option value="1">Active</option>
                                            </select>
                                            @error('active')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-5">
                                            <label class="mb-1">Price (Ks)</label>
                                            <input id="" name="price" type="number" class="form-control @error('price') is-invalid @enderror" required>
                                            @error('price')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-5">
                                            <div class="d-flex align-items-center">
                                                <label class="me-3">Color : </label>
                                                <input id="" name="seat_color" type="color" class=" @error('seat_color') is-invalid @enderror" style="cursor: pointer;">
                                            </div>
                                            @error('seat_color')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 ps-md-5">
                                        <div class="form-group mb-4">
                                            <label class="mb-1">Seat Plan Image</label>
                                            <div>
                                                <img src="{{asset('image/placeholder.webp')}}" alt="image" class="img-thumbnail" id="image">
                                                <div>
                                                    <label for="input_image" class="file-pen-logo mt-3">
                                                        <span><i class="fa-solid fa-upload me-1"></i> Upload Image</span>
                                                    </label>
                                                    <input type="file" name="image" class="d-none @error('image') is-invalid  @enderror" id="input_image" accept="image/*">
                                                </div>
                                                @error('image')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        </div>
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
        });
    });
</script>
@endsection
