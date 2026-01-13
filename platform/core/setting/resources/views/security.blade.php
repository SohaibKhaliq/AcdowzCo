@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core-setting::section
        :title="trans('core/setting::setting.security.title')"
        :description="trans('core/setting::setting.security.description')"
    >
        @if ($allSecure)
            <x-core::alert
                type="success"
                :title="trans('core/setting::setting.security.status_secure')"
                icon="ti ti-shield-check"
            >
            </x-core::alert>
        @else
            <x-core::alert
                type="warning"
                :title="trans('core/setting::setting.security.status_insecure')"
                icon="ti ti-shield-x"
            >
            </x-core::alert>
        @endif

        <div class="mt-4">
            <h4 class="mb-3">{{ trans('core/setting::setting.security.current_settings') }}</h4>

            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th class="w-1">{{ trans('core/setting::setting.security.status') }}</th>
                            <th>{{ trans('core/setting::setting.security.setting') }}</th>
                            <th>{{ trans('core/setting::setting.security.current_value') }}</th>
                            <th>{{ trans('core/setting::setting.security.recommended_value') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($settings as $key => $setting)
                            <tr>
                                <td class="text-center">
                                    @if ($setting['is_correct'])
                                        <span class="badge bg-success-lt">
                                            <x-core::icon name="ti ti-check" />
                                        </span>
                                    @else
                                        <span class="badge bg-danger-lt">
                                            <x-core::icon name="ti ti-x" />
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold">{{ $setting['label'] }}</div>
                                        <div class="text-muted small">{{ $setting['description'] }}</div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $value = is_bool($setting['value']) ? ($setting['value'] ? 'true' : 'false') : $setting['value'];
                                        $badgeClass = $setting['is_correct'] ? 'badge-outline text-success' : 'badge-outline text-warning';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $value }}</span>
                                </td>
                                <td>
                                    @php
                                        $recommended = is_bool($setting['recommended']) ? ($setting['recommended'] ? 'true' : 'false') : $setting['recommended'];
                                    @endphp
                                    <span class="badge badge-outline text-primary">{{ $recommended }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (! $isHttps)
                <div class="mt-3">
                    <x-core::alert
                        type="info"
                        :title="trans('core/setting::setting.security.https_warning')"
                    >
                        <x-slot:icon>
                            <x-core::icon name="ti ti-info-circle" />
                        </x-slot:icon>
                        <x-slot:subtitle>
                            {{ trans('core/setting::setting.security.https_warning_description') }}
                        </x-slot:subtitle>
                    </x-core::alert>
                </div>
            @endif
        </div>

        @if (!$allSecure)
        <div class="mt-4 pt-4 border-top">
            <h3 class="mb-3">
                <x-core::icon name="ti ti-settings" class="me-1" />
                {{ trans('core/setting::setting.security.how_to_fix') }}
            </h3>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ trans('core/setting::setting.security.env_file_location') }}</h4>
                    <div class="input-group mb-3">
                        <input
                            type="text"
                            id="env-path"
                            class="form-control font-monospace"
                            value="{{ $envPath }}"
                            readonly
                        >
                        <button
                            class="btn btn-outline-secondary"
                            data-bb-toggle="clipboard"
                            data-clipboard-target="#env-path"
                            data-clipboard-message="{{ trans('core/setting::setting.security.copied') }}"
                        >
                            <x-core::icon name="ti ti-copy" />
                            {{ trans('core/setting::setting.security.copy') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="card-title">{{ trans('core/setting::setting.security.add_to_env') }}</h4>
                    <div class="bg-dark text-white p-3 rounded font-monospace">
                        <pre class="mb-0 text-white" id="env-settings"># {{ trans('core/setting::setting.security.required_settings') }}
SESSION_HTTP_ONLY=true
ENABLE_HTTP_SECURITY_HEADERS=true

# {{ trans('core/setting::setting.security.for_https_sites') }}
@if ($isHttps)
SESSION_SECURE_COOKIE=true
@else
SESSION_SECURE_COOKIE=false
@endif</pre>
                    </div>
                    <button
                        class="btn btn-primary mt-3"
                        data-bb-toggle="clipboard"
                        data-clipboard-target="#env-settings"
                        data-clipboard-text="SESSION_HTTP_ONLY=true
ENABLE_HTTP_SECURITY_HEADERS=true
SESSION_SECURE_COOKIE={{ $isHttps ? 'true' : 'false' }}"
                        data-clipboard-message="{{ trans('core/setting::setting.security.copied') }}"
                    >
                        <x-core::icon name="ti ti-copy" />
                        {{ trans('core/setting::setting.security.copy_settings') }}
                    </button>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="card-title">
                        <x-core::icon name="ti ti-list-check" />
                        {{ trans('core/setting::setting.security.steps') }}
                    </h4>
                    <div class="list-group list-group-flush list-group-hoverable">
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="badge bg-blue-lt">1</span>
                                </div>
                                <div class="col text-truncate">
                                    {{ trans('core/setting::setting.security.step_1') }}
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="badge bg-blue-lt">2</span>
                                </div>
                                <div class="col text-truncate">
                                    {{ trans('core/setting::setting.security.step_2') }}
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="badge bg-blue-lt">3</span>
                                </div>
                                <div class="col text-truncate">
                                    {{ trans('core/setting::setting.security.step_3') }}
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="badge bg-blue-lt">4</span>
                                </div>
                                <div class="col text-truncate">
                                    {{ trans('core/setting::setting.security.step_4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="card-title">
                        <x-core::icon name="ti ti-shield-lock" />
                        {{ trans('core/setting::setting.security.security_headers_info') }}
                    </h4>
                    <p class="text-muted">{{ trans('core/setting::setting.security.security_headers_list') }}</p>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="badge badge-outline text-azure font-monospace">X-Content-Type-Options: nosniff</span>
                                    </td>
                                    <td class="text-muted">{{ trans('core/setting::setting.security.header_nosniff') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="badge badge-outline text-azure font-monospace">X-Frame-Options: SAMEORIGIN</span>
                                    </td>
                                    <td class="text-muted">{{ trans('core/setting::setting.security.header_frame') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="badge badge-outline text-azure font-monospace">X-XSS-Protection: 1; mode=block</span>
                                    </td>
                                    <td class="text-muted">{{ trans('core/setting::setting.security.header_xss') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="badge badge-outline text-azure font-monospace">Referrer-Policy: strict-origin-when-cross-origin</span>
                                    </td>
                                    <td class="text-muted">{{ trans('core/setting::setting.security.header_referrer') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <p class="border-top pt-4 text-sm mt-4">
                {!! BaseHelper::clean(trans('core/setting::setting.security.learn_more', [
                    'documentation' => sprintf(
                        '<a href="https://docs.botble.com/cms/security-cookies" target="_blank" class="hover:underline text-primary-500">%s</a>',
                        trans('core/setting::setting.security.documentation')
                    )
                ])) !!}
            </p>
        </div>
        @else
        <div class="mt-4 pt-4 border-top">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <x-core::icon name="ti ti-shield-lock" />
                        {{ trans('core/setting::setting.security.security_headers_info') }}
                    </h4>
                    <p class="text-muted">{{ trans('core/setting::setting.security.security_headers_list') }}</p>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="badge badge-outline text-azure font-monospace">X-Content-Type-Options: nosniff</span>
                                    </td>
                                    <td class="text-muted">{{ trans('core/setting::setting.security.header_nosniff') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="badge badge-outline text-azure font-monospace">X-Frame-Options: SAMEORIGIN</span>
                                    </td>
                                    <td class="text-muted">{{ trans('core/setting::setting.security.header_frame') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="badge badge-outline text-azure font-monospace">X-XSS-Protection: 1; mode=block</span>
                                    </td>
                                    <td class="text-muted">{{ trans('core/setting::setting.security.header_xss') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="badge badge-outline text-azure font-monospace">Referrer-Policy: strict-origin-when-cross-origin</span>
                                    </td>
                                    <td class="text-muted">{{ trans('core/setting::setting.security.header_referrer') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </x-core-setting::section>
@stop
