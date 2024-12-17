@extends('layouts.app')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h1>Create New User</h1>
    <form id="createUserForm" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Name
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Maximum 250 characters</span>
                </div>
            </label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="username">Username
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Maximum 250 characters</span>
                </div>
            </label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Format: @fe.up.pt <br> Maximum 250 characters</span>
                </div>
            </label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Password
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Minimum 8 characters</span>
                </div>
            </label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Description
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Maximum 255 characters</span>
                </div>
            </label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="photo">Profile Picture
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">File type: jpg, jpeg, png, gif <br> Maximum size: 2MB</span>
                </div>
            </label>
            <input type="file" name="photo" id="photo" class="form-control">
        </div>

        <div class="form-group">
            <label for="role">Role
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Select the role of the user</span>
                </div>
            </label>
            <select name="role" id="role" class="form-control" required>
                <option value="user">User</option>
                <option value="moderator">Moderator</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
</div>
@endsection