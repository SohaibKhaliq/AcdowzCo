<?php

use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        try {
            $oldPrivacyPolicy = theme_option('ecommerce_term_and_privacy_policy_url');

            if ($oldPrivacyPolicy) {
                theme_option()
                    ->setOption('term_and_privacy_policy_url', $oldPrivacyPolicy)
                    ->setOption('ecommerce_term_and_privacy_policy_url', null)
                    ->saveOptions();
            }
        } catch (Throwable) {
            // do nothing
        }
    }
};
