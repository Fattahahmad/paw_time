@extends('layouts.admin')

@section('page-title', 'Add New Reminder')
@section('page-subtitle', 'Create a new pet reminder')

@section('content')
    <div class="max-w-3xl">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800">Reminder Information</h3>
                <p class="text-sm text-gray-500">Fill in the details to create a new reminder</p>
            </div>

            <form action="{{ route('admin.reminders.store') }}" method="POST">
                @csrf

                {{-- Pet Selection --}}
                <div class="space-y-4 mb-6">
                    <h4 class="font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="text-xl">🐾</span>
                        <span>Select Pet</span>
                    </h4>

                    <div class="form-group">
                        <label class="form-label">Pet *</label>
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
                </div>

                {{-- Reminder Details --}}
                <div class="space-y-4 pt-6 border-t border-gray-200 mb-6">
                    <h4 class="font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="text-xl">🔔</span>
                        <span>Reminder Details</span>
                    </h4>

                    <div class="form-group">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-input" value="{{ old('title') }}" placeholder="e.g. Vaccination, Grooming" required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" rows="3" placeholder="Additional details about this reminder">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Category *</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select category</option>
                                <option value="vaccination" {{ old('category') == 'vaccination' ? 'selected' : '' }}>💉 Vaccination</option>
                                <option value="grooming" {{ old('category') == 'grooming' ? 'selected' : '' }}>✂️ Grooming</option>
                                <option value="feeding" {{ old('category') == 'feeding' ? 'selected' : '' }}>🍖 Feeding</option>
                                <option value="medication" {{ old('category') == 'medication' ? 'selected' : '' }}>💊 Medication</option>
                                <option value="checkup" {{ old('category') == 'checkup' ? 'selected' : '' }}>🩺 Health Checkup</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>📝 Other</option>
                            </select>
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Remind Date *</label>
                            <input type="datetime-local" name="remind_date" class="form-input" value="{{ old('remind_date') }}" required>
                            @error('remind_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Repeat *</label>
                            <select name="repeat_type" class="form-select" required>
                                <option value="">Select repeat type</option>
                                <option value="none" {{ old('repeat_type', 'none') == 'none' ? 'selected' : '' }}>No Repeat</option>
                                <option value="daily" {{ old('repeat_type') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ old('repeat_type') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ old('repeat_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ old('repeat_type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                            @error('repeat_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="">Select status</option>
                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>✅ Done</option>
                                <option value="skipped" {{ old('status') == 'skipped' ? 'selected' : '' }}>⏭️ Skipped</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-end space-x-2 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.reminders.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        Create Reminder
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
