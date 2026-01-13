<?php

namespace Botble\Blog\Http\Controllers\Settings;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Breadcrumb;
use Botble\Blog\Forms\Settings\BlogSettingForm;
use Botble\Blog\Http\Requests\Settings\BlogSettingRequest;
use Botble\Setting\Http\Controllers\SettingController;

class BlogSettingController extends SettingController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('core/base::base.panel.others'));
    }

    public function edit()
    {
        $this->pageTitle(trans('plugins/blog::base.settings.title'));

        return BlogSettingForm::create()->renderForm();
    }

    public function update(BlogSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
