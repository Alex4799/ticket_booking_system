@extends('main.admin.layout.master')
@section('title')
    Admin List
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin#list')}}">List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="profile-card">
                    <div class="profile-top">
                        <div class="profile-avatar">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <h5>{{$admin->name}}</h5>
                        <span class="profile-position">{{$admin->position}}</span>
                    </div>
                    <div class="profile-body">
                        <div class="profile-info mb-4">
                            <span>Email</span>
                            <p>{{$admin->email}}</p>
                        </div>
                        <div class="profile-info">
                            <span>Location</span>
                            <p>{{$admin->cinema_name??'All'}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="settings-card pb-4">
                    <div class="settings-header border-bottom mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-user-shield me-2"></i>
                            <h5 class="mb-0">Permission</h5>
                        </div>
                    </div>
                    <div class="movie-plan-view px-4">
                        <div class="details-grid">
                            <div class="detail-item">
                                <span>
                                    <i class="fa-solid fa-ticket me-2"></i>
                                    Ticket Report
                                </span>
                                @if ($admin->ticket_report)
                                    <strong class="text-success">
                                        <i class="fa-solid fa-circle-check me-1"></i>
                                        Granted
                                    </strong>
                                @else
                                    <strong class="text-danger">
                                        <i class="fa-solid fa-circle-xmark me-1"></i>
                                        Denied
                                    </strong>
                                @endif
                            </div>
                            <div class="detail-item">
                                <span>
                                    <i class="fa-solid fa-square-poll-vertical me-2"></i>
                                    Product Report
                                </span>
                                @if ($admin->product_report)
                                    <strong class="text-success">
                                        <i class="fa-solid fa-circle-check me-1"></i>
                                        Granted
                                    </strong>
                                @else
                                    <strong class="text-danger">
                                        <i class="fa-solid fa-circle-xmark me-1"></i>
                                        Denied
                                    </strong>
                                @endif
                            </div>
                            <div class="detail-item">
                                <span>
                                    <i class="fa-solid fa-user-xmark me-2"></i>
                                    Order Reject
                                </span>
                                @if ($admin->can_reject)
                                    <strong class="text-success">
                                        <i class="fa-solid fa-circle-check me-1"></i>
                                        Granted
                                    </strong>
                                @else
                                    <strong class="text-danger">
                                        <i class="fa-solid fa-circle-xmark me-1"></i>
                                        Denied
                                    </strong>
                                @endif
                            </div>
                            <div class="detail-item">
                                <span>
                                    <i class="fa-solid fa-cart-shopping me-2"></i>
                                    Purchase Product
                                </span>
                                @if ($admin->purchase)
                                    <strong class="text-success">
                                        <i class="fa-solid fa-circle-check me-1"></i>
                                        Granted
                                    </strong>
                                @else
                                    <strong class="text-danger">
                                        <i class="fa-solid fa-circle-xmark me-1"></i>
                                        Denied
                                    </strong>
                                @endif
                            </div>
                            <div class="detail-item">
                                <span>
                                    <i class="fa-solid fa-boxes-packing me-2"></i>
                                    Stock Change
                                </span>
                                @if ($admin->stock)
                                    <strong class="text-success">
                                        <i class="fa-solid fa-circle-check me-1"></i>
                                        Granted
                                    </strong>
                                @else
                                    <strong class="text-danger">
                                        <i class="fa-solid fa-circle-xmark me-1"></i>
                                        Denied
                                    </strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (in_array(Auth::user()->position,['developer','CEO','manager','admin']))
                        @if (Auth::user()->position=='manager')
                            @if (!in_array($admin->position,['developer','CEO','admin','manager']))
                                 <div class="border-top mt-4 px-3 pt-3 d-flex align-items-center justify-content-end flex-wrap gap-2">
                                    <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#edit"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Account</button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            @endif
                        @else
                            <div class="border-top mt-4 px-3 pt-3 d-flex align-items-center justify-content-end flex-wrap gap-2">
                                <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#edit"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Account</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </div>

        {{-- model  --}}
        <div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-block w-100 text-center" id="deleteLabel">Edit Account</h5>
                        <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#update')}}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="update-btn-group">
                                <div class="row">
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Name</label>
                                        <input name="name" value="{{old('name',$admin->name)}}" type="text" class="form-control @error('name') is-invalid @enderror" required>
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Email</label>
                                        <input name="email" value="{{old('email',$admin->email)}}" type="email" class="form-control @error('email') is-invalid @enderror" required>
                                        @error('email')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Position</label>
                                        <select name="position" id="" class="form-select @error('position') is-invalid @enderror">
                                            <option value="">Choose Position</option>
                                            @if (Auth::user()->position=='CEO'||Auth::user()->position=='developer')
                                                <option value="admin" @if ($admin->position=='admin') selected @endif>Admin</option>
                                                <option value="manager" @if ($admin->position=='manager') selected @endif>Manager</option>
                                            @elseif(Auth::user()->position=='admin')
                                                <option value="manager" @if ($admin->position=='manager') selected @endif>Manager</option>
                                            @endif
                                            <option value="ticket-seller" @if ($admin->position=='ticket-seller') selected @endif>Ticket Seller</option>
                                            <option value="movie-planner" @if ($admin->position=='movie-planner') selected @endif>Movie Planner</option>
                                            <option value="product-seller" @if ($admin->position=='product-seller') selected @endif>Product Seller</option>
                                            <option value="store-manager" @if ($admin->position=='store-manager') selected @endif>Store Manager</option>
                                        </select>
                                        @error('position')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 form-group mb-4">
                                        <label class="mb-1">Location</label>
                                        <select name="location" id="" class="form-select @error('location') is-invalid @enderror">
                                            <option value="">Choose Cinema</option>
                                            <option value="admin">Admin</option>
                                            @foreach ($cinema as $item)
                                                <option value="{{$item->id}}" @if ($admin->location==$item->id) selected @endif>{{$item->name}}({{$item->location}})</option>
                                            @endforeach
                                        </select>
                                        @error('location')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    @if ($admin->position=='ticket-seller' || $admin->position=='product-seller')
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Ticket Report</label>
                                            <select name="ticket_report" id="" class="form-select @error('ticket_report') is-invalid @enderror">
                                                <option value="0" @if ($admin->ticket_report==0) selected @endif>Denied</option>
                                                <option value="1" @if ($admin->ticket_report==1) selected @endif>Granted</option>
                                            </select>
                                            @error('ticket_report')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Product Report</label>
                                            <select name="product_report" id="" class="form-select @error('product_report') is-invalid @enderror">
                                                <option value="0" @if ($admin->product_report==0) selected @endif>Denied</option>
                                                <option value="1" @if ($admin->product_report==1) selected @endif>Granted</option>
                                            </select>
                                            @error('product_report')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4 form-group mb-4">
                                            <label class="mb-1">Order Reject</label>
                                            <select name="can_reject" id="" class="form-select @error('can_reject') is-invalid @enderror">
                                                <option value="0" @if ($admin->can_reject==0) selected @endif>Denied</option>
                                                <option value="1" @if ($admin->can_reject==1) selected @endif>Granted</option>
                                            </select>
                                            @error('can_reject')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4 form-group mb-4">
                                            <label class="mb-1">Purchase</label>
                                            <select name="purchase" id="" class="form-select @error('purchase') is-invalid @enderror">
                                                <option value="0" @if ($admin->purchase==0) selected @endif>Denied</option>
                                                <option value="1" @if ($admin->purchase==1) selected @endif>Granted</option>
                                            </select>
                                            @error('purchase')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4 form-group mb-4">
                                            <label class="mb-1">Stock Change</label>
                                            <select name="stock" id="" class="form-select @error('stock') is-invalid @enderror">
                                                <option value="0" @if ($admin->stock==0) selected @endif>Denied</option>
                                                <option value="1" @if ($admin->stock==1) selected @endif>Granted</option>
                                            </select>
                                            @error('stock')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    @endif
                                    <input type="hidden" name="id" value="{{$admin->id}}">
                                    <div class="d-flex align-items-center my-2">
                                        <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-block w-100 text-center" id="delete-inventoryLabel">Delete Account</h5>
                        <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#delete')}}" method="post" class="mt-1">
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

                            <input type="hidden" name="id" value="{{$admin->id}}">

                            <div class="d-flex align-items-center justify-content-end">
                                <button type="submit" class="btn btn-danger">Delete</button>
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
