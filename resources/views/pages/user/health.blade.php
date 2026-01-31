@extends('layouts.user')

@section('title', 'Paw Time - Konsultasi')

@section('content')
    {{-- Consultation List View --}}
    <div id="healthList" class="content-section active">
        {{-- Banner --}}
        <div class="health-banner mb-6">
            <div class="flex items-center justify-between relative z-10">
                <div class="flex-1">
                    <p class="text-sm text-gray-800 font-medium mb-1">take care of pet's health</p>
                    <p class="text-xs text-gray-600">tips and tricks for your pet</p>
                </div>
                <a href="#" class="btn-primary text-white rounded-full p-3 shadow-lg">
                    <x-ui.icon name="chevron-right" size="w-5 h-5" color="currentColor" />
                </a>
            </div>
            <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-orange-400 rounded-full opacity-20"></div>
        </div>

        {{-- My Appointments Section --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800">My Appointments</h2>
                <a href="{{ route('user.appointments.index') }}" class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
                    View All →
                </a>
            </div>

            @if($appointments->count() > 0)
                <div class="space-y-3">
                    @foreach($appointments as $appointment)
                        <div class="bg-white rounded-2xl p-4 shadow-sm border-l-4 hover:shadow-md transition-shadow
                            {{ $appointment->status === 'pending' ? 'border-yellow-500' : ($appointment->status === 'confirmed' ? 'border-blue-500' : ($appointment->status === 'completed' ? 'border-green-500' : 'border-red-500')) }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-2xl">
                                            @if(strtolower($appointment->pet->species) === 'cat')
                                                🐱
                                            @elseif(strtolower($appointment->pet->species) === 'dog')
                                                🐶
                                            @elseif(strtolower($appointment->pet->species) === 'bird')
                                                🦜
                                            @elseif(strtolower($appointment->pet->species) === 'rabbit')
                                                🐰
                                            @else
                                                🐾
                                            @endif
                                        </span>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $appointment->pet->pet_name }}</h3>
                                            <p class="text-xs text-gray-600">{{ $appointment->appointment_date->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {!! $appointment->status_badge !!}
                                        @if($appointment->status === 'completed' && $appointment->medicalRecord)
                                            <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Has Medical Record</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('user.appointments.show', $appointment) }}" 
                                   class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 text-sm font-medium transition-colors">
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 rounded-xl p-6 text-center">
                    <div class="text-4xl mb-2">📅</div>
                    <p class="text-gray-600 text-sm">No appointments yet</p>
                </div>
            @endif
        </div>

        {{-- Consultation History Section --}}
        <h2 class="text-lg font-bold text-gray-800 mb-4">Riwayat Konsultasi</h2>

        @forelse($consultations as $consultation)
            <div class="consultation-card bg-white rounded-2xl p-4 mb-3 shadow-sm border-l-4 cursor-pointer hover:shadow-md transition-shadow
                {{ $consultation->status === 'done' ? 'border-green-500' : ($consultation->status === 'on_progress' ? 'border-orange-500' : 'border-blue-500') }}"
                onclick="showConsultationDetail({{ $consultation->id }})">
                <div class="flex items-start space-x-3">
                    {{-- Status Icon --}}
                    <div class="flex-shrink-0">
                        @if($consultation->status === 'done')
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                <x-ui.icon name="check" size="w-5 h-5" color="#10b981" />
                            </div>
                        @elseif($consultation->status === 'on_progress')
                            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                                <x-ui.icon name="clock" size="w-5 h-5" color="#f97316" />
                            </div>
                        @else
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <x-ui.icon name="clipboard" size="w-5 h-5" color="#3b82f6" />
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-800 truncate">
                                    {{ Str::limit($consultation->complaint, 35) }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $consultation->pet->pet_name }} • {{ $consultation->check_date->format('d M Y') }}
                                </p>
                            </div>
                            {{-- Status Badge --}}
                            <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full flex-shrink-0
                                {{ $consultation->status === 'done' ? 'bg-green-100 text-green-700' :
                                   ($consultation->status === 'on_progress' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ $consultation->status === 'done' ? 'Done' : ($consultation->status === 'on_progress' ? 'On Progress' : 'Pending') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-2xl">
                <div class="inline-block bg-gray-100 p-4 rounded-full mb-4">
                    <x-ui.icon name="clipboard" size="w-10 h-10" color="#9ca3af" />
                </div>
                <p class="text-gray-500 mb-2">Belum ada riwayat konsultasi</p>
                <p class="text-sm text-gray-400">Klik tombol + untuk mulai konsultasi</p>
            </div>
        @endforelse
    </div>

    {{-- Doctor Detail View --}}
    <div id="doctorDetail" class="content-section">
        <div class="relative -mx-6 -mt-6">
            <img src="{{ $doctorInfo['image'] }}" alt="Doctor"
                class="w-full h-64 object-contain bg-gray-100">
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
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $doctorInfo['name'] }}</h1>
                <div class="flex items-center justify-center flex-wrap gap-2 mb-3">
                    @foreach($doctorInfo['tags'] as $tag)
                        <span class="tag tag-{{ ['blue', 'green', 'orange'][array_search($tag, $doctorInfo['tags']) % 3] }}">{{ $tag }}</span>
                    @endforeach
                </div>
                {{-- <div class="flex items-center justify-center space-x-1">
                    @for($i = 1; $i <= 5; $i++)
                        <x-ui.icon name="star" size="w-5 h-5" color="{{ $i <= floor($doctorInfo['rating']) ? '#FCD34D' : '#D1D5DB' }}" />
                    @endfor
                    <span class="text-sm text-gray-500 ml-2">{{ $doctorInfo['rating'] }}</span>
                </div> --}}
            </div>

            {{-- Schedule --}}
            <div class="mb-6">
                {{-- <h2 class="text-lg font-bold text-gray-800 mb-4">Schedule</h2>
                <div class="flex justify-between mb-4" id="scheduleContainer">
                </div> --}}

                <p class="text-sm text-gray-600 leading-relaxed mb-6">
                    {{ $doctorInfo['description'] }}
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex space-x-3 pb-6">
                <a href="https://wa.me/{{ $doctorInfo['whatsapp'] }}" target="_blank"
                    class="flex-1 btn-primary text-white py-4 rounded-2xl font-semibold shadow-lg flex items-center justify-center space-x-2">
                    <x-ui.icon name="email" size="w-5 h-5" color="currentColor" />
                    <span>WhatsApp</span>
                </a>
                <button onclick="openBookingModal()"
                    class="flex-1 btn-primary text-white py-4 rounded-2xl font-semibold shadow-lg flex items-center justify-center space-x-2">
                    <span>Book Now</span>
                    <x-ui.icon name="chevron-right" size="w-5 h-5" color="currentColor" />
                </button>
                {{-- <a href="tel:+{{ $doctorInfo['whatsapp'] }}"
                    class="btn-primary text-white p-4 rounded-2xl shadow-lg">
                    <x-ui.icon name="phone" size="w-6 h-6" color="currentColor" />
                </a> --}}
            </div>
        </div>
    </div>

    {{-- Consultation Detail View --}}
    <div id="consultationDetail" class="content-section">
        {{-- Will be populated by JS --}}
    </div>

    {{-- Floating Action Button --}}
    <button onclick="showDoctorDetail()"
        class="fixed bottom-24 right-6 w-14 h-14 bg-[#68C4CF] text-white rounded-full shadow-lg flex items-center justify-center hover:bg-[#5AB0BB] transition-colors z-40">
        <x-ui.icon name="plus" size="w-7 h-7" color="currentColor" />
    </button>
@endsection

@push('modals')
    {{-- Booking Modal --}}
    <x-ui.modal id="bookingModal" title="Book Konsultasi">
        <form id="bookingForm" class="space-y-5">
            @csrf

            @if($pets->count() > 0)
                {{-- Pet Selection --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Hewan</label>
                    <select name="pet_id" id="bookingPetId" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                        <option value="">-- Pilih Hewan --</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->id }}">{{ $pet->pet_name }} ({{ $pet->species }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Complaint --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keluhan / Gejala</label>
                    <textarea name="complaint" id="bookingComplaint" rows="4" required
                        placeholder="Deskripsikan keluhan atau gejala yang dialami hewan Anda..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50 resize-none"></textarea>
                    <p class="text-xs text-gray-400 mt-1">Jelaskan secara detail agar dokter dapat mempersiapkan konsultasi</p>
                </div>

                {{-- Submit Button --}}
                <button type="submit" id="bookingSubmitBtn"
                    class="btn-primary w-full py-4 rounded-2xl text-white font-bold text-lg shadow-lg flex items-center justify-center space-x-2">
                    <x-ui.icon name="email" size="w-5 h-5" color="currentColor" />
                    <span>Kirim & Hubungi via WhatsApp</span>
                </button>
            @else
                {{-- No Pets State --}}
                <div class="text-center py-6">
                    <div class="inline-block bg-orange-100 p-4 rounded-full mb-4">
                        <x-ui.icon name="paw" size="w-10 h-10" color="#FF8C42" />
                    </div>
                    <p class="text-gray-600 mb-4">Anda belum memiliki hewan terdaftar</p>
                    <a href="{{ route('user.profile') }}"
                        class="btn-primary px-6 py-3 rounded-2xl text-white font-semibold inline-block">
                        Tambah Hewan Dulu
                    </a>
                </div>
            @endif
        </form>
    </x-ui.modal>
@endpush

@push('scripts')
<script>
    // Consultation data from backend
    const consultations = @json($consultations);
    const doctorInfo = @json($doctorInfo);
    const pets = @json($pets);

    // View switching
    window.showHealthList = function() {
        document.getElementById('healthList').classList.add('active');
        document.getElementById('doctorDetail').classList.remove('active');
        document.getElementById('consultationDetail').classList.remove('active');
    };

    window.showDoctorDetail = function() {
        document.getElementById('healthList').classList.remove('active');
        document.getElementById('doctorDetail').classList.add('active');
        document.getElementById('consultationDetail').classList.remove('active');
        generateSchedule();
    };

    function showConsultationDetail(id) {
        const consultation = consultations.find(c => c.id === id);
        if (!consultation) return;

        const statusColors = {
            'pending': { bg: 'bg-blue-100', text: 'text-blue-700', label: 'Pending' },
            'on_progress': { bg: 'bg-orange-100', text: 'text-orange-700', label: 'On Progress' },
            'done': { bg: 'bg-green-100', text: 'text-green-700', label: 'Selesai' }
        };
        const status = statusColors[consultation.status] || statusColors.pending;

        const detailHtml = `
            <div class="mb-6">
                <button onclick="window.showHealthList()" class="flex items-center space-x-2 text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span class="font-semibold">Detail Konsultasi</span>
                </button>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm mb-4">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-gray-500">#${consultation.id}</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full ${status.bg} ${status.text}">
                        ${status.label}
                    </span>
                </div>

                <div class="border-t border-gray-100 pt-4 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Hewan</p>
                        <p class="font-semibold text-gray-800">${consultation.pet.pet_name} (${consultation.pet.species})</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Tanggal Konsultasi</p>
                        <p class="font-semibold text-gray-800">${formatDate(consultation.check_date)}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Keluhan</p>
                        <p class="text-gray-700">${consultation.complaint || '-'}</p>
                    </div>

                    ${consultation.diagnosis ? `
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Diagnosis</p>
                        <p class="text-gray-700">${consultation.diagnosis}</p>
                    </div>
                    ` : ''}

                    ${consultation.treatment ? `
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Pengobatan</p>
                        <p class="text-gray-700">${consultation.treatment}</p>
                    </div>
                    ` : ''}

                    ${consultation.prescription ? `
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Resep</p>
                        <p class="text-gray-700">${consultation.prescription}</p>
                    </div>
                    ` : ''}

                    ${consultation.notes ? `
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Catatan</p>
                        <p class="text-gray-700">${consultation.notes}</p>
                    </div>
                    ` : ''}
                </div>
            </div>

            ${consultation.status !== 'done' ? `
            <a href="https://wa.me/${doctorInfo.whatsapp}" target="_blank"
                class="btn-primary w-full py-4 rounded-2xl text-white font-semibold shadow-lg flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                <span>Hubungi Dokter</span>
            </a>
            ` : ''}
        `;

        document.getElementById('consultationDetail').innerHTML = detailHtml;
        document.getElementById('healthList').classList.remove('active');
        document.getElementById('doctorDetail').classList.remove('active');
        document.getElementById('consultationDetail').classList.add('active');
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    }

    // Generate schedule days
    function generateSchedule() {
        const container = document.getElementById('scheduleContainer');
        const today = new Date();
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        let html = '';
        for (let i = 0; i < 5; i++) {
            const date = new Date(today);
            date.setDate(today.getDate() + i);
            const dayName = days[date.getDay()];
            const dayNum = date.getDate();
            const isToday = i === 0;

            html += `
                <button class="schedule-day flex flex-col items-center px-4 py-3 rounded-2xl ${isToday ? 'active shadow-lg' : 'bg-gray-100 text-gray-600'}">
                    <span class="text-xs mb-1">${dayName}</span>
                    <span class="text-lg font-bold">${dayNum}</span>
                </button>
            `;
        }
        container.innerHTML = html;
    }

    // Open booking modal
    window.openBookingModal = function() {
        const modal = document.getElementById('bookingModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'show');
        }
    };

    // Close modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex', 'show');
        }
    }

    // Handle booking form submission
    document.getElementById('bookingForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const petId = document.getElementById('bookingPetId').value;
        const complaint = document.getElementById('bookingComplaint').value;
        const submitBtn = document.getElementById('bookingSubmitBtn');

        if (!petId || !complaint) {
            alert('Mohon lengkapi semua field!');
            return;
        }

        // Disable button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Memproses...</span>';

        try {
            const response = await fetch('{{ route('user.health.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ pet_id: petId, complaint: complaint })
            });

            const data = await response.json();

            if (data.success) {
                // Close modal
                closeModal('bookingModal');

                // Redirect to WhatsApp
                window.open(data.whatsapp_url, '_blank');

                // Reload page to show new consultation
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert(data.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim data');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg><span>Kirim & Hubungi via WhatsApp</span>';
        }
    });

    // Close modal on outside click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal') && e.target.classList.contains('show')) {
            closeModal(e.target.id);
        }
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        generateSchedule();
    });
</script>
@endpush
