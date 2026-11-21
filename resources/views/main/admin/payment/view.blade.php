@extends('main.admin.layout.master')
@section('title')
    Payment
@endsection
@section('content')
    <main class="content p-2 mb-5">
        <div class="container-fluid">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin#paymentList')}}">List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
            <div class="shadow-container-box">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="product-view-image">
                            @if ($payment->image!=null)
                                <img src="{{Storage::url('payment/'.$payment->image)}}" alt="image" class="img-thumbnail">
                            @else
                                <img src="{{asset('image/placeholder.webp')}}" alt="Default" class="img-thumbnail">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9 ps-md-5">
                        <div class="seat-view">
                            <div class="seat-detail-top">
                                <div class="seat-title">
                                    <div>
                                        <label>Name</label>
                                        <h4>{{$payment->name}}</h4>
                                    </div>
                                </div>
                                <div>
                                    <h6>
                                        @if ($payment->active==0)
                                            <span class="status reject fw-bold">Not Active</span>
                                        @else
                                            <span class="status success fw-bold">Active</span>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                            <div class="seat-detail-footer">
                                <div>
                                    <label>Cinema</label>
                                    <h6>{{$payment->cinema_name??'All'}}</h6>
                                </div>
                                <div>
                                    <label>User Name</label>
                                    <h6>{{$payment->username}}</h6>
                                </div>
                                <div>
                                    <label>Number</label>
                                    <h6>{{$payment->number}}</h6>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end flex-wrap gap-2">
                                <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#edit-payment"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Payment</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-payment" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="modal fade" id="edit-payment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-paymentLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="edit-paymentLabel">Edit Payment</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#paymentUpdate')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="update-btn-group">
                                        <div class="row">
                                            <div class="col-sm-7 form-group mb-4">
                                                <label class="mb-1">Cinema</label>
                                                <select name="cinema_id" id="" class="form-select @error('cinema_id') is-invalid @enderror">
                                                    <option value="">Choose Cinema</option>
                                                    @foreach ($cinema as $item)
                                                        <option value="{{$item->id}}" @if ($payment->cinema_id==$item->id) selected @endif>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('cinema_id')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                            <div class="col-sm-5 form-group mb-4">
                                                <label class="mb-1">Status</label>
                                                <select name="active" id="" class="form-select">
                                                    <option value="0" @if ($payment->active==0) selected @endif>Not Active</option>
                                                    <option value="1" @if ($payment->active==1) selected @endif>Active</option>
                                                </select>
                                                @error('active')
                                                    <small class=" text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="form-group mb-4">
                                                    <label class="mb-1">Method Name</label>
                                                    <input id="" value="{{$payment->name}}" name="name" type="text" class="form-control @error('name') is-invalid @enderror">
                                                    @error('name')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="mb-1">User Name</label>
                                                    <input id="" value="{{$payment->username}}" name="username" type="text" class="form-control @error('username') is-invalid @enderror">
                                                    @error('username')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="mb-1">Number</label>
                                                    <input id="" value="{{$payment->number}}" name="number" type="number" class="form-control @error('number') is-invalid @enderror">
                                                    @error('number')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-5 ps-md-4">
                                                <div class="form-group mb-4">
                                                    <label class="mb-1">QR Image</label>
                                                    <div>
                                                        @if ($payment->image==null)
                                                            <img src="{{asset('image/placeholder.webp')}}" alt="image" class="img-thumbnail" id="image">
                                                        @else
                                                            <img src="{{Storage::url('payment/'.$payment->image)}}" class="img-thumbnail" alt="" id="image">
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
                                        <input type="hidden" name="id" value="{{$payment->id}}">
                                        <div class="d-flex align-items-center my-2">
                                            <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="delete-payment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-paymentLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="delete-paymentLabel">Delete Payment</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#paymentDelete')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <div class="delete-confirmation">
                                        <div class="delete-warning">
                                            <div class="delete-icon">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                            </div>
                                            <div class="delete-message">
                                                <h6>Are you sure to delete this?</h6>
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
                                    <input type="hidden" name="id" value="{{$payment->id}}">
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
