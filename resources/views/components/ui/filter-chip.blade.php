@props([
    'label' => 'Filter',
    'active' => false,
    'onclick' => null,
])

<button
    class="filter-chip {{ $active ? 'active' : '' }} px-4 py-2 rounded-full text-sm font-semibold bg-white border border-gray-200 text-gray-700 whitespace-nowrap shadow-sm hover:bg-gray-50"
    @if ($onclick) onclick="{{ $onclick }}" @endif>
    {{ $label }}
</button>
