{{-- Example of using role directives in a blade template --}}

<div class="sidebar-menu">
    <ul>
        <li><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li><a href="{{ route('dashboard.posts') }}">Posts</a></li>
        <li><a href="{{ route('dashboard.analytics') }}">Analytics</a></li>
        
        @admin
            {{-- Only visible to administrators --}}
            <li><a href="{{ route('dashboard.users') }}">User Management</a></li>
        @endadmin
    </ul>
</div>

{{-- Alternative example using @role directive --}}
@role('A')
    <div class="admin-panel">
        <h3>Administrative Tools</h3>
        <ul>
            <li><a href="{{ route('roles.index') }}">Role Management</a></li>
            <li><a href="{{ route('dashboard.users') }}">User Management</a></li>
        </ul>
    </div>
@endrole

{{-- For both admin and contributor --}}
@role('A,C')
    <div class="content-management">
        <h3>Content Tools</h3>
        <ul>
            <li><a href="{{ route('posts.create') }}">Create New Post</a></li>
            <li><a href="{{ route('categories.index') }}">Manage Categories</a></li>
        </ul>
    </div>
@endrole
