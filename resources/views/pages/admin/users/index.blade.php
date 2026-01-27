@extends('layouts.admin')

@section('page-title', 'Users Management')
@section('page-subtitle', 'Manage all users and their accounts')

@section('content')
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Users</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">👥</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">New This Week</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['newThisWeek'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📈</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800">All Users</h3>
                <p class="text-sm text-gray-500">{{ $users->total() }} total users</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-primary">
                <span class="text-lg">+</span> Add User
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Pets</th>
                        <th>Join Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#68C4CF] to-[#5AB0BB] rounded-xl flex items-center justify-center">
                                    <span class="text-white font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-gray-600">{{ $user->email }}</span>
                        </td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-info">Admin</span>
                            @else
                                <span class="badge badge-gray">User</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center space-x-1">
                                <span>🐾</span>
                                <span class="text-gray-700 font-medium">{{ $user->pets_count }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-gray-600">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            No users found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-6 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
@endsection
