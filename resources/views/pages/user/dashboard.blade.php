@extends('layouts.user')

@section('title', 'Paw Time - Dashboard')

@section('content')
    {{-- Notification Permission Banner --}}
    <div id="notificationBanner" class="hidden mb-4 bg-gradient-to-r from-[#68C4CF] to-[#5AB0BB] rounded-2xl p-4 shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div class="text-white">
                    <p class="font-semibold">Aktifkan Notifikasi</p>
                    <p class="text-sm opacity-90">Dapatkan pengingat untuk perawatan hewan peliharaanmu</p>
                </div>
            </div>
            <button onclick="enableNotifications()" class="bg-white text-[#68C4CF] px-4 py-2 rounded-full text-sm font-semibold hover:bg-gray-100 transition">
                Aktifkan
            </button>
        </div>
    </div>

    <script>
        // Show notification banner if permission not granted
        document.addEventListener('DOMContentLoaded', function() {
            if ('Notification' in window && Notification.permission === 'default') {
                document.getElementById('notificationBanner').classList.remove('hidden');
            }
        });
    </script>

    {{-- Upcoming Reminder Banner --}}
    @if($upcomingReminders->count() > 0)
    <div x-data="{ currentSlide: 0, totalSlides: {{ $upcomingReminders->count() }} }" class="mb-6">
        <div class="relative overflow-hidden rounded-3xl">
            @foreach($upcomingReminders->take(5) as $index => $reminder)
            @php
                $categoryText = match($reminder->category) {
                    'feeding' => 'a feeding time',
                    'grooming' => 'a grooming session',
                    'vaccination' => 'a vaccination',
                    'medication' => 'a medication time',
                    'checkup' => 'a checkup appointment',
                    default => 'a reminder'
                };
            @endphp
            <div x-show="currentSlide === {{ $index }}"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-full"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform -translate-x-full"
                 class="gradient-card rounded-3xl p-6 text-white relative overflow-hidden shadow-lg">
                <div class="flex items-center justify-between relative z-10">
                    <div class="flex-1">
                        <p class="text-sm opacity-90 mb-1">{{ $reminder->pet->pet_name }} just got</p>
                        <p class="text-lg font-semibold mb-1">{{ $categoryText }}</p>
                        <p class="text-sm opacity-90">at {{ \Carbon\Carbon::parse($reminder->remind_date)->format('h:i A') }}</p>
                        <a href="{{ route('user.reminder') }}" class="inline-block btn-primary mt-4 px-6 py-2 rounded-full text-sm font-semibold text-white hover:bg-white/20 transition-colors">
                            See details
                        </a>
                    </div>
                    <img src="{{ $reminder->pet->image }}" alt="{{ $reminder->pet->pet_name }}"
                        class="w-32 h-32 rounded-2xl object-cover border-4 border-white/30 ml-4 flex-shrink-0"
                        onerror="this.src='https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=400&h=400&fit=crop'">
                </div>
            </div>
            @endforeach
        </div>

        {{-- Carousel Controls --}}
        @if($upcomingReminders->count() > 1)
        <div class="flex items-center justify-center space-x-4 mt-4">
            <button @click="currentSlide = currentSlide > 0 ? currentSlide - 1 : totalSlides - 1"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <div class="flex space-x-2">
                @foreach($upcomingReminders->take(5) as $index => $reminder)
                <button @click="currentSlide = {{ $index }}"
                        :class="currentSlide === {{ $index }} ? 'bg-[#68C4CF]' : 'bg-gray-300'"
                        class="w-2 h-2 rounded-full transition-colors"></button>
                @endforeach
            </div>
            <button @click="currentSlide = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
        @endif
    </div>
    @else
    {{-- Empty State --}}
    <div class="gradient-card rounded-3xl p-8 mb-6 text-white relative overflow-hidden shadow-lg">
        <div class="text-center relative z-10">
            <div class="inline-block bg-white/20 p-4 rounded-full mb-4">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-lg font-semibold mb-2">No Upcoming Reminders</p>
            <p class="text-sm opacity-90 mb-4">Create a reminder to never miss your pet's important moments</p>
            <a href="{{ route('user.reminder') }}?action=add" class="inline-block btn-primary px-6 py-2 rounded-full text-sm font-semibold text-white hover:bg-white/20 transition-colors">
                Add Reminder
            </a>
        </div>
    </div>
    @endif

    {{-- Your Pets / Add Pet Prompt --}}
    @if ($stats['total_pets'] > 0)
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800">Your Pets ({{ $stats['total_pets'] }})</h2>
                <a href="{{ route('user.profile') }}" class="text-[#68C4CF] font-semibold text-sm hover:text-[#5AB0BB]">See
                    all</a>
            </div>

            @foreach ($pets->take(3) as $pet)
                <div class="mb-3">
                    <x-cards.pet-card name="{{ $pet->pet_name }}" breed="{{ $pet->breed ?? 'Mixed' }}"
                        age="{{ $pet->age }}" type="{{ $pet->species }}" :image="$pet->image_url" />
                </div>
            @endforeach
        </div>
    @else
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
    @endif

    {{-- Quick Action --}}
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Quick Action</h2>
        <div class="grid grid-cols-4 gap-4">
            <x-ui.action-button icon="plus" iconColor="#FF8C42" iconBg="orange" label="Add Pet"
                onclick="openAddPetModal()" />

            <a href="{{ route('user.reminder') }}?action=add" class="block">
                <div class="action-btn bg-white rounded-2xl p-4 text-center shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                    <div class="bg-blue-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <x-ui.icon name="plus" size="w-6 h-6" color="#3b82f6" />
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Add Task</p>
                </div>
            </a>

            <a href="{{ route('user.chart') }}?tab=chart" class="block">
                <div class="action-btn bg-white rounded-2xl p-4 text-center shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                    <div class="bg-purple-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <x-ui.icon name="chart" size="w-6 h-6" color="#a855f7" />
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Growth Chart</p>
                </div>
            </a>

            <a href="{{ route('user.reminder') }}?tab=calendar" class="block">
                <div class="action-btn bg-white rounded-2xl p-4 text-center shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                    <div class="bg-green-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <x-ui.icon name="calendar" size="w-6 h-6" color="#10b981" />
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Calendar</p>
                </div>
            </a>
        </div>
    </div>



    {{-- Upcoming Reminder --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Upcoming Reminder ({{ $stats['pending_reminders'] }})</h2>
            <a href="{{ route('user.reminder') }}" class="text-[#68C4CF] font-semibold text-sm hover:text-[#5AB0BB]">See
                all</a>
        </div>

        @forelse($upcomingReminders as $reminder)
            <div class="mb-3">
                <x-cards.reminder-card title="{{ $reminder->title }}" pet="{{ $reminder->pet->pet_name }}"
                    date="{{ $reminder->remind_date ? \Carbon\Carbon::parse($reminder->remind_date)->format('D, d M Y') : '-' }}"
                    time="{{ $reminder->remind_date ? \Carbon\Carbon::parse($reminder->remind_date)->format('H:i') : '-' }}"
                    icon="calendar" iconBg="blue" borderColor="blue">
                    <x-slot:actions>
                        <form action="{{ route('user.reminders.done', $reminder) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="w-full bg-green-100 text-green-700 py-2 rounded-lg text-sm font-semibold flex items-center justify-center space-x-1 hover:bg-green-200">
                                <x-ui.icon name="check" size="w-4 h-4" color="currentColor" />
                                <span>Done</span>
                            </button>
                        </form>
                        <form action="{{ route('user.reminders.destroy', $reminder) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this reminder?')"
                                class="w-full bg-red-100 text-red-700 py-2 rounded-lg text-sm font-semibold flex items-center justify-center space-x-1 hover:bg-red-200">
                                <x-ui.icon name="trash" size="w-4 h-4" color="currentColor" />
                                <span>Delete</span>
                            </button>
                        </form>
                    </x-slot:actions>
                </x-cards.reminder-card>
            </div>
        @empty
            <x-ui.empty-state message="All caught up! 🎉" />
        @endforelse
    </div>
@endsection

@push('modals')
    <x-ui.modal id="addPetModal" title="Add Pet Profile">
        <form action="{{ route('user.pets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="flex flex-col items-center">
                <label for="pet_image" class="cursor-pointer">
                    <div id="imagePreview"
                        class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-2 hover:bg-gray-200 transition overflow-hidden">
                        <x-ui.icon name="image" size="w-10 h-10" color="currentColor" class="text-gray-400"
                            id="placeholderIcon" />
                    </div>
                </label>
                <input type="file" id="pet_image" name="image" accept="image/*" class="hidden"
                    onchange="previewImage(event)">
                <p class="text-sm text-gray-500">Upload Image (optional)</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                <input type="text" name="pet_name" placeholder="Pet name" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Birth Date</label>
                <input type="date" name="birth_date" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
                <x-ui.gender-selector name="gender" selected="male" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pet Type</label>
                <select name="species" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                    <option value="">Select type</option>
                    <option value="Cat">Cat</option>
                    <option value="Dog">Dog</option>
                    <option value="Bird">Bird</option>
                    <option value="Rabbit">Rabbit</option>
                    <option value="Hamster">Hamster</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Breed</label>
                <input type="text" name="breed" placeholder="Golden Retriever"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Height (cm)</label>
                <input type="number" name="height" step="0.1" placeholder="Ex : 40"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
                <input type="number" name="weight" step="0.1" placeholder="Ex : 5"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Colour</label>
                <input type="text" name="color" placeholder="mix brown"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" placeholder="Tell us about your pet..." rows="2"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50"></textarea>
            </div>

            <button type="submit" class="btn-primary w-full py-4 rounded-2xl text-white font-bold text-lg shadow-lg">
                Save Profile
            </button>
        </form>
    </x-ui.modal>
@endpush

@push('scripts')
    <script>
        function openAddPetModal() {
            const modal = document.getElementById('addPetModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex', 'show');
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'show');
            }
        }

        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    const icon = document.getElementById('placeholderIcon');
                    if (icon) icon.remove();
                    preview.innerHTML = '<img src="' + e.target.result +
                        '" class="w-full h-full object-cover rounded-full">';
                }
                reader.readAsDataURL(file);
            }
        }

        // Close modal on outside click
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal') && e.target.classList.contains('show')) {
                closeModal(e.target.id);
            }
        });
    </script>
@endpush
