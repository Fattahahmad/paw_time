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
        {{-- Filter Chips --}}
        <div class="flex space-x-2 mb-6 overflow-x-auto pb-2 scrollbar-hide">
            <button
                class="filter-chip active px-4 py-2 rounded-full text-sm font-semibold bg-white border border-gray-200 text-gray-700 whitespace-nowrap shadow-sm">
                All
            </button>
            <button
                class="filter-chip px-4 py-2 rounded-full text-sm font-semibold bg-white border border-gray-200 text-gray-700 whitespace-nowrap shadow-sm hover:bg-gray-50">
                Feeding
            </button>
            <button
                class="filter-chip px-4 py-2 rounded-full text-sm font-semibold bg-white border border-gray-200 text-gray-700 whitespace-nowrap shadow-sm hover:bg-gray-50">
                Grooming
            </button>
            <button
                class="filter-chip px-4 py-2 rounded-full text-sm font-semibold bg-white border border-gray-200 text-gray-700 whitespace-nowrap shadow-sm hover:bg-gray-50">
                Vet
            </button>
        </div>

        {{-- Date Header --}}
        <div class="mb-6">
            <div class="flex items-center space-x-2 mb-3">
                <x-ui.icon name="calendar" size="w-5 h-5" color="currentColor" class="text-gray-600" />
                <span class="text-sm font-bold text-gray-800">Today, 17 July 2024</span>
            </div>

            {{-- Reminder Cards --}}
            <div class="reminder-card bg-white rounded-2xl p-4 mb-3 shadow-sm border-l-4 border-orange-400">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-start space-x-3 flex-1">
                        <div class="bg-orange-100 p-2.5 rounded-xl">
                            <x-ui.icon name="cart" size="w-6 h-6" color="currentColor" class="text-orange-500" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 mb-1">Morning Feeding</h3>
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600">Bella</span>
                                <span>•</span>
                                <span>200g Dry Food</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">08:00 AM</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 mt-2">
                    <button
                        class="flex-1 bg-green-50 text-green-600 py-2 rounded-xl text-xs font-bold hover:bg-green-100 transition">Complete</button>
                </div>
            </div>

            <div class="reminder-card bg-white rounded-2xl p-4 mb-3 shadow-sm border-l-4 border-purple-400">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-start space-x-3 flex-1">
                        <div class="bg-purple-100 p-2.5 rounded-xl">
                            <x-ui.icon name="building" size="w-6 h-6" color="currentColor" class="text-purple-500" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 mb-1">Grooming Salon</h3>
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600">Mochi</span>
                                <span>•</span>
                                <span>Petshop Indonesia</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">02:00 PM</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 mt-2">
                    <button
                        class="flex-1 bg-green-50 text-green-600 py-2 rounded-xl text-xs font-bold hover:bg-green-100 transition">Complete</button>
                </div>
            </div>
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
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tasks for Today</h3>

            <div class="space-y-3" id="calendarTasks">
                <div class="reminder-card bg-white rounded-2xl p-4 shadow-sm border-l-4 border-orange-400">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 flex-1">
                            <div class="bg-orange-100 p-2 rounded-xl">
                                <x-ui.icon name="cart" size="w-5 h-5" color="currentColor" class="text-orange-500" />
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Morning Feeding</h4>
                                <p class="text-xs text-gray-500">Bella</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-semibold text-gray-700">08:00 AM</p>
                        </div>
                    </div>
                </div>

                <div class="reminder-card bg-white rounded-2xl p-4 shadow-sm border-l-4 border-purple-400">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 flex-1">
                            <div class="bg-purple-100 p-2 rounded-xl">
                                <x-ui.icon name="building" size="w-5 h-5" color="currentColor"
                                    class="text-purple-500" />
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Grooming</h4>
                                <p class="text-xs text-gray-500">Mochi</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-semibold text-gray-700">02:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- History Content --}}
    <div id="historyContent" class="content-section">
        {{-- Upcoming Tasks --}}
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Upcoming Tasks</h3>
            <div class="space-y-3">
                <div class="reminder-card bg-white rounded-2xl p-4 shadow-sm border-l-4 border-blue-400">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-100 p-2 rounded-xl">
                                <x-ui.icon name="meds" size="w-5 h-5" color="currentColor" class="text-blue-500" />
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Vaccination</h4>
                                <p class="text-xs text-gray-500">Bella • Tomorrow</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-blue-600 bg-blue-100 px-2 py-1 rounded">Pending</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Completed Tasks --}}
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4">Completed Tasks</h3>
            <div class="space-y-3">
                <div class="bg-gray-100 rounded-2xl p-4 shadow-sm opacity-75">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-gray-200 p-2 rounded-xl">
                                <x-ui.icon name="check-circle" size="w-5 h-5" color="currentColor"
                                    class="text-gray-500" />
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-600 text-sm line-through">Vaccination</h4>
                                <p class="text-xs text-gray-500">Bella • Yesterday</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-green-600 bg-green-100 px-2 py-1 rounded">Done</span>
                    </div>
                </div>

                <div class="bg-gray-100 rounded-2xl p-4 shadow-sm opacity-75">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-gray-200 p-2 rounded-xl">
                                <x-ui.icon name="check-circle" size="w-5 h-5" color="currentColor"
                                    class="text-gray-500" />
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-600 text-sm line-through">Evening Walk</h4>
                                <p class="text-xs text-gray-500">Mochi • Yesterday</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-green-600 bg-green-100 px-2 py-1 rounded">Done</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Reminder Modal --}}
    <div id="addReminderModal" class="modal items-end md:items-center justify-center">
        <div
            class="modal-content bg-white w-full md:max-w-md rounded-t-3xl md:rounded-3xl p-6 h-[85vh] md:h-auto overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Add Reminder</h2>
                <button onclick="window.closeAddReminderModal()" class="p-2 hover:bg-gray-100 rounded-full">
                    <x-ui.icon name="close" size="w-6 h-6" color="currentColor" class="text-gray-500" />
                </button>
            </div>

            <form class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Title</label>
                    <input type="text" placeholder="e.g. Morning Feeding"
                        class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-gray-50 focus:border-[#68C4CF] focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Category</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button"
                            class="category-btn active border px-4 py-2 rounded-xl text-sm font-semibold">Feeding</button>
                        <button type="button"
                            class="category-btn border px-4 py-2 rounded-xl text-sm font-semibold text-gray-600">Grooming</button>
                        <button type="button"
                            class="category-btn border px-4 py-2 rounded-xl text-sm font-semibold text-gray-600">Vet</button>
                        <button type="button"
                            class="category-btn border px-4 py-2 rounded-xl text-sm font-semibold text-gray-600">Walk</button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Date</label>
                        <input type="date"
                            class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-gray-50 focus:border-[#68C4CF] focus:outline-none text-gray-600">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Time</label>
                        <input type="time"
                            class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-gray-50 focus:border-[#68C4CF] focus:outline-none text-gray-600">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Repeat</label>
                    <div class="flex gap-2">
                        <button type="button"
                            class="repeat-btn active border px-4 py-2 rounded-xl text-sm font-semibold">Daily</button>
                        <button type="button"
                            class="repeat-btn border px-4 py-2 rounded-xl text-sm font-semibold text-gray-600">Weekly</button>
                        <button type="button"
                            class="repeat-btn border px-4 py-2 rounded-xl text-sm font-semibold text-gray-600">Monthly</button>
                    </div>
                </div>

                <button type="button" onclick="window.closeAddReminderModal()"
                    class="w-full btn-primary py-4 rounded-2xl text-white font-bold text-lg shadow-lg mt-4">
                    Save Reminder
                </button>
            </form>
        </div>
    </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr with custom config
            const calendar = flatpickr("#flatpickrCalendar", {
                inline: true,
                dateFormat: "Y-m-d",
                defaultDate: new Date(),
                showMonths: 1,
                monthSelectorType: "dropdown",
                onChange: function(selectedDates, dateStr) {
                    // Update tasks when date changes (optional)
                    console.log("Selected date:", dateStr);
                },
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // Mark days with events (demo data - tanggal dengan reminder)
                    const eventsOnDays = [15, 17, 18, 21, 25];
                    const day = dayElem.dateObj.getDate();
                    const month = dayElem.dateObj.getMonth();
                    const currentMonth = new Date().getMonth();

                    // Add has-event class for days with reminders
                    if (month === currentMonth && eventsOnDays.includes(day)) {
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
        });
    </script>
@endpush
