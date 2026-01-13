<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Forms\ProductLabelForm;
use Botble\Ecommerce\Http\Requests\ProductLabelRequest;
use Botble\Ecommerce\Models\ProductLabel;
use Botble\Ecommerce\Tables\ProductLabelTable;

class ProductLabelController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::product-label.name'), route('product-label.index'));
    }

    public function index(ProductLabelTable $table)
    {
        $this->pageTitle(trans('plugins/ecommerce::product-label.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/ecommerce::product-label.create'));

        return ProductLabelForm::create()->renderForm();
    }

    public function store(ProductLabelRequest $request)
    {
        $form = ProductLabelForm::create();

        $form->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('product-label.index'))
            ->setNextUrl(route('product-label.edit', $form->getModel()->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(ProductLabel $productLabel)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $productLabel->name]));

        return ProductLabelForm::createFromModel($productLabel)->renderForm();
    }

    public function update(ProductLabel $productLabel, ProductLabelRequest $request)
    {
        ProductLabelForm::createFromModel($productLabel)->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('product-label.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(ProductLabel $productLabel)
    {
        return DeleteResourceAction::make($productLabel);
    }
}
