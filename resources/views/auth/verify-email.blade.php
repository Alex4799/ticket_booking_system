@extends('auth.master')
@section('content')
        <!-- login form start  -->
        <div class="container-lg">
            <div class="login-content text-center">
                <div class="login-form">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <h2 class="title">Welcome</h2>
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" style="font-size: 9pt;" role="alert">
                                A new verification link has been sent to the email address you provided during registration.
                            <button type="button" class="btn-close shadow-none border-0" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif


                        <div style="font-size: 10pt; color:rgb(171, 171, 171);">
                            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                        </div>

                        <input type="submit" class="btn" value="Resend Verification Email">

                    </form>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- login form end  -->
@endsection
