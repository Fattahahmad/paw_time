{{--
  Modal Component
  Reusable modal dengan backdrop dan close button

  Usage:
  <x-ui.modal id="myModal" title="Modal Title">
      <p>Modal content here</p>
  </x-ui.modal>
--}}

@props([
    'id' => 'modal',
    'title' => 'Modal',
])

<div id="{{ $id }}" {{ $attributes->merge(['class' => 'modal px-4']) }}>
    <div class="modal-content bg-white rounded-3xl p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $title }}</h2>
            <button onclick="document.getElementById('{{ $id }}').classList.remove('show')">
                <x-ui.icon name="close" size="w-6 h-6" color="currentColor" class="text-gray-500" />
            </button>
        </div>

        {{ $slot }}
    </div>
</div>
