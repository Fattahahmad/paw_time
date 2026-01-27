@props([
    'label' => '',
    'name' => '',
    'placeholder' => '',
    'value' => '',
    'rows' => 4,
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
    
    <textarea 
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'admin-textarea w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#68C4CF]/20 focus:border-[#68C4CF] transition-all duration-300 resize-none ' . ($disabled ? 'bg-gray-100 cursor-not-allowed' : 'bg-white')]) }}
    >{{ old($name, $value) }}</textarea>
    
    @if($hint)
        <p class="mt-2 text-sm text-gray-500">{{ $hint }}</p>
    @endif
    
    @error($name)
        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
