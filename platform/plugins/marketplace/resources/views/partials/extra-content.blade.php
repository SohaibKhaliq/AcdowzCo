@if ($storeSocialLinks = MarketplaceHelper::getAllowedSocialLinks())
    <div class="col-lg-12">
        <div class="row">
            @foreach ($storeSocialLinks as $key => $item)
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label" for="social_links_{{ $key }}">{{ $item['title'] }}</label>
                        <div class="input-group mb-3">
                            @if (Arr::get($item, 'url'))
                                <span class="input-group-text px-2">{{ Arr::get($item, 'url') }}</span>
                            @endif
                            {!! Form::text('social_links[' . $key . ']', Arr::get($model->getMetaData('social_links', true) ?: [], $key, ''), [
                                'class' => 'form-control',
                                'placeholder' => Arr::get($item, 'placeholder', '{username}'),
                                'id' => 'social_links_' . $key,
                            ]) !!}
                            <span class="input-group-text">
                                <x-core::icon :name="'ti ti-brand-' . Arr::get($item, 'icon')" />
                        </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
