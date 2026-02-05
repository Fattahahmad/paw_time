<?php

namespace Database\Seeders;

use App\Models\HealthCheck;
use App\Models\Pet;
use App\Models\PetGrowth;
use App\Models\Reminder;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@pawtime.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create Regular Users
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create Pets for User 1
        $bella = Pet::create([
            'user_id' => $user1->id,
            'pet_name' => 'Bella',
            'species' => 'Cat',
            'breed' => 'American Curl',
            'gender' => 'female',
            'birth_date' => now()->subYears(2)->subMonths(3),
            'color' => 'Orange Tabby',
            'description' => 'A playful and curious cat who loves to explore.',
        ]);

        $max = Pet::create([
            'user_id' => $user1->id,
            'pet_name' => 'Max',
            'species' => 'Dog',
            'breed' => 'Golden Retriever',
            'gender' => 'male',
            'birth_date' => now()->subYears(3)->subMonths(6),
            'color' => 'Golden',
            'description' => 'Friendly and loyal companion, loves to play fetch.',
        ]);

        // Create Pets for User 2
        $mochi = Pet::create([
            'user_id' => $user2->id,
            'pet_name' => 'Mochi',
            'species' => 'Cat',
            'breed' => 'Persian',
            'gender' => 'male',
            'birth_date' => now()->subYear()->subMonths(2),
            'color' => 'White',
            'description' => 'Calm and gentle, enjoys lounging in sunny spots.',
        ]);

        $charlie = Pet::create([
            'user_id' => $user2->id,
            'pet_name' => 'Charlie',
            'species' => 'Dog',
            'breed' => 'Beagle',
            'gender' => 'male',
            'birth_date' => now()->subYears(4),
            'color' => 'Tricolor',
            'description' => 'Energetic and curious, loves outdoor adventures.',
        ]);

        // Create Growth Records
        $this->createGrowthRecords($bella, [
            ['weight' => 3.2, 'height' => 22, 'days_ago' => 90],
            ['weight' => 3.5, 'height' => 23, 'days_ago' => 60],
            ['weight' => 3.8, 'height' => 24, 'days_ago' => 30],
            ['weight' => 4.0, 'height' => 25, 'days_ago' => 0],
        ]);

        $this->createGrowthRecords($max, [
            ['weight' => 28, 'height' => 55, 'days_ago' => 90],
            ['weight' => 29, 'height' => 56, 'days_ago' => 60],
            ['weight' => 30, 'height' => 57, 'days_ago' => 30],
            ['weight' => 30.5, 'height' => 57, 'days_ago' => 0],
        ]);

        $this->createGrowthRecords($mochi, [
            ['weight' => 3.0, 'height' => 20, 'days_ago' => 60],
            ['weight' => 3.3, 'height' => 21, 'days_ago' => 30],
            ['weight' => 3.5, 'height' => 22, 'days_ago' => 0],
        ]);

        // Create Reminders
        Reminder::create([
            'pet_id' => $bella->id,
            'title' => 'Morning Feeding',
            'description' => '200g dry food + fresh water',
            'remind_date' => now()->addDays(1)->setTime(8, 0),
            'category' => 'feeding',
            'repeat_type' => 'daily',
            'status' => 'pending',
        ]);

        Reminder::create([
            'pet_id' => $bella->id,
            'title' => 'Monthly Grooming',
            'description' => 'Bath and nail trim',
            'remind_date' => now()->addDays(7)->setTime(14, 0),
            'category' => 'grooming',
            'repeat_type' => 'monthly',
            'status' => 'pending',
        ]);

        Reminder::create([
            'pet_id' => $max->id,
            'title' => 'Annual Vaccination',
            'description' => 'Rabies and DHPP vaccines',
            'remind_date' => now()->addDays(14)->setTime(10, 0),
            'category' => 'vaccination',
            'repeat_type' => 'yearly',
            'status' => 'pending',
        ]);

        Reminder::create([
            'pet_id' => $max->id,
            'title' => 'Evening Walk',
            'description' => '30 minute walk at the park',
            'remind_date' => now()->addDays(1)->setTime(17, 0),
            'category' => 'other',
            'repeat_type' => 'daily',
            'status' => 'pending',
        ]);

        Reminder::create([
            'pet_id' => $mochi->id,
            'title' => 'Medication - Vitamins',
            'description' => 'Daily vitamin supplement',
            'remind_date' => now()->subDays(1)->setTime(9, 0),
            'category' => 'medication',
            'repeat_type' => 'daily',
            'status' => 'done',
        ]);

        Reminder::create([
            'pet_id' => $charlie->id,
            'title' => 'Vet Checkup',
            'description' => 'Regular health checkup',
            'remind_date' => now()->addDays(21)->setTime(11, 0),
            'category' => 'checkup',
            'repeat_type' => 'none',
            'status' => 'pending',
        ]);

        // Create Health Checks
        HealthCheck::create([
            'pet_id' => $bella->id,
            'check_date' => now()->subDays(30),
            'complaint' => 'Reduced appetite and lethargy',
            'diagnosis' => 'Mild digestive upset',
            'treatment' => 'Prescribed digestive enzymes and bland diet for 3 days',
            'prescription' => 'Digestive Enzymes - 1 tablet twice daily with meals for 7 days',
            'notes' => 'Pet is recovering well. Follow up if symptoms persist.',
            'next_visit_date' => null,
        ]);

        HealthCheck::create([
            'pet_id' => $max->id,
            'check_date' => now()->subDays(60),
            'complaint' => 'Annual checkup - no specific complaints',
            'diagnosis' => 'Healthy, all vitals normal',
            'treatment' => 'Administered annual vaccines',
            'prescription' => null,
            'notes' => 'Weight is slightly above optimal. Recommend increased exercise.',
            'next_visit_date' => now()->addDays(305),
        ]);

        HealthCheck::create([
            'pet_id' => $mochi->id,
            'check_date' => now()->subDays(14),
            'complaint' => 'Excessive scratching and hair loss',
            'diagnosis' => 'Skin allergy, likely food-related',
            'treatment' => 'Prescribed antihistamines and hypoallergenic diet',
            'prescription' => "Antihistamine - 5mg once daily for 14 days\nHypoallergenic food - switch completely",
            'notes' => 'Monitor for improvement. May need allergy testing if no improvement.',
            'next_visit_date' => now()->addDays(7),
        ]);

        HealthCheck::create([
            'pet_id' => $charlie->id,
            'check_date' => now()->subDays(90),
            'complaint' => 'Limping on left front leg',
            'diagnosis' => 'Minor sprain, no fracture detected',
            'treatment' => 'Rest and anti-inflammatory medication',
            'prescription' => 'Meloxicam 1.5mg - once daily with food for 5 days',
            'notes' => 'X-ray showed no bone damage. Restrict activity for 2 weeks.',
            'next_visit_date' => now()->subDays(75),
        ]);

        // Create Appointments
        Appointment::create([
            'user_id' => $user1->id,
            'pet_id' => $bella->id,
            'appointment_date' => now()->addDays(3)->setTime(10, 0),
            'status' => 'pending',
            'notes' => 'Regular checkup and vaccination',
        ]);

        Appointment::create([
            'user_id' => $user1->id,
            'pet_id' => $max->id,
            'appointment_date' => now()->addDays(5)->setTime(14, 30),
            'status' => 'confirmed',
            'notes' => 'Follow-up visit for vaccination',
        ]);

        Appointment::create([
            'user_id' => $user2->id,
            'pet_id' => $mochi->id,
            'appointment_date' => now()->addDays(7)->setTime(11, 0),
            'status' => 'pending',
            'notes' => 'Skin allergy follow-up',
        ]);

        Appointment::create([
            'user_id' => $user2->id,
            'pet_id' => $charlie->id,
            'appointment_date' => now()->subDays(5)->setTime(9, 30),
            'status' => 'completed',
            'notes' => 'Annual health check',
            'veterinarian_notes' => 'All vitals normal. Recommended diet adjustment.',
        ]);

        Appointment::create([
            'user_id' => $user1->id,
            'pet_id' => $bella->id,
            'appointment_date' => now()->subDays(2)->setTime(15, 0),
            'status' => 'cancelled',
            'notes' => 'Grooming session',
            'cancellation_reason' => 'Owner not available',
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->table(['Role', 'Email', 'Password'], [
            ['Admin', 'admin@pawtime.com', 'password'],
            ['User', 'john@example.com', 'password'],
            ['User', 'jane@example.com', 'password'],
        ]);
    }

    /**
     * Create growth records for a pet.
     */
    private function createGrowthRecords(Pet $pet, array $records): void
    {
        foreach ($records as $record) {
            PetGrowth::create([
                'pet_id' => $pet->id,
                'weight' => $record['weight'],
                'height' => $record['height'] ?? null,
                'record_date' => now()->subDays($record['days_ago']),
                'notes' => null,
            ]);
        }
    }
}
