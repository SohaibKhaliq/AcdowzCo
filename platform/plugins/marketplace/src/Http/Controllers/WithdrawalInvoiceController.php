<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Marketplace\Enums\WithdrawalStatusEnum;
use Botble\Marketplace\Models\Withdrawal;
use Botble\Marketplace\Services\GeneratePayoutInvoiceService;
use Illuminate\Http\Request;

class WithdrawalInvoiceController extends BaseController
{
    public function __invoke(
        Withdrawal $withdrawal,
        Request $request,
        GeneratePayoutInvoiceService $generateWithdrawalInvoiceService
    ) {
        abort_if($withdrawal->status != WithdrawalStatusEnum::COMPLETED, 404);

        $generateWithdrawalInvoiceService->withdrawal($withdrawal->loadMissing('customer'));

        if ($request->input('type') === 'print') {
            return $generateWithdrawalInvoiceService->stream();
        }

        return $generateWithdrawalInvoiceService->download();
    }
}
