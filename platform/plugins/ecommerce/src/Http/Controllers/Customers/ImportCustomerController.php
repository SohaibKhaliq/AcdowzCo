<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Botble\DataSynchronize\Http\Controllers\ImportController;
use Botble\DataSynchronize\Importer\Importer;
use Botble\Ecommerce\Importers\CustomerImporter;

class ImportCustomerController extends ImportController
{
    protected function getImporter(): Importer
    {
        return CustomerImporter::make();
    }
}
