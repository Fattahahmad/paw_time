@extends('layouts.admin')

@section('page-title', 'Edit Reminder')
@section('page-subtitle', 'Update reminder information')

@section('content')
    <div class="max-w-3xl">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800">Edit Reminder</h3>
                <p class="text-sm text-gray-500">Update the details for this reminder</p>
            </div>

            <form action="{{ route('admin.reminders.update', $reminder) }}" method="POST">
                @csrf
                @method('PUT')

            {{-- User Selection --}}
            <div class="space-y-4">
                <h4 class="font-semibold text-gray-700 flex items-center space-x-2">
                    <span class="text-xl">👤</span>
                    <span>User Information</span>
                </h4>

                <div class="form-group">
                    <label class="form-label">User *</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Choose user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $reminder->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Reminder Details --}}
            <div class="space-y-4 pt-6 border-t border-gray-200">
                <h4 class="font-semibold text-gray-700 flex items-center space-x-2">
                    <span class="text-xl">🔔</span>
                    <span>Reminder Details</span>
                </h4>

                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-input" value="{{ old('title', $reminder->title) }}" placeholder="e.g. Vaccination, Grooming" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="3" placeholder="Additional details about this reminder">{{ old('description', $reminder->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-select" required>
                            <option value="">Select category</option>
                            <option value="vaccination" {{ old('category', $reminder->category) == 'vaccination' ? 'selected' : '' }}>💉 Vaccination</option>
                            <option value="grooming" {{ old('category', $reminder->category) == 'grooming' ? 'selected' : '' }}>✂️ Grooming</option>
                            <option value="feeding" {{ old('category', $reminder->category) == 'feeding' ? 'selected' : '' }}>🍖 Feeding</option>
                            <option value="medication" {{ old('category', $reminder->category) == 'medication' ? 'selected' : '' }}>💊 Medication</option>
                            <option value="checkup" {{ old('category', $reminder->category) == 'checkup' ? 'selected' : '' }}>🩺 Health Checkup</option>
                            <option value="other" {{ old('category', $reminder->category) == 'other' ? 'selected' : '' }}>📝 Other</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Remind Date *</label>
                        <input type="datetime-local" name="remind_date" class="form-input" value="{{ old('remind_date', $reminder->remind_date?->format('Y-m-d\TH:i')) }}" required>
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
                            <option value="none" {{ old('repeat_type', $reminder->repeat_type) == 'none' ? 'selected' : '' }}>No Repeat</option>
                            <option value="daily" {{ old('repeat_type', $reminder->repeat_type) == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('repeat_type', $reminder->repeat_type) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('repeat_type', $reminder->repeat_type) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ old('repeat_type', $reminder->repeat_type) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                        @error('repeat_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="">Select status</option>
                            <option value="pending" {{ old('status', $reminder->status) == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="done" {{ old('status', $reminder->status) == 'done' ? 'selected' : '' }}>✅ Done</option>
                            <option value="skipped" {{ old('status', $reminder->status) == 'skipped' ? 'selected' : '' }}>⏭️ Skipped</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Reminder Info --}}
            <div class="space-y-4 pt-6 border-t border-gray-200">
                <h4 class="font-semibold text-gray-700 flex items-center space-x-2">
                    <span class="text-xl">📊</span>
                    <span>Reminder Info</span>
                </h4>

                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Created:</span>
                            <span class="text-gray-800 ml-2">{{ $reminder->created_at?->format('d M Y, H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Status:</span>
                            <span
                                class="ml-2 px-2 py-1 text-xs rounded-full {{ $reminder->status === 'done' ? 'bg-green-100 text-green-700' : ($reminder->is_overdue ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ $reminder->is_overdue && $reminder->status === 'pending' ? 'Overdue' : ucfirst($reminder->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <button type="button" onclick="if(confirm('Are you sure you want to delete this reminder?')) { document.getElementById('delete-form').submit(); }" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Delete Reminder
                    </button>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.reminders.index') }}" class="btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Update Reminder
                        </button>
                    </div>
                </div>
            </form>

            {{-- Hidden Delete Form --}}
            <form id="delete-form" action="{{ route('admin.reminders.destroy', $reminder) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@endsection
