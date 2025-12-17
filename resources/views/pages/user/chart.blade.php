<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paw Time - Growth Chart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #F8F9FA;
        }

        .btn-primary {
            background: #68C4CF;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #5AB0BB;
            transform: translateY(-2px);
        }

        .tab-btn {
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            background: white;
            color: #68C4CF;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .filter-btn {
            transition: all 0.3s ease;
        }

        .filter-btn.active {
            background: #FFD4B2;
            color: #FF8C42;
        }

        .view-btn {
            transition: all 0.3s ease;
        }

        .view-btn.active {
            background: #E5E7EB;
        }

        .bubble {
            position: fixed;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            animation: bubble-float 4s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes bubble-float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-30px);
            }
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.5);
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }
    </style>
</head>

<body class="pb-20">
    <!-- Bubbles -->
    <div class="bubble" style="width: 40px; height: 40px; bottom: 10%; left: 5%; animation-delay: 0s;"></div>
    <div class="bubble" style="width: 60px; height: 60px; bottom: 20%; right: 10%; animation-delay: 1s;"></div>

    <!-- Header -->
    <header class="bg-[#68C4CF] text-white px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <button onclick="history.back()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h1 class="text-xl font-bold">Growth Chart</h1>
            </div>
            <div class="flex items-center space-x-3">
                <button>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
                <button>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex space-x-3 mt-4">
            <button onclick="switchTab('entry')" id="entryTab"
                class="tab-btn active flex-1 py-3 rounded-2xl font-semibold text-sm flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                </svg>
                <span>Entry</span>
            </button>
            <button onclick="switchTab('chart')" id="chartTab"
                class="tab-btn flex-1 py-3 rounded-2xl font-semibold text-sm flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z" />
                </svg>
                <span>Chart</span>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-6">

        <!-- Entry Section -->
        <div id="entryContent" class="content-section active">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Add New Data</h2>

            <form class="space-y-4">
                <!-- Date Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z" />
                            </svg>
                        </span>
                        <input type="date" value="2026-01-05"
                            class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700 font-medium">
                    </div>
                </div>

                <!-- Weight Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </span>
                        <input type="number" placeholder="0.0" step="0.1"
                            class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700 font-medium">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Current weight</p>
                </div>

                <!-- Height Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Height (cm)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm1-13h-2v4H8v2h3v4h2v-4h3v-2h-3z" />
                            </svg>
                        </span>
                        <input type="number" placeholder="0.0" step="0.1"
                            class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700 font-medium">
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                        <input type="checkbox"
                            class="w-4 h-4 mr-2 text-[#68C4CF] border-gray-300 rounded focus:ring-[#68C4CF]">
                        Notes (optional)
                    </label>
                    <textarea placeholder="Add notes here..." rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700 resize-none"></textarea>
                    <p class="text-xs text-gray-400 mt-1">You can add notes about this entry</p>
                </div>

                <!-- Photo Upload -->
                <div>
                    <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                        </svg>
                        photo (optional)
                    </label>
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center cursor-pointer hover:border-[#68C4CF] transition bg-gray-50">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                        </svg>
                        <p class="text-sm text-gray-500 font-medium">Add photo</p>
                    </div>
                </div>

                <!-- Save Button -->
                <button type="submit"
                    class="btn-primary w-full py-4 rounded-2xl text-white font-semibold text-lg shadow-lg flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z" />
                    </svg>
                    <span>Save Entry</span>
                </button>
            </form>
        </div>

        <!-- Chart Section -->
        <div id="chartContent" class="content-section">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Pet growth chart</h2>

            <!-- Chart Canvas -->
            <div class="bg-white rounded-2xl p-4 shadow-sm mb-6">
                <canvas id="growthChart" height="200"></canvas>
            </div>

            <!-- Chart Options -->
            <div class="mb-6">
                <h3 class="text-sm font-bold text-gray-800 mb-3">Chart Options</h3>

                <!-- Time Filter -->
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

                <!-- Display Type -->
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
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z" />
                        </svg>
                    </button>
                    <button
                        class="view-btn flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold border-2 border-gray-200 text-gray-600">
                        Grid
                    </button>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z" />
                        </svg>
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

    </main>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-around">
            <button class="flex flex-col items-center space-y-1 text-gray-400">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                </svg>
                <span class="text-xs font-semibold">Home</span>
            </button>

            <button class="flex flex-col items-center space-y-1 text-gray-400">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z" />
                </svg>
                <span class="text-xs font-semibold">Calendar</span>
            </button>

            <button class="bg-[#FF8C42] text-white rounded-full p-4 -mt-8 shadow-lg">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" />
                </svg>
            </button>

            <button class="flex flex-col items-center space-y-1 text-gray-400">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                </svg>
                <span class="text-xs font-semibold">Community</span>
            </button>

            <button class="flex flex-col items-center space-y-1 text-gray-400">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
                <span class="text-xs font-semibold">Profile</span>
            </button>
        </div>
    </nav>

    <script>
        // Tab Switching
        function switchTab(tab) {
            const entryTab = document.getElementById('entryTab');
            const chartTab = document.getElementById('chartTab');
            const entryContent = document.getElementById('entryContent');
            const chartContent = document.getElementById('chartContent');

            if (tab === 'entry') {
                entryTab.classList.add('active');
                chartTab.classList.remove('active');
                entryContent.classList.add('active');
                chartContent.classList.remove('active');
            } else {
                chartTab.classList.add('active');
                entryTab.classList.remove('active');
                chartContent.classList.add('active');
                entryContent.classList.remove('active');
                initChart();
            }
        }

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

        // Filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // View buttons
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>

</html>
