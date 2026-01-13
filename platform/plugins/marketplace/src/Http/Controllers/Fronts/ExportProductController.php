<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\DataSynchronize\Exporter\Exporter;
use Botble\DataSynchronize\Http\Controllers\ExportController;
use Botble\Marketplace\Exporters\ProductExporter;

class ExportProductController extends ExportController
{
    protected function getExporter(): Exporter
    {
        return ProductExporter::make();
    }
}
