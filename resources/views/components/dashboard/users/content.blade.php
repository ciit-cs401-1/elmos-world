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

<div class="bg-white rounded-t-lg shadow-sm border border-gray-100">
    <!-- Users Content (Filters Section) -->
    <div class="p-6 border-b border-gray-100">
        <x-dashboard.users.filters 
            :roles="$roles"
            :currentRole="$currentRole"
            :searchQuery="$searchQuery"
        />
    </div>
    
    <!-- Users Table Section -->
    <div class="overflow-x-auto bg-white rounded-b-lg shadow-sm border border-gray-100">
        <x-dashboard.users.table :users="$users" :roles="$roles" />
    </div>
</div>

