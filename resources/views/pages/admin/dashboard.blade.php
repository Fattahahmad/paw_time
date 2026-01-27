@extends('layouts.admin')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, Admin! Here\'s what\'s happening today.')

@section('content')
    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="stat-card bg-gradient-to-br from-cyan-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Total Users</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['totalUsers'] }}</p>
                    <p class="text-xs text-white/70 mt-1">+{{ $stats['newUsersThisWeek'] }} this week</p>
                </div>
                <div class="text-4xl">👥</div>
            </div>
        </div>
        
        <div class="stat-card bg-gradient-to-br from-orange-500 to-red-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Total Pets</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['totalPets'] }}</p>
                </div>
                <div class="text-4xl">🐾</div>
            </div>
        </div>
        
        <div class="stat-card bg-gradient-to-br from-green-500 to-emerald-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Total Appointments</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['totalAppointments'] }}</p>
                    <p class="text-xs text-white/70 mt-1">{{ $stats['pendingAppointments'] }} pending</p>
                </div>
                <div class="text-4xl">📅</div>
            </div>
        </div>
        
        <div class="stat-card bg-gradient-to-br from-purple-500 to-pink-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Health Checks</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['totalHealthChecks'] }}</p>
                </div>
                <div class="text-4xl">❤️</div>
            </div>
        </div>
    </div>

    {{-- Appointment Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-600 font-medium">Pending</p>
                    <p class="text-2xl font-bold text-yellow-700">{{ $stats['pendingAppointments'] }}</p>
                </div>
                <span class="text-3xl">⏳</span>
            </div>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600 font-medium">Confirmed</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $stats['confirmedAppointments'] }}</p>
                </div>
                <span class="text-3xl">✅</span>
            </div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-600 font-medium">Completed</p>
                    <p class="text-2xl font-bold text-green-700">{{ $stats['completedAppointments'] }}</p>
                </div>
                <span class="text-3xl">✔️</span>
            </div>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-600 font-medium">Export Data</p>
                    <a href="{{ route('admin.dashboard.export') }}" class="text-sm text-purple-700 hover:text-purple-900 font-medium underline">Download CSV</a>
                </div>
                <span class="text-3xl">📊</span>
            </div>
        </div>
    </div>

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- User Growth Chart --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">User Growth</h3>
                <select class="admin-select text-sm px-3 py-2 border border-gray-200 rounded-lg">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 90 days</option>
                </select>
            </div>
            <div class="h-64 bg-gradient-to-br from-[#68C4CF]/5 to-[#68C4CF]/20 rounded-xl flex items-center justify-center">
                <div class="text-center">
                    <span class="text-4xl mb-2 block">📈</span>
                    <p class="text-gray-500 text-sm">Chart visualization here</p>
                </div>
            </div>
        </div>

        {{-- Pet Categories --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Pet Categories</h3>
            </div>
            <div class="space-y-4">
                @foreach($stats['petCategories'] as $category)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br {{ $category['color'] }} rounded-xl flex items-center justify-center">
                            <span class="text-2xl">{{ $category['emoji'] }}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $category['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $category['count'] }} pets</p>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-600">{{ $category['percentage'] }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent Activity Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Recent Activities</h3>
            <p class="text-sm text-gray-500">Latest updates from your users</p>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivities as $activity)
                    <tr>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#68C4CF] to-[#5AB0BB] rounded-xl flex items-center justify-center">
                                    <span class="text-white font-semibold">{{ strtoupper(substr($activity['user'], 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $activity['user'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($activity['type'] === 'appointment')
                                <span class="badge badge-info">{{ ucfirst($activity['action']) }}</span>
                            @elseif($activity['type'] === 'user')
                                <span class="badge badge-success">{{ ucfirst($activity['action']) }}</span>
                            @elseif($activity['type'] === 'pet')
                                <span class="badge badge-warning">{{ ucfirst($activity['action']) }}</span>
                            @else
                                <span class="badge badge-gray">{{ ucfirst($activity['action']) }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-gray-700">{{ $activity['description'] }}</span>
                        </td>
                        <td>
                            <span class="text-gray-600">{{ $activity['date']->diffForHumans() }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500">
                            No recent activities.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
