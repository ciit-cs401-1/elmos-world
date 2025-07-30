{{-- 
    Posts Management Table Component
    
    Props:
    - posts: Collection of posts to display
--}}

@props([
    'posts'
])

<script>
function updateStatus(postId, status) {
    fetch(`posts/${postId}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status })

    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Failed to update status');
        }
    })
    .catch(err => console.error(err));
}
</script>



<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                ID
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Title
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Author
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Categories
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date
            </th>
            <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">Actions</span>
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @if($posts && $posts->count() > 0)
            @foreach($posts as $post)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">{{ $post->id }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $post->title }}
                        </div>
                        @if($post->excerpt)
                            <div class="text-sm text-gray-500 mt-1">
                                {{ Str::limit($post->excerpt, 60) }}
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-gray-600">
                                    {{ strtoupper(substr($post->users ? $post->users->name : 'N/A', 0, 1)) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-900">{{ $post->users ? $post->users->name : 'Unknown' }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs flex flex-wrap">
                        @foreach ($post->categories as $category)
                                    <span class="px-2 py-1 rounded-full text-white
                                        @switch($category->category_name)
                                            @case('Budgeting & Savings') bg-green-600 @break
                                            @case('Investing') bg-blue-600 @break
                                            @case('Debt & Credit') bg-red-600 @break
                                            @case('Financial Planning') bg-purple-600 @break
                                            @case('Career & Income') bg-yellow-500 text-black @break
                                            @default bg-gray-500 @break
                                        @endswitch
                                    ">
                                        {{ $category->category_name }}
                                    </span>
                        @endforeach
                          
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <select 
                            onchange="updateStatus({{ $post->id }}, this.value)" 
                            class="border-gray-300 rounded text-xs
                                @if($post->status === 'P') bg-green-100 text-green-800
                                @elseif($post->status === 'D') bg-yellow-100 text-yellow-800
                                @elseif($post->status === 'I') bg-red-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                            
                            @if($post->status === 'P' || $post->status === 'I')
                            <option value="P" @selected($post->status === 'P')>Published</option>
                            <option value="I" @selected($post->status === 'I')>Inactive</option>
                            @else
                            <option value="P" @selected($post->status === 'P')>Published</option>
                            <option value="D" @selected($post->status === 'D')>Draft</option>
                            @endif
                        </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $post->publication_date ? $post->publication_date : 'No date' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{route('dashboard.posts.show', $post->id)}}" class="bg-indigo-600 text-white px-3 py-2 rounded-2xl cursor-pointer">
                                    <x-tabler-eye class="h-5"/>
                            </a>
                           
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No posts found</h3>
                        <p class="text-gray-500">Get started by creating your first post.</p>
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
</table>
<div class="px-10 pb-5 mt-3">
    {{ $posts->links() }}
</div>



