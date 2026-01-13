@if (! empty($pageName))
    <div class="bb-product-listing-page-description">
        <div class="bb-block__header">
            <h1 class="h1">{{ $pageName }}</h1>
        </div>

        @if (! empty($pageDescription))
            <div class="bb-block__content ck-content">
                {!! BaseHelper::clean($pageDescription) !!}
            </div>
        @endif
    </div>
@else
    <div class="bb-product-listing-page-description"></div>
@endif
