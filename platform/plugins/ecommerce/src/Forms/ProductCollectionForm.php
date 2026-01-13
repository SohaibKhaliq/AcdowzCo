<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Http\Requests\ProductCollectionRequest;
use Botble\Ecommerce\Models\ProductCollection;

class ProductCollectionForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addStylesDirectly('vendor/core/plugins/ecommerce/css/ecommerce.css')
            ->addScriptsDirectly('vendor/core/plugins/ecommerce/js/edit-product-collection.js');

        $this
            ->model(ProductCollection::class)
            ->setValidatorClass(ProductCollectionRequest::class)
            ->add(
                'name',
                TextField::class,
                NameFieldOption::make()
                    ->when($this->getModel()->slug, function (NameFieldOption $option, string $slug): void {
                        $option
                            ->helperText(trans('plugins/ecommerce::product-collections.slug_help_block', compact('slug')));
                    })
            )
            ->add('slug', 'text', [
                'label' => trans('core/base::forms.slug'),
                'required' => true,
                'attr' => [
                    'data-counter' => 120,
                ],
            ])
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->add(
                'is_featured',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('core/base::forms.is_featured'))
                    ->defaultValue(false)
            )
            ->add('image', MediaImageField::class, MediaImageFieldOption::make())
            ->setBreakFieldPoint('status');

        if ($productCollectionId = $this->getModel()->id) {
            $this
                ->addMetaBoxes([
                    'collection-products' => [
                        'title' => trans('plugins/ecommerce::products.name'),
                        'content' =>
                            Html::tag('div', '', [
                                'class' => 'wrap-collection-products',
                                'data-target' => route('product-collections.get-product-collection', $productCollectionId),
                            ]),
                        'priority' => 9999,
                    ],
            ]);
        }
    }
}
