@extends('main.admin.layout.master')
@section('title')
    Social
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div class="table-bg">
            <div>
                <div class="total-count-title">
                    <div class="d-flex align-items-center">
                        <div class="count-content">
                            <h5 class="mb-0">Total - {{$social->total()}}</h5>
                        </div>
                        <div class="ms-3">
                            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#add-social">
                                <i class="fa-solid fa-square-plus me-2"></i>Add Social
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Link</th>
                                <th class="col-2">Logo</th>
                                <th class="text-center">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($social as $item)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->name}}</td>
                                    <td><a href="{{$item->link}}" target="_blank">{{$item->link}}</a></td>
                                    <td><img src="{{Storage::url('social/'.$item->logo)}}" alt="" class="w-25 d-block rounded"></td>
                                    <td class="text-center">
                                        @if ($item->active==1)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Not Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#edit-social{{$item->id}}"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm delete" data-bs-toggle="modal" data-bs-target="#delete{{$item->id}}"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                                <div>
                                    <div class="modal fade" id="edit-social{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-socialLabel{{$item->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title d-block w-100 text-center" id="edit-socialLabel{{$item->id}}">Edit Social</h5>
                                                    <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('admin#socialUpdate')}}" class="mt-1" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="update-btn-group">
                                                            <div class="row">
                                                                <div class="col-sm-7">
                                                                    <div class="form-group mb-4">
                                                                        <label class="mb-1">Name</label>
                                                                        <input id="" name="name" value="{{$item->name}}" type="text" class="form-control">
                                                                    </div>
                                                                    <div class="form-group mb-4">
                                                                        <label class="mb-1">Link</label>
                                                                        <input id="" name="link" value="{{$item->link}}" type="text" class="form-control">
                                                                    </div>
                                                                    <div class="form-group mb-4">
                                                                        <label class="mb-1">Status</label>
                                                                        <select name="active" id="" class="form-select">
                                                                            <option value="0" @if ($item->active==0) selected @endif>Not Active</option>
                                                                            <option value="1" @if ($item->active==1) selected @endif>Active</option>
                                                                        </select>
                                                                        @error('active')
                                                                            <small class=" text-danger">{{$message}}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-5 ps-sm-4">
                                                                    <div class="form-group mb-4">
                                                                        <label class="mb-1">Logo</label>
                                                                        <div>
                                                                            @if ($item->logo!=null)
                                                                                <img src="{{Storage::url('social/'.$item->logo)}}" alt="logo" class="img-thumbnail" id="image-preview-{{ $item->id }}">
                                                                            @else
                                                                                <img src="{{asset('image/placeholder.webp')}}" alt="logo" class="img-thumbnail" id="image-preview-{{ $item->id }}">
                                                                            @endif
                                                                            <div>
                                                                                <label for="input_image_{{ $item->id }}" class="file-pen-logo mt-3">
                                                                                    <span><i class="fa-solid fa-upload me-1"></i> Upload Image</span>
                                                                                </label>
                                                                                <input type="file" name="logo" class="d-none" id="input_image_{{ $item->id }}" accept="image/*">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="id" value="{{$item->id}}">
                                                            <div class="d-flex align-items-center my-2">
                                                                <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="delete{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-Label{{$item->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title d-block w-100 text-center" id="delete-Label{{$item->id}}">Delete Social</h5>
                                                    <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('admin#socialDelete')}}" method="post" class="mt-1">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="delete-confirmation">
                                                            <div class="delete-warning">
                                                                <div class="delete-icon">
                                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                                </div>
                                                                <div class="delete-message">
                                                                    <h6>Are you sure to delete "{{$item->name}}" ?</h6>
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

                                                        <input type="hidden" name="id" value="{{$item->id}}">
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{$social->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="modal fade" id="add-social" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-socialLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="add-socialLabel">Add Social</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#socialCreate')}}" class="mt-1 parents" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group mb-4">
                                            <label class="mb-1">Name</label>
                                            <input id="" name="name" type="text" class="form-control" required>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="mb-1">Link</label>
                                            <input id="" name="link" type="text" class="form-control" required>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="mb-1">Status</label>
                                            <select name="active" id="" class="form-select">
                                                <option value="0">Not Active</option>
                                                <option value="1">Active</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5 ps-md-4">
                                        <div class="form-group mb-4">
                                            <label class="mb-1">Logo</label>
                                            <div>
                                                <img src="{{asset('image/placeholder.webp')}}" alt="image" class="img-thumbnail" id="image">
                                                <div>
                                                    <label for="add_image" class="file-pen-logo mt-3">
                                                        <span><i class="fa-solid fa-upload me-1"></i> Upload Image</span>
                                                    </label>
                                                    <input type="file" name="logo" class="d-none" id="add_image" accept="image/*">
                                                </div>
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
    </div>
</main>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $('#add_image').change(function(){
            $parents=$(this).parents('.parents');
            $parents.find('#image').attr("src",window.URL.createObjectURL(this.files[0]));
        });
    });

    document.querySelectorAll('input[type="file"][accept="image/*"]').forEach(function(input) {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) { return; }

            const previewId = 'image-preview-' + this.id.replace('input_image_', '');
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.src = URL.createObjectURL(file);
            }
        });
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
