<?php

namespace Botble\Marketplace\Console;

use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Models\VendorSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireSubscriptionsCommand extends Command
{
    protected $signature = 'marketplace:expire-subscriptions';

    protected $description = 'Expire vendor subscriptions and remove verified badges';

    public function handle(): int
    {
        $this->info('Checking for expired subscriptions...');

        $expiredSubscriptions = VendorSubscription::query()
            ->where('status', 'active')
            ->where('expires_at', '<', Carbon::now())
            ->with(['store', 'plan'])
            ->get();

        if ($expiredSubscriptions->isEmpty()) {
            $this->info('No expired subscriptions found.');
            return self::SUCCESS;
        }

        $count = 0;

        foreach ($expiredSubscriptions as $subscription) {
            $subscription->markAsExpired();
            
            // Remove verified badge if plan had verification eligibility
            if ($subscription->plan && $subscription->plan->verified_eligible) {
                $store = $subscription->store;
                if ($store && $store->is_verified) {
                    $store->update([
                        'is_verified' => false,
                        'verified_at' => null,
                        'verification_note' => 'Verification removed due to subscription expiry',
                    ]);
                    
                    $this->warn("Removed verification for store: {$store->name}");
                }
            }
            
            $count++;
        }

        $this->info("Expired {$count} subscription(s) successfully.");

        return self::SUCCESS;
    }
}
