{{--
  Tab Switch Component

  Usage:
  @include('components.tab-switch', [
      'tabs' => [
          ['id' => 'login', 'label' => 'Sign In'],
          ['id' => 'register', 'label' => 'Sign Up']
      ],
      'activeTab' => 'login'
  ])
--}}

<div class="flex border-b border-gray-200 mb-8">
    @foreach ($tabs as $tab)
        <button onclick="switchTab('{{ $tab['id'] }}')" id="{{ $tab['id'] }}Tab"
            class="tab-btn {{ $activeTab === $tab['id'] ? 'active' : '' }} flex-1 pb-4 text-lg font-semibold text-gray-500">
            {{ $tab['label'] }}
        </button>
    @endforeach
</div>
