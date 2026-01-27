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

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <div class="mb-4">
            <x-ui.form-success :message="session('success')" />
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm font-medium text-red-800">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Entry Section --}}
    <div id="entryContent" class="content-section active">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Add New Data</h2>

        <form action="{{ route('user.growth.store') }}" method="POST" class="space-y-4">
            @csrf
            {{-- Pet Selection --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Select Pet</label>
                <select name="pet_id" required
                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700 font-medium">
                    <option value="">Choose pet</option>
                    @if (isset($pets))
                        @foreach ($pets as $pet)
                            <option value="{{ $pet->id }}"
                                {{ isset($selectedPetId) && $selectedPetId == $pet->id ? 'selected' : '' }}>
                                {{ $pet->pet_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            {{-- Date Input --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-gray-400">
                        <x-ui.icon name="calendar" size="w-5 h-5" color="currentColor" />
                    </span>
                    <input type="date" name="record_date" value="{{ date('Y-m-d') }}" required
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
                    <input type="number" name="weight" placeholder="0.0" step="0.1" required
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
                    <input type="number" name="height" placeholder="0.0" step="0.1"
                        class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700 font-medium">
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                    Notes (optional)
                </label>
                <textarea name="notes" placeholder="Add notes here..." rows="3"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700 resize-none"></textarea>
                <p class="text-xs text-gray-400 mt-1">You can add notes about this entry</p>
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

        {{-- Pet Selector --}}
        @if ($pets->count() > 0)
            <div class="mb-4">
                <select id="petSelector" onchange="window.location.href='{{ route('user.chart') }}?pet_id=' + this.value"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700 font-medium">
                    @foreach ($pets as $pet)
                        <option value="{{ $pet->id }}"
                            {{ isset($selectedPet) && $selectedPet->id == $pet->id ? 'selected' : '' }}>
                            {{ $pet->pet_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        {{-- Chart Canvas --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm mb-6">
            <canvas id="growthChart" height="80"></canvas>
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
                <button id="weightBtn" onclick="switchChartType('weight')"
                    class="filter-btn active flex-1 py-2.5 rounded-xl text-sm font-semibold border-2 border-transparent">
                    Weight
                </button>
                <button id="heightBtn" onclick="switchChartType('height')"
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
        @if (isset($selectedPet) && $growthData->count() >= 2)
            @php
                $firstEntry = $growthData->first();
                $lastEntry = $growthData->last();
                $daysDiff = $firstEntry->record_date->diffInDays($lastEntry->record_date);
                $monthsDiff = max(1, $daysDiff / 30);

                $weightDiff = $lastEntry->weight - $firstEntry->weight;
                $heightDiff = $lastEntry->height - $firstEntry->height;

                $weightPerMonth = $weightDiff / $monthsDiff;
                $heightPerMonth = $heightDiff / $monthsDiff;
            @endphp
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-{{ $weightDiff >= 0 ? 'green' : 'red' }}-100 p-2 rounded-lg">
                        <x-ui.icon name="trending-{{ $weightDiff >= 0 ? 'up' : 'down' }}" size="w-5 h-5"
                            color="currentColor" class="text-{{ $weightDiff >= 0 ? 'green' : 'red' }}-600" />
                    </div>
                    <h3 class="font-bold text-gray-800">Summary Month Growth</h3>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <div id="weightSummary" class="flex items-center space-x-2">
                        <span class="text-{{ $weightDiff >= 0 ? 'green' : 'red' }}-600 font-bold text-lg">
                            {{ $weightDiff >= 0 ? '+' : '' }} {{ number_format($weightPerMonth, 2) }} kg/mo
                        </span>
                    </div>
                    <div id="heightSummary" class="flex items-center space-x-2" style="display:none;">
                        <span class="text-{{ $heightDiff >= 0 ? 'green' : 'red' }}-600 font-bold text-lg">
                            {{ $heightDiff >= 0 ? '+' : '' }} {{ number_format($heightPerMonth, 2) }} cm/mo
                        </span>
                    </div>
                </div>

                <p class="text-sm text-gray-600 leading-relaxed">
                    Growth tracked from <span
                        class="font-semibold text-gray-800">{{ $firstEntry->record_date->format('M d, Y') }}</span> to
                    <span class="font-semibold text-gray-800">{{ $lastEntry->record_date->format('M d, Y') }}</span>
                    <span class="font-semibold text-gray-800">({{ $daysDiff }} days)</span>
                </p>
                <p id="summaryMessage" class="text-sm text-gray-600 mt-2">
                    @if ($weightPerMonth > 0)
                        "Great! {{ number_format($weightPerMonth, 2) }} kg/mo growth! Your
                        {{ strtolower($selectedPet->species) }} is growing healthily 🐾
                        Keep monitoring for the next few weeks."
                    @else
                        "Weight seems stable. Make sure to provide proper nutrition and exercise."
                    @endif
                </p>
            </div>

            {{-- Hidden data for JS --}}
            <script>
                window.summaryData = {
                    weightPerMonth: {{ number_format($weightPerMonth, 2) }},
                    heightPerMonth: {{ number_format($heightPerMonth, 2) }},
                    weightDiff: {{ $weightDiff }},
                    heightDiff: {{ $heightDiff }},
                    species: '{{ strtolower($selectedPet->species) }}'
                };
            </script>
        @else
            <div class="bg-white rounded-2xl p-5 shadow-sm text-center">
                <p class="text-gray-500">Add at least 2 growth entries to see summary</p>
            </div>
        @endif
    </div>
@endsection


@push('scripts')
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Handle URL query parameters on page load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');

            // Switch to chart tab if specified
            if (tab === 'chart') {
                setTimeout(() => {
                    window.switchTab('chart');
                }, 100);
            }
        });

        // Tab Switching Function
        window.switchTab = function(tab) {
            const entryTab = document.getElementById('entryTab');
            const chartTab = document.getElementById('chartTab');
            const entryContent = document.getElementById('entryContent');
            const chartContent = document.getElementById('chartContent');

            if (tab === 'entry') {
                // Activate Entry Tab
                entryTab.classList.add('active');
                chartTab.classList.remove('active');
                entryContent.classList.add('active');
                chartContent.classList.remove('active');
            } else if (tab === 'chart') {
                // Activate Chart Tab
                chartTab.classList.add('active');
                entryTab.classList.remove('active');
                chartContent.classList.add('active');
                entryContent.classList.remove('active');

                // Initialize chart when switching to chart tab
                setTimeout(() => {
                    initChart();
                }, 100);
            }
        };

        // Initialize Chart
        let chartInstance = null;
        window.growthDatasets = {
            weight: [],
            height: []
        };

        function initChart() {
            if (chartInstance) return;

            const ctx = document.getElementById('growthChart').getContext('2d');

            // Get growth data from backend
            const growthData = @json($growthData);

            let labels = [];
            let weightData = [];
            let heightData = [];

            if (growthData && growthData.length > 0) {
                growthData.forEach(entry => {
                    // Format date
                    const date = new Date(entry.record_date);
                    labels.push(date.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    }));
                    weightData.push(entry.weight || 0);
                    heightData.push(entry.height || 0);
                });
            } else {
                // Default empty data
                labels = ['No data'];
                weightData = [0];
                heightData = [0];
            }

            // Store datasets globally
            window.growthDatasets = {
                weight: weightData,
                height: heightData
            };

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Weight (kg)',
                        data: weightData,
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

        // Function to switch chart type
        window.switchChartType = function(type) {
            if (!chartInstance) return;

            const weightBtn = document.getElementById('weightBtn');
            const heightBtn = document.getElementById('heightBtn');
            const weightSummary = document.getElementById('weightSummary');
            const heightSummary = document.getElementById('heightSummary');
            const summaryMessage = document.getElementById('summaryMessage');

            if (type === 'weight') {
                // Update buttons
                weightBtn.classList.add('active');
                weightBtn.classList.remove('border-gray-200', 'text-gray-600');
                heightBtn.classList.remove('active');
                heightBtn.classList.add('border-gray-200', 'text-gray-600');

                // Update chart
                chartInstance.data.datasets[0].label = 'Weight (kg)';
                chartInstance.data.datasets[0].data = window.growthDatasets.weight;
                chartInstance.options.scales.y.ticks.callback = function(value) {
                    return value + ' kg';
                };
                chartInstance.options.plugins.tooltip.callbacks.label = function(context) {
                    return context.parsed.y + ' kg';
                };

                // Update summary
                weightSummary.style.display = 'flex';
                heightSummary.style.display = 'none';

                if (window.summaryData) {
                    const data = window.summaryData;
                    if (data.weightDiff > 0) {
                        summaryMessage.innerHTML =
                            `\"Great! ${data.weightPerMonth} kg/mo growth! Your ${data.species} is growing healthily 🐾 Keep monitoring for the next few weeks.\"`;
                    } else {
                        summaryMessage.innerHTML =
                            '\"Weight seems stable. Make sure to provide proper nutrition and exercise.\"';
                    }
                }
            } else if (type === 'height') {
                // Update buttons
                heightBtn.classList.add('active');
                heightBtn.classList.remove('border-gray-200', 'text-gray-600');
                weightBtn.classList.remove('active');
                weightBtn.classList.add('border-gray-200', 'text-gray-600');

                // Update chart
                chartInstance.data.datasets[0].label = 'Height (cm)';
                chartInstance.data.datasets[0].data = window.growthDatasets.height;
                chartInstance.options.scales.y.ticks.callback = function(value) {
                    return value + ' cm';
                };
                chartInstance.options.plugins.tooltip.callbacks.label = function(context) {
                    return context.parsed.y + ' cm';
                };

                // Update summary
                weightSummary.style.display = 'none';
                heightSummary.style.display = 'flex';

                if (window.summaryData) {
                    const data = window.summaryData;
                    if (data.heightDiff > 0) {
                        summaryMessage.innerHTML =
                            `\"Excellent! ${data.heightPerMonth} cm/mo height growth! Your ${data.species} is developing well 🐾 Continue tracking progress.\"`;
                    } else {
                        summaryMessage.innerHTML = '\"Height seems stable. This is normal for adult pets.\"';
                    }
                }
            }

            chartInstance.update();
        };

        // Initialize chart on page load if on chart tab
        document.addEventListener('DOMContentLoaded', function() {
            const chartContent = document.getElementById('chartContent');
            if (chartContent && chartContent.classList.contains('active')) {
                initChart();
            }
        });
    </script>
@endpush
