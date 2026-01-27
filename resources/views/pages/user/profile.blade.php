@extends('layouts.user')

@section('title', 'Paw Time - Profile')

@section('content')
    {{-- Profile List View --}}
    <div id="profileList" class="content-section active">
        {{-- Profile Header with Cyan Background --}}
        <div class="bg-[#68C4CF] -mx-6 -mt-6 px-6 pt-6 pb-16 rounded-b-[40px] relative">
            {{-- Top Bar --}}
            <div class="flex items-center justify-between mb-6">
                <span class="text-white text-sm font-medium">Profile page</span>
                <button onclick="openEditProfileModal()" class="text-white">
                    <x-ui.icon name="edit" size="w-5 h-5" color="currentColor" />
                </button>
            </div>

            {{-- Profile Info --}}
            <div class="flex flex-col items-center">
                <div class="relative mb-3">
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                        class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                    <button onclick="openEditProfileModal()"
                        class="absolute bottom-0 right-0 bg-white p-1.5 rounded-full shadow-md hover:bg-gray-50">
                        <x-ui.icon name="camera" size="w-4 h-4" color="#68C4CF" />
                    </button>
                </div>
                <h1 class="text-xl font-bold text-white mb-2">{{ $user->name }}</h1>
                <button onclick="openAddPetModal()"
                    class="bg-white/20 backdrop-blur-sm text-white text-sm px-4 py-2 rounded-full hover:bg-white/30 transition">
                    add new family
                </button>
            </div>
        </div>

        {{-- My Family Section --}}
        <div class="px-0 -mt-8 relative z-10">
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">My Family</h2>

                {{-- Filter Chips --}}
                <div class="flex flex-wrap gap-2 mb-5">
                    <button onclick="filterPets('all')"
                        class="filter-chip px-4 py-2 rounded-full text-sm font-semibold transition active"
                        data-filter="all">
                        <x-ui.icon name="check" size="w-4 h-4" color="currentColor" class="inline mr-1" />
                        All
                    </button>
                    <button onclick="filterPets('cat')"
                        class="filter-chip px-4 py-2 rounded-full text-sm font-semibold transition"
                        data-filter="cat">
                        🐱 Cat
                    </button>
                    <button onclick="filterPets('dog')"
                        class="filter-chip px-4 py-2 rounded-full text-sm font-semibold transition"
                        data-filter="dog">
                        🐶 Dog
                    </button>
                    <button onclick="filterPets('other')"
                        class="filter-chip px-4 py-2 rounded-full text-sm font-semibold transition"
                        data-filter="other">
                        🐾 Other
                    </button>
                </div>

                {{-- Pet Cards --}}
                <div id="petsList" class="space-y-3">
                    @forelse($pets as $pet)
                        <div class="pet-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100 cursor-pointer hover:shadow-md transition-all flex items-center justify-between"
                            data-species="{{ strtolower($pet->species) }}"
                            onclick="showPetDetail({{ $pet->id }})">
                            <div class="flex items-center space-x-4">
                                {{-- Pet Icon based on species --}}
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                    {{ strtolower($pet->species) === 'cat' ? 'bg-orange-100' :
                                       (strtolower($pet->species) === 'dog' ? 'bg-yellow-100' : 'bg-gray-100') }}">
                                    @if(strtolower($pet->species) === 'cat')
                                        <span class="text-2xl">🐱</span>
                                    @elseif(strtolower($pet->species) === 'dog')
                                        <span class="text-2xl">🐶</span>
                                    @else
                                        <x-ui.icon name="paw" size="w-6 h-6" color="#6b7280" />
                                    @endif
                                </div>
                                {{-- Pet Info --}}
                                <div>
                                    <h3 class="font-bold text-gray-800">{{ $pet->pet_name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $pet->breed ?? 'Unknown breed' }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-gray-400">{{ $pet->age }}</span>
                                        <span class="px-2 py-0.5 text-xs rounded-full
                                            {{ strtolower($pet->species) === 'cat' ? 'bg-orange-100 text-orange-600' :
                                               (strtolower($pet->species) === 'dog' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                                            {{ ucfirst($pet->species) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <x-ui.icon name="chevron-right" size="w-6 h-6" color="#9ca3af" />
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="inline-block bg-gray-100 p-4 rounded-full mb-4">
                                <x-ui.icon name="paw" size="w-10 h-10" color="#9ca3af" />
                            </div>
                            <p class="text-gray-500 mb-2">Belum ada hewan peliharaan</p>
                            <button onclick="openAddPetModal()" class="text-[#68C4CF] font-semibold">
                                + Tambah Hewan Pertama
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Pet Detail View --}}
    <div id="petDetail" class="content-section">
        {{-- Will be populated by JS --}}
    </div>

    {{-- Floating Action Button --}}
    <button onclick="openAddPetModal()"
        class="fixed bottom-24 right-6 w-14 h-14 bg-[#FF8C42] text-white rounded-full shadow-lg flex items-center justify-center hover:bg-orange-500 transition-colors z-40">
        <x-ui.icon name="plus" size="w-7 h-7" color="currentColor" />
    </button>
@endsection

@push('modals')
    {{-- Edit Profile Modal --}}
    <x-ui.modal id="editProfileModal" title="Edit Profile">
        <form id="editProfileForm" class="space-y-5" enctype="multipart/form-data">
            @csrf

            {{-- Profile Image Preview --}}
            <div class="flex flex-col items-center">
                <div class="relative mb-3">
                    <img id="profilePreview" src="{{ $user->avatar }}" alt="Profile"
                        class="w-24 h-24 rounded-full object-cover border-4 border-[#68C4CF]">
                    <label for="profileImageInput"
                        class="absolute bottom-0 right-0 bg-[#68C4CF] p-2 rounded-full shadow-md cursor-pointer hover:bg-[#5AB0BB]">
                        <x-ui.icon name="camera" size="w-4 h-4" color="white" />
                    </label>
                    <input type="file" id="profileImageInput" name="profile_image" accept="image/*" class="hidden"
                        onchange="previewProfileImage(this)">
                </div>
            </div>

            {{-- Name --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama</label>
                <input type="text" name="name" id="profileName" value="{{ $user->name }}" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>

            {{-- Email (Read-only) --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" value="{{ $user->email }}" disabled
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl bg-gray-100 text-gray-500">
            </div>

            {{-- Submit Button --}}
            <button type="submit" id="editProfileBtn"
                class="btn-primary w-full py-4 rounded-2xl text-white font-bold text-lg shadow-lg">
                Simpan Perubahan
            </button>
        </form>
    </x-ui.modal>

    {{-- Add Pet Modal --}}
    <x-ui.modal id="addPetModal" title="Tambah Hewan Baru" maxWidth="lg">
        <form id="addPetForm" action="{{ route('user.pets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Pet Image --}}
            <div class="flex justify-center">
                <div class="relative">
                    <img id="petImagePreview" src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=200&h=200&fit=crop"
                        alt="Pet" class="w-24 h-24 rounded-2xl object-cover border-2 border-gray-200">
                    <label for="petImageInput"
                        class="absolute -bottom-2 -right-2 bg-[#68C4CF] p-2 rounded-full shadow-md cursor-pointer hover:bg-[#5AB0BB]">
                        <x-ui.icon name="camera" size="w-4 h-4" color="white" />
                    </label>
                    <input type="file" id="petImageInput" name="image" accept="image/*" class="hidden"
                        onchange="previewPetImage(this)">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Pet Name --}}
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Hewan *</label>
                    <input type="text" name="pet_name" required placeholder="Contoh: Bella"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Species --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis *</label>
                    <select name="species" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                        <option value="">Pilih</option>
                        <option value="cat">Kucing</option>
                        <option value="dog">Anjing</option>
                        <option value="rabbit">Kelinci</option>
                        <option value="bird">Burung</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>

                {{-- Breed --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Ras</label>
                    <input type="text" name="breed" placeholder="Contoh: Persian"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Gender --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Gender *</label>
                    <select name="gender" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                        <option value="">Pilih</option>
                        <option value="male">Jantan</option>
                        <option value="female">Betina</option>
                    </select>
                </div>

                {{-- Birth Date --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir *</label>
                    <input type="date" name="birth_date" required max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Color --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Warna</label>
                    <input type="text" name="color" placeholder="Contoh: Hitam"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Weight --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Berat (kg)</label>
                    <input type="number" name="weight" step="0.1" min="0" placeholder="0.0"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Height --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tinggi (cm)</label>
                    <input type="number" name="height" step="0.1" min="0" placeholder="0.0"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Description --}}
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="2" placeholder="Ceritakan tentang hewan peliharaanmu..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50 resize-none"></textarea>
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="btn-primary w-full py-4 rounded-2xl text-white font-bold text-lg shadow-lg">
                Tambah Hewan
            </button>
        </form>
    </x-ui.modal>

    {{-- Edit Pet Modal --}}
    <x-ui.modal id="editPetModal" title="Edit Hewan" maxWidth="lg">
        <form id="editPetForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <input type="hidden" id="editPetId" name="pet_id">

            {{-- Pet Image --}}
            <div class="flex justify-center">
                <div class="relative">
                    <img id="editPetImagePreview" src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=200&h=200&fit=crop"
                        alt="Pet" class="w-24 h-24 rounded-2xl object-cover border-2 border-gray-200">
                    <label for="editPetImageInput"
                        class="absolute -bottom-2 -right-2 bg-[#68C4CF] p-2 rounded-full shadow-md cursor-pointer hover:bg-[#5AB0BB]">
                        <x-ui.icon name="camera" size="w-4 h-4" color="white" />
                    </label>
                    <input type="file" id="editPetImageInput" name="image" accept="image/*" class="hidden"
                        onchange="previewEditPetImage(this)">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Pet Name --}}
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Hewan *</label>
                    <input type="text" name="pet_name" id="editPetName" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Species --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis *</label>
                    <select name="species" id="editPetSpecies" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                        <option value="cat">Kucing</option>
                        <option value="dog">Anjing</option>
                        <option value="rabbit">Kelinci</option>
                        <option value="bird">Burung</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>

                {{-- Breed --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Ras</label>
                    <input type="text" name="breed" id="editPetBreed"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Gender --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Gender *</label>
                    <select name="gender" id="editPetGender" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                        <option value="male">Jantan</option>
                        <option value="female">Betina</option>
                    </select>
                </div>

                {{-- Birth Date --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir *</label>
                    <input type="date" name="birth_date" id="editPetBirthDate" required max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Color --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Warna</label>
                    <input type="text" name="color" id="editPetColor"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Description --}}
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" id="editPetDescription" rows="2"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50 resize-none"></textarea>
                </div>
            </div>

            <div class="flex space-x-3">
                <button type="button" onclick="deletePet()"
                    class="flex-1 py-4 rounded-2xl font-bold text-lg border-2 border-red-500 text-red-500 hover:bg-red-50">
                    Hapus
                </button>
                <button type="submit"
                    class="flex-1 btn-primary py-4 rounded-2xl text-white font-bold text-lg shadow-lg">
                    Simpan
                </button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Add Growth Data Modal --}}
    <x-ui.modal id="addGrowthModal" title="Tambah Data Pertumbuhan">
        <form id="addGrowthForm" class="space-y-4">
            @csrf
            <input type="hidden" id="growthPetId" name="pet_id">

            <div class="grid grid-cols-2 gap-4">
                {{-- Weight --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Berat (kg) *</label>
                    <input type="number" name="weight" step="0.1" min="0" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Height --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tinggi (cm)</label>
                    <input type="number" name="height" step="0.1" min="0"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Record Date --}}
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal *</label>
                    <input type="date" name="record_date" required value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Notes --}}
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" rows="2" placeholder="Catatan tambahan..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50 resize-none"></textarea>
                </div>
            </div>

            <button type="submit"
                class="btn-primary w-full py-4 rounded-2xl text-white font-bold text-lg shadow-lg">
                Simpan Data
            </button>
        </form>
    </x-ui.modal>

    {{-- Add Reminder Modal --}}
    <x-ui.modal id="addReminderModal" title="Tambah Reminder">
        <form id="addReminderForm" class="space-y-4">
            @csrf
            <input type="hidden" id="reminderPetId" name="pet_id">

            {{-- Category --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori *</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="category" value="feeding" class="hidden peer" required>
                        <div class="peer-checked:bg-orange-100 peer-checked:border-orange-400 p-3 rounded-xl border-2 border-gray-200 text-center transition">
                            <span class="text-2xl">🍖</span>
                            <p class="text-xs font-semibold mt-1">Food</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="category" value="grooming" class="hidden peer">
                        <div class="peer-checked:bg-pink-100 peer-checked:border-pink-400 p-3 rounded-xl border-2 border-gray-200 text-center transition">
                            <span class="text-2xl">✂️</span>
                            <p class="text-xs font-semibold mt-1">Grooming</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="category" value="vaccination" class="hidden peer">
                        <div class="peer-checked:bg-blue-100 peer-checked:border-blue-400 p-3 rounded-xl border-2 border-gray-200 text-center transition">
                            <span class="text-2xl">💉</span>
                            <p class="text-xs font-semibold mt-1">Vaccine</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Title --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Judul *</label>
                <input type="text" name="title" required placeholder="Contoh: Makan Siang"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
            </div>

            <div class="grid grid-cols-2 gap-3">
                {{-- Remind Date --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal *</label>
                    <input type="date" name="remind_date" required min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>

                {{-- Remind Time --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jam *</label>
                    <input type="time" name="remind_time" required value="09:00"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                </div>
            </div>

            {{-- Repeat Type --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ulangi</label>
                <select name="repeat_type"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50">
                    <option value="none">Tidak berulang</option>
                    <option value="daily">Harian</option>
                    <option value="weekly">Mingguan</option>
                    <option value="monthly">Bulanan</option>
                </select>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan</label>
                <textarea name="description" rows="2" placeholder="Catatan tambahan..."
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-[#68C4CF] focus:outline-none bg-gray-50 resize-none"></textarea>
            </div>

            <button type="submit"
                class="btn-primary w-full py-4 rounded-2xl text-white font-bold text-lg shadow-lg">
                Tambah Reminder
            </button>
        </form>
    </x-ui.modal>
@endpush

@push('scripts')
<script>
    // Pet data from backend
    const petsData = @json($pets);
    let currentPet = null;

    // Filter pets by species
    window.filterPets = function(species) {
        // Update active filter chip
        document.querySelectorAll('.filter-chip').forEach(chip => {
            chip.classList.remove('active');
            if (chip.dataset.filter === species) {
                chip.classList.add('active');
            }
        });

        // Filter pet cards
        document.querySelectorAll('.pet-card').forEach(card => {
            if (species === 'all') {
                card.style.display = 'flex';
            } else {
                const petSpecies = card.dataset.species;
                // Handle "other" filter - show pets that are not cat or dog
                if (species === 'other') {
                    card.style.display = (petSpecies !== 'cat' && petSpecies !== 'dog') ? 'flex' : 'none';
                } else {
                    card.style.display = petSpecies === species ? 'flex' : 'none';
                }
            }
        });
    };

    // Show pet detail
    window.showPetDetail = async function(petId) {
        try {
            const response = await fetch(`/pets/${petId}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();

            if (data.success) {
                currentPet = data.pet;
                renderPetDetail(data.pet);

                document.getElementById('profileList').classList.remove('active');
                document.getElementById('petDetail').classList.add('active');
            }
        } catch (error) {
            console.error('Error fetching pet detail:', error);
        }
    };

    // Render pet detail view
    function renderPetDetail(pet) {
        const genderIcon = pet.gender === 'female' ? '♀️' : '♂️';
        const genderColor = pet.gender === 'female' ? 'bg-pink-500' : 'bg-blue-500';

        const detailHtml = `
            <div class="relative -mx-6 -mt-6">
                <img src="${pet.image}" alt="${pet.pet_name}" class="w-full h-72 object-cover">
                <button onclick="showProfileList()"
                    class="absolute top-6 left-6 bg-white/90 backdrop-blur-sm p-2 rounded-full hover:bg-white transition">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button onclick="openEditPetModal()"
                    class="absolute top-6 right-6 bg-white/90 backdrop-blur-sm p-2 rounded-full hover:bg-white transition">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                </button>
            </div>

            <div class="bg-white rounded-t-3xl -mt-8 relative z-10 px-6 pt-6 -mx-6 pb-24">
                {{-- Pet Name Card --}}
                <div class="bg-white rounded-2xl shadow-lg p-4 -mt-12 relative z-20 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl font-bold text-gray-800">${pet.pet_name}</h1>
                            <p class="text-sm text-gray-500">${pet.breed || 'Unknown breed'}</p>
                        </div>
                        <div class="${genderColor} text-white w-10 h-10 rounded-xl flex items-center justify-center text-lg">
                            ${genderIcon}
                        </div>
                    </div>
                </div>

                {{-- About Section --}}
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-3">About ${pet.pet_name.split(' ')[0]}</h2>
                    <div class="grid grid-cols-4 gap-2 mb-4">
                        <div class="bg-[#D4F1F4] rounded-xl p-3 text-center">
                            <p class="text-xs text-gray-500 font-medium">Age</p>
                            <p class="text-sm font-bold text-gray-800">${pet.age || '-'}</p>
                        </div>
                        <div class="bg-[#D4F1F4] rounded-xl p-3 text-center">
                            <p class="text-xs text-gray-500 font-medium">Weight</p>
                            <p class="text-sm font-bold text-gray-800">${pet.current_weight ? pet.current_weight + ' kg' : '-'}</p>
                        </div>
                        <div class="bg-[#D4F1F4] rounded-xl p-3 text-center">
                            <p class="text-xs text-gray-500 font-medium">Height</p>
                            <p class="text-sm font-bold text-gray-800">${pet.current_height ? pet.current_height + ' cm' : '-'}</p>
                        </div>
                        <div class="bg-[#D4F1F4] rounded-xl p-3 text-center">
                            <p class="text-xs text-gray-500 font-medium">Color</p>
                            <p class="text-sm font-bold text-gray-800">${pet.color || '-'}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        ${pet.description || 'No description available.'}
                    </p>
                </div>

                {{-- Quick Menu --}}
                <div class="space-y-2">
                    <div onclick="openEditPetModal()" class="menu-item flex items-center justify-between p-4 rounded-xl cursor-pointer bg-white border border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-800">Pet Information</span>
                                <p class="text-xs text-gray-400">View and edit pet details</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>

                    <div onclick="openAddReminderFromDetail('food')" class="menu-item flex items-center justify-between p-4 rounded-xl cursor-pointer bg-white border border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="bg-orange-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-800">Add Food Reminder</span>
                                <p class="text-xs text-gray-400">Set feeding schedule</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>

                    <div onclick="openAddGrowthModal()" class="menu-item flex items-center justify-between p-4 rounded-xl cursor-pointer bg-white border border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="bg-purple-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-800">Add Pet Data</span>
                                <p class="text-xs text-gray-400">Record weight & height</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>

                    <div onclick="openAddReminderFromDetail('grooming')" class="menu-item flex items-center justify-between p-4 rounded-xl cursor-pointer bg-white border border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="bg-pink-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9.64 7.64c.23-.5.36-1.05.36-1.64 0-2.21-1.79-4-4-4S2 3.79 2 6s1.79 4 4 4c.59 0 1.14-.13 1.64-.36L10 12l-2.36 2.36C7.14 14.13 6.59 14 6 14c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4c0-.59-.13-1.14-.36-1.64L12 14l7 7h3v-1L9.64 7.64zM6 8c-1.1 0-2-.89-2-2s.9-2 2-2 2 .89 2 2-.9 2-2 2zm0 12c-1.1 0-2-.89-2-2s.9-2 2-2 2 .89 2 2-.9 2-2 2zm6-7.5c-.28 0-.5-.22-.5-.5s.22-.5.5-.5.5.22.5.5-.22.5-.5.5zM19 3l-6 6 2 2 7-7V3h-3z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-800">Add Grooming Schedule</span>
                                <p class="text-xs text-gray-400">Set grooming reminder</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('petDetail').innerHTML = detailHtml;
    }

    // Show profile list
    window.showProfileList = function() {
        document.getElementById('petDetail').classList.remove('active');
        document.getElementById('profileList').classList.add('active');
    };

    // Modal functions
    window.openEditProfileModal = function() {
        openModal('editProfileModal');
    };

    window.openAddPetModal = function() {
        openModal('addPetModal');
    };

    window.openEditPetModal = function() {
        if (!currentPet) return;

        document.getElementById('editPetId').value = currentPet.id;
        document.getElementById('editPetName').value = currentPet.pet_name;
        document.getElementById('editPetSpecies').value = currentPet.species;
        document.getElementById('editPetBreed').value = currentPet.breed || '';
        document.getElementById('editPetGender').value = currentPet.gender;
        document.getElementById('editPetBirthDate').value = currentPet.birth_date;
        document.getElementById('editPetColor').value = currentPet.color || '';
        document.getElementById('editPetDescription').value = currentPet.description || '';
        document.getElementById('editPetImagePreview').src = currentPet.image;
        document.getElementById('editPetForm').action = `/pets/${currentPet.id}`;

        openModal('editPetModal');
    };

    window.openAddGrowthModal = function() {
        if (!currentPet) return;
        document.getElementById('growthPetId').value = currentPet.id;
        openModal('addGrowthModal');
    };

    window.openAddReminderFromDetail = function(category) {
        if (!currentPet) return;
        document.getElementById('reminderPetId').value = currentPet.id;

        // Map display category to backend category
        const categoryMap = {
            'food': 'feeding',
            'grooming': 'grooming',
            'vaccine': 'vaccination'
        };
        const backendCategory = categoryMap[category] || category;

        // Pre-select category
        const categoryInput = document.querySelector(`input[name="category"][value="${backendCategory}"]`);
        if (categoryInput) categoryInput.checked = true;

        openModal('addReminderModal');
    };

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
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

    // Preview image functions
    window.previewProfileImage = function(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    window.previewEditPetImage = function(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('editPetImagePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    window.previewPetImage = function(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('petImagePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    // Edit Profile Form
    document.getElementById('editProfileForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const btn = document.getElementById('editProfileBtn');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        try {
            const response = await fetch('{{ route('user.profile.update') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                closeModal('editProfileModal');
                window.location.reload();
            } else {
                alert(data.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Simpan Perubahan';
        }
    });

    // Add Growth Form
    document.getElementById('addGrowthForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData);

        try {
            const response = await fetch('{{ route('user.growth.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (result.success || response.ok) {
                closeModal('addGrowthModal');
                // Refresh pet detail
                if (currentPet) showPetDetail(currentPet.id);
                alert('Data pertumbuhan berhasil ditambahkan!');
            } else {
                alert(result.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan');
        }
    });

    // Add Reminder Form
    document.getElementById('addReminderForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData);

        try {
            const response = await fetch('{{ route('user.reminders.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (result.success || response.ok) {
                closeModal('addReminderModal');
                alert('Reminder berhasil ditambahkan!');
            } else {
                alert(result.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan');
        }
    });

    // Delete Pet
    window.deletePet = async function() {
        if (!currentPet) return;

        if (!confirm(`Yakin ingin menghapus ${currentPet.pet_name}? Semua data terkait akan ikut terhapus.`)) {
            return;
        }

        try {
            const response = await fetch(`/pets/${currentPet.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            });

            if (response.ok) {
                closeModal('editPetModal');
                window.location.reload();
            } else {
                alert('Gagal menghapus hewan');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus');
        }
    };

    // Edit Pet Form
    document.getElementById('editPetForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        if (!currentPet) return;

        const formData = new FormData(this);

        try {
            const response = await fetch(`/pets/${currentPet.id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT',
                },
                body: formData
            });

            const result = await response.json();
            if (result.success) {
                closeModal('editPetModal');
                // Refresh pet detail
                showPetDetail(currentPet.id);
                alert('Pet berhasil diupdate!');
            } else {
                alert(result.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan');
        }
    });

    // Close modal on outside click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal') && e.target.classList.contains('show')) {
            closeModal(e.target.id);
        }
    });
</script>
@endpush
