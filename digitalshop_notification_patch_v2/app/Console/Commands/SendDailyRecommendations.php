<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendDailyRecommendations extends Command
{
    protected $signature = 'digitalshop:recommendations {--dry-run : Only show eligible buyer count without sending}';
    protected $description = 'Send up to 3 daily product recommendations to each buyer.';

    public function handle(NotificationService $service): int
    {
        if ($this->option('dry-run')) {
            $count = User::query()
                ->where('is_active', true)
                ->where('role', 'buyer')
                ->count();

            $this->info("Eligible buyer accounts: {$count}");
            $this->info('Each buyer is capped at 3 recommendation notifications per calendar day.');
            return self::SUCCESS;
        }

        $sent = $service->sendDailyRecommendations();
        $this->info("Sent {$sent} recommendation notification(s).");

        return self::SUCCESS;
    }
}
