{{--
  Form Error Summary Component

  Usage:
  <x-ui.form-error-summary :errors="$errors->all()" />
  <x-ui.form-error-summary :errors="['Error 1', 'Error 2']" title="Please fix the following:" />
--}}

@props([
    'errors' => [],
    'title' => 'Please fix the following errors:',
])

@if (is_array($errors) && count($errors) > 0)
    <div {{ $attributes->merge(['class' => 'bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6']) }}>
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-red-800 mb-2">{{ $title }}</h3>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
