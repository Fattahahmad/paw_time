@extends('layouts.user')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        {{-- Back Button --}}
        <a href="{{ route('user.appointments.index') }}" class="inline-flex items-center text-cyan-600 hover:text-cyan-800 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Appointments
        </a>

        {{-- Appointment Details Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Appointment #{{ $appointment->id }}</h1>
                    <p class="text-gray-600">{{ $appointment->formatted_date }}</p>
                </div>
                <div>
                    {!! $appointment->status_badge !!}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                {{-- Pet Info --}}
                <div>
                    <h3 class="font-bold text-gray-800 mb-4 text-lg">Pet Information</h3>
                    <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-4">
                        <span class="text-4xl">
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
                            <p class="font-semibold text-lg">{{ $appointment->pet->pet_name }}</p>
                            <p class="text-gray-600">{{ $appointment->pet->species }} - {{ $appointment->pet->breed }}</p>
                        </div>
                    </div>
                </div>

                {{-- Appointment Info --}}
                <div>
                    <h3 class="font-bold text-gray-800 mb-4 text-lg">Details</h3>
                    <div class="space-y-2 bg-gray-50 rounded-xl p-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="font-medium">{{ ucfirst($appointment->status) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date:</span>
                            <span class="font-medium">{{ $appointment->appointment_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Time:</span>
                            <span class="font-medium">{{ $appointment->appointment_date->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($appointment->notes)
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <h3 class="font-bold text-blue-800 mb-2">Notes</h3>
                <p class="text-blue-700">{{ $appointment->notes }}</p>
            </div>
            @endif

            @if($appointment->veterinarian_notes)
            <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-xl">
                <h3 class="font-bold text-purple-800 mb-2">Veterinarian Notes</h3>
                <p class="text-purple-700">{{ $appointment->veterinarian_notes }}</p>
            </div>
            @endif
        </div>

        {{-- Medical Record Section --}}
        @if($appointment->status === 'completed' && $appointment->medicalRecord)
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Medical Record</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('user.medical-records.download', $appointment->medicalRecord) }}" 
                           class="px-6 py-3 bg-cyan-600 text-white rounded-xl hover:bg-cyan-700 font-semibold transition-colors">
                            📄 Download PDF
                        </a>
                        <a href="{{ route('user.medical-records.download', $appointment->medicalRecord) }}" 
                           target="_blank"
                           class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 font-semibold transition-colors">
                            🖨️ Print
                        </a>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Diagnosis --}}
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <span>🩺</span> Diagnosis
                        </h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $appointment->medicalRecord->diagnosis }}</p>
                        </div>
                    </div>

                    {{-- Treatment --}}
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <span>💊</span> Treatment
                        </h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $appointment->medicalRecord->treatment }}</p>
                        </div>
                    </div>

                    {{-- Prescription --}}
                    @if($appointment->medicalRecord->prescription)
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <span>📝</span> Prescription
                        </h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $appointment->medicalRecord->prescription }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Attachments --}}
                    @if($appointment->medicalRecord->has_attachments)
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <span>📎</span> Attachments
                        </h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($appointment->medicalRecord->attachments as $attachment)
                                    <a href="{{ asset('storage/' . $attachment) }}" 
                                       target="_blank"
                                       class="flex items-center gap-2 p-3 bg-white border border-gray-200 rounded-lg hover:border-cyan-500 hover:shadow-sm transition-all">
                                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-700 truncate">{{ basename($attachment) }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Veterinarian Info --}}
                    <div class="pt-6 border-t">
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Veterinarian:</span> {{ $appointment->medicalRecord->createdBy->name }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Record Created:</span> {{ $appointment->medicalRecord->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        @elseif($appointment->status === 'completed')
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center">
                <div class="text-5xl mb-4">⏳</div>
                <h3 class="text-xl font-bold text-yellow-800 mb-2">Medical Record Pending</h3>
                <p class="text-yellow-700">Your medical record is being prepared and will be available soon.</p>
            </div>
        @endif
    </div>
</div>
@endsection
