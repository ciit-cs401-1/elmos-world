{{-- 
    Users Management Table Component
    
    Props:
    - users: Collection of users to display
--}}

@props([
    'users',
    'roles' => null
])

<table class="w-full divide-y divide-gray-200" style="width: 100%;">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                ID
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                User Information
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Email
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Roles
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Posts
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
            </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @if($users && $users->count() > 0)
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $user->id }}</span>
                        </td>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 bg-gray-200 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-500">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            <div class="text-gray-900">{{ $user->email }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            @if($user->roles && $user->roles->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($user->roles as $role)
                                        @php
                                            $roleName = strtolower($role->role_name);
                                            // For debugging - uncomment if needed
                                            // dd($roleName, $role->display_name, $role);
                                        @endphp
                                        
                                        @if($roleName == 'admin' || $roleName == 'a')
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-red-100 text-red-800 ring-1 ring-inset ring-red-500/30">
                                                {{ $role->display_name ?? $role->role_name }}
                                            </span>
                                        @elseif($roleName == 'contributor' || $roleName == 'c')
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 ring-1 ring-inset ring-blue-500/30">
                                                {{ $role->display_name ?? $role->role_name }}
                                            </span>
                                        @elseif($roleName == 'subscriber' || $roleName == 's')
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 ring-1 ring-inset ring-yellow-500/30">
                                                {{ $role->display_name ?? $role->role_name }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                                {{ $role->display_name ?? $role->role_name }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                    No Role
                                </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            {{ $user->posts ? $user->posts->count() : 0 }} posts
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <div class="flex justify-end gap-3">
                                <div class="relative">
                                    <button type="button" 
                                           onclick="toggleRoleDropdown({{ $user->id }})"
                                           class="bg-blue-600 text-white px-3 py-2 rounded-2xl cursor-pointer flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    
                                    <!-- Role Dropdown -->
                                    <div id="roleDropdown-{{ $user->id }}" class="absolute right-0 mt-2 z-10 hidden bg-white rounded-md shadow-lg p-3 border border-gray-200 w-52">
                                        <div class="text-sm font-medium text-gray-900 mb-2">Change role for {{ $user->name }}</div>
                                        <form method="POST" action="/dashboard/users/{{ $user->id }}/role">
                                            @csrf
                                            @method('PUT')
                                            <select name="role_id" class="mb-3 w-full text-sm border-gray-300 rounded-md">
                                                @if($roles)
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" {{ $user->roles->contains('id', $role->id) ? 'selected' : '' }}>
                                                            {{ $role->display_name ?? $role->role_name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="flex justify-end">
                                                <button type="submit" class="bg-indigo-600 text-white text-xs px-3 py-1 rounded">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('dashboard.users.delete', $user) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded-2xl cursor-pointer flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                            <p class="text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Pagination - exact match with posts management -->
    @if(method_exists($users, 'links'))
        <div class="px-6 pb-5 mt-3">
            {{ $users->links() }}
        </div>
    @endif

<script>
    // Keep track of the currently open dropdown
    let currentOpenDropdown = null;
    
    // Function to toggle role dropdown
    function toggleRoleDropdown(userId) {
        const dropdownId = 'roleDropdown-' + userId;
        const dropdown = document.getElementById(dropdownId);
        
        // Close any other open dropdown first
        if (currentOpenDropdown && currentOpenDropdown !== dropdown) {
            currentOpenDropdown.classList.add('hidden');
        }
        
        // Toggle the clicked dropdown
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            currentOpenDropdown = dropdown;
        } else {
            dropdown.classList.add('hidden');
            currentOpenDropdown = null;
        }
    }
    
    // Close dropdown when clicking elsewhere
    document.addEventListener('click', function(event) {
        if (currentOpenDropdown && !event.target.closest('.relative')) {
            currentOpenDropdown.classList.add('hidden');
            currentOpenDropdown = null;
        }
    });
    
    // Close dropdown on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && currentOpenDropdown) {
            currentOpenDropdown.classList.add('hidden');
            currentOpenDropdown = null;
        }
    });
</script>
