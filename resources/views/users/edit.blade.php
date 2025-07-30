@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit User</h1>

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password (leave blank to keep current)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <div class="form-group">
            <label>Roles</label>
            @foreach ($roles as $role)
                <div class="form-check">
                    <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->id }}" 
                        class="form-check-input" {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                    <label for="role_{{ $role->id }}" class="form-check-label">{{ $role->role_name }}</label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>
@endsection