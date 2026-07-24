@extends('auth.master')
@section('content')
        <!-- login form start  -->
        <div class="container-lg">

            <div class="login-content text-center">
                <div class="login-form">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="register-link text-start mb-3">
                            <a href="{{route('login')}}" class="text-decoration-none"><i class="fa-solid fa-chevron-left me-2"></i>Login</a>
                        </div>
                        <h2 class="title">Welcome</h2>
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" style="font-size: 9pt;" role="alert">
                                {{session('status')}}
                            <button type="button" class="btn-close shadow-none border-0" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger py-3 alert-dismissible fade show" style="font-size: 9pt;" role="alert">
                                    {{ $error }}
                                    <button type="button" class="btn-close shadow-none border-0" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endforeach
                        @endif
                        <div style="font-size: 10pt; color:rgb(171, 171, 171);">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</div>
                        <div class="input-div mt-4">
                            <div class="input-i"><i class="fa-solid fa-envelope"></i></div>
                            <div>
                                <input type="email" name="email" placeholder="Email address">
                            </div>
                        </div>

                        <!-- <a href="#">Forgot Password?</a> -->
                        <input type="submit" class="btn" value="Email Password Reset Link">

                    </form>
                </div>
            </div>
        </div>
        <!-- login form end  -->
@endsection

