# Flutter Integration Guide untuk Paw Time Push Notifications

## Step 1: Setup Flutter Project

```bash
flutter create paw_time_app
cd paw_time_app
```

## Step 2: Tambahkan Dependencies

Edit `pubspec.yaml`:

```yaml
dependencies:
  flutter:
    sdk: flutter
  
  # HTTP Client
  dio: ^5.4.0
  
  # Firebase
  firebase_core: ^3.1.0
  firebase_messaging: ^15.0.0
  
  # Local Notifications
  flutter_local_notifications: ^18.0.0
  
  # State Management (optional)
  provider: ^6.1.1
  
  # Storage
  shared_preferences: ^2.2.2
```

Jalankan:
```bash
flutter pub get
```

## Step 3: Setup Firebase di Android

### 3.1 Tambahkan `google-services.json`

Letakkan file `google-services.json` yang sudah didownload dari Firebase Console ke:
```
android/app/google-services.json
```

### 3.2 Edit `android/build.gradle`

```gradle
buildscript {
    dependencies {
        // Add this line
        classpath 'com.google.gms:google-services:4.4.0'
    }
}
```

### 3.3 Edit `android/app/build.gradle`

```gradle
plugins {
    id 'com.android.application'
    id 'kotlin-android'
    // Add this line
    id 'com.google.gms.google-services'
}

android {
    defaultConfig {
        // Set minimum SDK to 21 or higher
        minSdk = 21
    }
}
```

### 3.4 Edit `android/app/src/main/AndroidManifest.xml`

Tambahkan permissions dan notification channel:

```xml
<manifest xmlns:android="http://schemas.android.com/apk/res/android">
    
    <!-- Permissions -->
    <uses-permission android:name="android.permission.INTERNET"/>
    <uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
    <uses-permission android:name="android.permission.VIBRATE"/>
    <uses-permission android:name="android.permission.POST_NOTIFICATIONS"/>
    
    <application
        android:label="Paw Time"
        android:name="${applicationName}"
        android:icon="@mipmap/ic_launcher">
        
        <!-- FCM Default Channel -->
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_channel_id"
            android:value="paw_time_reminders" />
        
        <!-- Default Notification Icon -->
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_icon"
            android:resource="@drawable/ic_notification" />
        
        <!-- Default Notification Color -->
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_color"
            android:resource="@color/notification_color" />
            
        <activity
            android:name=".MainActivity"
            ...
        </activity>
    </application>
</manifest>
```

### 3.5 Buat Notification Icon

Buat file `android/app/src/main/res/drawable/ic_notification.xml`:

```xml
<vector xmlns:android="http://schemas.android.com/apk/res/android"
    android:width="24dp"
    android:height="24dp"
    android:viewportWidth="24"
    android:viewportHeight="24">
    <path
        android:fillColor="#FFFFFF"
        android:pathData="M12,2C6.48,2 2,6.48 2,12s4.48,10 10,10 10,-4.48 10,-10S17.52,2 12,2zM12,20c-4.41,0 -8,-3.59 -8,-8s3.59,-8 8,-8 8,3.59 8,8 -3.59,8 -8,8z"/>
</vector>
```

Buat file `android/app/src/main/res/values/colors.xml`:

```xml
<?xml version="1.0" encoding="utf-8"?>
<resources>
    <color name="notification_color">#68C4CF</color>
</resources>
```

## Step 4: Kode Flutter

### 4.1 `lib/main.dart`

```dart
import 'package:flutter/material.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'services/notification_service.dart';
import 'services/api_service.dart';

// Handler untuk background messages
@pragma('vm:entry-point')
Future<void> _firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  await Firebase.initializeApp();
  print('Background message: ${message.messageId}');
}

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  // Initialize Firebase
  await Firebase.initializeApp();
  
  // Setup background message handler
  FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);
  
  // Initialize notification service
  await NotificationService.instance.initialize();
  
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Paw Time',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: const Color(0xFF68C4CF)),
        useMaterial3: true,
      ),
      home: const HomePage(),
    );
  }
}

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  String? _fcmToken;
  
  @override
  void initState() {
    super.initState();
    _setupFCM();
  }
  
  Future<void> _setupFCM() async {
    // Get FCM token
    final token = await NotificationService.instance.getToken();
    setState(() => _fcmToken = token);
    
    if (token != null) {
      // Register token to backend (after user is logged in)
      // await ApiService.registerFcmToken(token);
      print('FCM Token: $token');
    }
    
    // Listen for token refresh
    NotificationService.instance.onTokenRefresh((newToken) {
      setState(() => _fcmToken = newToken);
      // Update token on backend
      // ApiService.registerFcmToken(newToken);
    });
    
    // Listen for foreground messages
    NotificationService.instance.onForegroundMessage((message) {
      print('Foreground message: ${message.notification?.title}');
      // Show local notification
      NotificationService.instance.showLocalNotification(
        title: message.notification?.title ?? 'Paw Time',
        body: message.notification?.body ?? '',
        payload: message.data.toString(),
      );
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Paw Time'),
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.pets, size: 80, color: Color(0xFF68C4CF)),
            const SizedBox(height: 20),
            const Text(
              'Welcome to Paw Time!',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            Text(
              _fcmToken != null 
                ? 'FCM Token ready ✓'
                : 'Getting FCM token...',
              style: TextStyle(
                color: _fcmToken != null ? Colors.green : Colors.grey,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
```

### 4.2 `lib/services/notification_service.dart`

```dart
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';

class NotificationService {
  NotificationService._();
  static final NotificationService instance = NotificationService._();
  
  final FirebaseMessaging _messaging = FirebaseMessaging.instance;
  final FlutterLocalNotificationsPlugin _localNotifications = 
      FlutterLocalNotificationsPlugin();
  
  bool _isInitialized = false;
  
  Future<void> initialize() async {
    if (_isInitialized) return;
    
    // Request permission
    await _requestPermission();
    
    // Initialize local notifications
    await _initializeLocalNotifications();
    
    // Create notification channel for Android
    await _createNotificationChannel();
    
    _isInitialized = true;
  }
  
  Future<void> _requestPermission() async {
    final settings = await _messaging.requestPermission(
      alert: true,
      announcement: false,
      badge: true,
      carPlay: false,
      criticalAlert: false,
      provisional: false,
      sound: true,
    );
    
    print('Permission status: ${settings.authorizationStatus}');
  }
  
  Future<void> _initializeLocalNotifications() async {
    const androidSettings = AndroidInitializationSettings('@drawable/ic_notification');
    const iosSettings = DarwinInitializationSettings(
      requestAlertPermission: true,
      requestBadgePermission: true,
      requestSoundPermission: true,
    );
    
    const initSettings = InitializationSettings(
      android: androidSettings,
      iOS: iosSettings,
    );
    
    await _localNotifications.initialize(
      initSettings,
      onDidReceiveNotificationResponse: _onNotificationTap,
    );
  }
  
  Future<void> _createNotificationChannel() async {
    const channel = AndroidNotificationChannel(
      'paw_time_reminders',
      'Paw Time Reminders',
      description: 'Notifications for pet care reminders',
      importance: Importance.high,
      playSound: true,
      enableVibration: true,
    );
    
    await _localNotifications
        .resolvePlatformSpecificImplementation<AndroidFlutterLocalNotificationsPlugin>()
        ?.createNotificationChannel(channel);
  }
  
  void _onNotificationTap(NotificationResponse response) {
    print('Notification tapped: ${response.payload}');
    // Handle notification tap - navigate to specific screen
  }
  
  Future<String?> getToken() async {
    return await _messaging.getToken();
  }
  
  void onTokenRefresh(Function(String) callback) {
    _messaging.onTokenRefresh.listen(callback);
  }
  
  void onForegroundMessage(Function(RemoteMessage) callback) {
    FirebaseMessaging.onMessage.listen(callback);
  }
  
  void onMessageOpenedApp(Function(RemoteMessage) callback) {
    FirebaseMessaging.onMessageOpenedApp.listen(callback);
  }
  
  Future<RemoteMessage?> getInitialMessage() async {
    return await _messaging.getInitialMessage();
  }
  
  Future<void> showLocalNotification({
    required String title,
    required String body,
    String? payload,
  }) async {
    const androidDetails = AndroidNotificationDetails(
      'paw_time_reminders',
      'Paw Time Reminders',
      channelDescription: 'Notifications for pet care reminders',
      importance: Importance.high,
      priority: Priority.high,
      playSound: true,
      enableVibration: true,
      icon: '@drawable/ic_notification',
      color: Color(0xFF68C4CF),
    );
    
    const iosDetails = DarwinNotificationDetails(
      presentAlert: true,
      presentBadge: true,
      presentSound: true,
    );
    
    const details = NotificationDetails(
      android: androidDetails,
      iOS: iosDetails,
    );
    
    await _localNotifications.show(
      DateTime.now().millisecondsSinceEpoch ~/ 1000,
      title,
      body,
      details,
      payload: payload,
    );
  }
}
```

### 4.3 `lib/services/api_service.dart`

```dart
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static const String baseUrl = 'http://YOUR_SERVER_IP:8000/api';
  
  static final Dio _dio = Dio(BaseOptions(
    baseUrl: baseUrl,
    connectTimeout: const Duration(seconds: 30),
    receiveTimeout: const Duration(seconds: 30),
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    },
  ));
  
  static Future<void> setAuthToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
    _dio.options.headers['Authorization'] = 'Bearer $token';
  }
  
  static Future<void> loadAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');
    if (token != null) {
      _dio.options.headers['Authorization'] = 'Bearer $token';
    }
  }
  
  // ==========================================
  // AUTH
  // ==========================================
  
  static Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await _dio.post('/auth/login', data: {
      'email': email,
      'password': password,
    });
    
    if (response.data['token'] != null) {
      await setAuthToken(response.data['token']);
    }
    
    return response.data;
  }
  
  static Future<void> logout() async {
    await _dio.post('/auth/logout');
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
    _dio.options.headers.remove('Authorization');
  }
  
  // ==========================================
  // NOTIFICATIONS
  // ==========================================
  
  static Future<Map<String, dynamic>> registerFcmToken(String token) async {
    final response = await _dio.post('/notifications/register', data: {
      'token': token,
      'device_type': 'android',
      'device_name': 'Flutter App',
    });
    return response.data;
  }
  
  static Future<Map<String, dynamic>> removeFcmToken(String token) async {
    final response = await _dio.post('/notifications/remove', data: {
      'token': token,
    });
    return response.data;
  }
  
  static Future<Map<String, dynamic>> sendTestNotification() async {
    final response = await _dio.post('/notifications/test');
    return response.data;
  }
  
  // ==========================================
  // PETS
  // ==========================================
  
  static Future<List<dynamic>> getPets() async {
    final response = await _dio.get('/pets');
    return response.data['data'];
  }
  
  // ==========================================
  // REMINDERS
  // ==========================================
  
  static Future<List<dynamic>> getReminders({String? petId, String? status}) async {
    final response = await _dio.get('/reminders', queryParameters: {
      if (petId != null) 'pet_id': petId,
      if (status != null) 'status': status,
    });
    return response.data['data'];
  }
  
  static Future<Map<String, dynamic>> createReminder(Map<String, dynamic> data) async {
    final response = await _dio.post('/reminders', data: data);
    return response.data;
  }
}
```

## Step 5: Testing

### 5.1 Test dari Flutter

Setelah login, panggil:

```dart
// Register FCM token
final token = await NotificationService.instance.getToken();
if (token != null) {
  await ApiService.registerFcmToken(token);
}

// Send test notification
await ApiService.sendTestNotification();
```

### 5.2 Test dari Laravel

```bash
php artisan reminders:send-notifications
```

## Step 6: Production Deployment

### 6.1 Laravel Server

1. Setup cron job untuk scheduler:
```cron
* * * * * cd /path/to/paw_time && php artisan schedule:run >> /dev/null 2>&1
```

2. Atau untuk Windows Task Scheduler:
```batch
php C:\xampp\htdocs\paw_time\artisan schedule:run
```

### 6.2 Environment Variables

Tambahkan ke `.env`:
```env
FIREBASE_PROJECT_ID=your-project-id
```

Letakkan `firebase-credentials.json` di `storage/app/firebase-credentials.json`

## Troubleshooting

### Token tidak terkirim
- Pastikan Firebase sudah di-setup dengan benar
- Check apakah `google-services.json` sudah ada
- Pastikan permission sudah di-grant

### Notifikasi tidak muncul
- Check notification channel di Android settings
- Pastikan app tidak di-kill oleh battery optimization
- Test dengan `php artisan reminders:send-notifications`

### Error "Firebase credentials not found"
- Pastikan file `firebase-credentials.json` ada di `storage/app/`
- Check permission file tersebut
