@props([
    'id' => 'confirmationModal',
    'title' => 'Confirmation',
    'message' => 'Are you sure?',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'confirmColor' => 'red', // red, blue, green, orange
    'icon' => 'alert',
    'iconColor' => 'red',
    'showDetails' => true,
])

<div id="{{ $id }}" class="modal">
    <div class="modal-content bg-white md:max-w-md rounded-3xl p-6">
        {{-- Warning Icon --}}
        <div class="flex justify-center mb-4">
            <div class="bg-{{ $iconColor }}-100 p-4 rounded-full">
                <x-ui.icon :name="$icon" size="w-8 h-8" color="currentColor" class="text-{{ $iconColor }}-600" />
            </div>
        </div>

        {{-- Title --}}
        <h2 class="text-xl font-bold text-gray-800 text-center mb-2">{{ $title }}</h2>
        <p class="text-sm text-gray-600 text-center mb-6">{{ $message }}</p>

        {{-- Details Section (Optional) --}}
        @if ($showDetails)
            <div class="bg-gray-50 rounded-2xl p-4 mb-6 border-l-4 border-{{ $iconColor }}-400">
                <div class="flex items-start space-x-3">
                    <div id="{{ $id }}Icon" class="bg-orange-100 p-2 rounded-xl">
                        <x-ui.icon name="cart" size="w-5 h-5" color="currentColor" class="text-orange-500" />
                    </div>
                    <div class="flex-1">
                        <h3 id="{{ $id }}Title" class="font-bold text-gray-800 text-sm mb-2"></h3>
                        <div class="space-y-1 text-xs text-gray-600">
                            <div class="flex items-center space-x-2">
                                <x-ui.icon name="user" size="w-4 h-4" color="currentColor" class="text-gray-500" />
                                <span id="{{ $id }}Detail1"></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <x-ui.icon name="clock" size="w-4 h-4" color="currentColor" class="text-gray-500" />
                                <span id="{{ $id }}Detail2"></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <x-ui.icon name="tag" size="w-4 h-4" color="currentColor" class="text-gray-500" />
                                <span id="{{ $id }}Detail3"></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <x-ui.icon name="info" size="w-4 h-4" color="currentColor" class="text-gray-500" />
                                <span id="{{ $id }}Detail4"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex gap-3">
            <button onclick="window.close{{ ucfirst($id) }}()"
                class="flex-1 px-6 py-3 rounded-xl font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                {{ $cancelText }}
            </button>
            <button onclick="window.confirm{{ ucfirst($id) }}()"
                class="flex-1 px-6 py-3 rounded-xl font-bold text-white
                    @if ($confirmColor === 'red') bg-red-600 hover:bg-red-700
                    @elseif($confirmColor === 'blue') bg-blue-600 hover:bg-blue-700
                    @elseif($confirmColor === 'green') bg-green-600 hover:bg-green-700
                    @elseif($confirmColor === 'orange') bg-orange-600 hover:bg-orange-700 @endif
                    transition shadow-lg hover:shadow-xl transform hover:scale-105">
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>
