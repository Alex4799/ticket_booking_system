@extends('main.admin.layout.master')
@section('title')
    Seat Plan
@endsection
@section('content')
    <main class="content p-2 mb-5">
        <div class="container-fluid">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin#seatPlanList')}}">List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
            <div class="shadow-container-box">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="seat-plan-image">
                            @if ($seatPlan->image!=null)
                                <img src="{{Storage::url('seat_plan/'.$seatPlan->image)}}" alt="seat-plan" class="img-thumbnail">
                            @else
                                <img src="{{asset('image/placeholder.webp')}}" alt="Default" class="img-thumbnail">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8 ps-md-5">
                        <div class="seat-view">
                            <div class="seat-detail-top">
                                <div class="seat-title">
                                    <div>
                                        <label>Seat Plan</label>
                                        <h4>{{$seatPlan->name}}</h4>
                                    </div>
                                </div>
                                <div>
                                    <h6>
                                        @if ($seatPlan->active==0)
                                            <span class="status reject fw-bold">Not Active</span>
                                        @else
                                            <span class="status success fw-bold">Active</span>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                            <div class="seat-detail-footer">
                                <div>
                                    <label>Price</label>
                                    <h6>{{number_format($seatPlan->price)}} Ks</h6>
                                </div>
                                <div>
                                    <label>Seat Color</label>
                                    <h6 class="d-flex align-items-center">
                                        <span class="seat-color" style="background: {{$seatPlan->seat_color}};"></span>
                                    </h6>
                                </div>
                                <div>
                                    <label>Seat Count</label>
                                    <h6>{{$seatCount}}</h6>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end flex-wrap gap-2">
                                <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#edit-seatPlan"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Seat Plan</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-seatPlan" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="modal fade" id="edit-seatPlan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-seatPlanLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="edit-seatPlanLabel">Edit Seat Plan</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#seatPlanUpdate')}}" method="post" class="mt-1" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="update-btn-group">
                                        <div class="form-group mb-4">
                                            <label class="mb-1">Seat Plan Name</label>
                                            <input id="" name="name" type="text" value="{{$seatPlan->name}}" class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group mb-4">
                                                    <label class="mb-1">Status</label>
                                                    <select name="active" id="" class="form-select">
                                                        <option value="0" @if ($seatPlan->active==0) selected @endif>Not Active</option>
                                                        <option value="1" @if ($seatPlan->active==1) selected @endif>Active</option>
                                                    </select>
                                                    @error('active')
                                                        <small class=" text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>
                                                <div class="form-group mb-5">
                                                    <label class="mb-1"> Price (Ks)</label>
                                                    <input id="" name="price" type="number" value="{{$seatPlan->price}}" class="form-control @error('price') is-invalid @enderror">
                                                    @error('price')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>
                                                <div class="form-group mb-5">
                                                    <div class="d-flex align-items-center">
                                                        <label class="me-3">Color : </label>
                                                        <input id="" name="seat_color" type="color" value="{{$seatPlan->seat_color}}" class=" @error('seat_color') is-invalid @enderror" style="cursor: pointer;">
                                                    </div>
                                                    @error('seat_color')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ps-sm-5">
                                                <div class="form-group mb-4">
                                                    <label class="mb-1">Seat Plan Image</label>
                                                    <div>
                                                        @if ($seatPlan->image!=null)
                                                            <img src="{{Storage::url('seat_plan/'.$seatPlan->image)}}" alt="seat-plan" class="img-thumbnail" id="image">
                                                        @else
                                                            <img src="{{asset('image/placeholder.webp')}}" alt="Default" class="img-thumbnail" id="image">
                                                        @endif
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
                                        <input type="hidden" name="id" value="{{$seatPlan->id}}">
                                        <div class="d-flex align-items-center my-2">
                                            <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="delete-seatPlan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-seatPlanLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="delete-seatPlanLabel">Delete Seat Plan</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#seatPlanDelete')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <div class="delete-confirmation">
                                        <div class="delete-warning">
                                            <div class="delete-icon">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                            </div>
                                            <div class="delete-message">
                                                <h6>Are you sure to delete this? If you delete this, the seat of this seat plan will be change "deleted".</h6>
                                                <small>Verify you are admin. Please enter the password.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group my-3">
                                        <label class="mb-1">Password</label>
                                        <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="id" value="{{$seatPlan->id}}">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
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

        // updatebtn disabled check
        const forms = document.querySelectorAll('.update-btn-group');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input,select');
            const button = form.querySelector('.action-btn');
            const initialValues = {};
            inputs.forEach(input => {
                initialValues[input.name] = input.value;
            });
            function checkUpdateValueForBtn() {
                let hasChanged = false;
                inputs.forEach(input => {
                    if (input.value != initialValues[input.name]) {
                        hasChanged = true;
                    }
                });
                button.disabled = !hasChanged;
            }
            inputs.forEach(input => {
                input.addEventListener('input', checkUpdateValueForBtn);
                input.addEventListener('change', checkUpdateValueForBtn);
            });
            checkUpdateValueForBtn();
        });
    </script>
@endsection
