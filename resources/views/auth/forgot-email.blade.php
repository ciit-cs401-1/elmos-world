@extends('layouts.auth')
@section('title', 'login')

@section('content')
    <form action="{{route('password.email')}}" method="POST" class="bg-white rounded-2xl border border-solid border-gray-500 px-10 py-15 m-10 sm:m-0"> 
        <h1 class="font-semibold text-green-700 text-2xl">Think Finance!</h1>
        <p class="text-lg mb-4 text-gray-400">Forgot Password</p>
        @csrf
    
        <label for="Email" class="block text-gray-700 text-md font-bold mb-2">Email</label>
        <input type="email" id="email" name="email" placeholder="Email" class="border border-solid w-full rounded-xl pl-4 p-2 mb-3"></input>

        @error('email')
            <div class="text-red-500 text-sm mt-1 mb-5">{{ $message }}</div>
        @enderror

        <button type="submit" class="bg-green-800 p-2 w-full text-white rounded-xl cursor-pointer mt-3">Confirm Email</button>
        <a class="text-green-800 block mt-10 text-sm cursor-pointer" href={{ route('login')}}><- Back</a>
    </form>
@endsection
