@extends('layouts.user')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Appointments</h1>
            <p class="text-gray-600">View and manage your pet appointments</p>
        </div>

        @if($appointments->count() > 0)
            <div class="space-y-4">
                @foreach($appointments as $appointment)
                    <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="text-3xl">
                                        @if(strtolower($appointment->pet->species) === 'cat')
                                            🐱
                                        @elseif(strtolower($appointment->pet->species) === 'dog')
                                            🐶
                                        @elseif(strtolower($appointment->pet->species) === 'bird')
                                            🦜
                                        @elseif(strtolower($appointment->pet->species) === 'rabbit')
                                            🐰
                                        @else
                                            🐾
                                        @endif
                                    </span>
                                    <div>
                                        <h3 class="font-bold text-lg text-gray-900">{{ $appointment->pet->pet_name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $appointment->pet->species }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <span>📅</span>
                                        <span class="font-medium">{{ $appointment->appointment_date->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {!! $appointment->status_badge !!}
                                    </div>
                                </div>

                                @if($appointment->notes)
                                    <p class="text-sm text-gray-600 mb-3">{{ $appointment->notes }}</p>
                                @endif

                                @if($appointment->veterinarian_notes)
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                        <p class="text-sm font-semibold text-blue-800 mb-1">Veterinarian Notes:</p>
                                        <p class="text-sm text-blue-700">{{ $appointment->veterinarian_notes }}</p>
                                    </div>
                                @endif

                                @if($appointment->status === 'completed' && $appointment->medicalRecord)
                                    <div class="flex gap-2 mt-3">
                                        <a href="{{ route('user.appointments.show', $appointment) }}" 
                                           class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 text-sm font-medium transition-colors">
                                            View Details & Medical Record
                                        </a>
                                        <a href="{{ route('user.medical-records.download', $appointment->medicalRecord) }}" 
                                           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium transition-colors">
                                            📄 Download PDF
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('user.appointments.show', $appointment) }}" 
                                       class="inline-block px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-medium transition-colors mt-3">
                                        View Details
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $appointments->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <div class="text-6xl mb-4">📅</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Appointments Yet</h3>
                <p class="text-gray-600">You don't have any appointments scheduled.</p>
            </div>
        @endif
    </div>
</div>
@endsection
