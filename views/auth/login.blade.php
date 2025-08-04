@extends('layouts.auth')
@section('title', 'login')

@section('content')
    <form action="{{route('login')}}" method="POST" class="bg-white rounded-2xl border border-solid border-gray-500 px-10 py-15 m-10 sm:m-0"> 
        <h1 class="font-semibold text-green-700 text-2xl">Think Finance!</h1>
        <p class="text-lg mb-4 text-gray-400">Login</p>
        @csrf
        
        <label for="email" class="block text-gray-700 text-md font-bold mb-2">Email</label>
        <input type="email" id="email" name="email" placeholder="Email address" class="border border-solid w-full rounded-xl pl-4 p-2 mb-3"></input>
        
        <label for="password" class="block text-gray-700 text-md font-bold mb-2">Password</label>
        <input type="password" id="password" name="password" placeholder="Password" class="border border-solid w-full rounded-xl pl-4 p-2 mb-1"></input>

        <div class="flex items-center justify-between mb-5">
            <span class="text-gray-500 text-xs flex items-center gap-1">
                <input name="remember" type="checkbox"> Remember me</input>
            </span>
            <a class="text-gray-500 block text-xs cursor-pointer mt-1" href={{ route('password.request')}}>Forgot password?</a>
        </div>

        @error('email')
            <div class="text-red-500 text-sm mt-1 mb-5">{{ $message }}</div>
        @enderror

        <button type="submit" class="bg-green-800 p-2 w-full text-white rounded-xl cursor-pointer">Login</button>
        <a class="text-center text-green-800 block mt-10 text-sm cursor-pointer" href={{ route('register')}}>Don't have an account? Register</a>
    </form>
@endsection
