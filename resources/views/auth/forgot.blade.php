@extends('layouts.auth')
@section('title', 'login')

@section('content')
    <form action="{{route('password.update')}}" method="POST" class="bg-white rounded-2xl border border-solid border-gray-500 px-10 py-15 m-10 sm:m-0"> 
        <h1 class="font-semibold text-green-700 text-2xl">Think Finance!</h1>
        <p class="text-lg mb-4 text-gray-400">Forgot Password</p>
        @csrf
    
        <label for="password" class="block text-gray-700 text-md font-bold mb-2">Password</label>
        <input type="password" id="password" name="password" placeholder="Password" class="border border-solid w-full rounded-xl pl-4 p-2 mb-3"></input>

        <label for="confirmed" class="block text-gray-700 text-md font-bold mb-2">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" class="border border-solid w-full rounded-xl pl-4 p-2 mb-3"></input>

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">


        @error('email')
            <div class="text-red-500 text-sm mt-1 mb-5">{{ $message }}</div>
        @enderror

        <button type="submit" class="bg-green-800 p-2 w-full text-white rounded-xl cursor-pointer mt-3">Forgot Password</button>
        <a class="text-green-800 block mt-10 text-sm cursor-pointer" href={{ route('login')}}><- Back</a>
    </form>
@endsection
