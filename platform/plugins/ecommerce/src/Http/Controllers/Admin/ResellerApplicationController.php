<?php

namespace Botble\Ecommerce\Http\Controllers\Admin;

use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Enums\CustomerStatusEnum;
use Botble\Ecommerce\Enums\ResellerApplicationStatusEnum;
use Botble\Ecommerce\Models\ResellerApplication;
use Botble\Ecommerce\Tables\ResellerApplicationTable;
use Botble\Ecommerce\Forms\ResellerApplicationForm;
use Botble\Base\Forms\FormBuilder;
use Illuminate\Http\Request;
use Exception;

class ResellerApplicationController extends BaseController
{
    public function index(ResellerApplicationTable $table)
    {
        $this->pageTitle(__('Reseller Applications'));

        return $table->renderTable();
    }

    public function edit(ResellerApplication $resellerApplication, FormBuilder $formBuilder)
    {
        $this->pageTitle(__('Process Reseller Application'));

        return $formBuilder->create(ResellerApplicationForm::class, ['model' => $resellerApplication])->renderForm();
    }

    public function update(ResellerApplication $resellerApplication, Request $request, BaseHttpResponse $response)
    {
        $status = $request->input('status');
        $oldStatus = $resellerApplication->status;

        $resellerApplication->fill([
            'status' => $status,
            'rejection_reason' => $status == ResellerApplicationStatusEnum::REJECTED ? $request->input('rejection_reason') : null,
            'handled_by' => auth()->id(),
        ]);

        $resellerApplication->save();

        if ($status == ResellerApplicationStatusEnum::APPROVED && (string)$oldStatus != ResellerApplicationStatusEnum::APPROVED) {
            $customer = $resellerApplication->customer;
            if ($customer && $customer->id) {
                $customer->is_reseller_active = true;
                $customer->save();
            }
        } elseif ($status != ResellerApplicationStatusEnum::APPROVED && (string)$oldStatus == ResellerApplicationStatusEnum::APPROVED) {
            $customer = $resellerApplication->customer;
            if ($customer && $customer->id) {
                $customer->is_reseller_active = false;
                $customer->save();
            }
        }

        event(new UpdatedContentEvent(RESELLER_APPLICATION_MODULE_SCREEN_NAME, $request, $resellerApplication));

        return $response
            ->setPreviousUrl(route('ecommerce.reseller-applications.index'))
            ->setMessage(__('Updated successfully'));
    }

    public function show(ResellerApplication $resellerApplication)
    {
        $this->pageTitle(__('View Reseller Application'));

        return view('plugins/ecommerce::reseller-applications.show', compact('resellerApplication'));
    }

    public function approve(ResellerApplication $resellerApplication, BaseHttpResponse $response)
    {
        $resellerApplication->status = ResellerApplicationStatusEnum::APPROVED;
        $resellerApplication->handled_by = auth()->id();
        $resellerApplication->save();

        $customer = $resellerApplication->customer;
        $customer->is_reseller_active = true;
        $customer->save();

        return $response->setMessage(__('Application approved successfully'));
    }

    public function reject(ResellerApplication $resellerApplication, Request $request, BaseHttpResponse $response)
    {
        $resellerApplication->status = ResellerApplicationStatusEnum::REJECTED;
        $resellerApplication->rejection_reason = $request->input('reason');
        $resellerApplication->handled_by = auth()->id();
        $resellerApplication->save();

        $customer = $resellerApplication->customer;
        $customer->is_reseller_active = false;
        $customer->save();

        return $response->setMessage(__('Application rejected successfully'));
    }
}
