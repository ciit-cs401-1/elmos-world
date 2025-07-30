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



