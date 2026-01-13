<?php

use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\Fields\TextField;
use Botble\Widget\AbstractWidget;
use Botble\Widget\Forms\WidgetForm;

class BecomeVendorWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Become a Vendor?'),
            'description' => __('Display Become a vendor on product detail sidebar'),
        ]);
    }

    protected function settingForm(): WidgetForm|string|null
    {
        if (! is_plugin_active('marketplace')) {
            return null;
        }

        return WidgetForm::createFromArray($this->getConfig())
            ->add('name', TextField::class, NameFieldOption::make()->toArray());
    }
}
