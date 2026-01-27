/**
 * Paw Time Firebase Notification Service
 * Handles push notifications for the web application
 */

(function() {
    'use strict';

// Firebase Configuration
const FIREBASE_CONFIG = {
    apiKey: "AIzaSyD5Xc_ybcA5OkNLW-EeN3_cqZ5lWXswNmQ",
    authDomain: "paw-time.firebaseapp.com",
    projectId: "paw-time",
    storageBucket: "paw-time.firebasestorage.app",
    messagingSenderId: "159330113172",
    appId: "1:159330113172:web:ad581ba6407965a88dc681",
    measurementId: "G-7DC9RFSNP1"
};

class PawTimeNotifications {
    constructor() {
        this.messaging = null;
        this.currentToken = null;
        this.isInitialized = false;
    }

    /**
     * Initialize Firebase Messaging
     */
    async initialize() {
        if (this.isInitialized) return true;

        try {
            // Check if browser supports notifications
            if (!('Notification' in window)) {
                console.warn('❌ Browser does not support notifications');
                return false;
            }

            // Check if service worker is supported
            if (!('serviceWorker' in navigator)) {
                console.warn('❌ Service Worker not supported');
                return false;
            }

            console.log('🔄 Initializing Firebase...');

            // Initialize Firebase
            if (typeof firebase !== 'undefined') {
                if (!firebase.apps.length) {
                    firebase.initializeApp(FIREBASE_CONFIG);
                    console.log('✅ Firebase app initialized');
                } else {
                    console.log('✅ Firebase app already initialized');
                }
            } else {
                console.error('❌ Firebase SDK not loaded');
                return false;
            }

            this.messaging = firebase.messaging();
            console.log('✅ Firebase messaging ready');

            // Register service worker with dynamic base path
            try {
                // Get base path from current URL
                const basePath = window.SW_BASE_PATH || '';
                const swPath = basePath + '/firebase-messaging-sw.js';
                console.log('🔄 Registering Service Worker at:', swPath);

                const registration = await navigator.serviceWorker.register(swPath);
                console.log('✅ Service Worker registered:', registration.scope);

                // Wait for service worker to be ready
                await navigator.serviceWorker.ready;
                console.log('✅ Service Worker is ready');
            } catch (swError) {
                console.error('❌ Service Worker registration failed:', swError);
                // Continue anyway - some features might still work
            }

            this.isInitialized = true;

            // Handle foreground messages
            this.messaging.onMessage((payload) => {
                console.log('📬 Foreground message received:', payload);
                this.showNotification(payload);
            });

            return true;
        } catch (error) {
            console.error('❌ Error initializing Firebase:', error);
            return false;
        }
    }

    /**
     * Request notification permission and get FCM token
     */
    async requestPermission() {
        try {
            const permission = await Notification.requestPermission();

            if (permission === 'granted') {
                console.log('Notification permission granted');
                return await this.getToken();
            } else {
                console.warn('Notification permission denied');
                return null;
            }
        } catch (error) {
            console.error('Error requesting permission:', error);
            return null;
        }
    }

    /**
     * Get FCM token
     */
    async getToken() {
        if (!this.messaging) {
            console.error('❌ Firebase messaging not initialized');
            return null;
        }

        try {
            const vapidKey = window.VAPID_KEY;
            console.log('🔑 VAPID Key available:', !!vapidKey, vapidKey ? `(${vapidKey.substring(0, 20)}...)` : '');

            if (!vapidKey) {
                console.error('❌ VAPID Key is missing! Check .env file for FIREBASE_VAPID_KEY');
                return null;
            }

            // Get service worker registration
            const basePath = window.SW_BASE_PATH || '';
            const swPath = basePath + '/firebase-messaging-sw.js';
            const registration = await navigator.serviceWorker.getRegistration(swPath);

            if (!registration) {
                console.error('❌ Service Worker not registered at:', swPath);
                return null;
            }

            console.log('🔄 Requesting FCM token...');

            this.currentToken = await this.messaging.getToken({
                vapidKey: vapidKey,
                serviceWorkerRegistration: registration
            });

            if (this.currentToken) {
                console.log('✅ FCM Token obtained:', this.currentToken.substring(0, 30) + '...');
            } else {
                console.warn('⚠️ No FCM token returned');
            }

            return this.currentToken;
        } catch (error) {
            console.error('❌ Error getting FCM token:', error);
            console.error('Error details:', error.message);
            return null;
        }
    }

    /**
     * Register token to backend
     * @param {string} authToken - User's authentication token
     */
    async registerTokenToBackend(authToken) {
        if (!this.currentToken) {
            console.error('No FCM token available');
            return false;
        }

        try {
            const response = await fetch('/api/notifications/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${authToken}`,
                },
                body: JSON.stringify({
                    token: this.currentToken,
                    device_type: 'web',
                    device_name: navigator.userAgent.substring(0, 100),
                }),
            });

            const data = await response.json();
            console.log('Token registered:', data);
            return data.success;
        } catch (error) {
            console.error('Error registering token:', error);
            return false;
        }
    }

    /**
     * Show notification in foreground
     * @param {Object} payload - Firebase message payload
     */
    showNotification(payload) {
        const title = payload.notification?.title || 'Paw Time';
        const options = {
            body: payload.notification?.body || '',
            icon: '/assets/image/notification-icon.png',
            badge: '/assets/image/notification-icon.png',
            tag: payload.data?.reminder_id || 'paw-time-notification',
            data: payload.data,
            requireInteraction: true,
        };

        // Show browser notification
        if (Notification.permission === 'granted') {
            const notification = new Notification(title, options);

            notification.onclick = () => {
                window.focus();
                if (payload.data?.reminder_id) {
                    window.location.href = '/reminder';
                }
                notification.close();
            };
        }

        // Also show in-app toast notification
        this.showToast(title, options.body);
    }

    /**
     * Show in-app toast notification
     * @param {string} title - Notification title
     * @param {string} body - Notification body
     */
    showToast(title, body) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-white rounded-2xl shadow-2xl p-4 max-w-sm z-50 transform translate-x-full transition-transform duration-300';
        toast.innerHTML = `
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-[#68C4CF] rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">${title}</p>
                    <p class="text-sm text-gray-500 truncate">${body}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full');
        });

        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    /**
     * Send test notification (for development)
     * @param {string} authToken - User's authentication token
     */
    async sendTestNotification(authToken) {
        try {
            const response = await fetch('/api/notifications/test', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${authToken}`,
                },
            });

            const data = await response.json();
            console.log('Test notification result:', data);
            return data;
        } catch (error) {
            console.error('Error sending test notification:', error);
            return { success: false, error: error.message };
        }
    }
}

// Create and expose ONLY the instance to global scope
window.PawTimeNotifications = new PawTimeNotifications();

// Log for debugging
console.log('✅ PawTimeNotifications instance created');
console.log('Type:', typeof window.PawTimeNotifications);
console.log('Has initialize?', typeof window.PawTimeNotifications.initialize);
console.log('Instance:', window.PawTimeNotifications);

})(); // End of IIFE
