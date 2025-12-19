@extends('layouts.user')

@section('title', 'Paw Time - Health')

@section('content')
    {{-- Health List View --}}
    <div id="healthList" class="content-section active">
        {{-- Banner --}}
        <div class="health-banner">
            <div class="flex items-center justify-between relative z-10">
                <div class="flex-1">
                    <p class="text-sm text-gray-800 font-medium mb-1">take care of pet's health</p>
                    <p class="text-xs text-gray-700">find out more on the link</p>
                </div>
                <button class="btn-primary text-white rounded-full p-3 shadow-lg">
                    <x-ui.icon name="chevron-right" size="w-5 h-5" color="currentColor" />
                </button>
            </div>
            <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-orange-400 rounded-full opacity-20"></div>
        </div>

        {{-- Search Bar --}}
        <div class="mb-6">
            <div class="relative">
                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <x-ui.icon name="search" size="w-5 h-5" color="currentColor" />
                </span>
                <input type="text" placeholder="Search"
                    class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-white text-gray-700">
            </div>
        </div>

        {{-- Veterinary Section --}}
        <h2 class="text-lg font-bold text-gray-800 mb-4">Veterinary</h2>

        <div class="grid grid-cols-2 gap-4">
            {{-- Doctor Card 1 --}}
            <x-cards.doctor-card name="Dr. Kalini Jithma" specialty="Veterinary surgery" rating="4.8"
                image="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=200&h=200&fit=crop"
                onclick="window.showDoctorDetail()">
                <button class="bg-white text-gray-700 rounded-full p-2 shadow-sm hover:bg-gray-50 transition">
                    <x-ui.icon name="email" size="w-4 h-4" color="currentColor" />
                </button>
                <button class="bg-[#FF8C42] text-white rounded-full p-2 shadow-lg hover:bg-orange-500 transition">
                    <x-ui.icon name="phone" size="w-4 h-4" color="currentColor" />
                </button>
            </x-cards.doctor-card>

            {{-- Doctor Card 2 --}}
            <x-cards.doctor-card name="Dr. D. Deshappriya" specialty="Veterinary surgery" rating="4.9"
                image="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=200&h=200&fit=crop"
                onclick="window.showDoctorDetail()">
                <button class="bg-white text-gray-700 rounded-full p-2 shadow-sm hover:bg-gray-50 transition">
                    <x-ui.icon name="email" size="w-4 h-4" color="currentColor" />
                </button>
                <button class="bg-[#FF8C42] text-white rounded-full p-2 shadow-lg hover:bg-orange-500 transition">
                    <x-ui.icon name="phone" size="w-4 h-4" color="currentColor" />
                </button>
            </x-cards.doctor-card>

            {{-- Doctor Card 3 --}}
            <x-cards.doctor-card name="Dr. Kalini Jithma" specialty="Veterinary surgery" rating="4.7"
                image="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=200&h=200&fit=crop"
                onclick="window.showDoctorDetail()">
                <button class="bg-white text-gray-700 rounded-full p-2 shadow-sm hover:bg-gray-50 transition">
                    <x-ui.icon name="email" size="w-4 h-4" color="currentColor" />
                </button>
                <button class="bg-[#FF8C42] text-white rounded-full p-2 shadow-lg hover:bg-orange-500 transition">
                    <x-ui.icon name="phone" size="w-4 h-4" color="currentColor" />
                </button>
            </x-cards.doctor-card>

            {{-- Doctor Card 4 --}}
            <x-cards.doctor-card name="Dr. D. Deshappriya" specialty="Veterinary surgery" rating="4.6"
                image="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=200&h=200&fit=crop"
                onclick="window.showDoctorDetail()">
                <button class="bg-white text-gray-700 rounded-full p-2 shadow-sm hover:bg-gray-50 transition">
                    <x-ui.icon name="email" size="w-4 h-4" color="currentColor" />
                </button>
                <button class="bg-[#FF8C42] text-white rounded-full p-2 shadow-lg hover:bg-orange-500 transition">
                    <x-ui.icon name="phone" size="w-4 h-4" color="currentColor" />
                </button>
            </x-cards.doctor-card>
        </div>
    </div>

    {{-- Doctor Detail View --}}
    <div id="doctorDetail" class="content-section">
        <div class="relative -mx-6 -mt-6">
            <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=800&h=500&fit=crop" alt="Doctor"
                class="w-full h-80 object-cover">
            <button onclick="window.showHealthList()"
                class="absolute top-6 left-6 bg-white/90 backdrop-blur-sm p-2 rounded-full hover:bg-white transition">
                <x-ui.icon name="chevron-left" size="w-6 h-6" color="currentColor" class="text-gray-700" />
            </button>
            <button class="absolute top-6 right-6 bg-white/90 backdrop-blur-sm p-2 rounded-full hover:bg-white transition">
                <x-ui.icon name="more-vert" size="w-6 h-6" color="currentColor" class="text-gray-700" />
            </button>
        </div>

        <div class="bg-white rounded-t-3xl -mt-8 relative z-10 px-6 pt-6 -mx-6">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Dr. Kalini Jithma</h1>
                <div class="flex items-center justify-center flex-wrap gap-2 mb-3">
                    <span class="tag tag-blue">Pet behaviors</span>
                    <span class="tag tag-green">Pet Food</span>
                    <span class="tag tag-orange">Pet Treatments</span>
                </div>
                <div class="flex items-center justify-center space-x-1">
                    <x-ui.icon name="star" size="w-5 h-5" color="#FCD34D" />
                    <x-ui.icon name="star" size="w-5 h-5" color="#FCD34D" />
                    <x-ui.icon name="star" size="w-5 h-5" color="#FCD34D" />
                    <x-ui.icon name="star" size="w-5 h-5" color="#FCD34D" />
                    <x-ui.icon name="star" size="w-5 h-5" color="#D1D5DB" />
                </div>
            </div>

            {{-- Schedule --}}
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Schedule</h2>
                <div class="flex justify-between mb-4">
                    <button
                        class="schedule-day flex flex-col items-center px-4 py-3 rounded-2xl bg-gray-100 text-gray-600">
                        <span class="text-xs mb-1">Mon</span>
                        <span class="text-lg font-bold">15</span>
                    </button>
                    <button
                        class="schedule-day flex flex-col items-center px-4 py-3 rounded-2xl bg-gray-100 text-gray-600">
                        <span class="text-xs mb-1">Tue</span>
                        <span class="text-lg font-bold">16</span>
                    </button>
                    <button class="schedule-day active flex flex-col items-center px-4 py-3 rounded-2xl shadow-lg">
                        <span class="text-xs mb-1">Wed</span>
                        <span class="text-lg font-bold">17</span>
                    </button>
                    <button
                        class="schedule-day flex flex-col items-center px-4 py-3 rounded-2xl bg-gray-100 text-gray-600">
                        <span class="text-xs mb-1">Thu</span>
                        <span class="text-lg font-bold">18</span>
                    </button>
                    <button
                        class="schedule-day flex flex-col items-center px-4 py-3 rounded-2xl bg-gray-100 text-gray-600">
                        <span class="text-xs mb-1">Fri</span>
                        <span class="text-lg font-bold">19</span>
                    </button>
                </div>

                <p class="text-sm text-gray-600 leading-relaxed mb-6">
                    Dr. Kalini Jithma is a highly experienced veterinarian with 11 years of dedicated practice,
                    showcasing a profound commitment to animal care.
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex space-x-3 pb-6">
                <button
                    class="flex-1 btn-primary text-white py-4 rounded-2xl font-semibold shadow-lg flex items-center justify-center space-x-2">
                    <x-ui.icon name="email" size="w-5 h-5" color="currentColor" />
                    <span>WhatsApp</span>
                </button>
                <button
                    class="flex-1 btn-primary text-white py-4 rounded-2xl font-semibold shadow-lg flex items-center justify-center space-x-2">
                    <span>Book Now</span>
                    <x-ui.icon name="chevron-right" size="w-5 h-5" color="currentColor" />
                </button>
                <button class="btn-primary text-white p-4 rounded-2xl shadow-lg">
                    <x-ui.icon name="phone" size="w-6 h-6" color="currentColor" />
                </button>
            </div>
        </div>
    </div>
@endsection
