# Image Access Configuration - Paw Time API

## Problem
Images returning 403 Forbidden on production/Cloudflare

## Root Cause
1. Inconsistent image URL format in API responses
2. Missing .htaccess in public/storage directory
3. Possible Cloudflare caching/security rules blocking direct file access

## Solution Implemented

### 1. Standardized Image URL Format

**Database Storage**: Store ONLY relative paths
```
pets/xxxxx.jpg
profiles/xxxxx.jpg
```

**API Response**: Return FULL URLs using `asset('storage/' . $path)`
```json
{
  "image_url": "https://your-domain.com/storage/pets/xxxxx.jpg"
}
```

### 2. Files Updated

#### Controllers:
- `app/Http/Controllers/Api/PetController.php`
  - Line 89-101: Store - save relative path only
  - Line 141-146: Update - save relative path, delete using relative path
  - Line 174-176: Delete - use relative path for deletion
  - Line 201: formatPet() - return `asset('storage/' . $image)`

- `app/Http/Controllers/Api/AuthController.php`
  - Line 216-223: uploadAvatar - save relative path only
  - Line 229: Response - return `asset('storage/' . $path)`
  - Line 251-254: removeAvatar - use relative path for deletion

- `app/Http/Controllers/Api/SearchController.php`
  - Line 51: Pet search results - return `asset('storage/' . $image)`

#### Views (already correct):
- `resources/views/components/cards/pet-card.blade.php` - uses `asset('storage/' . $image)`
- `app/Http/Controllers/User/ProfileController.php` - saves relative path
- `app/Http/Controllers/User/PetController.php` - saves relative path

### 3. Created .htaccess for Storage Directory

**File**: `public/storage/.htaccess`

Allows direct access to image files while preventing directory listing.

## Testing in Local Development

```bash
# 1. Verify storage link exists
php artisan storage:link

# 2. Clear cache
php artisan optimize

# 3. Test image URL
# Database stores: pets/xxxxx.jpg
# API returns: http://your-domain.com/storage/pets/xxxxx.jpg
```

## Production Deployment Checklist

### On Your Server:

1. **Pull latest code**
   ```bash
   git pull origin main
   ```

2. **Verify storage link**
   ```bash
   php artisan storage:link
   ```

3. **Set correct permissions**
   ```bash
   # Linux/Unix servers
   chmod -R 755 storage
   chmod -R 755 public/storage
   chown -R www-data:www-data storage
   chown -R www-data:www-data public/storage
   ```

4. **Clear all caches**
   ```bash
   php artisan optimize:clear
   php artisan optimize
   ```

### On Cloudflare:

1. **Purge Cache**
   - Go to Cloudflare Dashboard
   - Cache > Configuration
   - Click "Purge Everything"

2. **Security Rules**
   - Go to Security > WAF
   - Check if any rules block `/storage/*` paths
   - Add exception for `/storage/*` if needed

3. **Page Rules** (Optional but Recommended)
   - Create rule for: `your-domain.com/storage/*`
   - Settings:
     - Browser Cache TTL: 1 month
     - Cache Level: Standard
     - Security Level: Essentially Off (for images)

4. **Check DNS Settings**
   - Verify "Proxied" (orange cloud) is enabled
   - OR if direct access needed, use "DNS Only" (gray cloud)

## Verify Fix

### Test API Endpoints:

1. **Get Pet List**
   ```
   GET /api/pets
   Authorization: Bearer {token}
   
   Response should have:
   {
     "data": [{
       "image_url": "https://your-domain.com/storage/pets/xxxxx.jpg"
     }]
   }
   ```

2. **Get User Profile**
   ```
   GET /api/auth/user
   Authorization: Bearer {token}
   
   Response should have:
   {
     "profile_image": "https://your-domain.com/storage/profiles/xxxxx.jpg"
   }
   ```

3. **Direct Image Access**
   ```
   GET https://your-domain.com/storage/pets/xxxxx.jpg
   
   Should return: 200 OK with image data
   Should NOT return: 403 Forbidden
   ```

## Rollback Plan

If issues occur:
```bash
# Revert code changes
git revert HEAD

# Clear caches
php artisan optimize:clear
php artisan optimize
```

## Common Issues & Solutions

### Issue: Still getting 403 after deployment

**Solutions:**
1. Check web server (Apache/Nginx) has read permissions on storage directory
2. Verify .htaccess file is read by Apache (check AllowOverride)
3. Check Cloudflare firewall rules
4. Temporarily disable Cloudflare proxy to test if it's a Cloudflare issue

### Issue: Images load in web but not in mobile app

**Solution:**
- Check if mobile app accepts self-signed certificates (dev environment)
- Verify BASE_URL in mobile app matches APP_URL in Laravel .env
- Check CORS settings if different domain

### Issue: Old URLs still in database

**Migration script to fix:**
```php
// Run this in tinker or create a migration
DB::table('pets')
    ->whereNotNull('image_url')
    ->where('image_url', 'like', '/storage/%')
    ->update([
        'image_url' => DB::raw("REPLACE(image_url, '/storage/', '')")
    ]);

DB::table('users')
    ->whereNotNull('profile_image')
    ->where('profile_image', 'like', '/storage/%')
    ->update([
        'profile_image' => DB::raw("REPLACE(profile_image, '/storage/', '')")
    ]);
```

## Summary

✅ **Fixed**: Image URL generation now consistent across all API endpoints
✅ **Fixed**: Database stores only relative paths, API returns full URLs
✅ **Fixed**: Added .htaccess for storage directory
✅ **Ready**: Production deployment with Cloudflare configuration
