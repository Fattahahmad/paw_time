{{--
  Form Validation Components

  Usage - Inline Error:
  <x-ui.form-error message="This field is required" />

  Usage - Error Summary:
  <x-ui.form-error-summary :errors="['Name is required', 'Email is invalid']" />
--}}

{{-- Inline Error Message --}}
@props(['message' => ''])

@if ($message)
    <div {{ $attributes->merge(['class' => 'flex items-center space-x-2 mt-2 text-red-600 text-sm']) }}>
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                clip-rule="evenodd" />
        </svg>
        <span>{{ $message }}</span>
    </div>
@endif
