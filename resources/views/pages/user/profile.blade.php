@extends('layouts.user')

@section('title', 'Paw Time - Profile')

@section('content')
    {{-- Profile List View --}}
    <div id="profileList" class="content-section active">
        {{-- Header --}}
        <div class="bg-[#68C4CF] text-white px-6 py-4 mb-6 -mx-6 -mt-6">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold">Your Family</h1>
                <button>
                    <x-ui.icon name="more-vert" size="w-6 h-6" color="currentColor" />
                </button>
            </div>
        </div>

        {{-- Filter Chips --}}
        <div class="flex flex-wrap gap-2 mb-6">
            <button
                class="filter-chip active px-4 py-2 rounded-full text-sm font-semibold border-2 border-gray-300 text-gray-700">
                All
            </button>
            <button class="filter-chip px-4 py-2 rounded-full text-sm font-semibold border-2 border-gray-300 text-gray-700">
                Cat
            </button>
            <button class="filter-chip px-4 py-2 rounded-full text-sm font-semibold border-2 border-gray-300 text-gray-700">
                Dog
            </button>
            <button class="filter-chip px-4 py-2 rounded-full text-sm font-semibold border-2 border-gray-300 text-gray-700">
                Other
            </button>
        </div>

        {{-- Cat Section --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <span class="filter-chip px-4 py-1.5 rounded-full text-sm font-semibold bg-gray-200 text-gray-700">
                    Cat
                </span>
                <button class="add-link">+ Add Cat</button>
            </div>

            <div class="pet-card bg-white rounded-2xl p-4 shadow-sm flex items-center justify-between cursor-pointer"
                onclick="window.showDetailProfile()">
                <div class="flex items-center space-x-4">
                    <div class="bg-orange-100 p-3 rounded-2xl">
                        <x-ui.icon name="paw" size="w-10 h-10" color="#FF8C42" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Bella Bulul</h3>
                        <p class="text-sm text-gray-500">American curl</p>
                        <p class="text-xs text-gray-400">3 Age • <span class="info-badge inline-block">Cat</span></p>
                    </div>
                </div>
                <x-ui.icon name="chevron-right" size="w-6 h-6" color="currentColor" class="text-gray-400" />
            </div>
        </div>

        {{-- Dog Section --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <span class="filter-chip px-4 py-1.5 rounded-full text-sm font-semibold bg-gray-200 text-gray-700">
                    Dog
                </span>
                <button class="add-link">+ Add Dog</button>
            </div>

            <div class="pet-card bg-white rounded-2xl p-4 shadow-sm flex items-center justify-between cursor-pointer"
                onclick="window.showDetailProfile()">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 p-3 rounded-2xl">
                        <x-ui.icon name="paw" size="w-10 h-10" color="#2563EB" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Bella Bulul</h3>
                        <p class="text-sm text-gray-500">American curl</p>
                        <p class="text-xs text-gray-400">3 Age • <span class="info-badge inline-block">Dog</span></p>
                    </div>
                </div>
                <x-ui.icon name="chevron-right" size="w-6 h-6" color="currentColor" class="text-gray-400" />
            </div>
        </div>

        {{-- Other Section --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <span class="filter-chip px-4 py-1.5 rounded-full text-sm font-semibold bg-gray-200 text-gray-700">
                    Other
                </span>
                <button class="add-link">+ Add Pet</button>
            </div>

            <div class="pet-card bg-white rounded-2xl p-4 shadow-sm flex items-center justify-between cursor-pointer"
                onclick="window.showDetailProfile()">
                <div class="flex items-center space-x-4">
                    <div class="bg-purple-100 p-3 rounded-2xl">
                        <x-ui.icon name="paw" size="w-10 h-10" color="#7C3AED" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Bella Bulul</h3>
                        <p class="text-sm text-gray-500">American curl</p>
                        <p class="text-xs text-gray-400">3 Age • <span class="info-badge inline-block">Other</span></p>
                    </div>
                </div>
                <x-ui.icon name="chevron-right" size="w-6 h-6" color="currentColor" class="text-gray-400" />
            </div>
        </div>
    </div>

    {{-- Detail Profile View --}}
    <div id="detailProfile" class="content-section">
        <div class="relative -mx-6 -mt-6">
            <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=800&h=400&fit=crop" alt="Pet"
                class="w-full h-64 object-cover">
            <button onclick="window.showProfileList()"
                class="absolute top-6 left-6 bg-white/90 backdrop-blur-sm p-2 rounded-full hover:bg-white transition">
                <x-ui.icon name="chevron-left" size="w-6 h-6" color="currentColor" class="text-gray-700" />
            </button>
            <button class="absolute top-6 right-6 bg-white/90 backdrop-blur-sm p-2 rounded-full hover:bg-white transition">
                <x-ui.icon name="more-vert" size="w-6 h-6" color="currentColor" class="text-gray-700" />
            </button>
        </div>

        <div class="bg-white rounded-t-3xl -mt-8 relative z-10 px-6 pt-6 -mx-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Bella Bulul</h1>
                    <p class="text-sm text-gray-600">American curl</p>
                </div>
                <button class="bg-pink-500 text-white p-3 rounded-2xl hover:bg-pink-600 transition">
                    <x-ui.icon name="heart" size="w-6 h-6" color="currentColor" />
                </button>
            </div>

            {{-- About Section --}}
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-800 mb-3">About Bella</h2>
                <div class="grid grid-cols-4 gap-3 mb-4">
                    <div class="text-center">
                        <div class="bg-[#D4F1F4] rounded-xl p-3 mb-1">
                            <p class="text-xs text-gray-600 font-medium">Age</p>
                            <p class="text-sm font-bold text-gray-800">1y 9m 11d</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="bg-[#D4F1F4] rounded-xl p-3 mb-1">
                            <p class="text-xs text-gray-600 font-medium">Weight</p>
                            <p class="text-sm font-bold text-gray-800">7.5 kg</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="bg-[#D4F1F4] rounded-xl p-3 mb-1">
                            <p class="text-xs text-gray-600 font-medium">Height</p>
                            <p class="text-sm font-bold text-gray-800">84 cm</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="bg-[#D4F1F4] rounded-xl p-3 mb-1">
                            <p class="text-xs text-gray-600 font-medium">Color</p>
                            <p class="text-sm font-bold text-gray-800">Black</p>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">
                    My first cat which was gifted by my mother for my 20th birthday.
                </p>
            </div>

            {{-- Menu Items --}}
            <div class="space-y-2 pb-6">
                <div class="menu-item flex items-center justify-between p-4 rounded-xl cursor-pointer">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <x-ui.icon name="paw" size="w-5 h-5" color="#2563EB" />
                        </div>
                        <span class="font-semibold text-gray-800">Pet Information</span>
                    </div>
                    <x-ui.icon name="chevron-right" size="w-5 h-5" color="currentColor" class="text-gray-400" />
                </div>

                <div class="menu-item flex items-center justify-between p-4 rounded-xl cursor-pointer">
                    <div class="flex items-center space-x-3">
                        <div class="bg-orange-100 p-2 rounded-lg">
                            <x-ui.icon name="cart" size="w-5 h-5" color="#EA580C" />
                        </div>
                        <span class="font-semibold text-gray-800">Add Food Reminder</span>
                    </div>
                    <x-ui.icon name="chevron-right" size="w-5 h-5" color="currentColor" class="text-gray-400" />
                </div>

                <div class="menu-item flex items-center justify-between p-4 rounded-xl cursor-pointer">
                    <div class="flex items-center space-x-3">
                        <div class="bg-purple-100 p-2 rounded-lg">
                            <x-ui.icon name="clipboard" size="w-5 h-5" color="#7C3AED" />
                        </div>
                        <span class="font-semibold text-gray-800">Add Pet Data</span>
                    </div>
                    <x-ui.icon name="chevron-right" size="w-5 h-5" color="currentColor" class="text-gray-400" />
                </div>

                <div class="menu-item flex items-center justify-between p-4 rounded-xl cursor-pointer">
                    <div class="flex items-center space-x-3">
                        <div class="bg-pink-100 p-2 rounded-lg">
                            <x-ui.icon name="building" size="w-5 h-5" color="#DB2777" />
                        </div>
                        <span class="font-semibold text-gray-800">Add Grooming schedule</span>
                    </div>
                    <x-ui.icon name="chevron-right" size="w-5 h-5" color="currentColor" class="text-gray-400" />
                </div>
            </div>
        </div>
    </div>
@endsection
