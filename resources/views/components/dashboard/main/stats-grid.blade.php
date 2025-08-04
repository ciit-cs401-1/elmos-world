{{-- 
    Main Dashboard Statistics Cards Component
    
    Props:
    - stats: Array containing statistics data
--}}

@props(['stats'])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Posts Card -->
    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-lg" style="background-color: rgba(0, 130, 54, 0.1);">
                <svg class="w-6 h-6" style="color: #008236;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Posts</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_posts'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Total Users Card -->
    @admin
    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-lg" style="background-color: rgb(219, 234, 254);">
                <svg class="w-6 h-6" style="color: rgb(37, 99, 235);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_users'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Contributors Card -->
    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-lg" style="background-color: rgb(243, 232, 255);">
                <svg class="w-6 h-6" style="color: rgb(147, 51, 234);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Contributors</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_contributors'] ?? 0) }}</p>
            </div>
        </div>
    </div>
    @endadmin

   
    @role('C')
    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-lg" style="background-color: rgb(255, 244, 213);">
                <svg class="w-6 h-6" style="color: rgb(234, 88, 12);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Drafts</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_drafts'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-lg" style="background-color: rgb(233, 255, 213);">
                <svg class="w-6 h-6" style="color: rgb(40, 115, 47);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Published</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_published'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-lg" style="background-color: rgb(255, 213, 213);">
                <svg class="w-6 h-6" style="color: rgb(163, 29, 29);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Inactive</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_inactive'] ?? 0) }}</p>
            </div>
        </div>
    </div>
    @endrole
</div>
