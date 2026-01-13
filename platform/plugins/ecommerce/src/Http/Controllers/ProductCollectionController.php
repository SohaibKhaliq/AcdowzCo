<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Forms\ProductCollectionForm;
use Botble\Ecommerce\Http\Requests\ProductCollectionRequest;
use Botble\Ecommerce\Models\ProductCollection;
use Botble\Ecommerce\Tables\ProductCollectionTable;
use Illuminate\Http\Request;

class ProductCollectionController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::product-collections.name'), route('product-collections.index'));
    }

    public function index(ProductCollectionTable $dataTable)
    {
        $this->pageTitle(trans('plugins/ecommerce::product-collections.name'));

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/ecommerce::product-collections.create'));

        return ProductCollectionForm::create()->renderForm();
    }

    public function store(ProductCollectionRequest $request)
    {
        $productCollection = new ProductCollection();
        $productCollection->fill($request->input());

        $productCollection->slug = $request->input('slug');
        $productCollection->save();

        event(new CreatedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('product-collections.index'))
            ->setNextUrl(route('product-collections.edit', $productCollection->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(ProductCollection $productCollection, Request $request)
    {
        event(new BeforeEditContentEvent($request, $productCollection));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $productCollection->name]));

        return ProductCollectionForm::createFromModel($productCollection)
            ->remove('slug')
            ->renderForm();
    }

    public function update(ProductCollection $productCollection, ProductCollectionRequest $request)
    {
        $productCollection->fill($request->input());
        $productCollection->save();

        if ($productIds = $request->input('collection_products')) {
            $productIds = array_filter(explode(',', $productIds));
        }

        $productCollection->products()->sync($productIds);

        event(new UpdatedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('product-collections.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(ProductCollection $productCollection)
    {
        return DeleteResourceAction::make($productCollection);
    }

    public function getListForSelect()
    {
        $productCollections = ProductCollection::query()
            ->select(['id', 'name'])
            ->get()
            ->all();

        return $this
            ->httpResponse()
            ->setData($productCollections);
    }

    public function getProductCollection(?ProductCollection $productCollection)
    {
        $productCollection->load(['products']);

        return $this
            ->httpResponse()
            ->setData(view(
                'plugins/ecommerce::product-collections.partials.products',
                compact('productCollection')
            )->render());
    }
}
