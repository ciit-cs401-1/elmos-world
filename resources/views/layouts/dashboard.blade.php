<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page-title', 'Dashboard') - Think Finance!</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard-styles.css', 'resources/js/app.js'])
    @endif
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 dashboard-layout">
    <div class="flex h-full">
        <!-- Sidebar -->
        <div class="sidebar bg-white shadow-sm border-r border-gray-200 flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: #008236;">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Think Finance!</h1>
                        <p class="text-sm text-gray-500">Admin Dashboard</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-700 hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('dashboard') ? 'background-color: #008236;' : '' }}">
                    <x-heroicon-o-chart-bar class="w-5 h-5" />
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('dashboard.posts') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.posts') ? 'text-white' : 'text-gray-700 hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('dashboard.posts') ? 'background-color: #008236;' : '' }}">
                    <x-heroicon-o-document-text class="w-5 h-5" />
                    <span class="font-medium">Posts Management</span>
                </a>

                <a href="{{ route('dashboard.users') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.users') ? 'text-white' : 'text-gray-700 hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('dashboard.users') ? 'background-color: #008236;' : '' }}">
                    <x-heroicon-o-users class="w-5 h-5" />
                    <span class="font-medium">User Management</span>
                </a>

                <a href="{{ route('roles.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('roles.index') ? 'text-white' : 'text-gray-700 hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('roles.index') ? 'background-color: #008236;' : '' }}">
                    <x-heroicon-o-tag class="w-5 h-5" />
                    <span class="font-medium">Role List</span>
                </a>

                <a href="{{ route('categories.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('categories.index') ? 'text-white' : 'text-gray-700 hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('categories.index') ? 'background-color: #008236;' : '' }}">
                    <x-heroicon-m-paper-clip class="w-5 h-5" />
                    <span class="font-medium">Category List</span>
                </a>

                <a href="{{ route('home') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                    <x-heroicon-c-arrow-left class="w-5 h-5" />
                    <span class="font-medium">Back to Site</span>
                </a>
            </nav>

            <!-- User Info -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: rgba(0, 130, 54, 0.1);">
                            <svg class="w-4 h-4" style="color: #008236;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ auth()->user()->name ?? 'Admin User' }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">Administrator</p>
                        </div>
                    </div>
                    
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="ml-2">
                        @csrf
                        <button type="submit" 
                                class="relative group p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-all duration-200" 
                                title="Logout">
                                <x-heroicon-o-arrow-left-start-on-rectangle class="w-5 h-5" />
                            <!-- Tooltip -->
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                Logout
                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Page Content -->
            <main class="flex-1 p-6 dashboard-content">
                @include('layouts.flash-messages')
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Floating Action Button -->
    <a href="{{ route('posts.create') }}" class="fab" title="Create New Post">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </a>
</body>
</html>
