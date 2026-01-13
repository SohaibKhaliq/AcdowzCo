<?php

namespace Botble\Ecommerce\Commands;

use Botble\Ecommerce\Events\AbandonedCartReminderEvent;
use Botble\Ecommerce\Services\AbandonedCartService;
use Illuminate\Console\Command;

class CheckAbandonedCartsCommand extends Command
{
    protected $signature = 'cms:check-abandoned-carts 
                            {--hours=1 : Hours after which a cart is considered abandoned}
                            {--cleanup : Clean up old abandoned carts}
                            {--cleanup-days=30 : Days to keep abandoned carts}';

    protected $description = 'Check for abandoned carts and send reminders';

    public function handle(AbandonedCartService $service): int
    {
        $this->info('Checking for abandoned carts...');

        if ($this->option('cleanup')) {
            $deleted = $service->cleanupOldAbandonedCarts($this->option('cleanup-days'));
            $this->info("Cleaned up {$deleted} old abandoned carts.");

            return self::SUCCESS;
        }

        $hours = (int) $this->option('hours');
        $abandonedCarts = $service->identifyAbandonedCarts($hours);

        if ($abandonedCarts->isEmpty()) {
            $this->info('No abandoned carts found.');

            return self::SUCCESS;
        }

        $this->info("Found {$abandonedCarts->count()} abandoned carts.");

        foreach ($abandonedCarts as $abandonedCart) {
            if (! $abandonedCart->email) {
                $this->warn("Skipping cart {$abandonedCart->id} - no email address.");

                continue;
            }

            try {
                event(new AbandonedCartReminderEvent($abandonedCart));
                $abandonedCart->incrementRemindersSent();
                $this->info("Sent reminder for cart {$abandonedCart->id} to {$abandonedCart->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send reminder for cart {$abandonedCart->id}: {$e->getMessage()}");
            }
        }

        $this->info('Abandoned cart check completed.');

        return self::SUCCESS;
    }
}
