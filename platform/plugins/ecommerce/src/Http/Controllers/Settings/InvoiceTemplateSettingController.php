<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Facades\InvoiceHelper;
use Botble\Ecommerce\Http\Requests\Settings\InvoiceTemplateSettingRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class InvoiceTemplateSettingController extends SettingController
{
    public function edit(Request $request): View
    {
        $templates = apply_filters('ecommerce_invoice_templates', InvoiceHelper::getDefaultInvoiceTemplatesFilter());

        $request->validate([
            'template' => ['nullable', 'string', Rule::in(array_keys($templates))],
        ]);

        $this->pageTitle(trans('plugins/ecommerce::setting.invoice_templates'));

        Assets::addScriptsDirectly('vendor/core/core/setting/js/email-template.js');

        $currentTemplate = $request->input('template', array_key_first($templates));
        $template = Arr::get($templates, $currentTemplate, array_key_first($templates));
        $templates = collect($templates)
            ->mapWithKeys(fn ($template, $key) => [$key => Arr::get($template, 'label', $key)]);

        return view(
            'plugins/ecommerce::invoice-template.settings',
            compact('templates', 'template', 'currentTemplate')
        );
    }

    public function update(InvoiceTemplateSettingRequest $request)
    {
        $templates = apply_filters('ecommerce_invoice_templates', InvoiceHelper::getDefaultInvoiceTemplatesFilter());
        $template = value(Arr::get($templates, $request->input('template', array_key_first($templates))));

        $filePath = $template['customized_path'];

        File::ensureDirectoryExists(File::dirname($filePath));

        BaseHelper::saveFileData($filePath, $request->input('content'), false);

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }

    public function reset(string $template)
    {
        $templates = apply_filters('ecommerce_invoice_templates', InvoiceHelper::getDefaultInvoiceTemplatesFilter());
        $template = value(Arr::get($templates, $template, array_key_first($templates)));

        if (Arr::has($template, 'customized_path')) {
            File::delete($template['customized_path']);
        }

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/ecommerce::invoice-template.reset_success'));
    }

    public function preview(string $template)
    {
        $templates = apply_filters('ecommerce_invoice_templates', InvoiceHelper::getDefaultInvoiceTemplatesFilter());
        $template = value(Arr::get($templates, $template));

        abort_if(! $template || ! Arr::has($template, 'preview'), 404);

        return value($template['preview']);
    }
}
