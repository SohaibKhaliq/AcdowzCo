<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Forms\ProductTagForm;
use Botble\Ecommerce\Http\Requests\ProductTagRequest;
use Botble\Ecommerce\Models\ProductTag;
use Botble\Ecommerce\Tables\ProductTagTable;

class ProductTagController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::product-tag.name'), route('product-tag.index'));
    }

    public function index(ProductTagTable $table)
    {
        $this->pageTitle(trans('plugins/ecommerce::product-tag.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/ecommerce::product-tag.create'));

        return ProductTagForm::create()->renderForm();
    }

    public function store(ProductTagRequest $request)
    {
        $form = ProductTagForm::create();

        $form->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('product-tag.index'))
            ->setNextUrl(route('product-tag.edit', $form->getModel()->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(ProductTag $productTag)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $productTag->name]));

        return ProductTagForm::createFromModel($productTag)->renderForm();
    }

    public function update(ProductTag $productTag, ProductTagRequest $request)
    {
        ProductTagForm::createFromModel($productTag)->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('product-tag.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(ProductTag $productTag)
    {
        return DeleteResourceAction::make($productTag);
    }

    public function getAllTags()
    {
        return ProductTag::query()->pluck('name')->all();
    }
}
