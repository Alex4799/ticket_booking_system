@extends('auth.master')
@section('content')
            <!-- login form start  -->
            <div class="container-lg">
                <div class="login-content text-center">
                    <div class="login-form">
                        <form action="">
                            <h2 class="title">Sign Up</h2>
                            <div class="input-div mt-4">
                                <div class="input-i"><i class="fa-solid fa-user"></i></div>
                                <div>
                                    <input type="text" placeholder="Username">
                                </div>
                            </div>
                            <div class="input-div">
                                <div class="input-i"><i class="fa-solid fa-envelope"></i></div>
                                <div>
                                    <input type="email" placeholder="Email address">
                                </div>
                            </div>
                            <div class="input-div">
                                <div class="input-i"><i class="fas fa-lock"></i></div>
                                <div>
                                    <input type="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="input-div">
                                <div class="input-i"><i class="fas fa-lock"></i></div>
                                <div>
                                    <input type="password" placeholder="Confirm password">
                                </div>
                            </div>

                            <input type="submit" class="btn" value="Singup">

                            <div class="register-link mt-4 text-center">
                                <p>
                                    Already a member?
                                    <a href="" class="ms-2">Login Here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- login form end  -->
@endsection
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
