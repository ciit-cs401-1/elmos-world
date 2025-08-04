@extends('layouts.dashboard')
    
@section('content')
            
<a class="flex items-center gap-2 px-15 mb-10 mt-5 text-green-800 cursor-pointer" href="{{route('dashboard.posts')}}"><x-ri-arrow-left-long-line class="h-5"/>Back</a>

<div class="px-15 mt-5">

    <div class="flex items-center justify-between">
        <div class="text-4xl font-semibold mt-4 mb-1">{{ $post->title }}</div>
        <div class="flex gap-2">

            <a href="{{route('dashboard.posts.edit', $post->id)}}" class="bg-yellow-400 text-white px-3 py-2 rounded-2xl cursor-pointer">
                    <x-tabler-pencil class="h-5"/>
            </a>

            <form action="{{route('posts.destroy', $post->id)}}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded-2xl cursor-pointer">
                    <x-tabler-trash class="h-5"/>
                </button>  
            </form>
                  
    </div>
    </div>
    <div class="flex gap-5 text-gray-600 mb-5">
        <span class="flex items-center">
            <svg class="h-4 w-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            {{ $post->users->name }}
        </span>
        <span class="flex gap-1 items-center">
            <svg class="h-3 w-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ date('M d, Y', strtotime($post->publication_date)) }}
        </span>
        <span class="flex gap-1 items-center">
            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            {{ $post->views_count }}
        </span>   

        <div class="my-2 mr-3 text-xs flex flex-wrap gap-1">
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
        </div>
     
    </div>
    <div class="rounded-2xl h-90 mb-10 bg-cover bg-center" style="background-image: url('{{ $post->media->first()?->url }}')"></div>
    <div class="leading-7">
    {{$post->content}}
    </div>
</div>

<div class="px-15 mb-10 mt-10 max-w-2xl mx-auto space-y-8">
    
    <h2 class="text-xl font-semibold">Comments</h2>
    <div class="flex gap-4">
        <div class="flex-1">            
            @foreach ($comments as $comment)
                @if (auth()->user()->id != $comment->user_id)
                <div class="mb-10">
                    <img
                        src="https://i.pravatar.cc/40?u=1"
                        alt="User avatar"
                        class="w-10 h-10 rounded-full mb-2"
                    />
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-sm">{{$comment->users->name}}</h3>
                        <span class="flex gap-1 items-center text-sm">
                            <svg class="h-2 w-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ date('M d, Y', strtotime($comment->created_at)) }}
                        </span> 
                    </div>
                    <p class="mt-1 text-gray-700 text-sm">
                        {{$comment->comment_context}}
                    </p>
                </div>
                @endif
            @endforeach

            @if ($comments->isEmpty())
                <div class="flex justify-center items-center py-10">
                    <span class="text-lg text-gray-500">There are no comments yet...</span>
                </div>
            @endif
        </div>
    </div>
</div>


@endsection
