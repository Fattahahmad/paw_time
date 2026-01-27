@props([
    'name' => 'gender',
    'selected' => 'male', // 'male' or 'female'
])

<div class="grid grid-cols-2 gap-3" x-data="{ gender: '{{ $selected }}' }">
    <input type="hidden" name="{{ $name }}" x-model="gender">
    
    <button type="button"
        class="gender-btn py-3 rounded-2xl font-semibold flex items-center justify-center space-x-2 transition-all"
        :class="gender === 'male' ? 'border-2 border-blue-300 bg-blue-50 text-blue-600' : 'border-2 border-gray-200 text-gray-600 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600'"
        @click="gender = 'male'">
        <x-ui.icon name="male" size="w-5 h-5" color="currentColor" />
        <span>Male</span>
    </button>
    
    <button type="button"
        class="gender-btn py-3 rounded-2xl font-semibold flex items-center justify-center space-x-2 transition-all"
        :class="gender === 'female' ? 'border-2 border-pink-300 bg-pink-50 text-pink-600' : 'border-2 border-gray-200 text-gray-600 hover:border-pink-300 hover:bg-pink-50 hover:text-pink-600'"
        @click="gender = 'female'">
        <x-ui.icon name="female" size="w-5 h-5" color="currentColor" />
        <span>Female</span>
    </button>
</div>
