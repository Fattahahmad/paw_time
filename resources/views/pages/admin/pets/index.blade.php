@extends('layouts.admin')

@section('page-title', 'Pets Management')
@section('page-subtitle', 'Manage all pets in the system')

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Pets</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">🐾</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Cats</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['cats'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">🐱</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Dogs</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['dogs'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">🐕</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Others</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['others'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">🐹</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Pets Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800">All Pets</h3>
                <p class="text-sm text-gray-500">{{ $pets->total() }} total</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pet</th>
                        <th>Owner</th>
                        <th>Species</th>
                        <th>Breed</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pets as $pet)
                    <tr>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#68C4CF] to-[#5AB0BB] rounded-xl flex items-center justify-center">
                                    <span class="text-white text-lg">{{ $pet->species === 'Cat' ? '🐱' : ($pet->species === 'Dog' ? '🐕' : '🐾') }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $pet->pet_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $pet->color ?? 'No color' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="text-sm text-gray-800">{{ $pet->user->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $pet->user->email ?? '' }}</p>
                        </td>
                        <td>
                            <span class="text-gray-600">{{ $pet->species }}</span>
                        </td>
                        <td>
                            <span class="text-gray-600">{{ $pet->breed ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $pet->gender === 'male' ? 'badge-info' : 'bg-pink-100 text-pink-700' }}">
                                {{ ucfirst($pet->gender) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-gray-600">{{ $pet->age }}</span>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.pets.show', $pet) }}"
                                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-500">
                            <div class="flex flex-col items-center">
                                <span class="text-4xl mb-2">🐾</span>
                                <p>No pets found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-6 border-t border-gray-200">
            {{ $pets->links() }}
        </div>
    </div>
@endsection
