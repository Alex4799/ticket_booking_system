@extends('main.admin.layout.master')
@section('title')
    Recycle Bin
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
                    <li class="breadcrumb-item"><a href="{{route('admin#recycle')}}">List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Genre</li>
                </ol>
            </nav>
        </div>
        <div class="table-bg">
            <div>
                <div class="total-count-title">
                    <div class="d-flex align-items-center">
                        <div class="count-content">
                            <h5 class="mb-0">Total - {{$genre->total()}}</h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <form action="" method="get" id="search-form">
                            <div class="search-cinema">
                                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                                <input placeholder="Search" value="{{request('search_key')}}" name="search_key" type="text" class="input">
                            </div>
                        </form>
                        <a href="{{route('admin#recycleView','genre')}}" title="Refresh this page" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                    </div>
                </div>
                @if (count($genre)!=0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category Name</th>
                                    <th>Deleted At</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($genre as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{Carbon::parse($item->deleted_at)->format('d/m/Y - h:i A')}}</td>
                                        <td>
                                            <a href="{{route('admin#recycleGenreRestore',$item->id)}}" class="btn btn-outline-primary btn-sm me-2"><i class="fa-solid fa-trash-arrow-up me-2"></i>Restore</a>
                                            <a href="{{route('admin#recycleGenreDelete',$item->id)}}" class="btn btn-danger btn-sm delete"><i class="fa-solid fa-trash me-2"></i>Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            {{$genre->appends(request()->query())->links()}}
                        </div>
                    </div>
                @else
                    <h6 class="text-center mt-5 mb-4 text-secondary" style="font-size: 11pt;">There is no genre.</h6>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
