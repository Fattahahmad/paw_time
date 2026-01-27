@extends('layouts.admin')

@section('page-title', 'Health Checks Management')
@section('page-subtitle', 'Manage pet medical records')

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-gradient-to-br from-cyan-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Total Records</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="text-4xl">📋</div>
            </div>
        </div>
        <div class="stat-card bg-gradient-to-br from-green-500 to-emerald-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">This Month</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['thisMonth'] }}</p>
                </div>
                <div class="text-4xl">📅</div>
            </div>
        </div>
        <div class="stat-card bg-gradient-to-br from-yellow-500 to-orange-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Need Follow-up</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['needFollowUp'] }}</p>
                </div>
                <div class="text-4xl">🔄</div>
            </div>
        </div>
        <div class="stat-card bg-gradient-to-br from-red-500 to-pink-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Overdue Follow-up</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['overdueFollowUp'] }}</p>
                </div>
                <div class="text-4xl">⚠️</div>
            </div>
        </div>
    </div>

    {{-- Health Checks Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Medical Records</h3>
                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600 mt-1">{{ $healthChecks->total() }} records</span>
                </div>
                <a href="{{ route('admin.health-checks.create') }}" class="btn-primary">
                    <span class="mr-2">➕</span> Add Record
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="admin-checkbox rounded"></th>
                        <th>Pet</th>
                        <th>Owner</th>
                        <th>Date</th>
                        <th>Complaint</th>
                        <th>Diagnosis</th>
                        <th>Follow-up</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($healthChecks as $record)
                        <tr class="hover:bg-gray-50 transition {{ $record->is_follow_up_overdue ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="admin-checkbox rounded">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-[#68C4CF] to-[#5AB0BB] rounded-xl flex items-center justify-center">
                                        <span class="text-white text-lg">{{ $record->pet->species === 'Cat' ? '🐱' : ($record->pet->species === 'Dog' ? '🐕' : '🐾') }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $record->pet->pet_name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-500">{{ $record->pet->breed ?? 'No breed' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-800">{{ $record->pet->user->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">{{ $record->pet->user->email ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-800">{{ $record->check_date?->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $record->check_date?->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-800 line-clamp-2">{{ Str::limit($record->complaint, 40) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-800 line-clamp-2">{{ Str::limit($record->diagnosis, 40) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if ($record->next_visit_date)
                                    <p class="text-sm {{ $record->is_follow_up_overdue ? 'text-red-600 font-semibold' : 'text-gray-800' }}">
                                        {{ $record->next_visit_date->format('d M Y') }}
                                    </p>
                                    <p class="text-xs {{ $record->is_follow_up_overdue ? 'text-red-500' : 'text-gray-500' }}">
                                        {{ $record->is_follow_up_overdue ? 'Overdue' : $record->next_visit_date->diffForHumans() }}
                                    </p>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.health-checks.show', $record) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.health-checks.edit', $record) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.health-checks.destroy', $record) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                                    <span class="text-4xl mb-2">📋</span>
                                    <p>No medical records found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($healthChecks->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $healthChecks->links() }}
            </div>
        @endif
    </div>
@endsection
