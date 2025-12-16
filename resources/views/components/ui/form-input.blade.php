{{--
  Form Input Component with Icon

  Usage:
  <x-ui.form-input label="Email" type="email" placeholder="your@email.com" icon="email" id="emailInput" :required="true" />
  <x-ui.form-input label="Password" type="password" icon="password" id="password" :passwordToggleId="true" />
--}}

@props([
    'label' => null,
    'type' => 'text',
    'placeholder' => '',
    'icon' => null,
    'id' => '',
    'required' => false,
    'passwordToggleId' => false,
])

<div>
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-semibold text-gray-700 mb-2">
            {{ $label }}
        </label>
    @endif

    <div class="relative flex items-center">
        {{-- Left Icon --}}
        @if ($icon)
            <div class="absolute left-4 pointer-events-none flex items-center justify-center icon-wrapper">
                <x-ui.icon :name="$icon" size="w-5 h-5" color="currentColor" />
            </div>
        @endif

        {{-- Input Field --}}
        <input type="{{ $type }}" id="{{ $id }}" placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'input-field w-full ' . ($icon ? 'pl-12' : 'pl-4') . ' ' . ($passwordToggleId ? 'pr-12' : 'pr-4') . ' py-3.5 border-2 border-gray-200 rounded-2xl text-gray-700 bg-gray-50']) }}
            @if ($required) required @endif>

        {{-- Password Toggle (if applicable) --}}
        @if ($passwordToggleId)
            <div class="password-toggle absolute right-4 cursor-pointer flex items-center justify-center icon-wrapper"
                onclick="togglePassword('{{ $id }}', this)">
                <x-ui.icon name="eye-icon" size="w-5 h-5" color="currentColor" />
            </div>
        @endif
    </div>
</div>
