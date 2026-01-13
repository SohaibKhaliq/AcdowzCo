<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\DataSynchronize\Exporter\Exporter;
use Botble\DataSynchronize\Http\Controllers\ExportController;
use Botble\DataSynchronize\Http\Requests\ExportRequest;
use Botble\Ecommerce\Exporters\OrderExporter;

class OrderExportController extends ExportController
{
    protected function getExporter(): Exporter
    {
        $exporter = app(OrderExporter::class);

        if (request()->has('limit')) {
            $exporter->setLimit((int) request()->input('limit'));
        }

        if (request()->has('status') && request()->input('status') !== '') {
            $exporter->setStatus(request()->input('status'));
        }

        if (request()->has(['start_date', 'end_date'])) {
            $exporter->setDateRange(
                request()->input('start_date'),
                request()->input('end_date')
            );
        }

        return $exporter;
    }

    public function store(ExportRequest $request)
    {
        $request->validate([
            'limit' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        return parent::store($request);
    }
}
