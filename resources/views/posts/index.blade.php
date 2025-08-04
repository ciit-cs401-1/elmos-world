@extends('layouts.master')
    
@section('content')

<div class="flex gap-15 mt-5">
    <div class="w-[30%]">
        <div class="text-2xl font-bold">Categories</div>
        <div class="grid grid-cols-1 gap-2 mt-5">

            <a class="font-bold border border-gray-300 rounded-2xl mb-2 p-5 cursor-pointer text-gray text-left transition ease-in-out hover:translate-x-4 {{ request('category') === null ? 'bg-green-800 text-white font-semibold' : 'text-gray-700' }}"
                href="{{ route('posts.index') }}">
                All
            </a>

            @foreach ($categories as $category)
                <a
                    href="{{ route('posts.index', ['category' => $category->slug]) }}"
                    class="font-bold border border-gray-300 rounded-2xl mb-2 p-5 cursor-pointer text-left transition ease-in-out hover:translate-x-4 hover:bg-green-800 hover:text-white
                        {{ request('category') === $category->slug ? 'bg-green-800 text-white font-semibold' : 'text-gray-700' }}">
                    {{ $category->category_name }}
                </a>
            @endforeach
           
        </div>
    </div>
    <div class="w-[70%]">
        <div>
            <div class="grid grid-cols-2 gap-7 mt-5">
                @foreach ($posts as $post)
                <div class="cursor-pointer shadow-md rounded-2xl p-5 mb-2 transition ease-in-out hover:scale-102">
                        <div class="h-[200px] rounded-2xl mb-2 bg-cover bg-center"
                            style="background-image: url('{{ $post->media->first()?->url }}')">
                        </div>                    
                        <div class="text-2xl font-semibold">{{$post->title}}</div>
                        <div class="flex gap-3 text-gray-600 my-2 text-xs">
                            <span class="flex items-center"><x-css-profile class="h-4"/>{{ $post->users->name}}</span>
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
                        </div>
                        <div class="text-black mb-4">{{Str::limit($post->content,150)}}</div>
                        <div class="w-full mt-10 mb-2">
                            <a class="text-white bg-green-700 py-3 px-5 rounded-xl cursor-pointer transition ease-in-out hover:bg-green-900" href="{{ route('posts.show', $post->id) }}">Read more</a>
                        </div>                    
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
          
        </div>
    </div>
    
</div>

@endsection
