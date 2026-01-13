<?php

namespace Botble\Ads\Forms\Settings;

use Botble\Ads\Http\Requests\Settings\AdsSettingRequest;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\AlertFieldOption;
use Botble\Base\Forms\FieldOptions\CodeEditorFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\AlertField;
use Botble\Base\Forms\Fields\CodeEditorField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Setting\Forms\SettingForm;

class AdsSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ads::ads.settings.title'))
            ->setSectionDescription(trans('plugins/ads::ads.settings.description'))
            ->setValidatorClass(AdsSettingRequest::class);

        $googleAdSenseLink = Html::link('https://www.google.com/adsense', 'Google AdSense', ['target' => '_blank']);

        $currentMode = 'none';
        if (setting('ads_google_adsense_auto_ads')) {
            $currentMode = 'auto';
        } elseif (setting('ads_google_adsense_unit_client_id')) {
            $currentMode = 'unit';
        }

        $this
            ->add(
                'ads_google_adsense_mode',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('plugins/ads::ads.settings.google_adsense_mode'))
                    ->choices([
                        'none' => trans('plugins/ads::ads.settings.google_adsense_mode_none'),
                        'auto' => trans('plugins/ads::ads.settings.google_adsense_mode_auto'),
                        'unit' => trans('plugins/ads::ads.settings.google_adsense_mode_unit'),
                    ])
                    ->selected(old('ads_google_adsense_mode', $currentMode))
            )
            ->add(
                'ads_google_adsense_mode_info',
                AlertField::class,
                AlertFieldOption::make()
                    ->type('info')
                    ->content(trans('plugins/ads::ads.settings.google_adsense_mode_info'))
            )
            ->addOpenCollapsible('ads_google_adsense_mode', 'auto', old('ads_google_adsense_mode', $currentMode))
            ->add(
                'ads_google_adsense_auto_ads',
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->label(trans('plugins/ads::ads.settings.google_adsense_auto_ads_snippet'))
                    ->helperText(trans('plugins/ads::ads.settings.google_adsense_auto_ads_snippet_helper', [
                        'link' => $googleAdSenseLink,
                    ]))
                    ->value(setting('ads_google_adsense_auto_ads'))
                    ->mode('html')
            )
            ->addCloseCollapsible('ads_google_adsense_mode', 'auto')
            ->addOpenCollapsible('ads_google_adsense_mode', 'unit', old('ads_google_adsense_mode', $currentMode))
            ->add(
                'ads_google_adsense_unit_client_id',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ads::ads.settings.google_adsense_unit_ads_client_id'))
                    ->helperText(trans('plugins/ads::ads.settings.google_adsense_unit_ads_client_id_helper', [
                        'link' => $googleAdSenseLink,
                    ]))
                    ->value(setting('ads_google_adsense_unit_client_id'))
                    ->placeholder('ca-pub-123456789')
            )
            ->add(
                'ads_google_adsense_what_is_client_id',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->view('plugins/ads::partials.google-adsense.client-id')
            )
            ->addCloseCollapsible('ads_google_adsense_mode', 'unit')
            ->add(
                'ads_txt_divider',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content('<hr class="my-4">')
            )
            ->add(
                'ads_txt_info',
                AlertField::class,
                AlertFieldOption::make()
                    ->type('info')
                    ->content(trans('plugins/ads::ads.settings.google_adsense_txt_file_info'))
            )
            ->add('ads_google_adsense_txt_file', 'file', [
                'label' => trans('plugins/ads::ads.settings.google_adsense_txt_file'),
                'help_block' => [
                    'text' => trans('plugins/ads::ads.settings.google_adsense_txt_file_helper'),
                ],
            ])
            ->add(
                'ads_google_adsense_txt',
                HtmlField::class,
                HtmlFieldOption::make()->view('plugins/ads::partials.google-adsense.txt')
            );
    }
}
