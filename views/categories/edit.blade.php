@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto mb-10 bg-white p-6 rounded-lg shadow w-full max-w-md mt-10">
    <h1 class="text-xl font-bold text-gray-800 mb-6">Edit Role</h1>

    <!-- Display Validation Errors -->
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Whoops!</strong>
        <span class="block sm:inline">There were some problems with your input.</span>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <!-- Use PUT method for updates -->

        <div class="mb-4">
            <label for="category_name" class="block text-gray-700 text-md font-bold mb-2">Role Name</label>
            <input type="text" id="category_name" name="category_name" value="{{ old('category_name', $category->category_name) }}" required
                class="appearance-none border rounded-2xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-md font-bold mb-2">Description</label>
            <textarea type="text" id="description" name="description"
                required
                class="h-50 resize-none appearance-none border rounded-2xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500">{{ old('description', $category->description) }}
            </textarea>
        </div>

        <div class="flex justify-between items-center mt-6">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded-2xl focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                Update
            </button>
            <a href="{{ route('categories.index') }}"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded-2xl transition duration-150 ease-in-out">
                <x-tabler-x class="h-5"/>
            </a>
        </div>
    </form>
</div>
@endsection