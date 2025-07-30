@extends('layouts.master')

@section('content')
<div class="container">
    <h1>User Details</h1>
    
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $user->name }}</h5>
            <p class="card-text">
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>Registered:</strong> {{ $user->registration_date->format('Y-m-d H:i') }}<br>
                <strong>Last Login:</strong> {{ $user->last_login_date ? $user->last_login_date->format('Y-m-d H:i') : 'Never' }}
            </p>
            
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </div>
    </div>
    
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users</a>
</div>
@endsection