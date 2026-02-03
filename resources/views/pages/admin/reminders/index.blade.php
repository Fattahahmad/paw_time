@extends('layouts.admin')

@section('page-title', 'Reminders Management')
@section('page-subtitle', 'Manage all pet reminders')

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-gradient-to-br from-cyan-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Total Reminders</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="text-4xl">🔔</div>
            </div>
        </div>
        <div class="stat-card bg-gradient-to-br from-yellow-500 to-orange-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Pending</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['pending'] }}</p>
                </div>
                <div class="text-4xl">⏳</div>
            </div>
        </div>
        <div class="stat-card bg-gradient-to-br from-green-500 to-emerald-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Completed</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['done'] }}</p>
                </div>
                <div class="text-4xl">✅</div>
            </div>
        </div>
        <div class="stat-card bg-gradient-to-br from-red-500 to-pink-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Overdue</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['overdue'] }}</p>
                </div>
                <div class="text-4xl">⚠️</div>
            </div>
        </div>
    </div>

    {{-- Reminders Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">All Reminders</h3>
                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600 mt-1">{{ $reminders->total() }} total</span>
                </div>
                <a href="{{ route('admin.reminders.create') }}" class="btn-primary">
                    <span class="mr-2">➕</span> Add Reminder
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="admin-checkbox rounded"></th>
                        <th>Reminder</th>
                        <th>User</th>
                        <th>Category</th>
                        <th>Due Date</th>
                        <th>Repeat</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reminders as $reminder)
                        <tr class="hover:bg-gray-50 transition {{ $reminder->is_overdue ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="admin-checkbox rounded">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br {{ $reminder->category_color }} rounded-xl flex items-center justify-center">
                                        <span class="text-lg">{{ $reminder->category_icon }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $reminder->title }}</p>
                                        <p class="text-xs text-gray-500 line-clamp-1">{{ Str::limit($reminder->description, 40) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-800">{{ $reminder->user->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">{{ $reminder->user->email ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $reminder->category_color }}">
                                    {{ ucfirst($reminder->category) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm {{ $reminder->is_overdue ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    {{ $reminder->remind_date?->format('d M Y') }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $reminder->remind_date?->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                                    {{ ucfirst(str_replace('_', ' ', $reminder->repeat_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'done' => 'bg-green-100 text-green-700',
                                        'skipped' => 'bg-gray-100 text-gray-600',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$reminder->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($reminder->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.reminders.show', $reminder) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.reminders.edit', $reminder) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.reminders.destroy', $reminder) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="text-4xl mb-2">🔔</span>
                                    <p>No reminders found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reminders->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $reminders->links() }}
            </div>
        @endif
    </div>
@endsection
