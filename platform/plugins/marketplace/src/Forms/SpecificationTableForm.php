<?php

namespace Botble\Marketplace\Forms;

use Botble\Ecommerce\Forms\SpecificationTableForm as BaseSpecificationTableForm;
use Botble\Marketplace\Facades\MarketplaceHelper;

class SpecificationTableForm extends BaseSpecificationTableForm
{
    public function setup(): void
    {
        parent::setup();

        $this->template(MarketplaceHelper::viewPath('vendor-dashboard.forms.base'));
    }
}
