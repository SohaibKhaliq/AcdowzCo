<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Base\Exceptions\DisabledInDemoModeException;
use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Forms\Settings\CustomerSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\CustomerSettingRequest;

class CustomerSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/ecommerce::setting.customer.name'));

        return CustomerSettingForm::create()->renderForm();
    }

    public function update(CustomerSettingRequest $request)
    {
        if (BaseHelper::hasDemoModeEnabled()) {
            throw new DisabledInDemoModeException();
        }

        $data = $request->validated();

        return $this->performUpdate($data);
    }
}
