@extends('auth.master')
@section('content')
        <!-- login form start  -->
        <div class="container-lg">
            <div class="login-content text-center">

                <div class="login-form">
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        <h2 class="title">Welcome</h2>
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{session('status')}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger py-3 alert-dismissible fade show" role="alert">
                                    {{ $error }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endforeach
                        @endif
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <div class="input-div mt-4">
                            <div class="input-i"><i class="fa-solid fa-envelope"></i></div>
                            <div>
                                <input type="email" name="email" value="{{old('email', $request->email)}}" placeholder="Email address">
                            </div>
                        </div>
                        <div class="input-div">
                            <div class="input-i"><i class="fas fa-lock"></i></div>
                            <div>
                                <input type="password" name="password" placeholder="Password">
                            </div>
                        </div>

                        <div class="input-div">
                            <div class="input-i"><i class="fas fa-lock"></i></div>
                            <div>
                                <input type="password" name="password_confirmation" placeholder="Confirm Password">
                            </div>
                        </div>

                        <!-- <a href="#">Forgot Password?</a> -->
                        <input type="submit" class="btn" value="Reset Password">
                    </form>
                </div>
            </div>
        </div>
        <!-- login form end  -->
@endsection

