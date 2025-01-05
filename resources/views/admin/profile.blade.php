@extends('layouts.admin')

@section('title', 'Manage My Account')

@section('content')
<div class="profile-dashboard-container">
    <div class="header">
        <h2>Manage My Account</h2>
        <p>Update your profile information and settings.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="profile-info">
        <div class="profile-picture-container">
            <img src="{{ asset('storage/public/avatars/' . $user->avatar) }}" alt="Profile Picture" class="profile-picture">
            <button type="button" class="btn btn-dark btn-sm mt-2 update-picture-btn" data-toggle="modal" data-target="#updateAvatarModal">
                Update Profile Picture
            </button>
        </div>
        <div class="profile-details">
            <p><strong>First Name:</strong> {{ $user->first_name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>
    </div>

    <div class="action-buttons">
        <!-- Edit Profile Button -->
        <button type="button" class="btn btn-dark action-btn mt-3" data-toggle="modal" data-target="#editProfileModal">
            Edit Profile
        </button>

        <!-- Change Password Button -->
        <button type="button" class="btn btn-dark action-btn mt-3" data-toggle="modal" data-target="#changePasswordModal">
            Change Password
        </button>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $user->first_name }}" required>
                            @error('first_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-dark">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                            @error('current_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                            @error('new_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Avatar Modal -->
    <div class="modal fade" id="updateAvatarModal" tabindex="-1" role="dialog" aria-labelledby="updateAvatarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateAvatarModalLabel">Update Profile Picture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.profile.update-picture') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="avatar">Choose a new profile picture</label>
                            <input type="file" name="avatar" id="avatar" class="form-control" required>
                            @error('avatar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-dark">Update Avatar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
