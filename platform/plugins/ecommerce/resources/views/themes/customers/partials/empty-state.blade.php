<div class="bb-empty">
    <div class="text-center py-5">
        <!-- Empty State Icon -->
        <div class="bb-empty-img mb-4">
            <div class="bg-light rounded-circle p-4 d-inline-flex">
                <x-plugins-ecommerce::empty-state />
            </div>
        </div>

        <!-- Empty State Content -->
        <div class="bb-empty-content">
            <h3 class="bb-empty-title h5 mb-2">{{ $title }}</h3>
            <p class="bb-empty-subtitle text-muted mb-4">
                {{ $subtitle }}
            </p>

            <!-- Empty State Action -->
            @if (isset($actionUrl) && isset($actionLabel))
                <div class="bb-empty-action">
                    <a href="{{ $actionUrl }}" class="btn btn-primary">
                        <x-core::icon name="ti ti-plus" class="me-1" />
                        {{ $actionLabel }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
