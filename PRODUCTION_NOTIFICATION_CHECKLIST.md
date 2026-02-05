# 🚀 Production Notification Setup Checklist

## ✅ Files to Upload/Check

### 1. **Firebase Credentials**
```bash
# File location on server
storage/app/firebase-credentials.json
```

**Steps:**
- [ ] Download `firebase-credentials.json` dari Firebase Console
- [ ] Upload ke `storage/app/` di server production
- [ ] Set permission: `chmod 644 storage/app/firebase-credentials.json`
- [ ] Verify file exists: `ls -la storage/app/firebase-credentials.json`

### 2. **Environment Variables (.env)**
```bash
FIREBASE_PROJECT_ID=paw-time
FIREBASE_VAPID_KEY=BGsl1pnkBbh_6Rt6W46zah0rH3edA_V77cBMvdPiF9bJO9cX5XbSSYTr-wCqlqH-WW89Bfph6FeKgwNCOQZRX5Q
```

**Steps:**
- [ ] Copy `.env` values ke server production
- [ ] Run: `php artisan config:clear`
- [ ] Run: `php artisan cache:clear`
- [ ] Verify: `php artisan tinker --execute="echo config('services.firebase.project_id');"`

### 3. **Database Migration**
```bash
# Check if notification_logs table exists
php artisan migrate:status

# Check if notification_sent_at column exists in reminders
php artisan tinker --execute="Schema::hasColumn('reminders', 'notification_sent_at') ? 'YES' : 'NO';"
```

**Steps:**
- [ ] Run: `php artisan migrate`
- [ ] Verify tables: `notification_logs`, `fcm_tokens`
- [ ] Verify column: `reminders.notification_sent_at`

### 4. **Storage Permissions**
```bash
# Set proper permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### 5. **Composer Dependencies**
```bash
# Install Google Client Library
composer require google/apiclient:"^2.0"
```

**Check installation:**
```bash
php artisan tinker --execute="echo class_exists('Google\Client') ? 'INSTALLED' : 'NOT INSTALLED';"
```

---

## 🔍 Debug Production Errors

### Check Laravel Logs
```bash
# View latest errors
tail -f storage/logs/laravel.log

# Search for Firebase errors
grep -i "firebase" storage/logs/laravel.log | tail -20
grep -i "fcm" storage/logs/laravel.log | tail -20
```

### Test Firebase Connection
```bash
# Test access token generation
php artisan tinker --execute="
try {
    \$fs = app(App\Services\FirebaseService::class);
    echo 'Firebase service loaded OK\n';
} catch (Exception \$e) {
    echo 'ERROR: ' . \$e->getMessage() . '\n';
}
"
```

### Check FCM Tokens
```bash
# Count active tokens
php artisan tinker --execute="echo 'Active tokens: ' . App\Models\FcmToken::active()->count();"

# List tokens by user
php artisan tinker --execute="
App\Models\FcmToken::active()->get()->each(function(\$t) {
    echo 'User ' . \$t->user_id . ': ' . \$t->device_type . '\n';
});
"
```

### Test Notification Send
```bash
# Send test notification to user ID 1
php artisan tinker --execute="
\$fs = app(App\Services\FirebaseService::class);
\$user = App\Models\User::find(1);
if (\$user) {
    \$log = \$fs->sendAndLog(\$user, 'Test', 'Testing from production');
    echo 'Status: ' . \$log->status . '\n';
    echo 'Error: ' . (\$log->error_message ?? 'none') . '\n';
} else {
    echo 'User not found\n';
}
"
```

---

## ⚠️ Common Production Errors

### Error: "Unknown error"

**Possible Causes:**
1. **Firebase credentials not uploaded**
   ```bash
   ls -la storage/app/firebase-credentials.json
   ```

2. **Google Client library not installed**
   ```bash
   composer require google/apiclient:"^2.0"
   ```

3. **Wrong project ID in .env**
   ```bash
   grep FIREBASE_PROJECT_ID .env
   ```

4. **Insufficient permissions**
   ```bash
   chmod 644 storage/app/firebase-credentials.json
   ```

### Error: "No active FCM tokens"

**Solutions:**
- Users need to re-login from Flutter app
- Check: `SELECT COUNT(*) FROM fcm_tokens WHERE is_active = 1;`
- Tokens might be expired/invalid

### Error: "Failed to get access token"

**Solutions:**
1. Re-download credentials from Firebase Console
2. Ensure credentials file is valid JSON:
   ```bash
   php -r "json_decode(file_get_contents('storage/app/firebase-credentials.json'));"
   ```
3. Check service account permissions in Firebase Console

---

## 📊 Monitoring Commands

### Check Recent Notification Logs
```bash
php artisan tinker --execute="
App\Models\NotificationLog::latest()->take(10)->get()->each(function(\$log) {
    echo '[\' . \$log->id . \'] ' . \$log->status . ' - ' . \$log->title . ' (User: ' . \$log->user_id . ')\n';
});
"
```

### Check Failed Notifications
```bash
php artisan tinker --execute="
echo 'Failed: ' . App\Models\NotificationLog::where('status', 'failed')->count() . '\n';
echo 'Sent: ' . App\Models\NotificationLog::where('status', 'sent')->count() . '\n';
"
```

### View Last Error Details
```bash
php artisan tinker --execute="
\$log = App\Models\NotificationLog::where('status', 'failed')->latest()->first();
if (\$log) {
    echo 'User: ' . \$log->user->name . '\n';
    echo 'Error: ' . \$log->error_message . '\n';
    echo 'Data: ' . json_encode(\$log->data, JSON_PRETTY_PRINT) . '\n';
}
"
```

---

## 🔄 Scheduled Tasks (Cron)

### Setup Cron Job
```bash
# Add to crontab
crontab -e

# Add this line:
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### Verify Scheduler
```bash
# List scheduled tasks
php artisan schedule:list

# Test reminder command
php artisan reminders:send-notifications
```

---

## 🧪 Production Test Script

Create `test_production_notif.php` di server:

```php
<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PRODUCTION NOTIFICATION TEST ===\n\n";

// 1. Check credentials
if (!file_exists(storage_path('app/firebase-credentials.json'))) {
    echo "❌ Firebase credentials NOT FOUND!\n";
    exit(1);
}
echo "✅ Firebase credentials found\n";

// 2. Check Google Client
if (!class_exists('Google\Client')) {
    echo "❌ Google Client NOT INSTALLED!\n";
    exit(1);
}
echo "✅ Google Client installed\n";

// 3. Check project ID
$projectId = config('services.firebase.project_id');
if (!$projectId) {
    echo "❌ FIREBASE_PROJECT_ID not set!\n";
    exit(1);
}
echo "✅ Project ID: $projectId\n";

// 4. Test FirebaseService
try {
    $fs = app(App\Services\FirebaseService::class);
    echo "✅ FirebaseService instantiated\n";
} catch (Exception $e) {
    echo "❌ FirebaseService error: {$e->getMessage()}\n";
    exit(1);
}

// 5. Check active tokens
$tokenCount = App\Models\FcmToken::active()->count();
echo "Active FCM tokens: $tokenCount\n";

// 6. Send test notification
$user = App\Models\User::where('role', 'user')->first();
if (!$user) {
    echo "❌ No users found!\n";
    exit(1);
}

echo "\nSending test notification to {$user->name}...\n";
try {
    $log = $fs->sendAndLog($user, 'Production Test', 'Testing from production server');
    echo "Status: {$log->status}\n";
    if ($log->error_message) {
        echo "Error: {$log->error_message}\n";
    }
    if ($log->data) {
        echo "Details: " . json_encode($log->data, JSON_PRETTY_PRINT) . "\n";
    }
} catch (Exception $e) {
    echo "❌ Send error: {$e->getMessage()}\n";
}

echo "\n=== TEST COMPLETE ===\n";
```

Run:
```bash
php test_production_notif.php
```

---

## 📱 Flutter App Requirements

Users must:
1. Login/Register with updated app version
2. Grant notification permission
3. FCM token akan auto-register ke database
4. Token harus dalam status `is_active = 1`

---

## 🔐 Security Notes

- **NEVER** commit `firebase-credentials.json` to Git
- Add to `.gitignore`: `storage/app/firebase-credentials.json`
- Keep VAPID key secret
- Use environment-specific credentials for dev/staging/prod
