@extends('main.admin.layout.master')
@section('title')
    Seat
@endsection
@section('content')
    <main class="content p-2 mb-5">
        <div class="container-fluid">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin#hallView',$seat->hall_id)}}">View Hall</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Seat</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="shadow-container-box">
                        <div class="seat-view">
                            <div class="seat-detail-top">
                                <div class="seat-title">
                                    <div>
                                        <i class="fa-solid fa-couch"></i>
                                    </div>
                                    <div>
                                        <label>Seat</label>
                                        <h3>{{ $seat->name }}</h3>
                                    </div>
                                </div>
                                <div>
                                    <h6>
                                        @if ($seat->active == 0)
                                            <span class="status reject fw-bold">Not Active</span>
                                        @else
                                            <span class="status success fw-bold">Active</span>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                            <div class="seat-detail-footer">
                                <div>
                                    <label>Hall</label>
                                    <h6>{{ $seat->hall_name }}</h6>
                                </div>
                                <div>
                                    <label>Seat Plan</label>
                                    <h6>{{ $seat->seat_plan_name }}</h6>
                                </div>
                                <div>
                                    <label>Seat Type</label>
                                    <h6>
                                        @if ($seat->seat_type == 1)
                                            <span>Single</span>
                                        @elseif ($seat->seat_type == 2)
                                            <span>Couple</span>
                                        @elseif ($seat->seat_type == 3)
                                            <span>Bed</span>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                            <div class=" d-flex justify-content-end flex-wrap gap-2">
                                <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#edit-seat"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Seat</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-seat" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="modal fade" id="edit-seat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-seatLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="edit-seatLabel">Edit Seat </h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#seatUpdate')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <div class="update-btn-group">
                                        <div class="row">
                                            <div class="col-sm-6 form-group mb-4">
                                                <label class="mb-1">Seat Name</label>
                                                <input id="" name="name" value="{{$seat->name}}" type="text" class="form-control @error('name') is-invalid @enderror">
                                                @error('name')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6 form-group mb-4">
                                                <label class="mb-1">Status</label>
                                                <select name="active" id="" class="form-select">
                                                    <option value="0" @if ($seat->active==0) selected @endif>Not Active</option>
                                                    <option value="1" @if ($seat->active==1) selected @endif>Active</option>
                                                </select>
                                                @error('active')
                                                    <small class=" text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6 form-group mb-4">
                                                <label class="mb-1">Seat Plan</label>
                                                <select name="seat_plan_id" class="form-select @error('seat_plan') is-invalid @enderror">
                                                    @foreach ($seatPlan as $item)
                                                        <option value="{{$item->id}}" @if ($seat->seat_plan_id==$item->id) selected @endif>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('seat_plan_id')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6 form-group mb-4">
                                                <label class="mb-1">Seat Type</label>
                                                <select name="seat_type" id="" class="form-select ">
                                                    <option value="1" @if ($seat->seat_type==1) selected @endif>Single</option>
                                                    <option value="2" @if ($seat->seat_type==2) selected @endif>Couple</option>
                                                    {{-- <option value="3" @if ($seat->seat_type==3) selected @endif>Bed</option> --}}
                                                </select>
                                                @error('seat_type')
                                                    <small class=" text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="{{$seat->id}}">
                                        <input type="hidden" name="hall_id" value="{{$seat->hall_id}}">
                                        <div class="d-flex align-items-center my-2">
                                            <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="delete-seat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-seatLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="delete-seatLabel">Delete Seat</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#seatDelete')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <div class="delete-confirmation">
                                        <div class="delete-warning">
                                            <div class="delete-icon">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                            </div>
                                            <div class="delete-message">
                                                <h6>Are you sure to delete this? If you delete this, the booking and movie plan of this seat will be change "deleted".</h6>
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

                                    <input type="hidden" name="id" value="{{$seat->id}}">
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
