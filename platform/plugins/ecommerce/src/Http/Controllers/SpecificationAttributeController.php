<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Forms\SpecificationAttributeForm;
use Botble\Ecommerce\Http\Requests\SpecificationAttributeRequest;
use Botble\Ecommerce\Models\SpecificationAttribute;
use Botble\Ecommerce\Tables\SpecificationAttributeTable;
use Botble\Table\Abstracts\TableAbstract;

class SpecificationAttributeController extends BaseController
{
    public function index()
    {
        $this->pageTitle(trans('plugins/ecommerce::product-specification.specification_attributes.title'));

        return app($this->getTable())->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/ecommerce::product-specification.specification_attributes.create.title'));

        return $this->getForm()::create()->renderForm();
    }

    public function store(SpecificationAttributeRequest $request)
    {
        $form = $this->getForm()::create()->setRequest($request)->onlyValidatedData();
        $form->saving(function (SpecificationAttributeForm $form): void {
            $model = $form->getModel();
            if (! empty($additionalData = $this->getAdditionalDataForSaving())) {
                $model->fill($additionalData);
            }
            $form->save();
        });

        return $this
            ->httpResponse()
            ->withCreatedSuccessMessage()
            ->setPreviousRoute($this->getIndexRouteName())
            ->setNextRoute($this->getEditRouteName(), $form->getModel());
    }

    public function edit(string $attribute)
    {
        $attribute = $this->getSpecificationAttribute($attribute);

        $this->pageTitle(trans('plugins/ecommerce::product-specification.specification_attributes.edit.title', [
            'name' => $attribute->name,
        ]));

        return $this->getForm()::createFromModel($attribute)->renderForm();
    }

    public function update(SpecificationAttributeRequest $request, string $attribute)
    {
        $attribute = $this->getSpecificationAttribute($attribute);

        $form = $this->getForm()::createFromModel($attribute)->setRequest($request)->onlyValidatedData();
        $form->saving(function (SpecificationAttributeForm $form): void {
            $model = $form->getModel();
            if (! empty($additionalData = $this->getAdditionalDataForSaving())) {
                $model->fill($additionalData);
            }
            $form->save();
        });

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage()
            ->setPreviousRoute($this->getIndexRouteName())
            ->setNextRoute($this->getEditRouteName(), $form->getModel());
    }

    public function destroy(string $attribute)
    {
        $attribute = $this->getSpecificationAttribute($attribute);

        return DeleteResourceAction::make($attribute);
    }

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::product-specification.specification_attributes.title'), route($this->getIndexRouteName()));
    }

    /**
     * @return class-string<TableAbstract>
     */
    protected function getTable(): string
    {
        return SpecificationAttributeTable::class;
    }

    /**
     * @return class-string<FormAbstract>
     */
    protected function getForm(): string
    {
        return SpecificationAttributeForm::class;
    }

    protected function getAdditionalDataForSaving(): array
    {
        return [];
    }

    protected function getIndexRouteName(): string
    {
        return 'ecommerce.specification-attributes.index';
    }

    protected function getEditRouteName(): string
    {
        return 'ecommerce.specification-attributes.edit';
    }

    protected function getSpecificationAttribute(string $attribute)
    {
        return SpecificationAttribute::query()->findOrFail($attribute);
    }
}
