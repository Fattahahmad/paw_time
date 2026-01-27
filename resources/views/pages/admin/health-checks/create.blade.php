@extends('layouts.admin')

@section('page-title', 'Add Medical Record')
@section('page-subtitle', 'Create a new health check record')

@section('content')
    <div class="max-w-4xl">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800">Medical Record</h3>
                <p class="text-sm text-gray-500">Fill in the health check details</p>
            </div>

            <form action="{{ route('admin.health-checks.store') }}" method="POST">
                @csrf

                {{-- Pet Selection --}}
                <div class="space-y-4 mb-6">
                    <h4 class="font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="text-xl">🐾</span>
                        <span>Patient Information</span>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Select Pet *</label>
                            <select name="pet_id" class="form-select" required>
                                <option value="">Choose pet</option>
                                @foreach($pets as $pet)
                                    <option value="{{ $pet->id }}" {{ old('pet_id') == $pet->id ? 'selected' : '' }}>
                                        {{ $pet->pet_name }} ({{ $pet->user->name ?? 'No owner' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pet_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Check Date *</label>
                            <input type="date" name="check_date" class="form-input" value="{{ old('check_date', date('Y-m-d')) }}" required>
                            @error('check_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Consultation Details --}}
                <div class="space-y-4 pt-6 border-t border-gray-200 mb-6">
                    <h4 class="font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="text-xl">🩺</span>
                        <span>Consultation Details</span>
                    </h4>

                    <div class="form-group">
                        <label class="form-label">Complaint / Symptoms *</label>
                        <textarea name="complaint" class="form-textarea" rows="3" placeholder="Describe the pet's symptoms or reason for visit" required>{{ old('complaint') }}</textarea>
                        @error('complaint')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Diagnosis *</label>
                        <textarea name="diagnosis" class="form-textarea" rows="3" placeholder="Medical diagnosis based on examination" required>{{ old('diagnosis') }}</textarea>
                        @error('diagnosis')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Treatment --}}
                <div class="space-y-4 pt-6 border-t border-gray-200 mb-6">
                    <h4 class="font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="text-xl">💊</span>
                        <span>Treatment Plan</span>
                    </h4>

                    <div class="form-group">
                        <label class="form-label">Treatment</label>
                        <textarea name="treatment" class="form-textarea" rows="3" placeholder="Describe the treatment given or recommended">{{ old('treatment') }}</textarea>
                        @error('treatment')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Prescription</label>
                        <textarea name="prescription" class="form-textarea" rows="3" placeholder="List medications prescribed (name, dosage, frequency)">{{ old('prescription') }}</textarea>
                        @error('prescription')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Additional Notes</label>
                        <textarea name="notes" class="form-textarea" rows="2" placeholder="Any additional observations or instructions">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Follow-up --}}
                <div class="space-y-4 pt-6 border-t border-gray-200 mb-6">
                    <h4 class="font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="text-xl">📅</span>
                        <span>Follow-up</span>
                    </h4>

                    <div class="form-group">
                        <label class="form-label">Next Visit Date</label>
                        <input type="date" name="next_visit_date" class="form-input" value="{{ old('next_visit_date') }}">
                        <p class="text-xs text-gray-500 mt-1">Leave empty if no follow-up required</p>
                        @error('next_visit_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-end space-x-2 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.health-checks.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        Save Record
                    </button>
                </div>
            </form>
        </div>
@endsection
