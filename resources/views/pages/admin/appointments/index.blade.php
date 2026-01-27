@extends('layouts.admin')

@section('page-title', 'Appointments')
@section('page-subtitle', 'Manage all pet appointments')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div class="flex gap-2">
            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                {{ $appointments['pending']->count() }} Pending
            </span>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                {{ $appointments['confirmed']->count() }} Confirmed
            </span>
            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                {{ $appointments['completed']->count() }} Completed
            </span>
        </div>
        <a href="{{ route('admin.appointments.create') }}" class="btn-primary">
            <span class="text-lg">+</span> New Appointment
        </a>
    </div>

    {{-- Kanban Board --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Pending Column --}}
        <div class="bg-gray-50 rounded-2xl p-4">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-yellow-500">⏳</span> Pending
                <span class="ml-auto text-sm bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
                    {{ $appointments['pending']->count() }}
                </span>
            </h3>
            <div class="space-y-3">
                @forelse($appointments['pending'] as $appointment)
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $appointment->pet->pet_name ?? 'Unknown' }}</h4>
                                <p class="text-xs text-gray-500">{{ $appointment->user->name ?? 'No owner' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">{{ ucfirst($appointment->status) }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">📅 {{ $appointment->appointment_date?->format('d M Y, H:i') }}</p>
                        @if($appointment->notes)
                            <p class="text-xs text-gray-500 line-clamp-2">{{ $appointment->notes }}</p>
                        @endif
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('admin.appointments.show', $appointment) }}" class="text-xs text-blue-600 hover:underline">View</a>
                            <a href="{{ route('admin.appointments.edit', $appointment) }}" class="text-xs text-green-600 hover:underline">Edit</a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">No pending appointments</p>
                @endforelse
            </div>
        </div>

        {{-- Confirmed Column --}}
        <div class="bg-gray-50 rounded-2xl p-4">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-blue-500">✅</span> Confirmed
                <span class="ml-auto text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                    {{ $appointments['confirmed']->count() }}
                </span>
            </h3>
            <div class="space-y-3">
                @forelse($appointments['confirmed'] as $appointment)
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $appointment->pet->pet_name ?? 'Unknown' }}</h4>
                                <p class="text-xs text-gray-500">{{ $appointment->user->name ?? 'No owner' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">{{ ucfirst($appointment->status) }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">📅 {{ $appointment->appointment_date?->format('d M Y, H:i') }}</p>
                        @if($appointment->notes)
                            <p class="text-xs text-gray-500 line-clamp-2">{{ $appointment->notes }}</p>
                        @endif
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('admin.appointments.show', $appointment) }}" class="text-xs text-blue-600 hover:underline">View</a>
                            <a href="{{ route('admin.appointments.edit', $appointment) }}" class="text-xs text-green-600 hover:underline">Edit</a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">No confirmed appointments</p>
                @endforelse
            </div>
        </div>

        {{-- Completed Column --}}
        <div class="bg-gray-50 rounded-2xl p-4">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-green-500">✔️</span> Completed
                <span class="ml-auto text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full">
                    {{ $appointments['completed']->count() }}
                </span>
            </h3>
            <div class="space-y-3">
                @forelse($appointments['completed'] as $appointment)
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $appointment->pet->pet_name ?? 'Unknown' }}</h4>
                                <p class="text-xs text-gray-500">{{ $appointment->user->name ?? 'No owner' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">{{ ucfirst($appointment->status) }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">📅 {{ $appointment->appointment_date?->format('d M Y, H:i') }}</p>
                        @if($appointment->notes)
                            <p class="text-xs text-gray-500 line-clamp-2">{{ $appointment->notes }}</p>
                        @endif>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('admin.appointments.show', $appointment) }}" class="text-xs text-blue-600 hover:underline">View</a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">No completed appointments</p>
                @endforelse
            </div>
        </div>

        {{-- Cancelled Column --}}
        <div class="bg-gray-50 rounded-2xl p-4">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-red-500">❌</span> Cancelled
                <span class="ml-auto text-sm bg-red-100 text-red-800 px-2 py-1 rounded-full">
                    {{ $appointments['cancelled']->count() }}
                </span>
            </h3>
            <div class="space-y-3">
                @forelse($appointments['cancelled'] as $appointment)
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $appointment->pet->pet_name ?? 'Unknown' }}</h4>
                                <p class="text-xs text-gray-500">{{ $appointment->user->name ?? 'No owner' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">{{ ucfirst($appointment->status) }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">📅 {{ $appointment->appointment_date?->format('d M Y, H:i') }}</p>
                        @if($appointment->cancellation_reason)
                            <p class="text-xs text-red-600">Reason: {{ $appointment->cancellation_reason }}</p>
                        @endif
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('admin.appointments.show', $appointment) }}" class="text-xs text-blue-600 hover:underline">View</a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">No cancelled appointments</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
