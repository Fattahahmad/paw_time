# Testing Checklist Notifikasi

## ✅ Yang Sudah Siap:

### 1. Database & FCM Tokens
- ✅ Table `fcm_tokens` ada
- ✅ Ada 3 FCM tokens tersimpan
- ✅ Column `device_type` sudah VARCHAR (bisa android/ios/web)

### 2. Backend Integration
- ✅ API Register - terima `fcm_token` (nullable)
- ✅ API Login - terima `fcm_token` (nullable)
- ✅ Web Register - auto-ambil FCM token dari Firebase
- ✅ Web Login - auto-ambil FCM token dari Firebase
- ✅ Auto-detect device: android/ios/web dari User-Agent

### 3. Admin Test Page
- ✅ Route: `/admin/notification-test`
- ✅ Controller: `NotificationTestController`
- ✅ View: `resources/views/pages/admin/notification-test.blade.php`
- ✅ Menu sidebar: "Test Notif"
- ✅ Fitur:
  - Send to specific user
  - Broadcast to all users
  - Test log history

### 4. Firebase Service
- ✅ `app/Services/FirebaseService.php` ada
- ✅ Method `sendToUser()` untuk kirim ke 1 user
- ✅ Ambil semua FCM tokens user yang aktif

---

## 🔧 Yang Perlu Dicek/Setup:

### 1. Firebase Configuration
**File: `.env`**
```env
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_CREDENTIALS_PATH=/path/to/firebase-credentials.json
FIREBASE_VAPID_KEY=your-vapid-key
```

**Atau tambahkan di `.env`:**
```env
FIREBASE_API_KEY=xxx
FIREBASE_AUTH_DOMAIN=xxx.firebaseapp.com
FIREBASE_MESSAGING_SENDER_ID=xxx
FIREBASE_APP_ID=xxx
```

### 2. Firebase Credentials File
Pastikan file `storage/app/firebase-credentials.json` ada dengan isi:
```json
{
  "type": "service_account",
  "project_id": "xxx",
  "private_key_id": "xxx",
  "private_key": "-----BEGIN PRIVATE KEY-----\nxxx\n-----END PRIVATE KEY-----\n",
  "client_email": "firebase-adminsdk-xxx@xxx.iam.gserviceaccount.com",
  ...
}
```

### 3. Web Firebase Config
File `resources/views/pages/auth.blade.php` sudah ada Firebase SDK, tapi perlu pastikan config:
```javascript
const firebaseConfig = {
    apiKey: "{{ config('services.firebase.api_key') }}",
    authDomain: "{{ config('services.firebase.auth_domain') }}",
    projectId: "{{ config('services.firebase.project_id') }}",
    ...
};
```

Update `config/services.php` untuk web:
```php
'firebase' => [
    'project_id' => env('FIREBASE_PROJECT_ID'),
    'credentials_path' => env('FIREBASE_CREDENTIALS_PATH', storage_path('app/firebase-credentials.json')),
    'vapid_key' => env('FIREBASE_VAPID_KEY'),
    // Tambahkan untuk web:
    'api_key' => env('FIREBASE_API_KEY'),
    'auth_domain' => env('FIREBASE_AUTH_DOMAIN'),
    'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID'),
    'app_id' => env('FIREBASE_APP_ID'),
],
```

---

## 🧪 Cara Testing:

### Test 1: Web Notification
1. Buka browser → login di `http://127.0.0.1:8000`
2. Browser akan minta permission notifikasi → Allow
3. FCM token otomatis tersimpan
4. Login sebagai admin
5. Buka `/admin/notification-test`
6. Pilih user → kirim notif
7. Cek browser notification muncul

### Test 2: Android (Postman/Flutter)
1. **Dapatkan FCM Token dari Android:**
   - Di Flutter app, request FCM token: `FirebaseMessaging.instance.getToken()`
   - Copy token

2. **Login dengan FCM Token:**
   ```
   POST /api/auth/login
   {
     "email": "user@example.com",
     "password": "password",
     "fcm_token": "paste-token-dari-android"
   }
   ```

3. **Cek Response:**
   ```json
   {
     "fcm_token": "token-yang-disimpan"
   }
   ```

4. **Test Kirim dari Admin:**
   - Login admin → Test Notif page
   - Pilih user yang tadi login
   - Send notification
   - Cek notif muncul di Android

### Test 3: Broadcast
1. Admin → Test Notif page
2. Tab "Broadcast to All"
3. Isi title & message
4. Send
5. Semua device (web + android yang online) akan terima

---

## ⚠️ Troubleshooting:

### Issue: "No active FCM tokens for user"
**Solusi:** User belum login atau FCM token belum tersimpan. Login ulang dengan kirim `fcm_token`.

### Issue: Web notification tidak muncul
**Solusi:** 
- Cek browser permission: Settings → Notifications → Allow
- Cek Firebase config di auth.blade.php
- Cek console browser ada error tidak

### Issue: Android notification tidak muncul
**Solusi:**
- Pastikan FCM token valid (tidak expired)
- Cek Firebase credentials di `storage/app/firebase-credentials.json`
- Cek FirebaseService bisa akses credentials

### Issue: "VAPID key not found"
**Solusi:** Tambahkan `FIREBASE_VAPID_KEY` di `.env`

---

## 📱 Next Steps untuk Production:

1. **Setup Firebase Project:**
   - Buat project di Firebase Console
   - Download `firebase-credentials.json`
   - Enable Cloud Messaging

2. **Update .env Production:**
   ```env
   FIREBASE_PROJECT_ID=paw-time-prod
   FIREBASE_CREDENTIALS_PATH=/var/www/html/storage/app/firebase-credentials.json
   FIREBASE_VAPID_KEY=xxx
   FIREBASE_API_KEY=xxx
   FIREBASE_AUTH_DOMAIN=xxx.firebaseapp.com
   FIREBASE_MESSAGING_SENDER_ID=xxx
   FIREBASE_APP_ID=xxx
   ```

3. **Upload credentials file** ke server

4. **Test dari production**

---

## 📊 Current Status:

| Feature | Status |
|---------|--------|
| FCM Token Storage | ✅ Working (3 tokens) |
| API Login/Register | ✅ Accept fcm_token |
| Web Login/Register | ✅ Auto-get token |
| Admin Test Page | ✅ Ready |
| FirebaseService | ✅ Code ready |
| Firebase Config | ⚠️ Need .env setup |
| Credentials File | ⚠️ Need firebase-credentials.json |

**Kesimpulan:** Infrastruktur sudah siap, tinggal setup Firebase credentials untuk testing.
