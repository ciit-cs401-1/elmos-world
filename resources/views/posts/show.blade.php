@extends('layouts.master')
    
@section('content')

<head>
    {{-- My Scripts for this view --}}
    @vite('resources/js/michael_edit_comment.js') 
</head>

<a class="flex items-center gap-2 px-15 mb-10 mt-5 text-green-800 cursor-pointer" href="{{route('landing')}}"><x-ri-arrow-left-long-line class="h-5"/>Back to home</a>

<div class="px-15 mt-5">
    <div class="text-4xl font-semibold mt-4 mb-1">{{ $post->title }}</div>
    <div class="flex gap-5 text-gray-600">
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
    <div class="mb-5 mr-3 text-xs flex flex-wrap items-center gap-1 block text-gray-500">
        Tags: 
        @foreach ($post->tags as $tag)
        <span class="px-2 py-1 rounded-full bg-gray-200">
            {{ $tag->tag_name }}
            </span>
        @endforeach
    </div>
    <div class="rounded-2xl h-90 mb-10 bg-cover bg-center" style="background-image: url('{{ $post->media->first()?->url }}')"></div>
    <div class="leading-7">
    {{$post->content}}
    </div>
</div>

<div class="px-15 mb-10 mt-10 max-w-2xl mx-auto space-y-8">

    <!-- 💬 Comment Form -->


    <form action="{{ route('comments.store') }}" method="POST">
        @csrf
        <div class="space-y-3">
            {{-- Get the user's content and leave --}}
            <h2 class="text-xl font-semibold">Leave a Comment</h2>
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <textarea
                name="comment_context"
                placeholder="Write your comment here..."
                class="w-full h-28 p-4 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            ></textarea>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-green-800 text-white rounded-2xl transition cursor-pointer">
                    Post Comment
                </button>
            </div>
        </div>
    </form>

    

    <!-- The Comment Section  -->
    <div class="flex gap-4">
        <div class="flex-1">

            

            {{-- Display my own comments first --}}
            @foreach ($myComments as $comment)
                <div class="mb-10">
                    <div class="flex items-center justify-between w-full mb-2">
                        <img
                            src="https://i.pravatar.cc/40?u=1"
                            alt="User avatar"
                            class="w-10 h-10 rounded-full"
                        />
                        
                        <div class="flex space-x-1 text-gray-400">
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"><x-heroicon-s-trash class="h-5 w-5 text-gray-400 cursor-pointer transition-colors hover:text-red-500" /></button>
                            </form>
                            
                            <button type="submit" title="Edit Comment" id="initiate-edit-comment-{{ $comment->id }}">
                                <x-heroicon-s-cog class="h-5 w-5 text-gray-400 cursor-pointer transition-colors hover:text-blue-500"/>
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-sm">{{ $comment->users->name }}</h3>
                        <span class="flex gap-1 items-center text-sm">
                            <svg class="h-2 w-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ date('M d, Y', strtotime($comment->created_at)) }}
                        </span>
                    </div>

                    <div id="comment-{{ $comment->id }}">
                        <div class="mt-1 text-gray-700 text-sm">
                            <p>{{ $comment->comment_context }}</p>
                        </div>
                        {{-- Update Comment Form --}}
                        <form action="{{ route('comments.update', $comment->id) }}" method="POST" class="hidden | columns-1">
                            @csrf
                            @method('PUT')
                            <textarea
                                name="updated_comment_context"
                                placeholder="Write your comment here..."
                                class="w-full h-28 p-4 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >{{$comment->comment_context}}</textarea>
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-green-800 text-white rounded-2xl transition cursor-pointer">
                                    Update Comment
                                </button>
                            </div>
                        </form>
                    </div>
                                    
                    {{-- Reply button for my comments --}}
                    <div class="">
                        <form action="{{ route('comments.reply') }}" method="POST" class="flex justify-end mt-2">
                            @csrf
                            <input type="hidden" name="target_comment_id border border-black" value="{{ $comment->id }}">
                            <textarea name="comment_context" required class="w-full border border-black"></textarea>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Add a reply to this comment
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="flex items-center my-10">
                <hr class="flex-grow border-t border-gray-300" />
                <span class="px-4 text-gray-500 text-sm">Below are other's Comments</span>
                <hr class="flex-grow border-t border-gray-300" />
            </div>


            {{-- Display other users' comments --}}
            @foreach ($othersComments as $comment)
                <div class="mb-10">
                    <img
                        src="https://i.pravatar.cc/40?u={{ $comment->user_id }}"
                        alt="User avatar"
                        class="w-10 h-10 rounded-full mb-2"
                    />
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-sm">{{ $comment->users->name }}</h3>
                        <span class="flex gap-1 items-center text-sm">
                            <svg class="h-2 w-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ date('M d, Y', strtotime($comment->created_at)) }}
                        </span>
                    </div>
                    <p class="mt-1 text-gray-700 text-sm">
                        {{ $comment->comment_context }}
                    </p>

                    {{-- Reply button --}}
                    <form action="{{ route('comments.reply') }}" method="POST" class="flex justify-end mt-2">
                        @csrf
                        <input type="hidden" name="target_comment_id" value="{{ $comment->id }}">
                        <textarea name="comment_context" required class="w-full border border-black"></textarea>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Add a reply to this comment
                        </button>
                    </form>
                </div>
            @endforeach

            @if ($othersComments->isEmpty() && $myComments->isEmpty())
                <div class="flex justify-center items-center py-10">
                    <span class="text-lg text-gray-500">There are no comments yet...</span>
                </div>
            @endif
        </div>
    </div>
</div>

<hr class="mt-20 text-gray-300">

<div class="px-20 text-2xl font-bold mt-10 text-center">Other Articles</div>
<div class="px-20 grid grid-cols-3 gap-10 mt-5 mb-30">
    @foreach ($others as $other)
        <a class="cursor-pointer shadow-md p-5 rounded-2xl transition hover:scale-105" href="{{ route('posts.show', $other->id) }}" >
            <div class="h-[200px] rounded-2xl mb-2 bg-cover bg-center mb-5" style="background-image: url('{{ $post->media->first()?->url }}')"></div>
            <div class="my-2 mr-3 text-xs flex flex-wrap gap-1">
            @foreach ($other->categories as $category)
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
        <div class="text-2xl font-semibold">{{$other->title}}</div>
        </a>
    @endforeach
</div>





@endsection


