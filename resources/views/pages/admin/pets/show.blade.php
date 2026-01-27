@extends('layouts.admin')

@section('page-title', 'Pet Details')
@section('page-subtitle', $pet->pet_name . ' - ' . $pet->species)

@section('content')
    {{-- Pet Header --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-20 h-20 bg-gradient-to-br from-[#68C4CF] to-[#5AB0BB] rounded-xl flex items-center justify-center">
                    <span class="text-4xl">{{ $pet->species === 'Cat' ? '🐱' : ($pet->species === 'Dog' ? '🐕' : '🐾') }}</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $pet->pet_name }}</h1>
                    <p class="text-gray-600">{{ $pet->species }} @if($pet->breed)- {{ $pet->breed }}@endif</p>
                    <div class="flex items-center space-x-4 mt-2">
                        <span class="badge {{ $pet->gender === 'male' ? 'badge-info' : 'bg-pink-100 text-pink-700' }}">
                            {{ ucfirst($pet->gender) }}
                        </span>
                        <span class="text-sm text-gray-500">Age: {{ $pet->age }}</span>
                        @if($pet->color)
                        <span class="text-sm text-gray-500">Color: {{ $pet->color }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.pets.edit', $pet) }}" class="btn-primary">
                    Edit Pet
                </a>
                <a href="{{ route('admin.pets.index') }}" class="btn-secondary">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Reminders</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $petStats['remindersCount'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">🔔</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Health Checks</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $petStats['healthChecksCount'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">❤️</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Growth Records</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $petStats['growthRecordsCount'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📈</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Appointments</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $petStats['appointmentsCount'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📅</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Pet Information --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Pet Information</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Owner:</dt>
                    <dd class="font-medium text-gray-800">{{ $pet->user->name ?? 'Unknown' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Species:</dt>
                    <dd class="font-medium text-gray-800">{{ $pet->species }}</dd>
                </div>
                @if($pet->breed)
                <div class="flex justify-between">
                    <dt class="text-gray-500">Breed:</dt>
                    <dd class="font-medium text-gray-800">{{ $pet->breed }}</dd>
                </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-gray-500">Gender:</dt>
                    <dd class="font-medium text-gray-800">{{ ucfirst($pet->gender) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Birth Date:</dt>
                    <dd class="font-medium text-gray-800">{{ $pet->birth_date?->format('M d, Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Age:</dt>
                    <dd class="font-medium text-gray-800">{{ $pet->age }}</dd>
                </div>
                @if($pet->color)
                <div class="flex justify-between">
                    <dt class="text-gray-500">Color:</dt>
                    <dd class="font-medium text-gray-800">{{ $pet->color }}</dd>
                </div>
                @endif
            </dl>
        </div>

        @if($pet->description)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Description</h3>
            <p class="text-gray-600">{{ $pet->description }}</p>
        </div>
        @endif
    </div>

    {{-- Recent Activity --}}
    @if($pet->reminders->count() > 0 || $pet->healthChecks->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Recent Activity</h3>
        <div class="space-y-4">
            @foreach($pet->healthChecks->take(5) as $check)
            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                <span class="text-2xl">🏥</span>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">Health Check</p>
                    <p class="text-sm text-gray-600">{{ $check->complaint }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $check->check_date->format('M d, Y') }}</p>
                </div>
            </div>
            @endforeach

            @foreach($pet->reminders->take(5) as $reminder)
            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                <span class="text-2xl">🔔</span>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">{{ $reminder->title }}</p>
                    <p class="text-sm text-gray-600">{{ ucfirst($reminder->category) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $reminder->remind_date?->format('M d, Y') }}</p>
                </div>
                <span class="badge badge-{{ $reminder->status === 'done' ? 'success' : 'warning' }}">
                    {{ ucfirst($reminder->status) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
@endsection
