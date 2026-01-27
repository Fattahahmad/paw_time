@extends('layouts.user')

@section('title', 'Paw Time - Reminders')

@section('content')
    {{-- Reminder Page Custom Header --}}
    <header class="bg-[#68C4CF] text-white px-6 py-4 rounded-3xl shadow-lg -mx-6 -mt-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <button onclick="history.back()">
                    <x-ui.icon name="chevron-left" size="w-6 h-6" color="currentColor" class="text-white" />
                </button>
                <h1 class="text-xl font-bold">Reminders</h1>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="window.openAddReminderModal()">
                    <x-ui.icon name="plus" size="w-6 h-6" color="currentColor" class="text-white" />
                </button>
                <button>
                    <x-ui.icon name="more-vert" size="w-6 h-6" color="currentColor" class="text-white" />
                </button>
            </div>
        </div>

        {{-- Search Bar --}}
        <div class="relative mb-4">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <x-ui.icon name="search" size="w-5 h-5" color="currentColor" />
            </span>
            <input type="text" placeholder="Search reminders..."
                class="w-full pl-12 pr-4 py-3 rounded-2xl bg-white/20 text-white placeholder-white/70 focus:outline-none focus:bg-white/30">
        </div>

        {{-- Tab Navigation --}}
        <div class="flex border-b border-white/20">
            <button onclick="window.switchReminderTab('reminders')" id="remindersTab"
                class="reminder-tab-btn active flex-1 pb-3 text-sm font-semibold">
                Reminders
            </button>
            <button onclick="window.switchReminderTab('history')" id="historyTab"
                class="reminder-tab-btn flex-1 pb-3 text-sm text-white/80">
                History
            </button>
            <button onclick="window.switchReminderTab('calendar')" id="calendarTab"
                class="reminder-tab-btn flex-1 pb-3 text-sm text-white/80">
                Calendar
            </button>
        </div>
    </header>

    {{-- Reminders Content --}}
    <div id="remindersContent" class="content-section active">
        {{-- Filter by Repeat Type --}}
        <div class="mb-4">
            <h3 class="text-xs font-bold text-gray-500 uppercase mb-2">Filter by Schedule</h3>
            <div class="flex space-x-2 overflow-x-auto pb-2 scrollbar-hide" id="scheduleFilters">
                <x-ui.filter-chip label="All" :active="true" onclick="filterBySchedule('all')" />
                <x-ui.filter-chip label="Daily" onclick="filterBySchedule('daily')" />
                <x-ui.filter-chip label="Weekly" onclick="filterBySchedule('weekly')" />
                <x-ui.filter-chip label="Monthly" onclick="filterBySchedule('monthly')" />
                <x-ui.filter-chip label="Yearly" onclick="filterBySchedule('yearly')" />
                <x-ui.filter-chip label="One-time" onclick="filterBySchedule('none')" />
            </div>
        </div>

        {{-- Filter by Category --}}
        <div class="mb-6">
            <h3 class="text-xs font-bold text-gray-500 uppercase mb-2">Filter by Category</h3>
            <div class="flex space-x-2 overflow-x-auto pb-2 scrollbar-hide" id="categoryFilters">
                <x-ui.filter-chip label="All" :active="true" onclick="filterByCategory('all')" />
                <x-ui.filter-chip label="Feeding" onclick="filterByCategory('feeding')" />
                <x-ui.filter-chip label="Grooming" onclick="filterByCategory('grooming')" />
                <x-ui.filter-chip label="Vaccination" onclick="filterByCategory('vaccination')" />
                <x-ui.filter-chip label="Medication" onclick="filterByCategory('medication')" />
            </div>
        </div>

        {{-- Pending Reminders (Upcoming Tasks) --}}
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Upcoming Tasks</h3>

            {{-- Reminder Cards --}}
            @forelse($pendingReminders->take(10) as $reminder)
                @php
                    $iconMap = [
                        'feeding' => 'cart',
                        'grooming' => 'scissors',
                        'vaccination' => 'syringe',
                        'medication' => 'pill',
                        'checkup' => 'stethoscope',
                        'other' => 'bell',
                    ];
                    $colorMap = [
                        'feeding' => 'orange',
                        'grooming' => 'purple',
                        'vaccination' => 'blue',
                        'medication' => 'green',
                        'checkup' => 'red',
                        'other' => 'gray',
                    ];
                    $icon = $iconMap[$reminder->category] ?? 'bell';
                    $color = $colorMap[$reminder->category] ?? 'gray';
                @endphp
                <div class="reminder-card-wrapper" data-category="{{ $reminder->category }}">
                    <x-cards.reminder-card :title="$reminder->title" :pet="$reminder->pet->pet_name" :detail="$reminder->description ?? ucfirst($reminder->category)" :time="$reminder->remind_date->format('M d, Y - h:i A')"
                        :icon="$icon" :iconBg="$color" :borderColor="$color" :showActions="true">
                        <x-slot:actions>
                            <form action="{{ route('user.reminders.done', $reminder->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-edit px-4 py-2 rounded-xl text-xs font-bold transition">
                                    <x-ui.icon name="check" size="w-4 h-4" color="currentColor" class="inline mr-1" />
                                    Done
                                </button>
                            </form>
                            <form action="{{ route('user.reminders.destroy', $reminder->id) }}" method="POST"
                                class="inline" onsubmit="return confirm('Delete this reminder?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete px-4 py-2 rounded-xl text-xs font-bold transition">
                                    <x-ui.icon name="trash" size="w-4 h-4" color="currentColor" class="inline mr-1" />
                                    Delete
                                </button>
                            </form>
                        </x-slot:actions>
                    </x-cards.reminder-card>
                </div>
            @empty
                <div class="text-center py-8 bg-white rounded-2xl">
                    <p class="text-gray-500">No pending reminders. Click + to add new reminder.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Calendar Content --}}
    <div id="calendarContent" class="content-section">
        {{-- Calendar Container - Centered --}}
        <div class="calendar-wrapper mb-6">
            <div id="flatpickrCalendar"></div>
        </div>

        {{-- Tasks for Selected Date --}}
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4" id="selectedDateTitle">Tasks for Today</h3>

            <div class="space-y-3" id="calendarTasks">
                @forelse($pendingReminders->filter(function($r) { return $r->remind_date->isToday(); })->take(10) as $reminder)
                    @php
                        $iconMap = [
                            'feeding' => 'cart',
                            'grooming' => 'scissors',
                            'vaccination' => 'syringe',
                            'medication' => 'pill',
                            'checkup' => 'stethoscope',
                            'other' => 'bell',
                        ];
                        $colorMap = [
                            'feeding' => 'orange',
                            'grooming' => 'purple',
                            'vaccination' => 'blue',
                            'medication' => 'green',
                            'checkup' => 'red',
                            'other' => 'gray',
                        ];
                        $icon = $iconMap[$reminder->category] ?? 'bell';
                        $color = $colorMap[$reminder->category] ?? 'gray';
                    @endphp
                    <x-cards.reminder-card :title="$reminder->title" :pet="$reminder->pet->pet_name" :time="$reminder->remind_date->format('h:i A')" :icon="$icon"
                        :iconBg="$color" :borderColor="$color" />
                @empty
                    <p class="text-center text-gray-500 py-4">No tasks for today</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- History Content --}}
    <div id="historyContent" class="content-section">
        {{-- Completed Tasks ONLY --}}
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">History - Completed Tasks</h3>
            <div class="space-y-3">
                @forelse($doneReminders->take(20) as $reminder)
                    @php
                        $iconMap = [
                            'feeding' => 'cart',
                            'grooming' => 'scissors',
                            'vaccination' => 'syringe',
                            'medication' => 'pill',
                            'checkup' => 'stethoscope',
                            'other' => 'bell',
                        ];
                        $colorMap = [
                            'feeding' => 'orange',
                            'grooming' => 'purple',
                            'vaccination' => 'blue',
                            'medication' => 'green',
                            'checkup' => 'red',
                            'other' => 'gray',
                        ];
                        $icon = $iconMap[$reminder->category] ?? 'bell';
                        $color = $colorMap[$reminder->category] ?? 'gray';
                    @endphp
                    <x-cards.reminder-card :title="$reminder->title" :pet="$reminder->pet->pet_name" :detail="$reminder->description ?? ucfirst($reminder->category)" :time="$reminder->remind_date->format('M d, Y - h:i A')"
                        :icon="$icon" :iconBg="$color" :borderColor="'green'" :showActions="false" />
                @empty
                    <div class="text-center py-8 bg-white rounded-2xl">
                        <p class="text-gray-500">No completed reminders yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Add Reminder Modal --}}
    <div id="addReminderModal" class="modal">
        <div class="modal-content bg-white md:max-w-md rounded-t-3xl md:rounded-3xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6 sticky top-0 bg-white z-10 pb-4 border-b">
                <h2 class="text-xl font-bold text-gray-800">Add Reminder</h2>
                <button onclick="closeModal('addReminderModal')" class="p-2 hover:bg-gray-100 rounded-full">
                    <x-ui.icon name="close" size="w-6 h-6" color="currentColor" class="text-gray-500" />
                </button>
            </div>

            <form action="{{ route('user.reminders.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Select Pet</label>
                    <select name="pet_id" required
                        class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-gray-50 focus:border-[#68C4CF] focus:outline-none">
                        <option value="">Choose pet</option>
                        @if (isset($pets))
                            @foreach ($pets as $pet)
                                <option value="{{ $pet->id }}">{{ $pet->pet_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" placeholder="e.g. Morning Feeding" required
                        class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-gray-50 focus:border-[#68C4CF] focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                    <textarea name="description" placeholder="Additional details..." rows="2"
                        class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-gray-50 focus:border-[#68C4CF] focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Category</label>
                    <select name="category" required
                        class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-gray-50 focus:border-[#68C4CF] focus:outline-none">
                        <option value="feeding">🍖 Feeding</option>
                        <option value="grooming">✂️ Grooming</option>
                        <option value="vaccination">💉 Vaccination</option>
                        <option value="medication">💊 Medication</option>
                        <option value="checkup">🩺 Checkup</option>
                        <option value="other">📝 Other</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Date</label>
                        <input type="date" name="remind_date" required
                            class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-gray-50 focus:border-[#68C4CF] focus:outline-none text-gray-600">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Time</label>
                        <input type="time" name="remind_time" required
                            class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-gray-50 focus:border-[#68C4CF] focus:outline-none text-gray-600">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Repeat</label>
                    <select name="repeat_type"
                        class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-gray-50 focus:border-[#68C4CF] focus:outline-none">
                        <option value="none">No Repeat</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full btn-primary py-4 rounded-2xl text-white font-bold text-lg shadow-lg mt-4">
                    Save Reminder
                </button>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <x-ui.confirmation-modal id="deleteConfirmModal" title="Hapus Reminder?"
        message="Data yang dihapus tidak dapat dikembalikan" confirmText="Hapus" cancelText="Batal" confirmColor="red"
        icon="alert" iconColor="red" :showDetails="true" />
@endsection

@push('styles')
    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Calendar Wrapper - Center the calendar */
        .calendar-wrapper {
            display: flex;
            justify-content: center;
        }

        /* Custom Flatpickr Styles - Minimal overrides */
        .flatpickr-calendar {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: none;
            background: white;
            border-radius: 1rem;
            padding: 1rem;
        }

        /* Month Navigation - Custom Arrow Styling */
        .flatpickr-prev-month,
        .flatpickr-next-month {
            display: flex !important;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: transparent;
            transition: all 0.2s;
        }

        .flatpickr-prev-month:hover,
        .flatpickr-next-month:hover {
            background: rgba(147, 197, 253, 0.2);
        }

        .flatpickr-prev-month svg,
        .flatpickr-next-month svg {
            width: 16px;
            height: 16px;
            fill: #68C4CF;
        }

        .flatpickr-month {
            height: auto;
        }

        .flatpickr-current-month {
            padding: 0.5rem 0;
        }

        /* Month Dropdown - Blue background */
        .flatpickr-monthDropdown-months {
            background: #93C5FD !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.4rem 1.5rem 0.4rem 0.75rem !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            cursor: pointer !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='white' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.5rem center !important;
        }

        .flatpickr-monthDropdown-months:hover {
            background: #7CB5F5 !important;
        }

        .flatpickr-monthDropdown-months option {
            background: white !important;
            color: #1f2937 !important;
        }

        /* Year Input - Blue background SAMA seperti Month */
        .numInputWrapper {
            background: #93C5FD;
            border-radius: 0.5rem;
            border: none;
            padding: 0;
            height: auto;
        }

        .numInputWrapper:hover {
            background: #7CB5F5;
        }

        .numInputWrapper input,
        .numInputWrapper input.cur-year {
            background: transparent;
            color: white;
            border: none;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.4rem 0.75rem;
            text-align: center;
            width: 70px;
            height: auto;
            line-height: normal;
            margin: 0;
        }

        .numInputWrapper input:focus {
            outline: none;
        }

        /* Year Arrow Buttons - Custom Styling */
        .numInputWrapper span.arrowUp,
        .numInputWrapper span.arrowDown {
            display: block !important;
            width: 14px !important;
            height: 14px !important;
            padding: 0 !important;
            border: none !important;
            opacity: 0.9;
            transition: opacity 0.2s;
        }

        .numInputWrapper span.arrowUp:hover,
        .numInputWrapper span.arrowDown:hover {
            opacity: 1;
        }

        .numInputWrapper span.arrowUp:after,
        .numInputWrapper span.arrowDown:after {
            border-left-color: white !important;
            border-right-color: white !important;
            border-bottom-color: white !important;
            border-top-color: white !important;
        }

        /* Weekday Headers - Blue background */
        .flatpickr-weekdays {
            background: #93C5FD;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }

        .flatpickr-weekday {
            background: transparent;
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Calendar Days */
        .flatpickr-day {
            color: #1f2937;
            border: none;
            font-weight: 500;
            border-radius: 50%;
        }

        .flatpickr-day:hover {
            background: #E5E7EB;
            border: none;
        }

        /* Today */
        .flatpickr-day.today {
            background: transparent;
            border: 2px solid #68C4CF;
            font-weight: 700;
        }

        .flatpickr-day.today:hover {
            background: #E8F4F8;
            border: 2px solid #68C4CF;
        }

        /* Selected Day - Teal Circle */
        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: #68C4CF;
            color: white;
            border: none;
            font-weight: 700;
        }

        /* Prev/Next Month Days - Grayed out */
        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            color: #D1D5DB;
        }

        /* Days with events */
        .flatpickr-day.has-event::after {
            content: '';
            position: absolute;
            bottom: 0.25rem;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: #FF8C42;
            border-radius: 50%;
        }

        /* Custom Year Dropdown */
        .flatpickr-year-dropdown {
            background: #93C5FD !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.4rem 1.5rem 0.4rem 0.75rem !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            cursor: pointer !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='white' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.5rem center !important;
        }

        .flatpickr-year-dropdown:hover {
            background: #7CB5F5 !important;
        }

        .flatpickr-year-dropdown option {
            background: white !important;
            color: #1f2937 !important;
        }
    </style>
@endpush

@push('scripts')
    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Open Add Reminder Modal
        window.openAddReminderModal = function() {
            const modal = document.getElementById('addReminderModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex', 'show');
            }
        };

        // Handle URL query parameters on page load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            const action = urlParams.get('action');

            // Switch to specified tab
            if (tab === 'calendar') {
                window.switchReminderTab('calendar');
            } else if (tab === 'history') {
                window.switchReminderTab('history');
            }

            // Open add modal if action=add
            if (action === 'add') {
                setTimeout(() => {
                    window.openAddReminderModal();
                }, 300);
            }
        });

        // Close Modal
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'show');
            }
        }

        // Switch Reminder Tab
        window.switchReminderTab = function(tab) {
            const remindersTab = document.getElementById('remindersTab');
            const historyTab = document.getElementById('historyTab');
            const calendarTab = document.getElementById('calendarTab');
            const remindersContent = document.getElementById('remindersContent');
            const historyContent = document.getElementById('historyContent');
            const calendarContent = document.getElementById('calendarContent');

            // Remove active from all tabs
            remindersTab.classList.remove('active');
            historyTab.classList.remove('active');
            calendarTab.classList.remove('active');
            remindersContent.classList.remove('active');
            historyContent.classList.remove('active');
            calendarContent.classList.remove('active');

            // Add active to selected tab
            if (tab === 'reminders') {
                remindersTab.classList.add('active');
                remindersContent.classList.add('active');
            } else if (tab === 'history') {
                historyTab.classList.add('active');
                historyContent.classList.add('active');
            } else if (tab === 'calendar') {
                calendarTab.classList.add('active');
                calendarContent.classList.add('active');
                // Initialize calendar when switching to calendar tab
                if (!window.calendarInitialized) {
                    initFlatpickr();
                    window.calendarInitialized = true;
                }
            }
        };

        // Get all reminders data from backend
        @php
            $reminderData = $pendingReminders
                ->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'title' => $r->title,
                        'pet_name' => $r->pet->pet_name,
                        'description' => $r->description,
                        'category' => $r->category,
                        'repeat_type' => $r->repeat_type,
                        'remind_date' => $r->remind_date->format('Y-m-d'),
                        'remind_time' => $r->remind_date->format('h:i A'),
                        'remind_full' => $r->remind_date->format('M d, Y - h:i A'),
                    ];
                })
                ->values();
        @endphp
        const allReminders = @json($reminderData);

        // Current filter state
        let currentSchedule = 'all';
        let currentCategory = 'all';

        // Apply combined filters
        function applyFilters() {
            const cards = document.querySelectorAll('#remindersContent .reminder-card-wrapper');

            cards.forEach((card, index) => {
                const reminder = allReminders[index];
                if (!reminder) return;

                const matchSchedule = currentSchedule === 'all' || reminder.repeat_type === currentSchedule;
                const matchCategory = currentCategory === 'all' || reminder.category === currentCategory;

                if (matchSchedule && matchCategory) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Filter by schedule
        window.filterBySchedule = function(schedule) {
            currentSchedule = schedule;

            // Update active state
            const filters = document.querySelectorAll('#scheduleFilters .filter-chip');
            filters.forEach(btn => {
                const label = btn.textContent.trim().toLowerCase();
                if ((schedule === 'all' && label === 'all') ||
                    (schedule === 'none' && label === 'one-time') ||
                    (schedule !== 'all' && schedule !== 'none' && label === schedule)) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            applyFilters();
        };

        // Filter by category
        window.filterByCategory = function(category) {
            currentCategory = category;

            // Update active state
            const filters = document.querySelectorAll('#categoryFilters .filter-chip');
            filters.forEach(btn => {
                const label = btn.textContent.trim().toLowerCase();
                if (label === category || (category === 'all' && label === 'all')) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            applyFilters();
        };

        // Initialize Flatpickr
        function initFlatpickr() {
            // Get unique dates that have reminders
            const reminderDates = allReminders.map(r => r.remind_date);

            // Initialize Flatpickr with custom config
            const calendar = flatpickr("#flatpickrCalendar", {
                inline: true,
                dateFormat: "Y-m-d",
                defaultDate: new Date(),
                showMonths: 1,
                monthSelectorType: "dropdown",
                onChange: function(selectedDates, dateStr) {
                    // Update title and filter tasks by selected date
                    const selectedDate = new Date(dateStr);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    selectedDate.setHours(0, 0, 0, 0);

                    const titleEl = document.getElementById('selectedDateTitle');
                    if (selectedDate.getTime() === today.getTime()) {
                        titleEl.textContent = 'Tasks for Today';
                    } else {
                        const options = {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        };
                        titleEl.textContent = 'Tasks for ' + selectedDate.toLocaleDateString('en-US', options);
                    }

                    // Filter and display tasks for selected date
                    const tasksContainer = document.getElementById('calendarTasks');
                    const tasksForDate = allReminders.filter(r => r.remind_date === dateStr);

                    if (tasksForDate.length === 0) {
                        tasksContainer.innerHTML =
                            '<p class=\"text-gray-500 text-center py-8\">No tasks scheduled for this date</p>';
                    } else {
                        let tasksHtml = '';
                        tasksForDate.forEach(task => {
                            const colorMap = {
                                feeding: 'orange',
                                grooming: 'purple',
                                vaccination: 'blue',
                                medication: 'green',
                                checkup: 'red',
                                other: 'gray'
                            };
                            const iconMap = {
                                feeding: 'cart',
                                grooming: 'scissors',
                                vaccination: 'syringe',
                                medication: 'pill',
                                checkup: 'stethoscope',
                                other: 'bell'
                            };

                            const color = colorMap[task.category] || 'gray';
                            const icon = iconMap[task.category] || 'bell';

                            tasksHtml += `
                                <div class="card-reminder border-l-4 border-${color} bg-white rounded-lg p-4 mb-3 shadow-sm">
                                    <div class="flex items-start space-x-3">
                                        <div class="icon-box bg-${color}/10 p-2 rounded-lg">
                                            <i class="fas fa-${icon} text-${color}"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-800">${task.title}</h4>
                                            <p class="text-sm text-gray-600">${task.pet_name}</p>
                                            <p class="text-sm text-gray-500 mt-1">${task.description || task.category}</p>
                                            <p class="text-xs text-gray-400 mt-2">
                                                <i class="far fa-clock mr-1"></i>${task.remind_time}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        tasksContainer.innerHTML = tasksHtml;
                    }
                },
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // Mark days with reminders
                    const dayDate = fp.formatDate(dayElem.dateObj, 'Y-m-d');

                    // Check if this date has any reminders
                    if (reminderDates.includes(dayDate)) {
                        dayElem.classList.add('has-event');
                    }
                },
                onReady: function(selectedDates, dateStr, instance) {
                    // Convert year input to dropdown
                    const yearInput = instance.currentYearElement;
                    const numInputWrapper = yearInput.parentNode;

                    // Create year dropdown
                    const yearSelect = document.createElement('select');
                    yearSelect.className = 'flatpickr-year-dropdown';

                    // Generate year options (2024-2030)
                    const currentYear = new Date().getFullYear();
                    for (let year = currentYear - 1; year <= currentYear + 5; year++) {
                        const option = document.createElement('option');
                        option.value = year;
                        option.textContent = year;
                        if (year === currentYear) {
                            option.selected = true;
                        }
                        yearSelect.appendChild(option);
                    }

                    // Replace the entire numInputWrapper with the select
                    numInputWrapper.parentNode.replaceChild(yearSelect, numInputWrapper);

                    // Handle year change
                    yearSelect.addEventListener('change', function() {
                        instance.changeYear(parseInt(this.value));
                    });
                }
            });
        }
    </script>
@endpush
