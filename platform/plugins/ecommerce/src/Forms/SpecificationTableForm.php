<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Http\Requests\SpecificationTableRequest;
use Botble\Ecommerce\Models\SpecificationGroup;
use Botble\Ecommerce\Models\SpecificationTable;

class SpecificationTableForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScripts('jquery-ui');

        $groups = SpecificationGroup::query()->pluck('name', 'id');

        $this
            ->model(SpecificationTable::class)
            ->setValidatorClass(SpecificationTableRequest::class)
            ->add(
                'name',
                TextField::class,
                NameFieldOption::make()
                    ->label(trans('plugins/ecommerce::product-specification.specification_tables.fields.name'))
                    ->required(),
            )
            ->add(
                'description',
                TextareaField::class,
                DescriptionFieldOption::make()
            )
            ->when($groups->isNotEmpty(), function (FormAbstract $form) use ($groups): void {
                $form->add(
                    'groups',
                    HtmlField::class,
                    HtmlFieldOption::make()
                        ->content(view('plugins/ecommerce::specification-tables.groups', [
                            'groups' => $groups,
                            'selectedGroups' => $this->getModel() ? $this->getModel()->groups : collect(),
                        ])->render())
                );
            });
    }
}
