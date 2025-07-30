{{-- 
    Users Management Section Component
    
    Props:
    - users: Collection of users
    - roles: Collection of roles for filtering
    - currentRole: Current role filter 
    - searchQuery: Current search query 
--}}

@props([
    'users',
    'roles' => null,
    'currentRole' => null,
    'searchQuery' => ''
])

<div class="bg-white rounded-t-lg shadow-sm border border-gray-100 mb-5">
    <!-- Users Content (Filters Section) -->
    <div class="px-6 py-3 border-b border-gray-100 bg-green-700 rounded-t-lg text-white font-bold">
      Profile
    </div>
    
    <!-- Users Table Section -->
    <div class="p-6 bg-white rounded-b-lg shadow-sm border border-gray-100">
        {{$user->name}}
    </div>
</div>

