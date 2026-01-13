<div class="{{ $cssClass ?? 'ms-md-5 ps-md-5' }}">
    <h2>{{ __('Drop Us A Line') }}</h2>

    {!!
        $form
            ->setFormOption('class', 'mt-5 contact-form')
            ->setFormInputClass('form-control')
            ->setFormLabelClass('d-none sr-only')
            ->modify(
                'submit',
                'submit',
                Botble\Base\Forms\FieldOptions\ButtonFieldOption::make()
                    ->cssClass('btn btn-primary')
                    ->label(__('Send message'))
                    ->wrapperAttributes(['class' => 'mt-4'])
                    ->toArray(),
                true
            )
            ->renderForm()
    !!}
</div>
