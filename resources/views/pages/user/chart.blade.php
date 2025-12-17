@extends('layouts.user')

@section('title', 'Paw Time - Growth Chart')

@section('content')
    {{-- Chart Page Custom Header --}}
    <header class="bg-[#68C4CF] text-white px-6 py-4 rounded-3xl shadow-lg -mx-6 -mt-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <button onclick="history.back()">
                    <x-ui.icon name="chevron-left" size="w-6 h-6" color="currentColor" class="text-white" />
                </button>
                <h1 class="text-xl font-bold">Growth Chart</h1>
            </div>
            <div class="flex items-center space-x-3">
                <button>
                    <x-ui.icon name="info" size="w-6 h-6" color="currentColor" class="text-white" />
                </button>
                <button>
                    <x-ui.icon name="search" size="w-6 h-6" color="currentColor" class="text-white" />
                </button>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="flex space-x-3 mt-4">
            <button onclick="window.switchTab('entry')" id="entryTab"
                class="tab-btn active flex-1 py-3 rounded-2xl font-semibold text-sm flex items-center justify-center space-x-2">
                <x-ui.icon name="clipboard" size="w-5 h-5" color="currentColor" />
                <span>Entry</span>
            </button>
            <button onclick="window.switchTab('chart')" id="chartTab"
                class="tab-btn flex-1 py-3 rounded-2xl font-semibold text-sm flex items-center justify-center space-x-2">
                <x-ui.icon name="chart" size="w-5 h-5" color="currentColor" />
                <span>Chart</span>
            </button>
        </div>
    </header>

    {{-- Entry Section --}}
    <div id="entryContent" class="content-section active">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Add New Data</h2>

        <form class="space-y-4">
            {{-- Date Input --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-gray-400">
                        <x-ui.icon name="calendar" size="w-5 h-5" color="currentColor" />
                    </span>
                    <input type="date" value="2026-01-05"
                        class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700 font-medium">
                </div>
            </div>

            {{-- Weight Input --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-gray-400">
                        <x-ui.icon name="weight" size="w-5 h-5" color="currentColor" />
                    </span>
                    <input type="number" placeholder="0.0" step="0.1"
                        class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700 font-medium">
                </div>
                <p class="text-xs text-gray-400 mt-1">Current weight</p>
            </div>

            {{-- Height Input --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Height (cm)</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-gray-400">
                        <x-ui.icon name="height" size="w-5 h-5" color="currentColor" />
                    </span>
                    <input type="number" placeholder="0.0" step="0.1"
                        class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700 font-medium">
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                    <input type="checkbox" class="w-4 h-4 mr-2 text-[#68C4CF] border-gray-300 rounded focus:ring-[#68C4CF]">
                    Notes (optional)
                </label>
                <textarea placeholder="Add notes here..." rows="4"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700 resize-none"></textarea>
                <p class="text-xs text-gray-400 mt-1">You can add notes about this entry</p>
            </div>

            {{-- Photo Upload --}}
            <div>
                <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                    <x-ui.icon name="image" size="w-5 h-5" color="currentColor" class="mr-2 text-gray-600" />
                    photo (optional)
                </label>
                <div
                    class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center cursor-pointer hover:border-[#68C4CF] transition bg-gray-50">
                    <x-ui.icon name="image" size="w-12 h-12" color="currentColor" class="mx-auto mb-2 text-gray-400" />
                    <p class="text-sm text-gray-500 font-medium">Add photo</p>
                </div>
            </div>

            {{-- Save Button --}}
            <button type="submit"
                class="btn-primary w-full py-4 rounded-2xl text-white font-semibold text-lg shadow-lg flex items-center justify-center space-x-2">
                <x-ui.icon name="save" size="w-5 h-5" color="currentColor" />
                <span>Save Entry</span>
            </button>
        </form>
    </div>

    {{-- Chart Section --}}
    <div id="chartContent" class="content-section">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Pet growth chart</h2>

        {{-- Chart Canvas --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm mb-6">
            <canvas id="growthChart" height="200"></canvas>
        </div>

        {{-- Chart Options --}}
        <div class="mb-6">
            <h3 class="text-sm font-bold text-gray-800 mb-3">Chart Options</h3>

            {{-- Time Filter --}}
            <div class="mb-4">
                <select
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700 font-medium">
                    <option>All time</option>
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                    <option>Last 6 months</option>
                    <option>Last year</option>
                </select>
            </div>

            {{-- Display Type --}}
            <div class="flex space-x-2">
                <button
                    class="filter-btn active flex-1 py-2.5 rounded-xl text-sm font-semibold border-2 border-transparent">
                    Month
                </button>
                <button
                    class="filter-btn flex-1 py-2.5 rounded-xl text-sm font-semibold border-2 border-gray-200 text-gray-600 hover:border-[#68C4CF] hover:text-[#68C4CF]">
                    Height
                </button>
                <button
                    class="view-btn flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold border-2 border-gray-200 text-gray-600">
                    <x-ui.icon name="list" size="w-5 h-5" color="currentColor" />
                </button>
                <button
                    class="view-btn flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold border-2 border-gray-200 text-gray-600">
                    Grid
                </button>
            </div>
        </div>

        {{-- Summary Card --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <div class="flex items-center space-x-3 mb-4">
                <div class="bg-green-100 p-2 rounded-lg">
                    <x-ui.icon name="trending-up" size="w-5 h-5" color="currentColor" class="text-green-600" />
                </div>
                <h3 class="font-bold text-gray-800">Summary Month Growth</h3>
            </div>

            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <span class="text-green-600 font-bold text-lg">+ 5.94 kg/mo</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-green-600 font-bold text-lg">+ 11.0 cm/mo</span>
                </div>
            </div>

            <p class="text-sm text-gray-600 leading-relaxed">
                Amazing growth from <span class="font-semibold text-gray-800">October 9</span> to now,
                <span class="font-semibold text-gray-800">7.2855 (30 days)</span>
            </p>
            <p class="text-sm text-gray-600 mt-2">
                "Great! 5.94 kg/mo won! You cat is growing healthily 😺
                Target the same for the next 3 weeks."
            </p>
        </div>
    </div>
@endsection


@push('scripts')
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Initialize Chart
        let chartInstance = null;

        function initChart() {
            if (chartInstance) return;

            const ctx = document.getElementById('growthChart').getContext('2d');

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Weight (kg)',
                        data: [3.2, 3.5, 4.1, 4.8, 5.5, 6.3, 7.2],
                        borderColor: '#FF8C42',
                        backgroundColor: 'rgba(255, 140, 66, 0.2)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#FF8C42',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#1F2937',
                            bodyColor: '#6B7280',
                            borderColor: '#E5E7EB',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' kg';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#F3F4F6'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + ' kg';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
