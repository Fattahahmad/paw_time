@extends('layouts.admin')

@section('page-title', 'Appointment Details')
@section('page-subtitle', 'View and manage appointment')

@section('content')
    <div class="max-w-5xl mx-auto">
        {{-- Appointment Details Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Appointment #{{ $appointment->id }}</h2>
                    <p class="text-gray-600 mt-1">{{ $appointment->formatted_date }}</p>
                </div>
                <div class="flex gap-2">
                    {!! $appointment->status_badge !!}
                    <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn-primary">
                        Edit
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Pet & Owner Info --}}
                <div>
                    <h3 class="font-bold text-gray-800 mb-3">Pet Information</h3>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">🐾</span>
                            <div>
                                <p class="font-semibold">{{ $appointment->pet->pet_name }}</p>
                                <p class="text-sm text-gray-600">{{ $appointment->pet->species }} - {{ $appointment->pet->breed }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mt-3">
                            <span class="text-2xl">👤</span>
                            <div>
                                <p class="font-semibold">{{ $appointment->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $appointment->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Appointment Info --}}
                <div>
                    <h3 class="font-bold text-gray-800 mb-3">Appointment Details</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="font-medium">{{ ucfirst($appointment->status) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Created:</span>
                            <span class="font-medium">{{ $appointment->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        @if($appointment->updated_at != $appointment->created_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Last Updated:</span>
                            <span class="font-medium">{{ $appointment->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($appointment->notes)
            <div class="mt-6 pt-6 border-t">
                <h3 class="font-bold text-gray-800 mb-2">Notes</h3>
                <p class="text-gray-700">{{ $appointment->notes }}</p>
            </div>
            @endif

            {{-- Veterinarian Notes --}}
            @if($appointment->veterinarian_notes)
            <div class="mt-6 pt-6 border-t">
                <h3 class="font-bold text-gray-800 mb-2">Veterinarian Notes</h3>
                <p class="text-gray-700">{{ $appointment->veterinarian_notes }}</p>
            </div>
            @endif

            {{-- Cancellation Info --}}
            @if($appointment->status === 'cancelled')
            <div class="mt-6 pt-6 border-t">
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <h3 class="font-bold text-red-800 mb-2">Cancellation Details</h3>
                    <p class="text-red-700 mb-2">{{ $appointment->cancellation_reason }}</p>
                    @if($appointment->cancellation_fee > 0)
                    <p class="text-red-700 font-semibold">Fee: Rp {{ number_format($appointment->cancellation_fee, 0, ',', '.') }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Medical Record Section --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Health Check History</h2>

            @if($appointment->healthCheck)
                {{-- View Existing Health Check --}}
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                    <p class="text-green-800 font-semibold">✅ This appointment has been completed and health check recorded.</p>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Complaint</h3>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-gray-700">{{ $appointment->healthCheck->complaint }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Check Date</h3>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-gray-700">{{ $appointment->healthCheck->check_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Diagnosis</h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $appointment->healthCheck->diagnosis }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Treatment</h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $appointment->healthCheck->treatment }}</p>
                        </div>
                    </div>

                    @if($appointment->healthCheck->prescription)
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Prescription</h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $appointment->healthCheck->prescription }}</p>
                        </div>
                    </div>
                    @endif

                    @if($appointment->healthCheck->notes)
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Notes</h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $appointment->healthCheck->notes }}</p>
                        </div>
                    </div>
                    @endif

                    @if($appointment->healthCheck->next_visit_date)
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Next Visit Date</h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <p class="text-blue-800 font-semibold">📅 {{ $appointment->healthCheck->next_visit_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            @elseif($appointment->status === 'completed' || in_array($appointment->status, ['pending', 'confirmed']))
                {{-- Complete Appointment Form --}}
                @if($appointment->status !== 'completed')
                <div class="bg-cyan-50 border border-cyan-200 rounded-xl p-4 mb-6">
                    <h3 class="font-bold text-cyan-900 mb-2">📋 Complete This Appointment</h3>
                    <p class="text-cyan-800 text-sm">Fill in the health check details below to complete this appointment and create a medical history record.</p>
                </div>
                @endif

                <form action="{{ route('admin.appointments.complete', $appointment) }}" method="POST" x-data="{ showForm: {{ $appointment->status === 'completed' ? 'true' : 'false' }} }">
                    @csrf

                    @if($appointment->status !== 'completed')
                    <div class="mb-6">
                        <button type="button" @click="showForm = !showForm" class="btn-primary w-full md:w-auto">
                            <span x-text="showForm ? '▼ Hide Form' : '▶ Complete Appointment & Create Health Check'"></span>
                        </button>
                    </div>
                    @endif

                    <div x-show="showForm" x-transition class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Complaint <span class="text-red-500">*</span></label>
                            <textarea name="complaint" rows="3" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500" placeholder="What was the pet's complaint or reason for visit?">{{ old('complaint', $appointment->notes) }}</textarea>
                            @error('complaint')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosis <span class="text-red-500">*</span></label>
                            <textarea name="diagnosis" rows="4" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500" placeholder="Enter diagnosis details...">{{ old('diagnosis') }}</textarea>
                            @error('diagnosis')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Treatment <span class="text-red-500">*</span></label>
                            <textarea name="treatment" rows="4" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500" placeholder="Enter treatment details...">{{ old('treatment') }}</textarea>
                            @error('treatment')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prescription</label>
                            <textarea name="prescription" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500" placeholder="Enter prescription (medicine, dosage, duration)...">{{ old('prescription') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea name="notes" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500" placeholder="Any additional notes...">{{ old('notes', $appointment->veterinarian_notes) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Next Visit Date (Optional)</label>
                            <input type="date" name="next_visit_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('next_visit_date') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500">
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="btn-primary">
                                ✅ Complete Appointment & Save Health Check
                            </button>
                            @if($appointment->status !== 'completed')
                            <button type="button" @click="showForm = false" class="btn-secondary">
                                Cancel
                            </button>
                            @endif
                        </div>
                    </div>
                </form>
            @elseif($appointment->status === 'cancelled')
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                    <p class="text-red-800">This appointment was cancelled and cannot be completed.</p>
                </div>
            @endif
        </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.appointments.index') }}" class="text-cyan-600 hover:text-cyan-800">
                ← Back to Appointments
            </a>
        </div>
    </div>
@endsection
