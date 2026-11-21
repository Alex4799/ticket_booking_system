@extends('main.admin.layout.master')
@section('title')
    Inventory
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin#inventoryList')}}">List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="shadow-container-box">
            <div class="movie-view">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="product-view-image">
                            @if ($inventory->product_image==null)
                                <img src="{{asset('image/placeholder.webp')}}" alt="default image" class="img-thumbnail">
                            @else
                                <img src="{{Storage::url('product/'.$inventory->product_image)}}" alt="image" class="img-thumbnail">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9 ps-md-5">
                        <div class="seat-view">
                            <div class="seat-detail-top border-none">
                                <div class="seat-title">
                                    <div>
                                        <h4>{{$inventory->product_name}}</h4>
                                    </div>
                                </div>
                                <div>
                                    <h6>
                                        @if ($inventory->status==0)
                                            <span class="status pending fw-bold">Pending</span>
                                        @elseif($inventory->status==1)
                                            <span class="status success fw-bold">Success</span>
                                        @else
                                            <span class="status reject fw-bold">Reject</span>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                            <div class="seat-detail-footer pt-2">
                                <div>
                                    <label>QTY</label>
                                    <h6>{{$inventory->qty}}</h6>
                                </div>
                                <div>
                                    <label>Product Price</label>
                                    <h6>{{number_format($inventory->price)}} Ks</h6>
                                </div>
                                <div>
                                    <label>Total Price</label>
                                    <h6>{{number_format($inventory->total_price)}} Ks</h6>
                                </div>
                            </div>
                        </div>
                        <div class="movie-plan-view">
                            <div class="details px-3">
                                <h6 class="section-label">Details</h6>
                                <div class="details-grid">
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-shop me-2"></i>From Store
                                        </span>
                                        <strong>{{$inventory->send_store_name}}</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-shop me-2"></i>To Store
                                        </span>
                                        <strong>{{$inventory->receive_store_name}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
                            <div>
                                @if ($inventory->status==0)
                                    <div class="dropdown">
                                        <button class="action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-eye-dropper me-2 text-white"></i>
                                        @if ($inventory->status==0)
                                            Pending
                                        @elseif ($inventory->status==1)
                                            Success
                                        @elseif ($inventory->status==2)
                                            Reject
                                        @endif
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{route('admin#inventoryChangeStatus',[$inventory->id,1])}}">Success</a></li>
                                            <li><a class="dropdown-item" href="{{route('admin#inventoryChangeStatus',[$inventory->id,2])}}">Reject</a></li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            @if ($inventory->status!=1)
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-inventory" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="modal fade" id="delete-inventory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-inventoryLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="delete-inventoryLabel">Delete Inventory</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#inventoryDelete')}}" method="post" class="mt-1">
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

                                <input type="hidden" name="id" value="{{$inventory->id}}">
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

@endsection
