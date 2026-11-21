@extends('main.admin.layout.master')
@section('title')
    Purchase
@endsection
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin#purchaseList')}}">List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="shadow-container-box">
            <div class="movie-view">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="product-view-image">
                            @if ($purchase->product_image==null)
                                <img src="{{asset('image/placeholder.webp')}}" alt="default image" class="img-thumbnail">
                            @else
                                <img src="{{Storage::url('product/'.$purchase->product_image)}}" alt="image" class="img-thumbnail">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9 ps-md-5">
                        <div class="seat-view">
                            <div class="seat-detail-top border-none">
                                <div class="seat-title">
                                    <div>
                                        <h4>{{$purchase->product_name}}</h4>
                                    </div>
                                </div>
                                <div>
                                    <h6>
                                        @if ($purchase->status==0)
                                            <span class="status pending fw-bold">Pending</span>
                                        @elseif($purchase->status==1)
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
                                    <h6>{{$purchase->qty}}</h6>
                                </div>
                                <div>
                                    <label>Purchase Price</label>
                                    <h6>{{number_format($purchase->price)}} Ks</h6>
                                </div>
                                <div>
                                    <label>Total Price</label>
                                    <h6>{{number_format($purchase->total_price)}} Ks</h6>
                                </div>
                            </div>
                        </div>
                        <div class="movie-view">
                            <div class="details px-2">
                                <h6 class="section-label">Details</h6>
                                <div class="details-grid">
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-calendar me-2"></i>Date
                                        </span>
                                        <strong>{{Carbon::parse($purchase->created_at)->format('d/m/Y')}}</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-clock me-2"></i>Time
                                        </span>
                                        <strong>{{Carbon::parse($purchase->created_at)->format('h:i A')}}</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-shop me-2"></i>Store Name
                                        </span>
                                        <strong>{{$purchase->store_name}}</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-user me-2"></i>Supplier Name
                                        </span>
                                        <strong>{{$purchase->supplier_name}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
                            @if (Auth::user()->purchase)
                                <div>
                                    <div class="dropdown">
                                        <button  class="action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-eye-dropper me-2 text-white"></i>
                                            @if ($purchase->status==0)
                                                Pending
                                            @elseif ($purchase->status==1)
                                                Success
                                            @elseif ($purchase->status==2)
                                                Reject
                                            @endif
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{route('admin#purchaseChangeStatus',[$purchase->id,0])}}">Pending</a></li>
                                            <li><a class="dropdown-item" href="{{route('admin#purchaseChangeStatus',[$purchase->id,1])}}">Success</a></li>
                                            <li><a class="dropdown-item" href="{{route('admin#purchaseChangeStatus',[$purchase->id,2])}}">Reject</a></li>
                                        </ul>
                                    </div>
                                </div>
                                @if ($purchase->status==0)
                                    <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#edit-purchase"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Purchase</button>
                                @endif
                                @if ($purchase->status!=1)
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-product" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                                @endif
                            @else
                                <button class="btn @if ($purchase->status==0) btn-warning  @elseif ($purchase->status==1) btn-success @elseif ($purchase->status==2) btn-danger @endif" type="button">
                                    @if ($purchase->status==0)
                                        Pending
                                    @elseif ($purchase->status==1)
                                        Success
                                    @elseif ($purchase->status==2)
                                        Reject
                                    @endif
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="modal fade" id="edit-purchase" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-purchaseLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="edit-purchaseLabel">Edit Purchase</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#purchaseUpdate')}}" method="post" class="mt-1">
                                @csrf
                                @method('PATCH')
                                <div class="update-btn-group">
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Product</label>
                                        <select name="product" class="form-select @error('product') is-invalid @enderror" required>
                                            <option value="">Choose Product</option>
                                            @foreach ($product as $item)
                                                <option value="{{json_encode(['id'=>$item->id,'name'=>$item->name])}}" @if ($item->name==$purchase->product_name) selected @endif>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('product')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Supplier</label>
                                            <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                                <option value="">Choose Supplier</option>
                                                @foreach ($supplier as $item)
                                                    <option value="{{$item->id}}" @if ($item->id==$purchase->supplier_id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('supplier_id')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Store</label>
                                            <select name="store_id" class="form-select @error('store_id') is-invalid @enderror" required>
                                                <option value="">Choose Store</option>
                                                @foreach ($store as $item)
                                                    <option value="{{$item->id}}" @if ($item->id==$purchase->store_id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('store_id')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Qty</label>
                                            <input name="qty" type="number" value="{{$purchase->qty}}" class="form-control @error('qty') is-invalid @enderror" required>
                                            @error('qty')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Price (For one item)</label>
                                            <input name="price" type="number" value="{{$purchase->price}}" class="form-control @error('price') is-invalid @enderror" required>
                                            @error('price')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$purchase->id}}">
                                    <div class="d-flex align-items-center my-2">
                                        <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="delete-product" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-productLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="delete-productLabel">Delete Purchase</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#purchaseDelete')}}" method="post" class="mt-1">
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

                                <input type="hidden" name="id" value="{{$purchase->id}}">
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
        const inputs = form.querySelectorAll('input, select, textarea');
        const button = form.querySelector('.action-btn');
        const initialValues = {};

        inputs.forEach(input => {
            if (input.type === 'checkbox' || input.type === 'radio') {
                initialValues[input.name] = input.checked;
            } else {
                initialValues[input.name] = input.value;
            }
        });

        function checkUpdateValueForBtn() {
            let hasChanged = false;
            inputs.forEach(input => {
                let currentValue;
                if (input.type === 'checkbox' || input.type === 'radio') {
                    currentValue = input.checked;
                } else {
                    currentValue = input.value;
                }
                if (currentValue !== initialValues[input.name]) {
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
