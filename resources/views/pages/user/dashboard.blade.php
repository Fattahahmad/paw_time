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
            <x-ui.action-button icon="plus" iconColor="#FF8C42" iconBg="orange" label="Add Pet"
                onclick="openAddPetModal()" />
            <x-ui.action-button icon="calendar" iconColor="#10b981" iconBg="green" label="Pet Log" />
            <x-ui.action-button icon="clipboard" iconColor="#3b82f6" iconBg="blue" label="Med Log" />
            <x-ui.action-button icon="users" iconColor="#a855f7" iconBg="purple" label="Vet Visit" />
        </div>
    </div>

    {{-- Your Pets --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Your Pets</h2>
            <button class="text-[#68C4CF] font-semibold text-sm">See all</button>
        </div>

        <x-cards.pet-card name="Bella Bulul" breed="American curl" age="3" type="Cat" icon="paw"
            iconBg="orange" iconColor="#FF8C42" onclick="openPetDetail()" />
    </div>

    {{-- Upcoming Reminder --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Upcoming Reminder</h2>
            <button class="text-[#68C4CF] font-semibold text-sm">See all</button>
        </div>

        <x-cards.reminder-card title="Feeding" pet="Bella" date="Fri, 25 Juni 2025" time="10:00 AM" icon="cart"
            iconBg="orange" borderColor="orange">
            <x-slot:actions>
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
            </x-slot:actions>
        </x-cards.reminder-card>

        <x-ui.empty-state message="All caught up! 🎉" />
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
                <x-ui.gender-selector selected="male" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pet Type</label>
                <input type="text" placeholder="Dog, Cat, etc."
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Breed</label>
                <input type="text" placeholder="Golden Retriever"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Height (cm)</label>
                <input type="text" placeholder="Ex : 40 cm"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
                <input type="text" placeholder="Ex : 5 kg"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Colour</label>
                <input type="text" placeholder="mix brown"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>

            <button type="submit" class="btn-primary w-full py-4 rounded-2xl text-white font-bold text-lg shadow-lg">
                Save Profile
            </button>
        </form>
    </x-ui.modal>
@endpush
