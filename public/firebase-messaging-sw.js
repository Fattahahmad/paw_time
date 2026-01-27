// Firebase Messaging Service Worker
// This file should be at the root of your public directory

importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging-compat.js');

// Initialize Firebase
firebase.initializeApp({
    apiKey: "AIzaSyD5Xc_ybcA5OkNLW-EeN3_cqZ5lWXswNmQ",
    authDomain: "paw-time.firebaseapp.com",
    projectId: "paw-time",
    storageBucket: "paw-time.firebasestorage.app",
    messagingSenderId: "159330113172",
    appId: "1:159330113172:web:ad581ba6407965a88dc681"
});

const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message:', payload);

    const notificationTitle = payload.notification?.title || 'Paw Time';
    const notificationOptions = {
        body: payload.notification?.body || '',
        icon: '/assets/image/notification-icon.png',
        badge: '/assets/image/notification-icon.png',
        tag: payload.data?.reminder_id || 'paw-time-notification',
        data: payload.data,
        requireInteraction: true,
        actions: [
            { action: 'view', title: 'Lihat' },
            { action: 'dismiss', title: 'Tutup' }
        ]
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});

// Handle notification click
self.addEventListener('notificationclick', (event) => {
    console.log('[firebase-messaging-sw.js] Notification click:', event);

    event.notification.close();

    if (event.action === 'view' || !event.action) {
        // Open the app
        event.waitUntil(
            clients.matchAll({ type: 'window', includeUncontrolled: true })
                .then((clientList) => {
                    // If app is already open, focus it
                    for (const client of clientList) {
                        if (client.url.includes('/reminder') && 'focus' in client) {
                            return client.focus();
                        }
                    }
                    // Otherwise open new window
                    if (clients.openWindow) {
                        return clients.openWindow('/reminder');
                    }
                })
        );
    }
});
