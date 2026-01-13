@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    <style>
        .dropzone {
            border: 2px dashed var(--primary-color);
        }
    </style>

    <div class="form__header">
        <h3>{{ SeoHelper::getTitleOnly() }}</h3>

        {!! $form->renderForm() !!}
    </div>
@endsection
