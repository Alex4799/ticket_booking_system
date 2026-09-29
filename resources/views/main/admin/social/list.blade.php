@extends('main.admin.layout.master')
@section('title')
    Social
@endsection
@section('content')
<main class="content px-3 mb-5">
    <div class="container-fluid">
        <div id="table-bg">
            <div class="">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
                        <h5>Total - {{$social->total()}}</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#add-social">Add Social</button>
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
                                <th>Active</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($social as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                                <td><a href="{{$item->link}}" target="_blank">{{$item->link}}</a></td>
                                <td><img src="{{Storage::url('social/'.$item->logo)}}" alt="" class="w-50 d-block"></td>
                                <td>
                                    @if ($item->active==1)
                                        <span class="text-success">Active</span>
                                    @else
                                        <span class="text-danger">Not Active</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="table-data-feature">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit-social{{$item->id}}">Edit</button>
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete{{$item->id}}">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <div class="modal fade" id="edit-social{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-social{{$item->id}}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title d-block w-100 text-center" id="edit-social{{$item->id}}Label">Edit Social</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{route('admin#socialUpdate')}}" class="" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PATCH')
                                                <div class="form-group mb-4">
                                                    <label class="mb-1">Name</label>
                                                    <input id="" name="name" value="{{$item->name}}" type="text" class="form-control" placeholder="Enter social name">
                                                </div>

                                                <div class="form-group mb-4">
                                                    <label class="mb-1">Link</label>
                                                    <input id="" name="link" value="{{$item->link}}" type="text" class="form-control" placeholder="Enter social link">
                                                </div>

                                                <div class="form-group mb-4 parents">
                                                    <label class="mb-1">Logo</label>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            @if ($item->logo!=null)
                                                                <img src="{{Storage::url('social/'.$item->logo)}}" id="image" alt="" class="w-100 d-block">
                                                            @else
                                                                <img src="{{asset('image/placeholder.webp')}}" id="image" class="w-100" alt="">
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 m-auto">
                                                            <input name="logo" id="input_image" type="file" class="form-control input_image">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <label for="name" class="form-label mb-1">Active</label>
                                                    <select name="active" id="" class="form-control">
                                                        <option value="">Choose Active Status</option>
                                                        <option value="0" @if ($item->active==0) selected @endif>Not Active</option>
                                                        <option value="1" @if ($item->active==1) selected @endif>Active</option>
                                                    </select>
                                                    @error('active')
                                                        <small class=" text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>

                                                <input type="hidden" name="id" value="{{$item->id}}">

                                                <button type="submit" class="btn btn-primary w-100 my-2">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="delete{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete{{$item->id}}-Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title d-block w-100 text-center" id="delete{{$item->id}}-Label">Delete Social</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{route('admin#socialDelete')}}" method="post" class="">
                                                @csrf
                                                @method('DELETE')
                                                <div class="py-1">
                                                    <p class="text-danger">Are you sure to delete this.</p>
                                                </div>
                                                <div class="py-1">
                                                    <h6>Verify you are admin. Please enter the password.</h6>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="mb-1">Password</label>
                                                    <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password." required>
                                                    @error('password')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>

                                                <input type="hidden" name="id" value="{{$item->id}}">

                                                <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
                                            </form>
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

                <!-- modal -->
                <div class="modal fade" id="add-social" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-socialLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="add-socialLabel">Add Social Details</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#socialCreate')}}" class="" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Name</label>
                                        <input id="" name="name" type="text" class="form-control" placeholder="Enter social name">
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="mb-1">Link</label>
                                        <input id="" name="link" type="text" class="form-control" placeholder="Enter social link">
                                    </div>

                                    <div class="form-group mb-4 parents">
                                        <label class="mb-1">Logo</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <img src="{{asset('image/placeholder.webp')}}" id="image" class="w-100 img-thumbnail" alt="">
                                            </div>
                                            <div class="col-md-6 m-auto">
                                                <input name="logo" id="input_image" type="file" class="form-control input_image">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="name" class="form-label mb-1">Active</label>
                                        <select name="active" id="" class="form-control">
                                            <option value="0">Not Active</option>
                                            <option value="1">Active</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 my-2">Create</button>
                                </form>
                            </div>
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
        $('.input_image').change(function(){
            $parents=$(this).parents('.parents')
            $parents.find('#image').attr("src",window.URL.createObjectURL(this.files[0]));
            // document.getElementById('image').src = window.URL.createObjectURL(this.files[0]);
        })
    })
</script>
@endsection
