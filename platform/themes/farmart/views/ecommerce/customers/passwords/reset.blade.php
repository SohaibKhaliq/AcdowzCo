@php Theme::layout('full-width'); @endphp

{!! Theme::partial('page-header', ['size' => 'xl', 'withTitle' => false]) !!}

{!! $form->renderForm() !!}
