<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Botble\DataSynchronize\Exporter\Exporter;
use Botble\DataSynchronize\Http\Controllers\ExportController;
use Botble\Ecommerce\Exporters\CustomerExporter;

class ExportCustomerController extends ExportController
{
    protected function getExporter(): Exporter
    {
        return CustomerExporter::make();
    }
}
