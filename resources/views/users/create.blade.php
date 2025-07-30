@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New User</h1>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Roles</label>
            @foreach ($roles as $role)
                <div class="form-check">
                    <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->id }}" class="form-check-input">
                    <label for="role_{{ $role->id }}" class="form-check-label">{{ $role->role_name }}</label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
</div>
@endsection