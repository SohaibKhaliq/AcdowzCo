<?php

namespace Botble\Marketplace\Forms;

use Botble\Ecommerce\Forms\SpecificationGroupForm as BaseSpecificationGroupForm;
use Botble\Marketplace\Facades\MarketplaceHelper;

class SpecificationGroupForm extends BaseSpecificationGroupForm
{
    public function setup(): void
    {
        parent::setup();

        $this->template(MarketplaceHelper::viewPath('vendor-dashboard.forms.base'));
    }
}
