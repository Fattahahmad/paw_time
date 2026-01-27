@extends('layouts.admin')

@section('page-title', 'Medical Record Details')
@section('page-subtitle', 'View complete health check information')

@section('content')
    <div class="max-w-4xl">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('admin.health-checks.index') }}"
                class="inline-flex items-center text-gray-600 hover:text-gray-800 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Back to Records
            </a>
        </div>

        {{-- Patient Info Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-[#68C4CF] to-[#5AB0BB] rounded-2xl flex items-center justify-center">
                        <span
                            class="text-3xl text-white">{{ $healthCheck->pet->species === 'Cat' ? '🐱' : ($healthCheck->pet->species === 'Dog' ? '🐕' : '🐾') }}</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $healthCheck->pet->pet_name ?? 'Unknown Pet' }}</h2>
                        <p class="text-gray-600">{{ $healthCheck->pet->breed ?? 'Unknown breed' }} •
                            {{ $healthCheck->pet->species }}</p>
                        <p class="text-sm text-gray-500">Owner: {{ $healthCheck->pet->user->name ?? 'Unknown' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Record #{{ $healthCheck->id }}</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $healthCheck->check_date?->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Medical Details --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Complaint --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-700 mb-3 flex items-center space-x-2">
                    <span class="text-xl">🤒</span>
                    <span>Complaint / Symptoms</span>
                </h3>
                <p class="text-gray-600 leading-relaxed">{{ $healthCheck->complaint ?: 'No complaint recorded' }}</p>
            </div>

            {{-- Diagnosis --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-700 mb-3 flex items-center space-x-2">
                    <span class="text-xl">🩺</span>
                    <span>Diagnosis</span>
                </h3>
                <p class="text-gray-600 leading-relaxed">{{ $healthCheck->diagnosis ?: 'No diagnosis recorded' }}</p>
            </div>
        </div>

        {{-- Treatment & Prescription --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold text-gray-700 mb-4 flex items-center space-x-2">
                <span class="text-xl">💊</span>
                <span>Treatment & Prescription</span>
            </h3>

            <div class="space-y-4">
                @if ($healthCheck->treatment)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Treatment</h4>
                        <p class="text-gray-700 bg-gray-50 rounded-lg p-3">{{ $healthCheck->treatment }}</p>
                    </div>
                @endif

                @if ($healthCheck->prescription)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Prescription</h4>
                        <div class="text-gray-700 bg-blue-50 rounded-lg p-3 border border-blue-100">
                            <pre class="whitespace-pre-wrap font-sans">{{ $healthCheck->prescription }}</pre>
                        </div>
                    </div>
                @endif

                @if ($healthCheck->notes)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Additional Notes</h4>
                        <p class="text-gray-700 bg-yellow-50 rounded-lg p-3 border border-yellow-100">
                            {{ $healthCheck->notes }}</p>
                    </div>
                @endif

                @if (!$healthCheck->treatment && !$healthCheck->prescription && !$healthCheck->notes)
                    <p class="text-gray-500 italic">No treatment details recorded</p>
                @endif
            </div>
        </div>

        {{-- Follow-up --}}
        @if ($healthCheck->next_visit_date)
            <div
                class="bg-{{ $healthCheck->is_follow_up_overdue ? 'red' : 'green' }}-50 rounded-2xl shadow-sm border border-{{ $healthCheck->is_follow_up_overdue ? 'red' : 'green' }}-200 p-6 mb-6">
                <h3
                    class="font-semibold text-{{ $healthCheck->is_follow_up_overdue ? 'red' : 'green' }}-700 mb-2 flex items-center space-x-2">
                    <span class="text-xl">📅</span>
                    <span>Follow-up Visit</span>
                </h3>
                <p class="text-{{ $healthCheck->is_follow_up_overdue ? 'red' : 'green' }}-600">
                    {{ $healthCheck->is_follow_up_overdue ? 'Overdue since' : 'Scheduled for' }}
                    <strong>{{ $healthCheck->next_visit_date->format('d F Y') }}</strong>
                    ({{ $healthCheck->next_visit_date->diffForHumans() }})
                </p>
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.health-checks.edit', $healthCheck) }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
                Edit Record
            </a>
            <form action="{{ route('admin.health-checks.destroy', $healthCheck) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this medical record?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>
@endsection
