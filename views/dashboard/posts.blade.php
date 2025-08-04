@extends('layouts.dashboard')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <x-dashboard.main.page-header 
        title="Posts Management" 
        subtitle="Manage all your blog posts, categories, and content." 
        icon="posts" 
    />

    <!-- Posts Content -->
    <x-dashboard.posts.content 
        :posts="$posts ?? collect()"
        :categories="$categories ?? collect()"
        :currentFilter="request('status')"
        :currentSearch="request('search')"
        :currentCategory="request('category')"
    />
</div>
@endsection
