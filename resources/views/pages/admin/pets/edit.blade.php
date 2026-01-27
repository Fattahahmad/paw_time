@extends('layouts.admin')

@section('page-title', 'Edit Pet')
@section('page-subtitle', 'Update pet information')

@section('content')
    <div class="max-w-3xl">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800">Edit Pet Information</h3>
                <p class="text-sm text-gray-500">Update the details for {{ $pet->pet_name }}</p>
            </div>

            <form action="{{ route('admin.pets.update', $pet) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Owner Selection --}}
                <div class="space-y-4 mb-6">
                    <h4 class="font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="text-xl">👤</span>
                        <span>Owner Information</span>
                    </h4>

                    <div class="form-group">
                        <label class="form-label">Select Owner *</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Choose pet owner</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $pet->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Pet Details --}}
                <div class="space-y-4 pt-6 border-t border-gray-200 mb-6">
                    <h4 class="font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="text-xl">🐾</span>
                        <span>Pet Details</span>
                    </h4>

                    <div class="form-group">
                        <label class="form-label">Pet Name *</label>
                        <input type="text" name="pet_name" class="form-input" value="{{ old('pet_name', $pet->pet_name) }}" placeholder="Enter pet name" required>
                        @error('pet_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Species *</label>
                            <select name="species" class="form-select" required>
                                <option value="">Select species</option>
                                <option value="Cat" {{ old('species', $pet->species) == 'Cat' ? 'selected' : '' }}>Cat</option>
                                <option value="Dog" {{ old('species', $pet->species) == 'Dog' ? 'selected' : '' }}>Dog</option>
                                <option value="Bird" {{ old('species', $pet->species) == 'Bird' ? 'selected' : '' }}>Bird</option>
                                <option value="Rabbit" {{ old('species', $pet->species) == 'Rabbit' ? 'selected' : '' }}>Rabbit</option>
                                <option value="Hamster" {{ old('species', $pet->species) == 'Hamster' ? 'selected' : '' }}>Hamster</option>
                                <option value="Other" {{ old('species', $pet->species) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('species')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Breed</label>
                            <input type="text" name="breed" class="form-input" value="{{ old('breed', $pet->breed) }}" placeholder="e.g. Persian, Golden Retriever">
                            @error('breed')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Gender *</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select gender</option>
                                <option value="male" {{ old('gender', $pet->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $pet->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Birth Date *</label>
                            <input type="date" name="birth_date" class="form-input" value="{{ old('birth_date', $pet->birth_date?->format('Y-m-d')) }}" required>
                            @error('birth_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Color</label>
                        <input type="text" name="color" class="form-input" value="{{ old('color', $pet->color) }}" placeholder="e.g. Black, White, Brown">
                        @error('color')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" rows="3" placeholder="Additional information about this pet">{{ old('description', $pet->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Pet Stats --}}
                <div class="space-y-4 pt-6 border-t border-gray-200 mb-6">
                    <h4 class="font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="text-xl">📊</span>
                        <span>Pet Statistics</span>
                    </h4>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-gradient-to-br from-[#68C4CF]/10 to-[#68C4CF]/5 rounded-xl p-4 text-center">
                            <p class="text-2xl font-bold text-[#68C4CF]">{{ $petStats['remindersCount'] }}</p>
                            <p class="text-sm text-gray-600 mt-1">Reminders</p>
                        </div>
                        <div class="bg-gradient-to-br from-[#FFD4B2]/10 to-[#FFD4B2]/5 rounded-xl p-4 text-center">
                            <p class="text-2xl font-bold text-[#FFA07A]">{{ $petStats['healthChecksCount'] }}</p>
                            <p class="text-sm text-gray-600 mt-1">Health Checks</p>
                        </div>
                        <div class="bg-gradient-to-br from-green-500/10 to-green-500/5 rounded-xl p-4 text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $petStats['growthRecordsCount'] }}</p>
                            <p class="text-sm text-gray-600 mt-1">Growth Records</p>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <button type="button" onclick="if(confirm('Are you sure you want to delete this pet?')) { document.getElementById('delete-form').submit(); }" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Delete Pet
                    </button>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.pets.index') }}" class="btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Update Pet
                        </button>
                    </div>
                </div>
            </form>

            {{-- Hidden Delete Form --}}
            <form id="delete-form" action="{{ route('admin.pets.destroy', $pet) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@endsection
