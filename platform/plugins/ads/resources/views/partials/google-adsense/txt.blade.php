@if (File::exists(public_path('ads.txt')))
    <div class="mt-2">
        <x-core::button
            type="button"
            color="danger"
            size="md"
            onclick="if (confirm('{{ trans('plugins/ads::ads.settings.confirm_delete_ads_txt') }}')) { 
                var form = this.closest('form');
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'google_adsense_ads_delete_txt';
                input.value = '1';
                form.appendChild(input);
                form.submit(); 
            }"
        >
            <x-core::icon name="ti ti-trash" /> {{ trans('plugins/ads::ads.settings.delete_ads_txt') }}
        </x-core::button>
        
        <small class="form-hint mt-2 d-block">
            {!! BaseHelper::clean(trans('plugins/ads::ads.settings.view_ads_txt', ['url' => Html::link(url('ads.txt'), attributes: ['target' => '_blank'])])) !!}
        </small>
    </div>
@endif
