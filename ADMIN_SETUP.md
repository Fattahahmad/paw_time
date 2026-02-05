# Admin User Setup Guide

## Create Admin User in Production

### Method 1: Using create_admin.php Script

1. **Upload script to server:**
   ```bash
   # Script: create_admin.php already in project root
   ```

2. **Run the script:**
   ```bash
   cd /path/to/paw_time
   php create_admin.php
   ```

3. **Delete script after use:**
   ```bash
   rm create_admin.php
   ```

### Method 2: Using Artisan Tinker (Alternative)

```bash
php artisan tinker
```

Then run:
```php
$admin = App\Models\User::updateOrCreate(
    ['email' => 'admin@pawtime.com'],
    [
        'name' => 'Admin Paw Time',
        'password' => Hash::make('admin123'),
        'role' => 'admin'
    ]
);

echo "Admin created: " . $admin->email;
exit;
```

### Method 3: Using Database Seeder

```bash
php artisan db:seed
```

This will create:
- Admin: `admin@pawtime.com` / `admin123`
- Test users: `john@example.com` & `jane@example.com` / `password`

## Default Credentials

**Admin Account:**
- Email: `admin@pawtime.com`
- Password: `admin123`
- Role: `admin`

## Security Best Practices

⚠️ **IMPORTANT FOR PRODUCTION:**

1. **Change Password Immediately:**
   - Login with default credentials
   - Go to profile settings
   - Update password to a strong one

2. **Delete Setup Scripts:**
   ```bash
   rm create_admin.php
   ```

3. **Update via API:**
   ```
   PUT /api/auth/user
   Authorization: Bearer {admin_token}
   
   {
     "password": "NewStr0ngP@ssw0rd!"
   }
   ```

4. **Environment Variables:**
   - Never commit credentials to git
   - Use `.env` for sensitive data
   - Keep `.env` in `.gitignore`

## API Endpoints for Admin

### Login
```
POST /api/auth/login
Content-Type: application/json

{
  "email": "admin@pawtime.com",
  "password": "admin123"
}
```

### Get Profile
```
GET /api/auth/user
Authorization: Bearer {token}
```

### Update Profile
```
PUT /api/auth/user
Authorization: Bearer {token}

{
  "name": "New Name",
  "email": "new.email@example.com",
  "password": "new_password"
}
```

## Troubleshooting

### Admin User Not Found
```bash
# Check if user exists
php artisan tinker --execute="App\Models\User::where('email', 'admin@pawtime.com')->first()"

# Create manually if not exists
php create_admin.php
```

### Password Not Working
```bash
# Reset password
php artisan tinker --execute="\$u = App\Models\User::where('email', 'admin@pawtime.com')->first(); \$u->password = Hash::make('admin123'); \$u->save(); echo 'Password reset';"
```

### Check Admin Role
```bash
php artisan tinker --execute="App\Models\User::where('email', 'admin@pawtime.com')->value('role')"
```

## Production Deployment Checklist

- [ ] Run `php create_admin.php` or `php artisan db:seed`
- [ ] Test login with default credentials
- [ ] Change admin password immediately
- [ ] Delete `create_admin.php` script
- [ ] Verify admin role is set correctly
- [ ] Test admin endpoints in Postman
- [ ] Document actual admin email used (if different)
- [ ] Secure `.env` file permissions (chmod 600)

## Notes

- Default admin email: `admin@pawtime.com`
- Can be changed in `create_admin.php` or `DatabaseSeeder.php`
- Password is hashed using bcrypt
- Role field must be 'admin' for admin access
