<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Forms\SpecificationGroupForm;
use Botble\Ecommerce\Http\Requests\SpecificationGroupRequest;
use Botble\Ecommerce\Models\SpecificationGroup;
use Botble\Ecommerce\Tables\SpecificationGroupTable;
use Botble\Table\Abstracts\TableAbstract;

class SpecificationGroupController extends BaseController
{
    public function index()
    {
        $this->pageTitle(trans('plugins/ecommerce::product-specification.specification_groups.title'));

        return app($this->getTable())->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/ecommerce::product-specification.specification_groups.create.title'));

        return $this->getForm()::create()->renderForm();
    }

    public function store(SpecificationGroupRequest $request)
    {
        $form = $this->getForm()::create()->setRequest($request)->onlyValidatedData();

        $form->saving(function (SpecificationGroupForm $form): void {
            $model = $form->getModel();
            if (! empty($this->getAdditionalDataForSaving())) {
                $model->fill($this->getAdditionalDataForSaving());
            }
            $form->save();
        });

        return $this
            ->httpResponse()
            ->withCreatedSuccessMessage()
            ->setPreviousRoute($this->getIndexRouteName())
            ->setNextRoute($this->getEditRouteName(), $form->getModel());
    }

    public function edit(string $group)
    {
        $group = $this->getSpecificationGroup($group);

        $this->pageTitle(trans('plugins/ecommerce::product-specification.specification_groups.edit.title', [
            'name' => $group->name,
        ]));

        return $this->getForm()::createFromModel($group)->renderForm();
    }

    public function update(SpecificationGroupRequest $request, string $group)
    {
        $group = $this->getSpecificationGroup($group);

        $form = $this->getForm()::createFromModel($group)->setRequest($request)->onlyValidatedData();
        $form->saving(function (SpecificationGroupForm $form): void {
            $model = $form->getModel();
            if (! empty($this->getAdditionalDataForSaving())) {
                $model->fill($this->getAdditionalDataForSaving());
            }
            $form->save();
        });

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage()
            ->setPreviousRoute($this->getIndexRouteName())
            ->setNextRoute($this->getEditRouteName(), $form->getModel());
    }

    public function destroy(string $group)
    {
        $group = $this->getSpecificationGroup($group);

        return DeleteResourceAction::make($group);
    }

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::product-specification.specification_groups.title'), route($this->getIndexRouteName()));
    }

    /**
     * @return class-string<TableAbstract>
     */
    protected function getTable(): string
    {
        return SpecificationGroupTable::class;
    }

    /**
     * @return class-string<FormAbstract>
     */
    protected function getForm(): string
    {
        return SpecificationGroupForm::class;
    }

    protected function getAdditionalDataForSaving(): array
    {
        return [];
    }

    protected function getIndexRouteName(): string
    {
        return 'ecommerce.specification-groups.index';
    }

    protected function getEditRouteName(): string
    {
        return 'ecommerce.specification-groups.edit';
    }

    protected function getSpecificationGroup(string $group)
    {
        return SpecificationGroup::query()->findOrFail($group);
    }
}
