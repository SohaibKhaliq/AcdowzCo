<?php

namespace Botble\Ecommerce\Http\Controllers\Admin;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Tables\ResellerDeletionRequestTable;
use Botble\Ecommerce\Tables\ResellerManagementTable;
use Illuminate\Http\Request;

class ResellerManagementController extends BaseController
{
    public function index(ResellerManagementTable $table)
    {
        $this->pageTitle(__('Reseller Management'));

        if (request()->ajax()) {
            return $table->ajax();
        }

        return $table->renderTable();
    }

    public function deletionRequests(ResellerDeletionRequestTable $table)
    {
        $this->pageTitle(__('Reseller Deletion Requests'));

        if (request()->ajax()) {
            return $table->ajax();
        }

        return $table->renderTable();
    }

    public function processDeletion(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $customer = Customer::findOrFail($id);

        if (!$customer->reseller_deletion_requested_at) {
            return $response
                ->setError()
                ->setMessage(__('This customer has not requested deletion.'));
        }

        // Process the deletion - deactivate reseller status
        $customer->is_reseller_active = false;
        $customer->reseller_deletion_requested_at = null;
        $customer->reseller_deleted_at = now();
        $customer->reseller_deleted_by = auth()->id();
        $customer->save();

        // Optionally send notification email to customer
        try {
            $mailer = \Botble\Base\Facades\EmailHandler::module('ecommerce');
            $data = [
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'deleted_at' => $customer->reseller_deleted_at->format('Y-m-d H:i:s'),
            ];
            $mailer->setVariableValues($data);
            $mailer->sendUsingTemplate('reseller-deletion-processed', $customer->email);
        } catch (\Exception $e) {
            // Log but don't fail
        }

        return $response
            ->setMessage(__('Reseller account has been deactivated successfully.'));
    }

    public function rejectDeletion(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $customer = Customer::findOrFail($id);

        if (!$customer->reseller_deletion_requested_at) {
            return $response
                ->setError()
                ->setMessage(__('This customer has not requested deletion.'));
        }

        // Reject the deletion request
        $customer->reseller_deletion_requested_at = null;
        $customer->save();

        // Optionally send notification email to customer
        try {
            $mailer = \Botble\Base\Facades\EmailHandler::module('ecommerce');
            $data = [
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'rejection_reason' => $request->input('reason', __('Your deletion request has been reviewed and rejected.')),
            ];
            $mailer->setVariableValues($data);
            $mailer->sendUsingTemplate('reseller-deletion-rejected', $customer->email);
        } catch (\Exception $e) {
            // Log but don't fail
        }

        return $response
            ->setMessage(__('Deletion request has been rejected.'));
    }

    public function disableReseller(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $customer = Customer::findOrFail($id);

        if (!$customer->is_reseller_active) {
            return $response
                ->setError()
                ->setMessage(__('This customer is not an active reseller.'));
        }

        $customer->is_reseller_active = false;
        $customer->reseller_disabled_at = now();
        $customer->reseller_disabled_by = auth()->id();
        $customer->reseller_disable_reason = $request->input('reason');
        $customer->save();

        // Send notification email to customer
        try {
            $mailer = \Botble\Base\Facades\EmailHandler::module('ecommerce');
            $data = [
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'reason' => $customer->reseller_disable_reason,
                'disabled_at' => $customer->reseller_disabled_at->format('Y-m-d H:i:s'),
            ];
            $mailer->setVariableValues($data);
            $mailer->sendUsingTemplate('reseller-disabled', $customer->email);
        } catch (\Exception $e) {
            // Log but don't fail
        }

        return $response
            ->setMessage(__('Reseller account has been disabled successfully.'));
    }

    public function enableReseller(int|string $id, BaseHttpResponse $response)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->is_reseller_active) {
            return $response
                ->setError()
                ->setMessage(__('This customer is already an active reseller.'));
        }

        $customer->is_reseller_active = true;
        $customer->reseller_disabled_at = null;
        $customer->reseller_disabled_by = null;
        $customer->reseller_disable_reason = null;
        $customer->save();

        // Send notification email to customer
        try {
            $mailer = \Botble\Base\Facades\EmailHandler::module('ecommerce');
            $data = [
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'enabled_at' => now()->format('Y-m-d H:i:s'),
            ];
            $mailer->setVariableValues($data);
            $mailer->sendUsingTemplate('reseller-enabled', $customer->email);
        } catch (\Exception $e) {
            // Log but don't fail
        }

        return $response
            ->setMessage(__('Reseller account has been enabled successfully.'));
    }
}
