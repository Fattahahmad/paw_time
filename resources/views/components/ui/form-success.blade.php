{{--
  Success Message Component

  Usage:
  <x-ui.form-success message="Pet added successfully!" />
--}}

@props(['message' => ''])

@if ($message)
    <div {{ $attributes->merge(['class' => 'bg-green-50 border-l-4 border-green-500 rounded-lg p-4 mb-6']) }}>
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span class="ml-3 text-sm font-medium text-green-800">{{ $message }}</span>
        </div>
    </div>
@endif
