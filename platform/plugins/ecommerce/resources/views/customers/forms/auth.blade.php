@php
    $icon = Arr::get($formOptions, 'icon');
    $heading = Arr::get($formOptions, 'heading');
    $description = Arr::get($formOptions, 'description');
    $bannerDirection = Arr::get($formOptions, 'bannerDirection', 'vertical');

    $banner = Arr::get($formOptions, 'banner');

    if (! $banner) {
        $bannerDirection = 'vertical';
    }
@endphp

@if (Arr::get($formOptions, 'has_wrapper', 'yes') === 'yes')
    <div class="container">
        <div @class(['row justify-content-center py-5'])>
            <div @class(['col-xl-6 col-lg-8' => $bannerDirection === 'vertical', 'col-lg-10' => $bannerDirection === 'horizontal'])>
                @endif
                <div @class(['auth-card', 'card' => $bannerDirection === 'vertical', 'auth-card__horizontal row' => $bannerDirection === 'horizontal'])>
                    @if ($banner)
                        @if ($bannerDirection === 'horizontal')
                            <div class="col-md-6 auth-card__left">
                        @endif
                            {{ RvMedia::image($banner, $heading ?: '', attributes: ['class' => 'auth-card__banner']) }}
                        @if ($bannerDirection === 'horizontal')
                        </div>
                        @endif
                    @endif

                    @if ($bannerDirection === 'horizontal')
                    <div class="col-md-6 auth-card__right">
                    @endif
                        @if ($icon || $heading || $description)
                            <div class="auth-card__header">
                                <div @class(['d-flex flex-column flex-md-row align-items-start gap-3' => $icon, 'text-center' => ! $icon])>
                                    @if ($icon)
                                        <div class="auth-card__header-icon bg-white p-3 rounded">
                                            <x-core::icon :name="$icon" class="text-primary" />
                                        </div>
                                    @endif
                                    <div>
                                        @if ($heading)
                                            <h3 class="auth-card__header-title fs-4 mb-1">{{ $heading }}</h3>
                                        @endif
                                        @if ($description)
                                            <p class="auth-card__header-description text-muted">{{ $description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="auth-card__body">
                            @if ($showStart)
                                {!! Form::open(Arr::except($formOptions, ['template'])) !!}
                            @endif

                            @if (session()->has('status'))
                                <div role="alert" class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @elseif (session()->has('auth_error_message'))
                                <div role="alert" class="alert alert-danger">
                                    {{ session('auth_error_message') }}
                                </div>
                            @elseif (session()->has('auth_success_message'))
                                <div role="alert" class="alert alert-success">
                                    {{ session('auth_success_message') }}
                                </div>
                            @elseif (session()->has('auth_warning_message'))
                                <div role="alert" class="alert alert-warning">
                                    {{ session('auth_warning_message') }}
                                </div>
                            @endif

                            @if ($showFields)
                                {{ $form->getOpenWrapperFormColumns() }}

                                @foreach ($fields as $field)
                                    @continue(in_array($field->getName(), $exclude))

                                    {!! $field->render() !!}
                                @endforeach

                                {{ $form->getCloseWrapperFormColumns() }}
                            @endif

                            @if ($showEnd)
                                {!! Form::close() !!}
                            @endif

                            @if ($form->getValidatorClass())
                                @push('footer')
                                    {!! $form->renderValidatorJs() !!}
                                @endpush
                            @endif
                        </div>

                    @if ($bannerDirection === 'horizontal')
                    </div>
                    @endif
                </div>
                @if (Arr::get($formOptions, 'has_wrapper', 'yes') === 'yes')
            </div>
        </div>
    </div>
@endif
