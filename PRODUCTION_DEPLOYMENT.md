# Production Deployment Guide

## 📋 Checklist Deployment

### 1️⃣ BACKUP DULU (PENTING!)
```bash
# Backup database
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# Backup files
tar -czf backup_files_$(date +%Y%m%d_%H%M%S).tar.gz /path/to/project
```

### 2️⃣ GIT PUSH & PULL
```bash
# Di local
git add .
git commit -m "Major update: appointments flow, reminders, image fixes"
git push origin main

# Di server production
cd /path/to/project
git pull origin main
```

### 3️⃣ COMPOSER & NPM
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 4️⃣ MIGRATION DATABASE
```bash
# Cek migration yang belum jalan
php artisan migrate:status

# Jalankan migration
php artisan migrate

# Migration yang akan jalan:
# - 2026_02_03_000001_change_reminders_pet_id_to_user_id.php (TRUNCATE reminders!)
# - 2026_02_05_151237_add_appointment_id_to_health_checks_table.php
```

### 5️⃣ MIGRATE DATA HEALTH CHECKS KE APPOINTMENTS
```bash
# Upload file migrate_health_to_appointments.php ke server
# Jalankan:
php migrate_health_to_appointments.php
```

### 6️⃣ CREATE ADMIN ACCOUNT
```bash
# Upload file create_admin.php ke server
# Jalankan:
php create_admin.php

# Login dengan:
# Email: admin@pawtime.com
# Password: admin123
```

### 7️⃣ CLEAR CACHE
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize
```

### 8️⃣ STORAGE & PERMISSIONS
```bash
# Pastikan storage link
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## ⚠️ BREAKING CHANGES - FLUTTER APP HARUS UPDATE!

### 1. REMINDERS API
**❌ TIDAK BISA DIPAKAI LAGI:**
```json
GET /api/reminders?pet_id=1
```

**✅ GANTI JADI:**
```json
GET /api/reminders
// Response tidak ada pet relationship
{
  "id": 1,
  "user_id": 1,
  "title": "Reminder Title",
  "description": "...",
  "remind_date": "2026-02-10 10:00:00"
}
```

### 2. IMAGE URLs
**❌ RESPONSE LAMA:**
```json
{
  "image_url": "http://domain.com/storage/pets/xxx.jpg"
}
```

**✅ RESPONSE BARU:**
```json
{
  "image_url": "pets/xxx.jpg"
}
```

**Flutter harus handle:**
```dart
// Di Flutter, tambahin base URL:
String imageUrl = "${baseUrl}/storage/${pet.imageUrl}";
// Atau
NetworkImage("${baseUrl}/storage/${pet.imageUrl}")
```

### 3. BOOKING APPOINTMENT
**❌ JANGAN PAKAI INI LAGI:**
```
POST /api/health-checks
{
  "pet_id": 1,
  "complaint": "...",
  "check_date": "..."
}
```

**✅ PAKAI INI:**
```
POST /api/appointments
{
  "pet_id": 1,
  "notes": "Complaint atau keluhan",
  "appointment_date": "2026-02-10 10:00:00" // optional
}

Response:
{
  "success": true,
  "appointment": {
    "id": 1,
    "user_id": 1,
    "pet_id": 1,
    "appointment_date": "2026-02-10 10:00:00",
    "status": "pending",
    "notes": "..."
  }
}
```

**GET Appointments:**
```
GET /api/appointments
GET /api/appointments?status=pending
GET /api/appointments?upcoming=true
GET /api/appointments/{id}
```

---

## 🔍 TESTING SETELAH DEPLOYMENT

### Test Admin Panel
1. ✅ Login admin (admin@pawtime.com / admin123)
2. ✅ Cek halaman Appointments - harus ada data
3. ✅ Buka detail appointment → Complete appointment
4. ✅ Cek Health Checks Management - harus ada appointment_id
5. ✅ Logout dari dropdown profile

### Test Web User
1. ✅ Login user
2. ✅ Book appointment dari halaman Health
3. ✅ Cek data masuk ke Appointments (bukan Health Checks)
4. ✅ Cek reminders (user-level, tidak ada filter pet)

### Test API (Postman/Thunder Client)
1. ✅ POST /api/appointments (buat appointment baru)
2. ✅ GET /api/appointments (list appointments)
3. ✅ GET /api/reminders (list user reminders)
4. ✅ GET /api/pets (cek image_url relative path)

---

## 📱 UPDATE FLUTTER APP

### Perubahan Wajib:

#### 1. Update Image Loader
```dart
// OLD
NetworkImage(pet.imageUrl)

// NEW
NetworkImage("${Config.baseUrl}/storage/${pet.imageUrl}")
```

#### 2. Update Booking Flow
```dart
// OLD - Jangan pakai
apiService.post('/health-checks', {...})

// NEW
apiService.post('/appointments', {
  'pet_id': petId,
  'notes': complaint,
  'appointment_date': selectedDate, // optional
})
```

#### 3. Update Reminders
```dart
// OLD - Jangan filter by pet
apiService.get('/reminders?pet_id=$petId')

// NEW - Get all user reminders
apiService.get('/reminders')
```

#### 4. Update Models
```dart
// Pet model
class Pet {
  String imageUrl; // Simpan relative path
  
  String get fullImageUrl => "${Config.baseUrl}/storage/$imageUrl";
}

// Reminder model - hapus petId
class Reminder {
  int userId; // Ganti dari petId
  // Remove: int petId
}
```

---

## 🚨 ROLLBACK PLAN (Kalau Ada Masalah)

```bash
# 1. Restore database
mysql -u username -p database_name < backup_YYYYMMDD_HHMMSS.sql

# 2. Restore files
tar -xzf backup_files_YYYYMMDD_HHMMSS.tar.gz -C /path/to/project

# 3. Clear cache
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

---

## 📞 Support Contacts

Setelah deployment, monitor:
- Laravel logs: `storage/logs/laravel.log`
- Nginx/Apache error logs
- Database slow query log

**Jangan deploy Jumat sore atau weekend!** 😅
