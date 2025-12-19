{{--
  Modal Component
  Reusable modal dengan backdrop dan close button

  Usage Standard:
  <x-ui.modal id="myModal" title="Modal Title">
      <p>Modal content here</p>
  </x-ui.modal>

  Usage Confirmation:
  <x-ui.modal id="confirmModal" title="Confirm Delete" :isConfirm="true" confirmText="Yes, Delete" confirmColor="danger" cancelText="Cancel">
      <p>Are you sure you want to delete this?</p>
  </x-ui.modal>
--}}

@props([
    'id' => 'modal',
    'title' => 'Modal',
    'isConfirm' => false,
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'confirmColor' => 'primary', // primary | danger | warning
    'maxWidth' => 'md', // sm | md | lg
])

@php
    $maxWidthClass =
        [
            'sm' => 'max-w-sm',
            'md' => 'max-w-md',
            'lg' => 'max-w-lg',
            'xl' => 'max-w-xl',
        ][$maxWidth] ?? 'max-w-md';

    $confirmColorClass =
        [
            'primary' => 'bg-[#68C4CF] hover:bg-[#5AB0BB]',
            'danger' => 'bg-red-500 hover:bg-red-600',
            'warning' => 'bg-orange-500 hover:bg-orange-600',
        ][$confirmColor] ?? 'bg-[#68C4CF] hover:bg-[#5AB0BB]';
@endphp

<div id="{{ $id }}" {{ $attributes->merge(['class' => 'modal']) }}>
    <div class="modal-content bg-white rounded-3xl p-6 w-full {{ $maxWidthClass }} max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $title }}</h2>
            <button onclick="closeModal('{{ $id }}')">
                <x-ui.icon name="close" size="w-6 h-6" color="currentColor"
                    class="text-gray-500 hover:text-gray-700 transition" />
            </button>
        </div>

        <div class="{{ $isConfirm ? 'mb-6' : '' }}">
            {{ $slot }}
        </div>

        @if ($isConfirm)
            <div class="flex space-x-3 mt-6">
                <button onclick="closeModal('{{ $id }}')"
                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 px-4 rounded-2xl font-semibold transition">
                    {{ $cancelText }}
                </button>
                <button onclick="confirmAction('{{ $id }}')"
                    class="flex-1 {{ $confirmColorClass }} text-white py-3 px-4 rounded-2xl font-semibold transition">
                    {{ $confirmText }}
                </button>
            </div>
        @endif
    </div>
</div>

<script>
    // Close modal function
    window.closeModal = function(modalId) {
        document.getElementById(modalId).classList.remove('show');
    };

    // Confirm action (akan di-override per modal)
    window.confirmAction = function(modalId) {
        console.log('Confirm action for:', modalId);
        closeModal(modalId);
    };
</script>
