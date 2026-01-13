<?php

namespace Botble\Marketplace\Http\Controllers\Vendor;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Models\Customer;
use Botble\Marketplace\Forms\Vendor\LanguageSettingForm;
use Botble\Marketplace\Http\Requests\Vendor\LanguageSettingRequest;

class LanguageSettingController extends BaseController
{
    public function index()
    {
        $this->pageTitle(__('Settings'));

        /**
         * @var Customer $customer
         */
        $customer = auth('customer')->user();

        return LanguageSettingForm::createFromModel($customer)->renderForm();
    }

    public function update(LanguageSettingRequest $request)
    {
        /**
         * @var Customer $customer
         */
        $customer = auth('customer')->user();

        LanguageSettingForm::createFromModel($customer)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('marketplace.vendor.language-settings.index'))
            ->withUpdatedSuccessMessage();
    }
}
