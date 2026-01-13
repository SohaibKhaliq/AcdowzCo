<x-core::alert :title="trans('plugins/stripe-connect::stripe-connect.withdrawal.payout_info', ['stripe_account_id' => auth('customer')->user()->stripe_account_id])" />
