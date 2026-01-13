<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\MetaBox;
use Botble\Ecommerce\Models\Product;
use Botble\Faq\FaqSupport;

class SaveProductFaqListener
{
    public function handle(CreatedContentEvent|UpdatedContentEvent $event): void
    {
        $request = $event->request;
        $model = $event->data;

        if (! is_object($model) || ! $model instanceof Product) {
            return;
        }

        if ($request->has('content') && $request->has('faq_schema_config')) {
            (new FaqSupport())->saveConfigs($model, $request->input('faq_schema_config'));
        }

        $faqIds = $request->input('selected_existing_faqs', []);

        if (! empty($faqIds) && is_array($faqIds) && count(array_filter($faqIds)) > 0) {
            MetaBox::saveMetaBoxData($model, 'faq_ids', $faqIds);
        } else {
            MetaBox::deleteMetaData($model, 'faq_ids');
        }
    }
}
