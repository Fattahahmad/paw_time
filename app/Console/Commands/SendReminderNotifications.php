<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendReminderNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send-notifications {--test : Send test notification}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notifications for due reminders';

    /**
     * Execute the console command.
     */
    public function handle(FirebaseService $firebase)
    {
        $this->info('Checking for due reminders...');

        // Get reminders that are due (within the last 5 minutes to handle any delays)
        $now = Carbon::now();
        $fiveMinutesAgo = $now->copy()->subMinutes(5);

        $dueReminders = Reminder::with('user')
            ->where('status', 'pending')
            ->whereBetween('remind_date', [$fiveMinutesAgo, $now])
            ->whereNull('notification_sent_at') // Only send once
            ->get();

        if ($dueReminders->isEmpty()) {
            $this->info('No due reminders found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$dueReminders->count()} due reminder(s).");

        $sent = 0;
        $failed = 0;

        foreach ($dueReminders as $reminder) {
            $user = $reminder->user;

            if (!$user) {
                $this->warn("Reminder #{$reminder->id}: No user found, skipping.");
                continue;
            }

            // Build notification content
            $title = $this->getNotificationTitle($reminder);
            $body = $this->getNotificationBody($reminder);
            $data = [
                'reminder_id' => (string) $reminder->id,
                'category' => $reminder->category,
                'type' => 'reminder',
            ];

            try {
                $result = $firebase->sendAndLog($user, $title, $body, $data, $reminder->id);

                // Mark reminder as notified
                $reminder->update(['notification_sent_at' => now()]);

                if ($result->status === 'sent') {
                    $sent++;
                    $this->info("✓ Sent notification for reminder #{$reminder->id} to {$user->name}");
                } else {
                    $failed++;
                    $this->warn("✗ Failed to send notification for reminder #{$reminder->id}: {$result->error_message}");
                }
            } catch (\Exception $e) {
                $failed++;
                $this->error("✗ Error sending notification for reminder #{$reminder->id}: {$e->getMessage()}");
                Log::error('Reminder notification error', [
                    'reminder_id' => $reminder->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Done! Sent: {$sent}, Failed: {$failed}");

        return Command::SUCCESS;
    }

    /**
     * Get notification title based on category.
     */
    private function getNotificationTitle(Reminder $reminder): string
    {
        $titles = [
            'feeding' => '🍖 Waktu Makan Hewan Peliharaan!',
            'grooming' => '✂️ Jadwal Grooming',
            'vaccination' => '💉 Waktu Vaksinasi',
            'medication' => '💊 Waktu Obat',
            'checkup' => '🏥 Waktu Check-up',
            'other' => '🔔 Pengingat',
        ];

        return $titles[$reminder->category] ?? $titles['other'];
    }

    /**
     * Get notification body.
     */
    private function getNotificationBody(Reminder $reminder): string
    {
        if ($reminder->description) {
            return $reminder->title . ' - ' . $reminder->description;
        }

        return $reminder->title;
    }
}
