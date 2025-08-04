{{-- 
    Main Dashboard Recent Users Component
    
    Props:
    - users: Collection of recent users
    - viewAllUrl: URL for "View all" link
--}}

@props([
    'users',
    'viewAllUrl' => null
])

<div class="bg-white rounded-lg shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
            @if($viewAllUrl)
                <a href="{{ $viewAllUrl }}" class="text-sm font-medium" style="color: #008236;" onmouseover="this.style.color='#006d2c'" onmouseout="this.style.color='#008236'">
                    View all →
                </a>
            @endif
        </div>
    </div>
    <div class="p-6">
        @if($users && $users->count() > 0)
            <div class="recent-users-scroll space-y-4">
                @foreach($users as $user)
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-600">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            @if($user->roles && $user->roles->count() > 0)
                                <p class="text-xs text-gray-500">
                                        @switch($user->roles->first()->role_name)
                                            @case('A') Admin @break
                                            @case('C') Contributor @break
                                            @case('S') Subscriber @break
                                            @default Invalid User @break
                                        @endswitch
                            @endif
                            <p class="text-xs text-gray-400">{{ $user->registration_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4">
                <p class="text-gray-500 text-sm">No recent users found.</p>
            </div>
        @endif
    </div>
</div>
