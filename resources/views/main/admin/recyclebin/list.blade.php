@extends('main.admin.layout.master')
@section('title')
    Recycle Bin
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div class="table-bg">
            <div>
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
                            <tr>
                                <td>1</td>
                                <td>Cinema</td>
                                <td>
                                    <a href="{{route('admin#recycleView','cinema')}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Hall</td>
                                <td>
                                    <a href="{{route('admin#recycleView','hall')}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Genre</td>
                                <td>
                                    <a href="{{route('admin#recycleView','genre')}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Movies</td>
                                <td>
                                    <a href="{{route('admin#recycleView','movie')}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Seat Plan</td>
                                <td>
                                    <a href="{{route('admin#recycleView','seat_plan')}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-circle-info me-2"></i>Details</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
