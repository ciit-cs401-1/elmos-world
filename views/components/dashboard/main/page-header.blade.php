{{-- 
    Main Dashboard Page Header Component
    
    Props:
    - title: Page title
    - subtitle: Optional subtitle
    - icon: Optional icon name
--}}

@props([
    'title',
    'subtitle' => null,
    'icon' => null
])

<div class="page-header mb-6">
    <div class="flex items-center space-x-3">
        @if($icon)
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: #008236;">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($icon === 'dashboard')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v4"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 5v4"></path>
                    @elseif($icon === 'posts')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    @elseif($icon === 'users')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    @endif
                </svg>
            </div>
        @endif
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $title }}</h1>
            @if($subtitle)
                <p class="text-gray-600 text-sm mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</div>
