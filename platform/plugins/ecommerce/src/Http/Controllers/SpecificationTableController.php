<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Forms\SpecificationTableForm;
use Botble\Ecommerce\Http\Requests\SpecificationTableRequest;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\SpecificationTable;
use Botble\Ecommerce\Tables\SpecificationTableTable;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SpecificationTableController extends BaseController
{
    public function index(Request $request)
    {
        if ($request->isMethod('GET') && $request->ajax()) {
            $specificationTable = SpecificationTable::query()
                ->with('groups.specificationAttributes')
                ->findOrFail($request->query('table'));

            $product = null;
            $language = $request->query('ref_lang');

            if ($request->query('product')) {
                $product = Product::query()
                    ->with('specificationAttributes')
                    ->findOrFail($request->query('product'));
            }

            return $this
                ->httpResponse()
                ->setData(view('plugins/ecommerce::products.partials.specification-table.table', compact('specificationTable', 'product', 'language'))->render());
        }

        $this->pageTitle(trans('plugins/ecommerce::product-specification.specification_tables.title'));

        return app($this->getTable())->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/ecommerce::product-specification.specification_tables.create.title'));

        return $this->getForm()::create()->renderForm();
    }

    public function store(SpecificationTableRequest $request)
    {
        $form = $this->getForm()::create()->setRequest($request)->onlyValidatedData();

        $form->saving(function (SpecificationTableForm $form): void {
            $model = $form->getModel();
            if (! empty($this->getAdditionalDataForSaving())) {
                $model->fill($this->getAdditionalDataForSaving());
            }

            $form->save();

            /** @var SpecificationTable $model */
            $model = $form->getModel();

            $model->groups()->sync(Arr::get($form->getRequest(), 'groups', []));
        });

        return $this
            ->httpResponse()
            ->withCreatedSuccessMessage()
            ->setPreviousRoute($this->getIndexRouteName())
            ->setNextRoute($this->getEditRouteName(), $form->getModel());
    }

    public function edit(string $table)
    {
        $table = $this->getSpecificationTable($table);

        $this->pageTitle(trans('plugins/ecommerce::product-specification.specification_tables.edit.title', [
            'name' => $table->name,
        ]));

        return $this->getForm()::createFromModel($table)->renderForm();
    }

    public function update(SpecificationTableRequest $request, string $table)
    {
        $table = $this->getSpecificationTable($table);

        $form = $this->getForm()::createFromModel($table)->setRequest($request)->onlyValidatedData();

        $form->saving(function (SpecificationTableForm $form): void {
            $model = $form->getModel();
            if (! empty($this->getAdditionalDataForSaving())) {
                $model->fill($this->getAdditionalDataForSaving());
            }
            $form->save();

            /** @var SpecificationTable $model */
            $model = $form->getModel();

            $orders = Arr::get($form->getRequest(), 'group_orders', []);

            $data = [];

            foreach (Arr::get($form->getRequest(), 'groups', []) as $index => $groupId) {
                $data[$groupId] = ['order' => $orders[$groupId] ?? $index];
            }

            $model->groups()->sync($data);
        });

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage()
            ->setPreviousRoute($this->getIndexRouteName())
            ->setNextRoute($this->getEditRouteName(), $form->getModel());
    }

    public function destroy(string $table)
    {
        $table = $this->getSpecificationTable($table);

        return DeleteResourceAction::make($table);
    }

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::product-specification.specification_tables.title'), route($this->getIndexRouteName()));
    }

    /**
     * @return class-string<TableAbstract>
     */
    protected function getTable(): string
    {
        return SpecificationTableTable::class;
    }

    /**
     * @return class-string<FormAbstract>
     */
    protected function getForm(): string
    {
        return SpecificationTableForm::class;
    }

    protected function getAdditionalDataForSaving(): array
    {
        return [];
    }

    protected function getIndexRouteName(): string
    {
        return 'ecommerce.specification-tables.index';
    }

    protected function getEditRouteName(): string
    {
        return 'ecommerce.specification-tables.edit';
    }

    protected function getSpecificationTable(string $table)
    {
        return SpecificationTable::query()->findOrFail($table);
    }
}
