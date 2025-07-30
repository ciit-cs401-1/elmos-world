@extends('layouts.app')

@section('content')
<div class="container">
    <h1>User Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $user->name }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="card-text"><strong>Roles:</strong> {{ $user->roles->pluck('role_name')->join(', ') }}</p>
            <p class="card-text"><strong>Created At:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}</p>
            <p class="card-text"><strong>Last Updated:</strong> {{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection