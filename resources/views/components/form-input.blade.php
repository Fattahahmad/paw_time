{{--
  Form Input Component with Icon

  Usage:
  @include('components.form-input', [
      'label' => 'Email',
      'type' => 'email',
      'placeholder' => 'your@email.com',
      'icon' => 'email',  // or 'password', 'user', etc
      'id' => 'emailInput',
      'required' => true
  ])
--}}

<div>
    @if (isset($label) && $label)
        <label for="{{ $id ?? '' }}" class="block text-sm font-semibold text-gray-700 mb-2">
            {{ $label }}
        </label>
    @endif

    <div class="relative flex items-center">
        {{-- Left Icon --}}
        @if (isset($icon) && $icon)
            <div class="absolute left-4 pointer-events-none flex items-center justify-center icon-wrapper">
                @include('components.icon', [
                    'name' => $icon,
                    'size' => 'w-5 h-5',
                    'color' => 'currentColor',
                ])
            </div>
        @endif

        {{-- Input Field --}}
        <input type="{{ $type ?? 'text' }}" id="{{ $id ?? '' }}" placeholder="{{ $placeholder ?? '' }}"
            class="input-field w-full {{ isset($icon) ? 'pl-12' : 'pl-4' }} {{ isset($passwordToggleId) ? 'pr-12' : 'pr-4' }} py-3.5 border-2 border-gray-200 rounded-2xl text-gray-700 bg-gray-50"
            {{ isset($required) && $required ? 'required' : '' }}>

        {{-- Password Toggle (if applicable) --}}
        @if (isset($passwordToggleId))
            <div class="password-toggle absolute right-4 cursor-pointer flex items-center justify-center icon-wrapper"
                onclick="togglePassword('{{ $id }}', this)">
                @include('components.icon', [
                    'name' => 'eye-icon',
                    'size' => 'w-5 h-5',
                    'color' => 'currentColor',
                ])
            </div>
        @endif
    </div>
</div>
