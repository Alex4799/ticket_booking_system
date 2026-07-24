@extends('auth.master')
@section('content')
        <!-- login form start  -->
        <div class="container-lg">
            <div class="login-content text-center">

                <div class="login-form">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <h2 class="title">Welcome</h2>
                        @if (session('status'))
                             <div class="alert alert-success alert-dismissible fade show" style="font-size: 9pt;" role="alert">
                                {{session('status')}}
                            <button type="button" class="btn-close shadow-none border-0" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            @foreach ($errors->all() as $error)
                                 <div class="alert alert-danger alert-dismissible fade show" style="font-size: 9pt;" role="alert">
                                    {{ $error }}
                                    <button type="button" class="btn-close shadow-none border-0" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endforeach
                        @endif
                        <div class="input-div mt-4">
                            <div class="input-i"><i class="fa-solid fa-envelope"></i></div>
                            <div>
                                <input type="email" name="email" placeholder="Email address">
                            </div>
                        </div>
                        <div class="input-div">
                            <div class="input-i"><i class="fas fa-lock"></i></div>
                            <div>
                                <input type="password" name="password" placeholder="Password">
                            </div>
                        </div>

                        <!-- <a href="#">Forgot Password?</a> -->
                        <input type="submit" class="btn" value="Login">

                        <div class="register-link mt-4 text-center">
                            <a href="{{route('password.request')}}">Forgot Password</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- login form end  -->
@endsection
