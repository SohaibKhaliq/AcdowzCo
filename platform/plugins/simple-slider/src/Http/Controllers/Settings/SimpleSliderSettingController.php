<?php

namespace Botble\SimpleSlider\Http\Controllers\Settings;

use Botble\Base\Supports\Breadcrumb;
use Botble\Setting\Http\Controllers\SettingController;
use Botble\SimpleSlider\Forms\Settings\SimpleSliderSettingForm;
use Botble\SimpleSlider\Http\Requests\Settings\SimpleSliderSettingRequest;

class SimpleSliderSettingController extends SettingController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('core/base::base.panel.others'));
    }

    public function edit()
    {
        $this->pageTitle(trans('plugins/simple-slider::simple-slider.settings.title'));

        return SimpleSliderSettingForm::create()->renderForm();
    }

    public function update(SimpleSliderSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
