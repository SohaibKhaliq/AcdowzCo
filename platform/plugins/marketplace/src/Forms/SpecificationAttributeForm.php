<?php

namespace Botble\Marketplace\Forms;

use Botble\Ecommerce\Forms\SpecificationAttributeForm as BaseSpecificationAttributeForm;
use Botble\Marketplace\Facades\MarketplaceHelper;

class SpecificationAttributeForm extends BaseSpecificationAttributeForm
{
    public function setup(): void
    {
        parent::setup();

        $this->template(MarketplaceHelper::viewPath('vendor-dashboard.forms.base'));
    }
}
