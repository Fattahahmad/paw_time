<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paw Time - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #F8F9FA;
        }

        .gradient-card {
            background: linear-gradient(135deg, #FFD4B2 0%, #FFA07A 100%);
        }

        .btn-primary {
            background: #68C4CF;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #5AB0BB;
            transform: translateY(-2px);
        }

        .action-btn {
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .pet-card {
            transition: all 0.3s ease;
        }

        .pet-card:hover {
            transform: translateX(5px);
        }

        .reminder-card {
            transition: all 0.3s ease;
        }

        .reminder-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 100;
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tab-active {
            color: #68C4CF;
            border-bottom: 3px solid #68C4CF;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.5);
        }

        /* Tambahan styling untuk tombol gender aktif */
        .gender-btn.active-male {
            border-color: #93C5FD;
            background-color: #EFF6FF;
            color: #2563EB;
        }

        .gender-btn.active-female {
            border-color: #F9A8D4;
            background-color: #FDF2F8;
            color: #DB2777;
        }
    </style>
</head>

<body class="pb-20">
    <div class="bubble" style="width: 40px; height: 40px; bottom: 10%; left: 5%; animation-delay: 0s;"></div>
    <div class="bubble" style="width: 60px; height: 60px; bottom: 20%; right: 10%; animation-delay: 1s;"></div>
    <div class="bubble" style="width: 30px; height: 30px; top: 30%; right: 15%; animation-delay: 2s;"></div>

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <button class="md:hidden" onclick="toggleSidebar()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-xl font-bold text-gray-800">Your Family</h1>
            </div>
            <button class="relative">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z" />
                </svg>
                <span
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
            </button>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-6">
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
                <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=150&h=150&fit=crop"
                    alt="Cat" class="w-32 h-32 rounded-2xl object-cover border-4 border-white/30">
            </div>
            <div class="flex justify-center space-x-2 mt-4 relative z-10">
                <div class="w-2 h-2 rounded-full bg-white"></div>
                <div class="w-2 h-2 rounded-full bg-white/40"></div>
                <div class="w-2 h-2 rounded-full bg-white/40"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 mb-6 shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-[#FF8C42]" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-4 2c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm8 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-4 2c-1.84 0-3.56.5-5.03 1.37-.61.35-.97 1.02-.97 1.72V18c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2v-2.91c0-.7-.36-1.37-.97-1.72C15.56 12.5 13.84 12 12 12z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Ready to add your pet?</p>
                </div>
            </div>
            <button class="btn-primary px-4 py-2 rounded-full text-sm font-semibold text-white"
                onclick="openAddPetModal()">
                Add
            </button>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Quick Action</h2>
            <div class="grid grid-cols-4 gap-4">
                <button class="action-btn bg-white rounded-2xl p-4 text-center shadow-sm" onclick="openAddPetModal()">
                    <div class="bg-orange-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-[#FF8C42]" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Add Pet</p>
                </button>

                <button class="action-btn bg-white rounded-2xl p-4 text-center shadow-sm">
                    <div class="bg-green-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Pet Log</p>
                </button>

                <button class="action-btn bg-white rounded-2xl p-4 text-center shadow-sm">
                    <div class="bg-blue-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Med Log</p>
                </button>

                <button class="action-btn bg-white rounded-2xl p-4 text-center shadow-sm">
                    <div class="bg-purple-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Vet Visit</p>
                </button>
            </div>
        </div>

        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800">Your Pets</h2>
                <button class="text-[#68C4CF] font-semibold text-sm">See all</button>
            </div>

            <div class="pet-card bg-white rounded-2xl p-4 shadow-sm flex items-center justify-between cursor-pointer"
                onclick="openPetDetail()">
                <div class="flex items-center space-x-4">
                    <div class="bg-orange-100 p-3 rounded-2xl">
                        <svg class="w-8 h-8 text-[#FF8C42]" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-4 2c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm8 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-4 2c-1.84 0-3.56.5-5.03 1.37-.61.35-.97 1.02-.97 1.72V18c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2v-2.91c0-.7-.36-1.37-.97-1.72C15.56 12.5 13.84 12 12 12z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Bella Bulul</h3>
                        <p class="text-sm text-gray-500">American curl</p>
                        <p class="text-xs text-gray-400">3 Age • <span class="text-[#68C4CF]">Cat</span></p>
                    </div>
                </div>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800">Upcoming Reminder</h2>
                <button class="text-[#68C4CF] font-semibold text-sm">See all</button>
            </div>

            <div class="reminder-card bg-white rounded-2xl p-4 shadow-sm mb-3">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-start space-x-3">
                        <div class="bg-orange-100 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-[#FF8C42]" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11 9h2V6h3V4h-3V1h-2v3H8v2h3v3zm-4 9c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zm-9.83-3.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.86-7.01L19.42 4h-.01l-1.1 2-2.76 5H8.53l-.13-.27L6.16 6l-.95-2-.94-2H1v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.13 0-.25-.11-.25-.25z" />
                            </svg>
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
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                        </svg>
                        <span>Done</span>
                    </button>
                    <button
                        class="flex-1 bg-red-100 text-red-700 py-2 rounded-lg text-sm font-semibold flex items-center justify-center space-x-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                        </svg>
                        <span>Delete</span>
                    </button>
                </div>
            </div>

            <div class="text-center py-8">
                <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                </div>
                <p class="text-sm text-gray-500">All caught up! 🎉</p>
            </div>
        </div>
    </main>

    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-around">
            <button class="flex flex-col items-center space-y-1 text-[#68C4CF]">
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

            <button class="bg-[#FF8C42] text-white rounded-full p-4 -mt-8 shadow-lg" onclick="openAddPetModal()">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
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

    <div id="addPetModal" class="modal items-center justify-center px-4">
        <div class="modal-content bg-white rounded-3xl p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">Add Pet Profile</h2>
                <button onclick="closeAddPetModal()">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form class="space-y-5">
                <div class="flex flex-col items-center">
                    <div
                        class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-2 cursor-pointer hover:bg-gray-200 transition">
                        <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                        </svg>
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
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M9 9c0-1.66 1.34-3 3-3s3 1.34 3 3-1.34 3-3 3-3-1.34-3-3zm3-5c-2.76 0-5 2.24-5 5 0 2.54 1.89 4.64 4.34 4.95V17h-2v2h2v3h2v-3h2v-2h-2v-3.05C15.11 13.64 17 11.54 17 9c0-2.76-2.24-5-5-5z" />
                            </svg>
                            <span>Male</span>
                        </button>
                        <button type="button"
                            class="gender-btn border-2 border-gray-200 text-gray-600 py-3 rounded-2xl font-semibold flex items-center justify-center space-x-2 hover:border-pink-300 hover:bg-pink-50 hover:text-pink-600"
                            onclick="selectGender(this, 'female')">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 4c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-2.76 0-5 2.24-5 5v2h2v-2c0-1.66 1.34-3 3-3s3 1.34 3 3v2h2v-2c0-2.76-2.24-5-5-5z" />
                            </svg>
                            <span>Female</span>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="btn-primary w-full py-4 rounded-2xl text-white font-bold text-lg shadow-lg">
                    Save Profile
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            // Logic for sidebar functionality would go here
            console.log("Toggle sidebar");
        }

        function openAddPetModal() {
            document.getElementById('addPetModal').classList.add('show');
        }

        function closeAddPetModal() {
            document.getElementById('addPetModal').classList.remove('show');
        }

        function openPetDetail() {
            // Logic to open pet details
            console.log("Open pet detail");
        }

        // Logic for Gender Selection visual toggle
        function selectGender(btn, type) {
            // Reset all buttons first
            const buttons = document.querySelectorAll('.gender-btn');
            buttons.forEach(b => {
                b.className =
                    'gender-btn border-2 border-gray-200 text-gray-600 py-3 rounded-2xl font-semibold flex items-center justify-center space-x-2 hover:border-gray-300';

                // Add specific hover classes based on button position/type logic if needed,
                // but for simplicity we reset to base gray style.
                if (b.textContent.trim() === 'Male') {
                    b.classList.add('hover:border-blue-300', 'hover:bg-blue-50', 'hover:text-blue-600');
                } else {
                    b.classList.add('hover:border-pink-300', 'hover:bg-pink-50', 'hover:text-pink-600');
                }
            });

            // Set active style for clicked button
            if (type === 'male') {
                btn.className =
                    'gender-btn active-male border-2 border-blue-300 bg-blue-50 text-blue-600 py-3 rounded-2xl font-semibold flex items-center justify-center space-x-2';
            } else {
                btn.className =
                    'gender-btn active-female border-2 border-pink-300 bg-pink-50 text-pink-600 py-3 rounded-2xl font-semibold flex items-center justify-center space-x-2';
            }
        }
    </script>
</body>

</html>
