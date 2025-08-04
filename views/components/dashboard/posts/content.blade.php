{{-- 
    Posts Management Content Section Component
    
    Props:
    - posts: Collection of posts
    - categories: Collection of categories for filtering
    - currentFilter: Current filter status (optional)
    - currentCategory: Current category filter (optional)
--}}

@props([
    'posts',
    'categories' => null,
    'currentFilter' => null,
    'currentSearch' => null,
    'currentCategory' => null
])

<div class="bg-white rounded-lg shadow-sm border border-gray-100">
    <!-- Filters Section -->
    <div class="p-6 border-b border-gray-100">
        <x-dashboard.posts.filters 
            :categories="$categories"
            :currentFilter="$currentFilter"
            :currentCategory="$currentCategory"
            :currentSearch="$currentSearch"
        />
    </div>

    <!-- Posts Table -->
    <div class="overflow-x-auto">
        <x-dashboard.posts.table :posts="$posts" />
    </div>
</div>
