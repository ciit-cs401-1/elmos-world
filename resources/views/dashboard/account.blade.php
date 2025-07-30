@extends('layouts.dashboard')

@section('content')
<div class="p-6">
    <div>
        <x-dashboard.main.page-header 
            title="Account Management" 
            subtitle="Manage your password and profile details" 
            icon="users" 
        />

        <!-- Profile Section -->
        <div x-data="{ editProfile: false }" class="rounded-t-lg shadow-sm border-gray-100 mb-4">
            <!-- Header -->
            <div class="px-6 py-3 border-b border-gray-100 bg-gray-200 rounded-t-lg text-gray-700 font-bold flex items-center justify-between">
                <span>Profile</span>
               <a @click.prevent="editProfile = !editProfile" 
                href="#" 
                class="bg-blue-800 text-white px-3 py-2 rounded-2xl cursor-pointer">

                    <template x-if="!editProfile">
                        <x-tabler-pencil class="h-5"/>
                    </template>
                    
                    <template x-if="editProfile">
                        <x-tabler-x class="h-5"/>
                    </template>
                </a>
            </div>

            <!-- Body -->
            <div class="p-6 bg-white rounded-b-lg shadow-sm border border-gray-100">
                <form class="grid grid-cols-2 gap-y-5">
                    <div class="font-semibold">Name:</div>
                    <input x-show="editProfile" name="name" type="text" value="{{ $user->name }}"
                        class="w-full h-10 p-4 mb-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <div x-show="!editProfile">{{ $user->name }}</div>

                    <div class="font-semibold">Email:</div>
                    <input x-show="editProfile" name="email" type="text" value="{{ $user->email }}"
                        class="w-full h-10 p-4 mb-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <div x-show="!editProfile">{{ $user->email }}</div>

                    <div class="font-semibold">Registration Date:</div>
                    <div>{{ $user->registration_date }}</div>

                    <div class="col-span-2 col-start-1 flex justify-end" x-show="editProfile">
                        <button class="px-10 py-3 mt-5 bg-green-800 text-white rounded-2xl">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Password Section -->
        <div x-data="{ editPassword: false }" class="rounded-t-lg shadow-sm border border-gray-100">
            <!-- Header -->
            <div class="px-6 py-3 border-b border-gray-100 bg-gray-200 rounded-t-lg text-gray-700 font-bold flex items-center justify-between">
                <span>Password</span>
               <a @click.prevent="editPassword = !editPassword" 
                href="#" 
                class="bg-blue-800 text-white px-3 py-2 rounded-2xl cursor-pointer">

                    <template x-if="!editPassword">
                        <x-tabler-pencil class="h-5"/>
                    </template>
                    
                    <template x-if="editPassword">
                        <x-tabler-x class="h-5"/>
                    </template>
                </a>
            </div>

            <!-- Body -->
            <form class="p-6 bg-white rounded-b-lg shadow-sm border border-gray-100" x-show="editPassword">
                <input name="password" type="password" placeholder="New Password"
                    class="w-full h-10 p-4 mb-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <input name="confirm_password" type="password" placeholder="Confirm Password"
                    class="w-full h-10 p-4 mb-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <div class="col-span-2 col-start-1 flex justify-end">
                    <button class="px-10 py-3 mt-5 bg-green-800 text-white rounded-2xl">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
