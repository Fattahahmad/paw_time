@extends('layouts.user')

@section('title', 'Paw Time - Dashboard')

@section('content')
    {{-- Gradient Card --}}
    <div class="gradient-card rounded-3xl p-6 mb-6 text-white relative overflow-hidden shadow-lg">
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-sm opacity-90 mb-1">Your Pet just got</p>
                <p class="text-lg font-semibold mb-1">a grooming session</p>
                <p class="text-sm opacity-90">at 10:45 AM</p>
                <button class="btn-primary mt-4 px-6 py-2 rounded-full text-sm font-semibold text-white">
                    See details
                </button>
            </div>
            <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=150&h=150&fit=crop" alt="Cat"
                class="w-32 h-32 rounded-2xl object-cover border-4 border-white/30">
        </div>
        <div class="flex justify-center space-x-2 mt-4 relative z-10">
            <div class="w-2 h-2 rounded-full bg-white"></div>
            <div class="w-2 h-2 rounded-full bg-white/40"></div>
            <div class="w-2 h-2 rounded-full bg-white/40"></div>
        </div>
    </div>

    {{-- Add Pet Prompt --}}
    <div class="bg-white rounded-2xl p-4 mb-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="bg-orange-100 p-3 rounded-full">
                <x-ui.icon name="paw" size="w-6 h-6" color="#FF8C42" />
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Ready to add your pet?</p>
            </div>
        </div>
        <button class="btn-primary px-4 py-2 rounded-full text-sm font-semibold text-white" onclick="openAddPetModal()">
            Add
        </button>
    </div>

    {{-- Quick Action --}}
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Quick Action</h2>
        <div class="grid grid-cols-4 gap-4">
            <button class="action-btn bg-white rounded-2xl p-4 text-center shadow-sm" onclick="openAddPetModal()">
                <div class="bg-orange-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <x-ui.icon name="plus" size="w-6 h-6" color="#FF8C42" />
                </div>
                <p class="text-xs font-semibold text-gray-700">Add Pet</p>
            </button>

            <button class="action-btn bg-white rounded-2xl p-4 text-center shadow-sm">
                <div class="bg-green-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <x-ui.icon name="calendar" size="w-6 h-6" color="#10b981" />
                </div>
                <p class="text-xs font-semibold text-gray-700">Pet Log</p>
            </button>

            <button class="action-btn bg-white rounded-2xl p-4 text-center shadow-sm">
                <div class="bg-blue-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <x-ui.icon name="clipboard" size="w-6 h-6" color="#3b82f6" />
                </div>
                <p class="text-xs font-semibold text-gray-700">Med Log</p>
            </button>

            <button class="action-btn bg-white rounded-2xl p-4 text-center shadow-sm">
                <div class="bg-purple-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <x-ui.icon name="users" size="w-6 h-6" color="#a855f7" />
                </div>
                <p class="text-xs font-semibold text-gray-700">Vet Visit</p>
            </button>
        </div>
    </div>

    {{-- Your Pets --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Your Pets</h2>
            <button class="text-[#68C4CF] font-semibold text-sm">See all</button>
        </div>

        <div class="pet-card bg-white rounded-2xl p-4 shadow-sm flex items-center justify-between cursor-pointer"
            onclick="openPetDetail()">
            <div class="flex items-center space-x-4">
                <div class="bg-orange-100 p-3 rounded-2xl">
                    <x-ui.icon name="paw" size="w-8 h-8" color="#FF8C42" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Bella Bulul</h3>
                    <p class="text-sm text-gray-500">American curl</p>
                    <p class="text-xs text-gray-400">3 Age • <span class="text-[#68C4CF]">Cat</span></p>
                </div>
            </div>
            <x-ui.icon name="chevron-right" size="w-6 h-6" color="currentColor" class="text-gray-400" />
        </div>
    </div>

    {{-- Upcoming Reminder --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Upcoming Reminder</h2>
            <button class="text-[#68C4CF] font-semibold text-sm">See all</button>
        </div>

        <div class="reminder-card bg-white rounded-2xl p-4 shadow-sm mb-3">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-start space-x-3">
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <x-ui.icon name="cart" size="w-5 h-5" color="#FF8C42" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Feeding</h3>
                        <p class="text-xs text-gray-500 mt-1">Bella</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-700">Fri, 25 Juni 2025</p>
                    <p class="text-xs text-gray-500 mt-1">10:00 AM</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button
                    class="flex-1 bg-green-100 text-green-700 py-2 rounded-lg text-sm font-semibold flex items-center justify-center space-x-1">
                    <x-ui.icon name="check" size="w-4 h-4" color="currentColor" />
                    <span>Done</span>
                </button>
                <button
                    class="flex-1 bg-red-100 text-red-700 py-2 rounded-lg text-sm font-semibold flex items-center justify-center space-x-1">
                    <x-ui.icon name="trash" size="w-4 h-4" color="currentColor" />
                    <span>Delete</span>
                </button>
            </div>
        </div>

        <div class="text-center py-8">
            <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3">
                <x-ui.icon name="info" size="w-10 h-10" color="currentColor" class="text-gray-400" />
            </div>
            <p class="text-sm text-gray-500">All caught up! 🎉</p>
        </div>
    </div>
@endsection

@push('modals')
    <x-ui.modal id="addPetModal" title="Add Pet Profile">
        <form class="space-y-5">
            <div class="flex flex-col items-center">
                <div
                    class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-2 cursor-pointer hover:bg-gray-200 transition">
                    <x-ui.icon name="image" size="w-10 h-10" color="currentColor" class="text-gray-400" />
                </div>
                <p class="text-sm text-gray-500">Upload Image</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                <input type="text" placeholder="Pet name"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Age</label>
                <input type="date"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button"
                        class="gender-btn active-male border-2 border-blue-300 bg-blue-50 text-blue-600 py-3 rounded-2xl font-semibold flex items-center justify-center space-x-2"
                        onclick="selectGender(this, 'male')">
                        <x-ui.icon name="male" size="w-5 h-5" color="currentColor" />
                        <span>Male</span>
                    </button>
                    <button type="button"
                        class="gender-btn border-2 border-gray-200 text-gray-600 py-3 rounded-2xl font-semibold flex items-center justify-center space-x-2 hover:border-pink-300 hover:bg-pink-50 hover:text-pink-600"
                        onclick="selectGender(this, 'female')">
                        <x-ui.icon name="female" size="w-5 h-5" color="currentColor" />
                        <span>Female</span>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-4 rounded-2xl text-white font-bold text-lg shadow-lg">
                Save Profile
            </button>
        </form>
    </x-ui.modal>
@endpush
