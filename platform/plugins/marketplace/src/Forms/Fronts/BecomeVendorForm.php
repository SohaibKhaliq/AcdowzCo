<?php

namespace Botble\Marketplace\Forms\Fronts;

use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Http\Requests\Fronts\BecomeVendorRequest;
use Botble\Theme\Facades\Theme;

class BecomeVendorForm extends FormAbstract
{
    public function setup(): void
    {
        Theme::asset()
            ->container('footer')
            ->add('marketplace-register', 'vendor/core/plugins/marketplace/js/customer-register.js', ['jquery']);

        Theme::asset()
            ->add('dropzone', 'vendor/core/core/base/libraries/dropzone/dropzone.css');

        Theme::asset()
            ->container('footer')
            ->add('dropzone', 'vendor/core/core/base/libraries/dropzone/dropzone.js');

        $this
            ->contentOnly()
            ->setValidatorClass(BecomeVendorRequest::class)
            ->formClass('become-vendor-form')
            ->setUrl(route('marketplace.vendor.become-vendor.post'))
            ->add(
                'is_vendor',
                'hidden',
                TextFieldOption::make()->value(1),
            )
            ->add(
                'shop_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Shop Name'))
                    ->placeholder(__('Store Name'))
                    ->required(),
            )
            ->add(
                'shop_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Shop URL'))
                    ->placeholder(__('Store URL'))
                    ->attributes([
                        'data-url' => route('public.ajax.check-store-url'),
                        'style' => 'direction: ltr; text-align: left;',
                    ])
                    ->wrapperAttributes(['class' => 'shop-url-wrapper mb-3 position-relative'])
                    ->prepend(
                        sprintf(
                            '<span class="position-absolute top-0 end-0 shop-url-status"></span><div class="input-group"><span class="input-group-text">%s</span>',
                            route('public.store', ['slug' => '/'])
                        )
                    )
                    ->append('</div>')
                    ->helperText(__('plugins/marketplace::store.forms.shop_url_helper'))
                    ->required(),
            )
            ->add(
                'shop_phone',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Shop Phone'))
                    ->placeholder(__('Ex: 0943243332'))
                    ->required(),
            )
            ->when(MarketplaceHelper::getSetting('requires_vendor_documentations_verification', true), function (): void {
                $this
                    ->add(
                        'certificate_of_incorporation',
                        'html',
                        HtmlFieldOption::make()
                            ->label(__('Certificate of Incorporation'))
                            ->required()
                            ->wrapperAttributes(['class' => 'mb-3 position-relative', 'data-field-name' => 'certificate_file'])
                            ->content('<div id="certificate-dropzone" class="dropzone" data-placeholder="' . __('Drop Certificate of Incorporation here or click to upload') . '"></div>'),
                    )
                    ->add(
                        'government_id',
                        'html',
                        HtmlFieldOption::make()
                            ->label(__('Government ID'))
                            ->required()
                            ->wrapperAttributes(['class' => 'mb-3 position-relative', 'data-field-name' => 'government_id_file'])
                            ->attributes(['data-placeholder' => ''])
                            ->content('<div id="government-id-dropzone" class="dropzone" data-placeholder="' . __('Drop Government ID here or click to upload') . '"></div>'),
                    );
            })
            ->add(
                'agree_terms_and_policy',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->when(
                        $privacyPolicyUrl = MarketplaceHelper::getSetting('term_and_privacy_policy_url') ?: Theme::termAndPrivacyPolicyUrl(),
                        function (CheckboxFieldOption $fieldOption, string $url): void {
                            $fieldOption->label(__('I agree to the :link', ['link' => Html::link($url, __('Terms and Privacy Policy'), attributes: ['class' => 'text-decoration-underline', 'target' => '_blank'])]));
                        }
                    )
                    ->when(! $privacyPolicyUrl, function (CheckboxFieldOption $fieldOption): void {
                        $fieldOption->label(__('I agree to the Terms and Privacy Policy'));
                    })
            )
            ->add(
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->label(__('Register'))
                    ->cssClass('btn btn-primary')
            );
    }
}
