@props([
    'headers' => [],
    'actions' => true,
    'checkbox' => false
])

<div class="admin-table-wrapper bg-white rounded-2xl shadow-sm overflow-hidden">
    {{-- Table Header --}}
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <h3 class="font-semibold text-gray-800">{{ $title ?? 'Data Table' }}</h3>
            @if(isset($badge))
                <span class="px-3 py-1 bg-[#68C4CF]/10 text-[#68C4CF] text-sm font-medium rounded-full">
                    {{ $badge }}
                </span>
            @endif
        </div>
        
        <div class="flex items-center space-x-3">
            {{-- Search in table --}}
            <div class="flex items-center bg-gray-100 rounded-xl px-3 py-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none ml-2 text-sm text-gray-600 w-32">
            </div>
            
            {{-- Filter button --}}
            <button class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <span>Filter</span>
            </button>
            
            {{-- Add button slot --}}
            @if(isset($addButton))
                {{ $addButton }}
            @endif
        </div>
    </div>
    
    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    @if($checkbox)
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" class="admin-checkbox rounded border-gray-300 text-[#68C4CF] focus:ring-[#68C4CF]">
                        </th>
                    @endif
                    @foreach($headers as $header)
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                    @if($actions)
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    @if(isset($pagination))
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">Showing 1 to 10 of 50 entries</p>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                    Previous
                </button>
                <button class="px-3 py-2 bg-[#68C4CF] text-white rounded-lg text-sm">1</button>
                <button class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200 transition-colors">2</button>
                <button class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200 transition-colors">3</button>
                <button class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
