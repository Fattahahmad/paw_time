@props([
    'title' => 'Form',
    'subtitle' => null,
    'action' => '#',
    'method' => 'POST'
])

<div class="admin-form-card bg-white rounded-2xl shadow-sm overflow-hidden">
    {{-- Form Header --}}
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#68C4CF]/5 to-transparent">
        <h3 class="font-semibold text-gray-800 text-lg">{{ $title }}</h3>
        @if($subtitle)
            <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    
    {{-- Form Body --}}
    <form action="{{ $action }}" method="{{ $method === 'GET' ? 'GET' : 'POST' }}" {{ $attributes }}>
        @csrf
        @if(!in_array($method, ['GET', 'POST']))
            @method($method)
        @endif
        
        <div class="p-6 space-y-6">
            {{ $slot }}
        </div>
        
        {{-- Form Footer --}}
        @if(isset($footer))
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end space-x-3">
                {{ $footer }}
            </div>
        @endif
    </form>
</div>
