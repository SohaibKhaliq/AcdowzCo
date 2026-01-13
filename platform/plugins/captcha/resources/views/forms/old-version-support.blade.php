@php
    // Check if this is a FormAbstract-based form that would have fields added via beforeRendering hook
    $isFormAbstract = $form && class_exists($form) && is_subclass_of($form, \Botble\Base\Forms\FormAbstract::class);
@endphp

@if (! $isFormAbstract)
    @if (Captcha::isEnabled() && (! $form || Captcha::formSetting($form, 'enable_recaptcha', 1)))
        <div style="margin-top: 10px; margin-bottom: 10px">
            {!! Captcha::display() !!}
        </div>
    @endif

    @if (Captcha::mathCaptchaEnabled() && (! $form || Captcha::formSetting($form, 'enable_math_captcha', 0)))
        <div style="margin-top: 10px; margin-bottom: 10px">
            <label class="form-label" for="math-group">{{ app('math-captcha')->label() }}</label>
            {!! app('math-captcha')->input([
                'class' => 'form-control',
                'id' => 'math-group',
                'placeholder' => app('math-captcha')->getMathLabelOnly() . ' = ?',
            ]) !!}
        </div>
    @endif
@endif
