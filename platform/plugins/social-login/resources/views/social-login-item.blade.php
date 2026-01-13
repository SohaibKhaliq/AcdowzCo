<li>
    <a
        class="{{ $social }}"
        data-bs-toggle="tooltip"
        data-bs-title="{{ $label = trans('plugins/social-login::social-login.sign_in_with', ['provider' => trans('plugins/social-login::social-login.socials.' . $social)]) }}"
        title="{{ $label }}"
        href="{{ $url }}"
    >
        @php
            $iconName = $social;
            if ($social === 'linkedin-openid') {
                $iconName = 'linkedin';
            } elseif ($social === 'x') {
                $iconName = 'x';
            }
        @endphp

        <x-core::icon name="ti ti-brand-{{ $iconName }}" />
        <span>{{ $label }}</span>
    </a>
</li>
