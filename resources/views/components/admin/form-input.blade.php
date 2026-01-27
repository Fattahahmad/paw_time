@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'disabled' => false,
    'hint' => null
])

<div class="admin-form-group">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'admin-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#68C4CF]/20 focus:border-[#68C4CF] transition-all duration-300 ' . ($disabled ? 'bg-gray-100 cursor-not-allowed' : 'bg-white')]) }}
    >
    
    @if($hint)
        <p class="mt-2 text-sm text-gray-500">{{ $hint }}</p>
    @endif
    
    @error($name)
        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
