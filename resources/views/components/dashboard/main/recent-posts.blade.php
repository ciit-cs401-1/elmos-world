{{-- 
    Main Dashboard Recent Posts Component
    
    Props:
    - posts: Collection of recent posts
    - viewAllUrl: URL for "View all" link
--}}

@props([
    'posts',
    'viewAllUrl' => null
])

<div class="bg-white rounded-lg shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Recent Posts</h3>
            @if($viewAllUrl)
                <a href="{{ $viewAllUrl }}" class="text-sm font-medium" style="color: #008236;" onmouseover="this.style.color='#006d2c'" onmouseout="this.style.color='#008236'">
                    View all →
                </a>
            @endif
        </div>
    </div>
    <div class="p-6">
        @if($posts && $posts->count() > 0)
            <div class="recent-posts-scroll space-y-4">
                @foreach($posts as $post)
                    <div class="flex items-start space-x-4 p-4 rounded-lg border border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(0, 130, 54, 0.1);">
                            <svg class="w-5 h-5" style="color: #008236;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">{{ $post->title }}</h4>
                            <div class="flex items-center text-xs text-gray-500 space-x-4">
                                <span>by {{ $post->user->name ?? 'Unknown' }}</span>
                            </div>
                            @if($post->categories && $post->categories->count() > 0)
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: rgba(0, 130, 54, 0.1); color: #008236;">
                                        {{ $post->categories->first()->category_name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $post->status === 'P' ? '' : ($post->status === 'D' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}" 
                                  style="{{ $post->status === 'P' ? 'background-color: rgba(0, 130, 54, 0.1); color: #008236;' : '' }}">
                                {{ $post->status === 'P' ? 'Published' : ($post->status === 'D' ? 'Draft' : 'Inactive') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No recent posts found.</p>
            </div>
        @endif
    </div>
</div>
