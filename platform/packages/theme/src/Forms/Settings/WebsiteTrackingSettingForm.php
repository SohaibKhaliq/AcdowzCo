<?php

namespace Botble\Theme\Forms\Settings;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\CodeEditorFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CodeEditorField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Setting\Forms\SettingForm;
use Botble\Theme\Http\Requests\WebsiteTrackingSettingRequest;

class WebsiteTrackingSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $googleTagManagerId = setting('google_tag_manager_id', setting('google_analytics'));
        $gtmContainerId = setting('gtm_container_id');
        $customTrackingHeaderJs = setting('custom_tracking_header_js');
        $customTrackingBodyHtml = setting('custom_tracking_body_html');

        $oldGoogleTagManagerCode = setting('google_tag_manager_code');

        if ($oldGoogleTagManagerCode && ! $customTrackingHeaderJs && ! $customTrackingBodyHtml) {
            $customTrackingHeaderJs = $oldGoogleTagManagerCode;
        }

        $defaultType = setting('google_tag_manager_type');
        if (! $defaultType) {
            if ($gtmContainerId) {
                $defaultType = 'gtm';
            } elseif ($oldGoogleTagManagerCode || $customTrackingHeaderJs || $customTrackingBodyHtml) {
                $defaultType = 'custom';
            } elseif ($googleTagManagerId) {
                $defaultType = 'id';
            } else {
                $defaultType = 'gtm';
            }
        } elseif ($defaultType === 'code') {
            $defaultType = 'custom';
        }

        $this
            ->setSectionTitle(trans('packages/theme::theme.settings.website_tracking.title'))
            ->setSectionDescription(trans('packages/theme::theme.settings.website_tracking.description'))
            ->setValidatorClass(WebsiteTrackingSettingRequest::class)
            ->add(
                'google_tag_manager_type',
                RadioField::class,
                RadioFieldOption::make()
                    ->choices([
                        'gtm' => trans('packages/theme::theme.settings.website_tracking.google_tag_manager'),
                        'id' => trans('packages/theme::theme.settings.website_tracking.google_tag_id'),
                        'custom' => trans('packages/theme::theme.settings.website_tracking.custom_tracking'),
                    ])
                    ->selected($targetValue = old('google_tag_manager_type', $defaultType))
            )
            ->add(
                'gtm_description',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->collapsible('google_tag_manager_type', 'gtm', $targetValue)
                    ->content('<div class="alert alert-success mb-3">' . BaseHelper::renderIcon('ti ti-info-circle') . ' ' . trans('packages/theme::theme.settings.website_tracking.google_tag_manager_description') . '</div>')
            )
            ->add(
                'gtm_container_id',
                TextField::class,
                TextFieldOption::make()
                    ->collapsible('google_tag_manager_type', 'gtm', $targetValue)
                    ->label(trans('packages/theme::theme.settings.website_tracking.gtm_container_id'))
                    ->value($gtmContainerId)
                    ->placeholder(trans('packages/theme::theme.settings.website_tracking.gtm_container_id_placeholder'))
                    ->helperText(
                        trans('packages/theme::theme.settings.website_tracking.gtm_container_id_helper') . ' ' .
                        Html::link('https://support.google.com/tagmanager/answer/6103696', trans('packages/theme::theme.settings.website_tracking.gtm_learn_more'), ['target' => '_blank'])
                    )
            )
            ->add(
                'id_description',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->collapsible('google_tag_manager_type', 'id', $targetValue)
                    ->content('<div class="alert alert-info mb-3">' . BaseHelper::renderIcon('ti ti-info-circle') . ' ' . trans('packages/theme::theme.settings.website_tracking.google_tag_id_description') . '</div>')
            )
            ->add(
                'google_tag_manager_id',
                TextField::class,
                TextFieldOption::make()
                    ->collapsible('google_tag_manager_type', 'id', $targetValue)
                    ->label(trans('packages/theme::theme.settings.website_tracking.google_tag_id'))
                    ->value($googleTagManagerId)
                    ->placeholder(trans('packages/theme::theme.settings.website_tracking.google_tag_id_placeholder'))
                    ->helperText(
                        Html::link('https://support.google.com/analytics/answer/9539598#find-G-ID', 'Find your Google Analytics ID', ['target' => '_blank'])
                    )
            )
            ->add(
                'custom_description',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->collapsible('google_tag_manager_type', 'custom', $targetValue)
                    ->content('<div class="alert alert-warning mb-3">' . BaseHelper::renderIcon('ti ti-info-circle') . ' ' . trans('packages/theme::theme.settings.website_tracking.custom_tracking_description') . '</div>')
            )
            ->add(
                'custom_tracking_instruction',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->collapsible('google_tag_manager_type', 'custom', $targetValue)
                    ->content('<div class="form-text mb-3">' . trans('packages/theme::theme.settings.website_tracking.custom_tracking_instruction') . '</div>')
            )
            ->add(
                'custom_tracking_header_js',
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->collapsible('google_tag_manager_type', 'custom', $targetValue)
                    ->label(trans('packages/theme::theme.settings.website_tracking.custom_tracking_header_js'))
                    ->value($customTrackingHeaderJs)
                    ->mode('html')
                    ->helperText(trans('packages/theme::theme.settings.website_tracking.custom_tracking_header_js_helper'))
            )
            ->add(
                'custom_tracking_body_html',
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                     ->collapsible('google_tag_manager_type', 'custom', $targetValue)
                     ->label(trans('packages/theme::theme.settings.website_tracking.custom_tracking_body_html'))
                     ->value($customTrackingBodyHtml)
                     ->mode('html')
                     ->helperText(trans('packages/theme::theme.settings.website_tracking.custom_tracking_body_html_helper'))
            )
            ->add(
                'gtm_debug_mode',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('packages/theme::theme.settings.website_tracking.gtm_debug_mode'))
                    ->value((bool) setting('gtm_debug_mode', false))
                    ->helperText(trans('packages/theme::theme.settings.website_tracking.gtm_debug_mode_helper'))
            );
    }
}
