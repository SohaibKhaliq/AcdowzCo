<?php

namespace Botble\Newsletter\Http\Controllers\Settings;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Breadcrumb;
use Botble\Newsletter\Forms\NewsletterSettingForm;
use Botble\Newsletter\Http\Requests\Settings\NewsletterSettingRequest;
use Botble\Setting\Http\Controllers\SettingController;

class NewsletterSettingController extends SettingController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('core/base::base.panel.others'));
    }

    public function edit()
    {
        $this->pageTitle(trans('plugins/newsletter::newsletter.settings.title'));

        return NewsletterSettingForm::create()->renderForm();
    }

    public function update(NewsletterSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
