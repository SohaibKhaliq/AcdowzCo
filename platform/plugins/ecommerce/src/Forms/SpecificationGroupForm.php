<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Http\Requests\SpecificationGroupRequest;
use Botble\Ecommerce\Models\SpecificationGroup;

class SpecificationGroupForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(SpecificationGroup::class)
            ->setValidatorClass(SpecificationGroupRequest::class)
            ->add(
                'name',
                TextField::class,
                NameFieldOption::make()
                    ->required(),
            )
            ->add(
                'description',
                TextareaField::class,
                DescriptionFieldOption::make()
            );
    }
}
