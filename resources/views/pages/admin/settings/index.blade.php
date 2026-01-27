@extends('layouts.admin')

@section('page-title', 'Settings')
@section('page-subtitle', 'Manage application settings')

@section('content')
    <div class="max-w-3xl">
        {{-- Profile Settings --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800">Profile Settings</h3>
                <p class="text-sm text-gray-500">Update your account information</p>
            </div>

            <form action="{{ route('admin.settings.update-profile') }}" method="POST">
                @csrf

                <div class="space-y-4 mb-6">
                    <div class="form-group">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', auth()->user()->name) }}" placeholder="Your name" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', auth()->user()->email) }}" placeholder="Your email" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button type="submit" class="btn-primary">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>

        {{-- Password Settings --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800">Change Password</h3>
                <p class="text-sm text-gray-500">Update your password for security</p>
            </div>

            <form action="{{ route('admin.settings.update-password') }}" method="POST">
                @csrf

                <div class="space-y-4 mb-6">
                    <div class="form-group">
                        <label class="form-label">Current Password *</label>
                        <input type="password" name="current_password" class="form-input" placeholder="Enter current password" required>
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password *</label>
                        <input type="password" name="password" class="form-input" placeholder="Enter new password" required>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm new password" required>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        Change Password
                    </button>
                </div>
            </form>
        </div>

        {{-- App Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Application Information</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Application Name</span>
                    <span class="font-medium text-gray-800">Paw Time</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Version</span>
                    <span class="font-medium text-gray-800">1.0.0</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Laravel Version</span>
                    <span class="font-medium text-gray-800">{{ app()->version() }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-gray-600">PHP Version</span>
                    <span class="font-medium text-gray-800">{{ phpversion() }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
