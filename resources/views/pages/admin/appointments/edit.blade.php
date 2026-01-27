@extends('layouts.admin')

@section('page-title', 'Edit Appointment')
@section('page-subtitle', 'Update appointment details')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Appointment Date --}}
                <div class="mb-6">
                    <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Appointment Date & Time <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="appointment_date" id="appointment_date" required
                           value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    @error('appointment_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
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
                              placeholder="Add any notes about the appointment...">{{ old('notes', $appointment->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Veterinarian Notes --}}
                <div class="mb-6">
                    <label for="veterinarian_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Veterinarian Notes
                    </label>
                    <textarea name="veterinarian_notes" id="veterinarian_notes" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                              placeholder="Private notes for veterinarian...">{{ old('veterinarian_notes', $appointment->veterinarian_notes) }}</textarea>
                    @error('veterinarian_notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary">
                        Update Appointment
                    </button>
                    <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        {{-- Cancel Appointment --}}
        @if($appointment->status !== 'cancelled')
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-red-600 mb-4">Cancel Appointment</h3>
            <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                @csrf

                <div class="mb-4">
                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Cancellation Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea name="cancellation_reason" id="cancellation_reason" rows="3" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent"
                              placeholder="Please provide a reason for cancellation..."></textarea>
                </div>

                <div class="mb-4">
                    <label for="cancellation_fee" class="block text-sm font-medium text-gray-700 mb-2">
                        Cancellation Fee (Optional)
                    </label>
                    <input type="number" name="cancellation_fee" id="cancellation_fee" min="0" step="0.01"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           placeholder="0.00">
                </div>

                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                    Cancel Appointment
                </button>
            </form>
        </div>
        @endif
    </div>
@endsection
