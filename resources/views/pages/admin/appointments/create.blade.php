@extends('layouts.admin')

@section('page-title', 'Create Appointment')
@section('page-subtitle', 'Schedule a new appointment for a pet')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form action="{{ route('admin.appointments.store') }}" method="POST">
                @csrf

                {{-- WhatsApp Discussion Note --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                    <p class="text-sm text-blue-800">
                        <span class="font-bold">💬 Note:</span> Please discuss the appointment date with the pet owner via WhatsApp before scheduling.
                    </p>
                </div>

                {{-- User Selection --}}
                <div class="mb-6">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pet Owner <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            onchange="filterPets()">
                        <option value="">Select Owner</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pet Selection --}}
                <div class="mb-6">
                    <label for="pet_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pet <span class="text-red-500">*</span>
                    </label>
                    <select name="pet_id" id="pet_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <option value="">Select Pet</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->id }}" data-user-id="{{ $pet->user_id }}" {{ old('pet_id') == $pet->id ? 'selected' : '' }}>
                                {{ $pet->pet_name }} ({{ $pet->species }})
                            </option>
                        @endforeach
                    </select>
                    @error('pet_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Appointment Date --}}
                <div class="mb-6">
                    <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Appointment Date & Time <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="appointment_date" id="appointment_date" required
                           value="{{ old('appointment_date') }}"
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    @error('appointment_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Notes --}}
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                              placeholder="Add any notes about the appointment...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary">
                        Create Appointment
                    </button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function filterPets() {
            const userId = document.getElementById('user_id').value;
            const petSelect = document.getElementById('pet_id');
            const options = petSelect.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                    return;
                }

                const petUserId = option.getAttribute('data-user-id');
                if (userId === '' || petUserId === userId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });

            petSelect.value = '';
        }

        // Filter pets on page load if user is pre-selected
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('user_id').value) {
                filterPets();
            }
        });
    </script>
@endsection
