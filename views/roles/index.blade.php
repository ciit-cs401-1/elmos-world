@extends('layouts.dashboard')

@section('content')
<div class="m-10">
    <div class="mb-5 flex items-end justify-between">
        <h1 class="font-semibold text-2xl">Roles</h1>
        <a href="{{route('roles.create')}}" class="bg-blue-500 text-white px-5 py-2 rounded-2xl cursor-pointer flex gap-2 items-center"><x-tabler-plus class="h-5"/> Add Role</a>
    </div>
    <div class="grid grid-cols-[1fr_2fr_4fr_4fr] bg-white rounded-lg shadow">
        <div class="bg-gray-100 p-5 font-semibold text-center">ID</div>
        <div class="bg-gray-100 p-5 font-semibold text-center">Role Name</div>
        <div class="bg-gray-100 p-5 font-semibold text-center">Description</div>
        <div class="bg-gray-100 p-5 font-semibold text-center">Actions</div>

        @foreach ($roles as $role)
        <div class="border-b-1 border-gray-200 p-5 text-center">{{$role->id}}</div>
        <div class="border-b-1 border-gray-200 p-5 text-center">{{$role->formal_name}}</div>
        <div class="border-b-1 border-gray-200 p-5">{{$role->description}}</div>
        <div class="border-b-1 border-gray-200 p-5 text-center">
            <div class="flex flex-wrap items-center justify-center gap-2">
                <a href="{{route('roles.edit', $role->id)}}" class="bg-yellow-400 text-white px-3 py-2 rounded-2xl cursor-pointer">
                    <x-tabler-pencil class="h-5"/>
                </a>

                <form action="{{route('roles.destroy', $role->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded-2xl cursor-pointer">
                        <x-tabler-trash class="h-5"/>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection