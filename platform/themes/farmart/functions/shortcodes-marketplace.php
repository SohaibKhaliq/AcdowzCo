<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\Assets;
use Botble\Base\Facades\Html;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Marketplace\Models\Store;
use Botble\Shortcode\Compilers\Shortcode;
use Botble\Shortcode\Facades\Shortcode as ShortcodeFacade;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Arr;

if (is_plugin_active('marketplace')) {
    Assets::addStylesDirectly('vendor/core/core/base/libraries/tagify/tagify.css');

    add_shortcode('marketplace-stores', __('Marketplace Stores'), __('Marketplace Stores'), function (Shortcode $shortcode) {
        $storeIds = [];

        if ($shortcode->stores) {
            $storeIds = explode(',', $shortcode->stores);
        }

        if (empty($storeIds)) {
            return null;
        }

        $layout = $shortcode->layout ?: theme_option('store_list_layout');

        $layout = $layout && in_array($layout, array_keys(get_store_list_layouts())) ? $layout : 'grid';

        $with = ['slugable'];
        if (EcommerceHelper::isReviewEnabled()) {
            $with['reviews'] = function ($query): void {
                $query->where([
                    'ec_products.status' => BaseStatusEnum::PUBLISHED,
                    'ec_reviews.status' => BaseStatusEnum::PUBLISHED,
                ]);
            };
        }

        $stores = Store::query()
            ->wherePublished()
            ->whereIn('id', $storeIds)
            ->with($with)
            ->withCount([
                'products' => function ($query): void {
                    $query->wherePublished();
                },
            ])
            ->orderByDesc('created_at')
            ->get();

        return Theme::partial('shortcodes.marketplace.stores', compact('shortcode', 'layout', 'stores'));
    });

    ShortcodeFacade::setAdminConfig('marketplace-stores', function (array $attributes) {
        $stores = Store::query()
            ->wherePublished()
            ->orderBy('name')
            ->pluck('name', 'id');

        $layouts = get_store_list_layouts();

        return ShortcodeForm::createFromArray($attributes)
            ->add('stores_scripts', 'html', [
                'html' => Html::script('vendor/core/core/base/libraries/tagify/tagify.js')->toHtml() .
                          Html::script('vendor/core/core/base/js/tags.js')->toHtml(),
            ])
            ->add('title', 'text', [
                'label' => __('Title'),
                'value' => Arr::get($attributes, 'title'),
                'placeholder' => __('Title'),
            ])
            ->add('stores', 'tags', [
                'label' => __('Stores'),
                'value' => Arr::get($attributes, 'stores'),
                'placeholder' => __('Select stores from the list'),
                'attr' => [
                    'class' => 'form-control list-tagify',
                    'data-list' => json_encode($stores),
                ],
            ])
            ->add('layout', 'customSelect', [
                'label' => __('Layout'),
                'choices' => $layouts,
                'selected' => Arr::get($attributes, 'layout'),
            ]);
    });
}
