@extends('layouts.admin')

@section('page-title', 'Test Notifications')
@section('page-subtitle', 'Send test notifications to users')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Send to Specific User --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-2xl">🔔</span>
                Send to User
            </h2>

            <form id="sendForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                    <select name="user_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        <option value="">Pilih User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" required placeholder="Notifikasi Title"
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" rows="3" required placeholder="Notifikasi message..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type (Optional)</label>
                    <select name="type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        <option value="test">Test</option>
                        <option value="appointment">Appointment</option>
                        <option value="reminder">Reminder</option>
                        <option value="general">General</option>
                    </select>
                </div>

                <button type="submit" id="sendBtn"
                        class="w-full btn-primary flex items-center justify-center gap-2">
                    <span class="text-xl">📤</span>
                    <span>Send Notification</span>
                </button>
            </form>

            <div id="sendResult" class="mt-4 hidden"></div>
        </div>

        {{-- Broadcast to All Users --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-2xl">📢</span>
                Broadcast to All
            </h2>

            <form id="broadcastForm" class="space-y-4">
                @csrf
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                    <p class="text-sm text-yellow-800">
                        ⚠️ Notifikasi akan dikirim ke <strong>{{ $users->count() }} users</strong>
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" required placeholder="Broadcast Title"
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" rows="4" required placeholder="Broadcast message..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500"></textarea>
                </div>

                <button type="submit" id="broadcastBtn"
                        class="w-full btn-primary bg-orange-600 hover:bg-orange-700 flex items-center justify-center gap-2">
                    <span class="text-xl">📣</span>
                    <span>Send Broadcast</span>
                </button>
            </form>

            <div id="broadcastResult" class="mt-4 hidden"></div>
        </div>
    </div>

    {{-- Recent Tests Log --}}
    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="text-2xl">📋</span>
            Test Log
        </h2>
        <div id="testLog" class="space-y-2 text-sm text-gray-600">
            <p class="text-gray-400 italic">No tests sent yet...</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Debug: Log when page loads
    console.log('Notification test page loaded');

    // Send to specific user
    document.getElementById('sendForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Send form submitted');

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        console.log('Form data:', data);

        const btn = document.getElementById('sendBtn');
        const resultDiv = document.getElementById('sendResult');

        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Sending...</span>';

        const url = '{{ route('admin.notification-test.send') }}';
        console.log('Sending to URL:', url);

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            });

            console.log('Response status:', response.status);
            const result = await response.json();
            console.log('Response data:', result);

            if (result.success) {
                resultDiv.className = 'mt-4 p-4 bg-green-50 border border-green-200 rounded-xl';
                resultDiv.innerHTML = `
                    <p class="text-green-800 font-semibold">✅ ${result.message}</p>
                    <pre class="text-xs text-green-700 mt-2">${JSON.stringify(result.details, null, 2)}</pre>
                `;
                addToLog('success', `Sent to ${data.user_id}: ${data.title}`);
                this.reset();
            } else {
                resultDiv.className = 'mt-4 p-4 bg-red-50 border border-red-200 rounded-xl';
                resultDiv.innerHTML = `<p class="text-red-800">❌ ${result.message}</p>`;
                addToLog('error', result.message);
            }
            resultDiv.classList.remove('hidden');

        } catch (error) {
            resultDiv.className = 'mt-4 p-4 bg-red-50 border border-red-200 rounded-xl';
            resultDiv.innerHTML = `<p class="text-red-800">❌ Error: ${error.message}</p>`;
            resultDiv.classList.remove('hidden');
            addToLog('error', error.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span class="text-xl">📤</span><span>Send Notification</span>';
        }
    });

    // Broadcast to all
    document.getElementById('broadcastForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Broadcast form submitted');

        if (!confirm('Kirim broadcast ke semua users?')) {
            return;
        }

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        console.log('Broadcast data:', data);

        const btn = document.getElementById('broadcastBtn');
        const resultDiv = document.getElementById('broadcastResult');

        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Broadcasting...</span>';

        const url = '{{ route('admin.notification-test.broadcast') }}';
        console.log('Broadcasting to URL:', url);

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            });

            console.log('Broadcast response status:', response.status);
            const result = await response.json();
            console.log('Broadcast response data:', result);

            if (result.success) {
                resultDiv.className = 'mt-4 p-4 bg-green-50 border border-green-200 rounded-xl';
                resultDiv.innerHTML = `
                    <p class="text-green-800 font-semibold">✅ ${result.message}</p>
                    <details class="mt-2">
                        <summary class="text-xs text-green-700 cursor-pointer">View Details</summary>
                        <pre class="text-xs text-green-700 mt-2">${JSON.stringify(result.results, null, 2)}</pre>
                    </details>
                `;
                addToLog('success', `Broadcast: ${data.title}`);
                this.reset();
            } else {
                resultDiv.className = 'mt-4 p-4 bg-red-50 border border-red-200 rounded-xl';
                resultDiv.innerHTML = `<p class="text-red-800">❌ ${result.message}</p>`;
                addToLog('error', result.message);
            }
            resultDiv.classList.remove('hidden');

        } catch (error) {
            resultDiv.className = 'mt-4 p-4 bg-red-50 border border-red-200 rounded-xl';
            resultDiv.innerHTML = `<p class="text-red-800">❌ Error: ${error.message}</p>`;
            resultDiv.classList.remove('hidden');
            addToLog('error', error.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span class="text-xl">📣</span><span>Send Broadcast</span>';
        }
    });

    // Add to log
    function addToLog(type, message) {
        const logDiv = document.getElementById('testLog');
        const timestamp = new Date().toLocaleTimeString();
        const icon = type === 'success' ? '✅' : '❌';
        const color = type === 'success' ? 'text-green-600' : 'text-red-600';

        if (logDiv.querySelector('.text-gray-400')) {
            logDiv.innerHTML = '';
        }

        const logEntry = document.createElement('div');
        logEntry.className = `p-2 border-l-4 ${type === 'success' ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50'} rounded`;
        logEntry.innerHTML = `
            <span class="${color} font-medium">${icon} ${timestamp}</span>
            <span class="text-gray-700 ml-2">${message}</span>
        `;
        logDiv.insertBefore(logEntry, logDiv.firstChild);

        // Keep only last 10 entries
        while (logDiv.children.length > 10) {
            logDiv.removeChild(logDiv.lastChild);
        }
    }
</script>
@endpush
