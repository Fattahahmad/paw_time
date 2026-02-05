<?php
/**
 * Create or Update Admin User
 *
 * Usage: php create_admin.php
 *
 * This script will:
 * - Check if admin@pawtime.com exists
 * - If exists: Update role to 'admin' and reset password to 'admin123'
 * - If not exists: Create new admin user
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "===========================================\n";
echo "   Paw Time - Create/Update Admin User    \n";
echo "===========================================\n\n";

$email = 'admin@pawtime.com';
$password = 'admin123';
$name = 'Admin Paw Time';

$admin = User::where('email', $email)->first();

if ($admin) {
    // Update existing user
    $admin->update([
        'role' => 'admin',
        'password' => Hash::make($password),
    ]);

    echo "✅ Admin user updated successfully!\n\n";
    echo "   User ID:  {$admin->id}\n";
    echo "   Name:     {$admin->name}\n";
    echo "   Email:    {$admin->email}\n";
    echo "   Role:     {$admin->role}\n";
    echo "   Password: [RESET TO DEFAULT]\n\n";
} else {
    // Create new admin
    $admin = User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'role' => 'admin',
    ]);

    echo "✅ New admin user created successfully!\n\n";
    echo "   User ID:  {$admin->id}\n";
    echo "   Name:     {$admin->name}\n";
    echo "   Email:    {$admin->email}\n";
    echo "   Role:     {$admin->role}\n\n";
}

echo "===========================================\n";
echo "   LOGIN CREDENTIALS                      \n";
echo "===========================================\n";
echo "   Email:    admin@pawtime.com\n";
echo "   Password: admin123\n";
echo "===========================================\n\n";

echo "⚠️  IMPORTANT FOR PRODUCTION:\n";
echo "   1. Change the password immediately after first login\n";
echo "   2. Delete this script after use: rm create_admin.php\n";
echo "   3. Keep credentials secure\n\n";

echo "✨ Done!\n";
