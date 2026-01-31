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
            <h2 class="text-xl font-bold text-gray-800 mb-6">Medical Record</h2>

            @if($appointment->medicalRecord)
                {{-- View Existing Medical Record --}}
                <div class="space-y-6">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Diagnosis</h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $appointment->medicalRecord->diagnosis }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Treatment</h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $appointment->medicalRecord->treatment }}</p>
                        </div>
                    </div>

                    @if($appointment->medicalRecord->prescription)
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Prescription</h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $appointment->medicalRecord->prescription }}</p>
                        </div>
                    </div>
                    @endif

                    @if($appointment->medicalRecord->has_attachments)
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Attachments</h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-sm text-gray-600">{{ $appointment->medicalRecord->attachment_count }} file(s) attached</p>
                            <div class="mt-2 space-y-2">
                                @foreach($appointment->medicalRecord->attachments as $attachment)
                                    <a href="{{ asset('storage/' . $attachment) }}" target="_blank"
                                       class="text-sm text-cyan-600 hover:text-cyan-800 block">
                                        📎 {{ basename($attachment) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="flex gap-3">
                        <a href="{{ route('admin.medical-records.download', $appointment->medicalRecord) }}"
                           class="btn-primary">
                            📄 Download PDF
                        </a>
                        <button onclick="document.getElementById('medical-record-form').style.display='block'"
                                class="btn-secondary">
                            ✏️ Edit Medical Record
                        </button>
                    </div>

                    {{-- Edit Form (Hidden by Default) --}}
                    <form id="medical-record-form" action="{{ route('admin.medical-records.store') }}" method="POST" enctype="multipart/form-data" style="display: none;" class="mt-6 pt-6 border-t">
                        @csrf
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosis *</label>
                                <textarea name="diagnosis" rows="4" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500">{{ $appointment->medicalRecord->diagnosis }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Treatment *</label>
                                <textarea name="treatment" rows="4" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500">{{ $appointment->medicalRecord->treatment }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prescription</label>
                                <textarea name="prescription" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500">{{ $appointment->medicalRecord->prescription }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Attachments (Max 5 files, 10MB each)</label>
                                <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500">
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="btn-primary">Update Medical Record</button>
                                <button type="button" onclick="document.getElementById('medical-record-form').style.display='none'"
                                        class="btn-secondary">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                {{-- Create New Medical Record --}}
                @if($appointment->status === 'completed')
                    <form action="{{ route('admin.medical-records.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                        <div class="space-y-4">
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
                                <textarea name="prescription" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500" placeholder="Enter prescription...">{{ old('prescription') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Attachments (Max 5 files, 10MB each)</label>
                                <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500">
                                <p class="text-sm text-gray-500 mt-1">Accepted formats: JPG, PNG, PDF</p>
                            </div>

                            <button type="submit" class="btn-primary">
                                Save Medical Record
                            </button>
                        </div>
                    </form>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
                        <p class="text-yellow-800">Medical record can be created once the appointment is completed.</p>
                    </div>
                @endif
            @endif
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.appointments.index') }}" class="text-cyan-600 hover:text-cyan-800">
                ← Back to Appointments
            </a>
        </div>
    </div>
@endsection
