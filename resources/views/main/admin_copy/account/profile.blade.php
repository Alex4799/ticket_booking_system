@extends('main.admin.layout.master')
@section('title')
    Profile
@endsection
@section('content')
                <!-- profile section start  -->
                <main class="content px-3 py-4">
                    <div class="container-fluid">
                        <div class="profile-card">
                            <div class="profile-header">
                                <i class="fa-regular fa-user fs-5 pb-2 me-3"></i>
                                <h3>Profile Card</h3>
                            </div>
                            <hr>
                            <div class="profile-content">
                                <div class="profile-item">
                                    <span class="profile-label">Username:</span>
                                    <span class="profile-value">{{$user->name}}</span>
                                </div>
                                <div class="profile-item">
                                    <span class="profile-label">Email:</span>
                                    <span class="profile-value">{{$user->email}}</span>
                                </div>
                                <div class="profile-item">
                                    <span class="profile-label">Position:</span>
                                    <span class="profile-value">{{$user->position}}</span>
                                </div>
                                <div class="profile-item">
                                    <span class="profile-label">Location:</span>
                                    <span class="profile-value">{{$user->cinema_name}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <!-- profile section end -->
                <div class="px-2">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{session('success')}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            {{session('warning')}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('danger'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{session('danger')}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                <!-- profile detail edit and change password start  -->
                <div class="container-lg px-4">
                    <div id="profile-detail-content" class="">
                        <ul id="profile-detail">
                            <li><a href="javascript:void(0)" class="text-content text-active" onclick="detailsClick(event,'editProfile')">Edit Profile</a></li>
                            <li><a href="javascript:void(0)" class="text-content" onclick="detailsClick(event,'changePassword')">Change Password</a></li>
                        </ul>
                        <div id="details-show">
                            <div id="edit-profile" class="px-2 mt-2 mx-auto">
                                <div class="d-flex align-items-center justify-content-center mx-auto">
                                    <div class="card p-2">
                                        <div class="card-body">
                                            <form action="{{route('admin#profileUpdate')}}" method="post" class="">
                                                @csrf
                                                @method('patch')
                                                <div class="form-group mb-4">
                                                    <label class="mb-1 fw-semibold">Username</label>
                                                    <input id="" type="text" value="{{$user->name}}" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Enter username">
                                                    @error('name')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>

                                                <div class="form-group mb-4">
                                                    <label class="mb-1 fw-semibold">Email</label>
                                                    <input id="" type="email" value="{{$user->email}}" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Enter email">
                                                    @error('email')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>

                                                <button type="submit" class="btn btn-success w-100">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="change-password" class="px-2 mt-2 mx-auto">
                                <div class="d-flex align-items-center justify-content-center mx-auto">
                                    <div class="card p-2">
                                        <div class="card-body">
                                            <form action="{{route('admin#changePassword')}}" method="post">
                                                @csrf
                                                <div class="form-group mb-4">
                                                    <label class="control-label mb-1 fw-semibold">Old Password</label>
                                                    <input type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" placeholder="Enter old password">
                                                    @error('old_password')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>

                                                <div class="form-group mb-4">
                                                    <label class="control-label mb-1 fw-semibold">New Password</label>
                                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" placeholder="Enter new password">
                                                    @error('new_password')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>

                                                <div class="form-group mb-4">
                                                    <label class="control-label mb-1 fw-semibold">Confirm Password</label>
                                                    <input type="password" class="form-control @error('con_password') is-invalid @enderror" name="con_password" placeholder="Enter confirm password">
                                                    @error('con_password')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>

                                                <button type="submit" class="btn btn-success w-100">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- profile detail edit and change password start  -->
@endsection
