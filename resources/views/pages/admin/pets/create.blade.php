@extends('layouts.admin')

@section('page-title', 'Add New Pet')
@section('page-subtitle', 'Create a new pet profile')

@section('content')
    <div class="max-w-3xl">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800">Pet Information</h3>
                <p class="text-sm text-gray-500">Fill in the details to create a new pet profile</p>
            </div>

            <form action="{{ route('admin.pets.store') }}" method="POST">
                @csrf

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
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                        <input type="text" name="pet_name" class="form-input" value="{{ old('pet_name') }}" placeholder="Enter pet name" required>
                        @error('pet_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Species *</label>
                            <select name="species" class="form-select" required>
                                <option value="">Select species</option>
                                <option value="Cat" {{ old('species') == 'Cat' ? 'selected' : '' }}>Cat</option>
                                <option value="Dog" {{ old('species') == 'Dog' ? 'selected' : '' }}>Dog</option>
                                <option value="Bird" {{ old('species') == 'Bird' ? 'selected' : '' }}>Bird</option>
                                <option value="Rabbit" {{ old('species') == 'Rabbit' ? 'selected' : '' }}>Rabbit</option>
                                <option value="Hamster" {{ old('species') == 'Hamster' ? 'selected' : '' }}>Hamster</option>
                                <option value="Other" {{ old('species') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('species')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Breed</label>
                            <input type="text" name="breed" class="form-input" value="{{ old('breed') }}" placeholder="e.g. Persian, Golden Retriever">
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
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Birth Date *</label>
                            <input type="date" name="birth_date" class="form-input" value="{{ old('birth_date') }}" required>
                            @error('birth_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Color</label>
                        <input type="text" name="color" class="form-input" value="{{ old('color') }}" placeholder="e.g. Black, White, Brown">
                        @error('color')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" rows="3" placeholder="Additional information about this pet">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-end space-x-2 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.pets.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Pet
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
