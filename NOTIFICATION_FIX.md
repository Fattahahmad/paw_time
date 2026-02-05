# Fix Notification Issues

## Problem Ditemukan:

### 1. **FCM Token Invalid**
Ada token lama yang sudah tidak valid:
```
"The registration token is not a valid FCM registration token"
```

### 2. **Log Tidak Tersimpan**
Admin test page tidak menggunakan `sendAndLog()`, hanya `sendToUser()`.

---

## Solusi yang Sudah Diterapkan:

### ✅ Update NotificationTestController
- **send()** - sekarang pakai `sendAndLog()`
- **broadcast()** - sekarang pakai `sendAndLog()`
- Semua notifikasi otomatis tersimpan di `notification_logs`

---

## Yang Perlu Dilakukan User:

### 1. **Refresh FCM Token di HP**

**Cara 1: Login Ulang**
```
POST /api/auth/login
{
  "email": "user@example.com",
  "password": "password",
  "fcm_token": "NEW_FCM_TOKEN_FROM_APP"
}
```

**Cara 2: Register Token Endpoint** (buat endpoint baru)
```
POST /api/fcm-token/register
{
  "fcm_token": "NEW_TOKEN"
}
```

### 2. **Hapus Token Lama yang Invalid**

Jalankan di tinker atau buat script:
```php
// Hapus token yang sudah deactivated
php artisan tinker

App\Models\FcmToken::where('is_active', false)->delete();

// Atau reset semua token
App\Models\FcmToken::truncate();
```

### 3. **Test Notifikasi Lagi**

1. Login ulang di HP dengan FCM token baru
2. Admin → Test Notif page
3. Send to user
4. Cek log: `SELECT * FROM notification_logs ORDER BY id DESC LIMIT 1`
5. Cek HP - notif harus masuk

---

## Cek Status Notifikasi

### Via Database:
```sql
SELECT 
  id, 
  user_id, 
  title, 
  status, 
  sent_at, 
  error_message,
  created_at
FROM notification_logs 
ORDER BY id DESC 
LIMIT 5;
```

### Via Tinker:
```php
php artisan tinker

// Cek log terbaru
NotificationLog::latest()->first();

// Cek FCM tokens aktif
FcmToken::where('is_active', 1)->get(['user_id', 'device_type', 'token']);

// Cek user punya token atau tidak
$user = User::find(1);
$user->fcmTokens()->active()->count(); // harus > 0
```

---

## Debugging Steps:

### Jika notif masih tidak masuk:

**1. Cek FCM Token Valid**
```php
$user = User::find(USER_ID);
$tokens = $user->fcmTokens()->active()->get();

foreach ($tokens as $token) {
    echo "Token ID: {$token->id}\n";
    echo "Device: {$token->device_type}\n";
    echo "Token: {$token->token}\n\n";
}
```

**2. Test Manual Send**
```php
$service = app(FirebaseService::class);
$user = User::find(USER_ID);

$log = $service->sendAndLog(
    $user,
    'Test Manual',
    'Testing dari tinker',
    ['type' => 'test']
);

echo "Status: {$log->status}\n";
echo "Error: {$log->error_message}\n";
print_r($log->data);
```

**3. Cek Firebase Credentials**
```bash
# Pastikan file ada
ls -la storage/app/firebase-credentials.json

# Cek isi file valid JSON
cat storage/app/firebase-credentials.json | python -m json.tool
```

**4. Cek Laravel Logs**
```bash
tail -f storage/logs/laravel.log
```

Saat kirim notif, cek ada error atau tidak.

---

## Root Cause:

Token FCM dari HP **expired** atau **tidak valid**. Flutter app perlu:

1. **Request token baru saat app start:**
```dart
final fcmToken = await FirebaseMessaging.instance.getToken();
print('FCM Token: $fcmToken');
```

2. **Kirim token saat login:**
```dart
await apiService.login(
  email: email,
  password: password,
  fcmToken: fcmToken, // <-- Ini yang penting
);
```

3. **Listen token refresh:**
```dart
FirebaseMessaging.instance.onTokenRefresh.listen((newToken) {
  // Update token ke server
  apiService.updateFcmToken(newToken);
});
```

---

## Expected Result After Fix:

1. ✅ Log tersimpan di `notification_logs`
2. ✅ Admin bisa lihat status sent/failed
3. ✅ Token invalid otomatis deactivated
4. ✅ Notif masuk ke HP kalau token valid
