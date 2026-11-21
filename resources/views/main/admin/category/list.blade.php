@extends('main.admin.layout.master')
@section('title')
    Product Category
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div class="table-bg">
            <div>
                <div class="total-count-title">
                    <div class="d-flex align-items-center">
                        <div class="count-content">
                            <h5 class="mb-0">Total - {{$category->total()}}</h5>
                        </div>
                        <div class="ms-3">
                            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#add-category">
                                <i class="fa-solid fa-square-plus me-2"></i>Add Category
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
                        <a href="{{route('admin#categoryList')}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                    </div>
                </div>
                @if (count($category)!=0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($category as $item)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#edit-category{{$item->id}}"><i class="fa-solid fa-pen-to-square"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm delete" data-bs-toggle="modal" data-bs-target="#delete-category{{$item->id}}"><i class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="edit-category{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-category{{$item->id}}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title d-block w-100 text-center" id="edit-category{{$item->id}}Label">Edit Category</h5>
                                                    <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('admin#categoryUpdate')}}" method="post" class="mt-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="update-btn-group">
                                                            <div class="form-group mb-4">
                                                                <label class="mb-1">Name</label>
                                                                <input id="" name="name" value="{{$item->name}}" type="text" class="form-control @error('name') is-invalid @enderror" required>
                                                                @error('name')
                                                                    <small class="text-danger">{{$message}}</small>
                                                                @enderror
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
                                    <div class="modal fade" id="delete-category{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-category{{$item->id}}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title d-block w-100 text-center" id="delete-category{{$item->id}}Label">Delete Product Category</h5>
                                                    <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('admin#categoryDelete')}}" method="post" class="mt-1">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="delete-confirmation">
                                                            <div class="delete-warning">
                                                                <div class="delete-icon">
                                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                                </div>
                                                                <div class="delete-message">
                                                                    <h6>Are you sure to delete "{{$item->name}}" ? If you delete this, the category of this product will be in other category.</h6>
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
                                    @endforeach
                                </tbody>
                            </table>
                            <div>
                                {{$category->appends(request()->query())->links()}}
                            </div>
                        </div>
                @else
                        <h6 class="text-center mt-5 mb-4 text-secondary" style="font-size: 11pt;">There is no category.</h6>
                @endif
            </div>
        </div>

        <div>
            <div class="modal fade" id="add-category" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-categoryLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="add-categoryLabel">Add Category</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#categoryCreate')}}" method="post" class="mt-1">
                                @csrf
                                <div class="form-group mb-4">
                                    <label class="mb-1">Category Name</label>
                                    <input id="" name="name" type="text" class="form-control @error('name') is-invalid @enderror" required>
                                    @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
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
        // updatebtn disabled check
        const forms = document.querySelectorAll('.update-btn-group');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input');
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
