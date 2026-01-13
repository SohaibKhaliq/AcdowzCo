<?php

namespace Botble\Marketplace\Forms\Vendor;

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Supports\Language;
use Botble\Ecommerce\Models\Customer;
use Botble\Marketplace\Forms\Concerns\HasSubmitButton;
use Botble\Marketplace\Http\Requests\Vendor\LanguageSettingRequest;
use Illuminate\Support\Facades\App;

class LanguageSettingForm extends FormAbstract
{
    use HasSubmitButton;

    public function setup(): void
    {
        $languages = Language::getAvailableLocales();

        $this
            ->setupModel(new Customer())
            ->setValidatorClass(LanguageSettingRequest::class)
            ->setUrl(route('marketplace.vendor.language-settings.update'))
            ->setMethod('PUT')
            ->contentOnly()
            ->when(count($languages) > 1, function (FormAbstract $form) use ($languages): void {
                $languages = collect($languages)
                    ->pluck('name', 'locale')
                    ->map(fn ($item, $key) => $item . ' - ' . $key)
                    ->all();

                $form
                    ->add(
                        'locale',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(__('Language'))
                            ->choices($languages)
                            ->selected($form->getModel()->getMetaData('locale', 'true') ?: App::getLocale())
                            ->metadata()
                    );
            })
            ->addSubmitButton(__('Save settings'));
    }
}
