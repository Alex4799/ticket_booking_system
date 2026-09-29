@extends('main.admin.layout.master')
@section('title')
    Store
@endsection
@section('content')
<main class="content px-4 py-4 mb-5">
    <div class="d-flex align-items-center justify-content-between">
        <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
            <h5>Total - {{$store->total()}}</h5>
        </div>
        <form action="" id="search-form">
            <div class="search-cinema">
                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                <input placeholder="Search" type="search" name="search_key" value="{{request('search_key')}}" class="input">
            </div>
        </form>
        @if (Auth::user()->position=='developer')
            <div class="form-group">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-store">Add Store</button>
            </div>
        @endif
    </div>
    <form action="" id="search-form-small" class="mb-3">
        <div class="search-cinema">
            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
            <input placeholder="Search" type="search" name="search_key" value="{{request('search_key')}}" class="input">
        </div>
    </form>
    <div class="">
        <div id="table-bg">
            <div class="">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Manager</th>
                                <th>Cinema</th>
                                <th>Active</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($store as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->manager_name}}</td>
                                <td>{{$item->cinema_name}}</td>
                                <td>
                                    @if ($item->active==0)
                                        <span class="text-warning">Not Active</span>
                                    @else
                                        <span class="text-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('admin#storeView',$item->id)}}" class="btn btn-outline-primary">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{$store->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="add-store" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-storeLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="add-storeLabel">Add Store</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#storeCreate')}}" method="post" class="mt-1">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="mb-1"> Name</label>
                                <input id="" name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter store name">
                                @error('name')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Store Manager</label>
                                <select name="manager" class="form-control @error('name') is-invalid @enderror"  id="">
                                    <option value="">Choose Store Manager</option>
                                    @foreach ($storeManager as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('manager')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Cinema</label>
                                <select name="cinema_id" class="form-control @error('cinema_id') is-invalid @enderror"  id="">
                                    <option value="">Choose Cinema</option>
                                    @foreach ($cinema as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('cinema_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Contact</label>
                                <textarea name="contact" id="editor" cols="30" rows="10" placeholder="Enter content info"></textarea>
                                @error('contact')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label class="mb-1">Location</label>
                                <input name="location" id="" type="text"class="form-control @error('location') is-invalid  @enderror" placeholder="Enter cinema location">
                                @error('location')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label for="name" class="form-label mb-1">Active</label>
                                <select name="active" id="" class="form-control">
                                    <option value="0" >Not Active</option>
                                    <option value="1" >Active</option>
                                </select>
                                @error('active')
                                    <small class=" text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 my-2">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('script')
        <script type="module">
            import {
                ClassicEditor,
                Essentials,
                Bold,
                Italic,
                Font,
                Paragraph
            } from 'ckeditor5';

            ClassicEditor
                .create( document.querySelector( '#editor' ), {
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: {
                        items: [
                            'undo', 'redo', '|', 'bold', 'italic', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                        ]
                    }
                } )
                .then( /* ... */ )
                .catch( /* ... */ );
        </script>
@endsection
