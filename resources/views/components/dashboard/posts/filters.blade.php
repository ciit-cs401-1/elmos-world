{{-- 
    Posts Management Filters Component
    
    Props:
    - categories: Collection of categories for filtering
    - currentFilter: Current filter status (optional)
    - currentCategory: Current category filter (optional)
--}}

@props([
    'categories' => null,
    'currentFilter' => null,
    'currentSearch' => null,
    'currentCategory' => null
])

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <!-- Search Bar -->
    <div class="flex-1 max-w-md">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <form method="GET" action="{{ url('/dashboard/posts') }}" class="flex items-center gap-3">
                @if(request()->filled('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request()->filled('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input name="search" type="text" value="{{ request('search') }}"
                       placeholder="Search posts by title..." 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm">
            </form>
        </div>
    </div>

    <!-- Filter Controls -->
    <form method="GET" action="{{ url('/dashboard/posts') }}" class="flex items-center gap-3">
        @if(request()->filled('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
        @if(request()->filled('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        <!-- Status Filter -->
        <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
            <option value="">All Status</option>
            <option value="P" {{ $currentFilter === 'P' ? 'selected' : '' }}>Published</option>
            <option value="D" {{ $currentFilter === 'D' ? 'selected' : '' }}>Draft</option>
            <option value="I" {{ $currentFilter === 'I' ? 'selected' : '' }}>Inactive</option>
        </select>
    </form>
</div>
