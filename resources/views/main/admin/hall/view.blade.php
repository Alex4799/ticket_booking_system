@extends('main.admin.layout.master')
@section('title')
    Hall
@endsection
@section('style')

@endsection
@section('content')
    <main class="content p-2 mb-5">
        <div class="container-fluid">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin#hallList')}}">List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
            <div>
                <div class="table-bg">
                    <div class="hall-view">
                        <h5 class="text-center">
                            <i class="fa-solid fa-door-open me-1"></i>
                            {{$hall->name}}@if ($hall->du_id!=null) (Copy) @endif
                        </h5>
                        <div class="row g-3 mb-4 plan">
                            @if (count($summary)!=0)
                                @foreach ($summary as $item)
                                    <div class="col-lg-2 col-md-3 col-sm-4 mb-2">
                                        <div class="seat-type-card">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="seat-color" style="background: {{$item['seat_color']}};"></span>
                                                <span class="ms-3"> {{$item['seat_count']}}</span>
                                            </div>
                                            <div class="plan-name">
                                                <h6>{{$item['seat_plan_name']}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <small class="text-secondary text-center my-5">There is no seat yet.</small>
                            @endif
                        </div>
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <label class="label-small me-1">Status - </label>
                                @if ($hall->active==0)
                                    <span class="text-danger">Not Active</span>
                                @else
                                    <span class="text-success">Active</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    @if ($hall->du_id==null)
                                        @if (Auth::user()->position=='developer')
                                            <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#duplicate-hall"><i class="fa-solid fa-copy me-2"></i>Duplicate Hall</button>
                                        @endif
                                    @endif
                                    <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#edit-hall"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Hall</button>
                                </div>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-hall" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-bg mt-5">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-5 mt-1">
                        <h6 class="mb-0">Seats ( Column - {{$hall->seat_column}} )</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#add-seat"><i class="fa-solid fa-square-plus me-2"></i>Add Seat</button>
                            @if ($hall->du_id!=null)
                                <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#replace-seat-plan"><i class="fa-solid fa-repeat me-2"></i>Replace Seat Plan</button>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="cinema-seating">
                            @if ($hall->seat_column > 10)
                                <div class="d-flex justify-content-end gap-2 mb-5">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="zoom-out">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="zoom-reset">
                                        <i class="fa-solid fa-expand"></i>
                                    </button>
                                </div>
                            @endif
                            <div class="screen-wrapper">
                                <div class="screen">
                                    <span>SCREEN</span>
                                </div>
                            </div>
                            <div id="seat-plan-container">
                                <div class="d-grid" style="grid-template-columns: repeat({{ $hall->seat_column }}, auto);" id="seat-plan">
                                    @if (count($seat)!=0)
                                        @foreach ($seat as $item)
                                            @if ($item->seat_type==1)
                                                <a href="{{route('admin#seatView',$item->id)}}" title="{{$item->name}},@if ($item->active==0) Not Active @else Active @endif,{{$item->seat_plan_name}}" class="mx-auto text-decoration-none" style="color: @if($item->active==0) red @else {{$item->seat_color}} @endif">
                                                    <div class="double-seat ">
                                                        <div class="seat-content">
                                                            <text class="svg-number">{{$item->name}}</text>
                                                            <svg class="lucide-armchair" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M19 9V6a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3"/>
                                                                <path d="M3 16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V11a2 2 0 0 0-4 0z"/>
                                                                <path d="M5 18v2"/>
                                                                <path d="M19 18v2"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </a>
                                            @else
                                                <a href="{{route('admin#seatView',$item->id)}}" title="{{$item->name}},@if ($item->active==0) Not Active @else Active @endif,{{$item->seat_plan_name}}" class="mx-auto text-decoration-none" style="color: @if($item->active==0) red @else {{$item->seat_color}} @endif">
                                                    <div class="seat ">
                                                        <div class="seat-content">
                                                            <text class="svg-number">{{$item->name}}</text>
                                                            <svg class="lucide-couple-armchair" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M22 12V10a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v8" />
                                                                <path d="M26 12V10a3 3 0 0 1 3-3h11a3 3 0 0 1 3 3v8" />
                                                                <path d="M2 30a3 3 0 0 0 3 3h38a3 3 0 0 0 3-3v-8a3 3 0 0 0-6 0v2a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2a3 3 0 0 0-6 0z" />
                                                                <path d="M6 34v6" />
                                                                <path d="M24 34v6" />
                                                                <path d="M42 34v6" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- modal  --}}
            <div>
                <div class="modal fade" id="edit-hall" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-hallLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="edit-hallLabel">Edit Hall</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#hallUpdate')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <div class="update-btn-group">
                                        <div class="row">
                                            <div class="col-sm-12 form-group mb-4">
                                                <label class="mb-1">Hall Name</label>
                                                <input id="" name="name" type="text" value="{{$hall->name}}" class="form-control @error('name') is-invalid @enderror">
                                                @error('name')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6 form-group mb-4">
                                                <label class="mb-1">Status</label>
                                                <select name="active" id="" class="form-select">
                                                    <option value="0" @if ($hall->active==0) selected @endif>Not Active</option>
                                                    <option value="1" @if ($hall->active==1) selected @endif>Active</option>
                                                </select>
                                                @error('active')
                                                    <small class=" text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6 form-group mb-4">
                                                <label class="mb-1">Hall Seat Column</label>
                                                <input id="" name="seat_column" type="number" value="{{$hall->seat_column}}" class="form-control @error('seat_column') is-invalid @enderror">
                                                @error('seat_column')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <input type="hidden" name="cinema_id" value="{{$hall->cinema_id}}">
                                        <input type="hidden" name="id" value="{{$hall->id}}">

                                        <div class="d-flex align-items-center my-2">
                                            <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="add-seat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-seatLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="add-seatLabel">Add Seat</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#seatCreate')}}" method="post" class="mt-1">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Seat Name</label>
                                            <input id="" name="name" type="text" class="form-control @error('name') is-invalid @enderror" required>
                                            @error('name')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Seat Count</label>
                                            <input type="number" name="count" class="form-control @error('count') is-invalid @enderror" min="1" value="1" id="">
                                            @error('count')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Seat Plan</label>
                                            <select name="seat_plan_id" class="form-select @error('seat_plan_id') is-invalid @enderror" required>
                                                <option value="">Choose Seat Plan</option>
                                                @foreach ($seatPlan as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('seat_plan_id')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Seat Type</label>
                                            <select name="seat_type" class="form-select @error('seat_type') is-invalid @enderror">
                                                <option value="1">Single</option>
                                                <option value="2">Couple</option>
                                            </select>
                                            @error('seat_type')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 form-group mb-4">
                                            <label class="mb-1">Status</label>
                                            <select name="active" id="" class="form-select @error('active') is-invalid @enderror">
                                                <option value="">Choose Status</option>
                                                <option value="0">Not Active</option>
                                                <option value="1">Active</option>
                                            </select>
                                            @error('active')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <input type="hidden" name="hall_id" value="{{$hall->id}}">
                                    <div class="d-flex align-items-center my-2">
                                        <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="delete-hall" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-hallLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="delete-hallLabel">Delete Hall</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#hallDelete')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <div class="delete-confirmation">
                                        <div class="delete-warning">
                                            <div class="delete-icon">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                            </div>
                                            <div class="delete-message">
                                                <h6>Are you sure to delete this? If you delete this, the count of seat in this must be "0".</h6>
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

                                    <input type="hidden" name="id" value="{{$hall->id}}">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="duplicate-hall" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="duplicate-hallLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="duplicate-hallLabel">Duplicate Hall</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#hallDuplicate')}}" method="post" class="mt-1">
                                    @csrf
                                    <div class="delete-warning">
                                        <div class="delete-icon">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                        </div>
                                        <div class="delete-message">
                                            <small>Verify you are admin. Please enter the password.</small>
                                        </div>
                                    </div>
                                    <div class="form-group my-3">
                                        <label class="mb-1">Password</label>
                                        <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="id" value="{{$hall->id}}">
                                    <div class="d-flex align-items-center mt-4 mb-2">
                                        <button type="submit" class="action-btn w-100"><i class="fa-solid fa-copy me-2"></i>Duplicate</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="replace-seat-plan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="replace-seat-planLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="replace-seat-planLabel">Replace Seat Plan</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#replaceSeatPlan')}}" method="post" class="mt-1">
                                    @csrf
                                    <div class="update-btn-group">
                                        <div class="row">
                                            <div class="col-sm-6 form-group mb-4">
                                                <label class="mb-1">From</label>
                                                <select name="from" class="form-select @error('from') is-invalid @enderror">
                                                    <option value="">Choose Seat Plan</option>
                                                    @foreach ($seatPlan as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('from')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6 form-group mb-4">
                                                <label class="mb-1">To</label>
                                                <select name="to" class="form-select @error('to') is-invalid @enderror">
                                                    <option value="">Choose Seat Plan</option>
                                                    @foreach ($seatPlan as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('to')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <input type="hidden" name="hall_id" value="{{$hall->id}}">
                                        <div class="d-flex align-items-center my-2">
                                            <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                        </div>
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
