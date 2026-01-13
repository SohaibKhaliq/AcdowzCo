@extends('packages/data-synchronize::export')

@php
    $totalItems = 0;
    $counters = $exporter->getCounters();
    foreach ($counters as $counter) {
        if (str_contains(strtolower($counter->getLabel()), 'total')) {
            $value = str_replace(',', '', $counter->getValue());
            if (is_numeric($value) && $value > $totalItems) {
                $totalItems = (int) $value;
            }
        }
    }
    $isLargeExport = $totalItems > 10000;
    $isMediumExport = $totalItems > 1000 && $totalItems <= 10000;
@endphp

@push('header')
    <style>
        .export-recommendation-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .export-recommendation-item {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 6px;
        }



        .export-progress-indicator {
            display: none;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: #f8f9fa;
        }

        .export-progress-bar {
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            margin: 0.5rem 0;
        }

        .export-progress-fill {
            height: 100%;
            background: #0d6efd;
            border-radius: 3px;
            transition: width 0.3s ease;
            width: 0%;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @media (max-width: 768px) {
            .export-recommendation-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('export_extra_filters_after')
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <x-core::form.checkbox
                    name="use_chunked_export"
                    :value="1"
                    :label="trans('plugins/ecommerce::products.export.use_chunked_export')"
                    :checked="true"
                    :helper-text="trans('plugins/ecommerce::products.export.use_chunked_export_helper')"
                />
            </div>

            <div class="mb-3">
                <x-core::form.checkbox
                    name="optimize_memory"
                    :value="1"
                    :label="trans('plugins/ecommerce::products.export.optimize_memory')"
                    :checked="true"
                    :helper-text="trans('plugins/ecommerce::products.export.optimize_memory_helper')"
                />
            </div>

            <div class="mb-3">
                <x-core::form.checkbox
                    name="use_streaming"
                    :value="1"
                    :label="trans('plugins/ecommerce::products.export.use_streaming')"
                    :checked="$isLargeExport"
                    :helper-text="trans('plugins/ecommerce::products.export.use_streaming_helper')"
                />
            </div>

            @if($isLargeExport)
                <div class="alert alert-success d-block">
                    <strong>{{ trans('plugins/ecommerce::products.export.streaming_enabled_title') }}</strong><br>
                    <small>{{ trans('plugins/ecommerce::products.export.streaming_enabled_message') }}</small>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <x-core::form.checkbox
                    name="include_variations"
                    :value="1"
                    :label="trans('plugins/ecommerce::products.export.include_variations')"
                    :checked="true"
                    :helper-text="trans('plugins/ecommerce::products.export.include_variations_helper')"
                />
            </div>

            <x-core::form-group>
                <x-core::form.label for="chunk_size">{{ trans('plugins/ecommerce::products.export.chunk_size') }}</x-core::form.label>
                <x-core::form.text-input
                    type="number"
                    name="chunk_size"
                    id="chunk_size"
                    value="{{ $isLargeExport ? 300 : 400 }}"
                    min="50"
                    max="5000"
                />
                <x-core::form.helper-text>
                    {{ trans('plugins/ecommerce::products.export.chunk_size_helper') }}
                </x-core::form.helper-text>

                <div class="mt-2">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>{{ trans('plugins/ecommerce::products.export.recommended_range') }}</span>
                        <span id="chunk-recommendation">
                            @if($isLargeExport)
                                {{ trans('plugins/ecommerce::products.export.range_large_export') }}
                            @elseif($isMediumExport)
                                {{ trans('plugins/ecommerce::products.export.range_medium_export') }}
                            @else
                                {{ trans('plugins/ecommerce::products.export.range_small_export') }}
                            @endif
                        </span>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 60%"></div>
                    </div>
                </div>
            </x-core::form-group>
        </div>
    </div>

    <!-- Export Progress Indicator -->
    <div class="export-progress-indicator" id="export-progress">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="fw-medium">{{ trans('plugins/ecommerce::products.export.export_progress') }}</span>
            <span class="text-muted small" id="progress-text">{{ trans('plugins/ecommerce::products.export.preparing_export') }}</span>
        </div>
        <div class="export-progress-bar">
            <div class="export-progress-fill" id="progress-fill"></div>
        </div>
        <div class="d-flex justify-content-between mt-2 small text-muted">
            <span id="progress-items">{{ trans('plugins/ecommerce::products.export.items_processed', ['count' => 0]) }}</span>
            <span id="progress-time">{{ trans('plugins/ecommerce::products.export.estimated_time_calculating') }}</span>
        </div>
    </div>

    @if($isLargeExport)
        <x-core::alert type="warning" class="mb-4">
            <div>
                    <h5 class="mb-2">{{ trans('plugins/ecommerce::products.export.large_dataset_warning_title') }}</h5>
                    <p class="mb-3">{{ trans('plugins/ecommerce::products.export.large_dataset_specific_message', ['count' => number_format($totalItems)]) }}</p>

                    <div class="export-recommendation-grid">
                        <div class="export-recommendation-item bg-white">
                            <strong class="d-block">{{ trans('plugins/ecommerce::products.export.format_label') }}</strong>
                            <span class="text-muted small">{{ trans('plugins/ecommerce::products.export.csv_recommended') }}</span>
                        </div>

                        <div class="export-recommendation-item bg-white">
                            <strong class="d-block">{{ trans('plugins/ecommerce::products.export.chunk_label') }}</strong>
                            <span class="text-muted small">{{ trans('plugins/ecommerce::products.export.chunk_recommended') }}</span>
                        </div>

                        <div class="export-recommendation-item bg-white">
                            <strong class="d-block">{{ trans('plugins/ecommerce::products.export.time_label') }}</strong>
                            <span class="text-muted small">{{ trans('plugins/ecommerce::products.export.time_estimate') }}</span>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-3 mb-0">
                        <strong>{{ trans('plugins/ecommerce::products.export.pro_tip') }}</strong>
                        {{ trans('plugins/ecommerce::products.export.pro_tip_message') }}
                    </div>
            </div>
        </x-core::alert>
    @elseif($isMediumExport)
        <x-core::alert type="info" class="mb-4">
            <h6 class="mb-1">{{ trans('plugins/ecommerce::products.export.medium_dataset_detected') }}</h6>
            <p class="mb-0">{{ trans('plugins/ecommerce::products.export.medium_dataset_message', ['count' => number_format($totalItems)]) }}</p>
        </x-core::alert>
    @endif

@push('footer')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.data-synchronize-export-form');
            const chunkSizeInput = document.getElementById('chunk_size');
            const progressIndicator = document.getElementById('export-progress');
            const progressFill = document.getElementById('progress-fill');
            const progressText = document.getElementById('progress-text');
            const progressItems = document.getElementById('progress-items');
            const progressTime = document.getElementById('progress-time');
            const totalItems = {{ $totalItems }};
            const isLargeExport = {{ $isLargeExport ? 'true' : 'false' }};

            if (chunkSizeInput) {
                chunkSizeInput.addEventListener('input', function() {
                    const value = parseInt(this.value);
                    const recommendation = document.getElementById('chunk-recommendation');
                    const progressBar = this.parentElement.nextElementSibling.querySelector('.progress-bar');

                    let recommendedMin, recommendedMax, color, width;

                    if (isLargeExport) {
                        recommendedMin = 200;
                        recommendedMax = 500;
                    } else if (totalItems > 1000) {
                        recommendedMin = 300;
                        recommendedMax = 800;
                    } else {
                        recommendedMin = 500;
                        recommendedMax = 1000;
                    }

                    if (value >= recommendedMin && value <= recommendedMax) {
                        color = 'bg-success';
                        width = '80%';
                        recommendation.textContent = '{{ trans('plugins/ecommerce::products.export.optimal_range') }}';
                    } else if (value < recommendedMin) {
                        color = 'bg-warning';
                        width = '40%';
                        recommendation.textContent = '{{ trans('plugins/ecommerce::products.export.too_small_slow') }}';
                    } else {
                        color = 'bg-danger';
                        width = '20%';
                        recommendation.textContent = '{{ trans('plugins/ecommerce::products.export.too_large_timeouts') }}';
                    }

                    progressBar.className = `progress-bar ${color}`;
                    progressBar.style.width = width;
                });
            }

            @if($isLargeExport)
                const excelRadio = document.querySelector('input[name="format"][value="xlsx"]');
                const csvRadio = document.querySelector('input[name="format"][value="csv"]');

                if (excelRadio && csvRadio) {
                    excelRadio.disabled = true;

                    const excelLabel = excelRadio.closest('label');
                    if (excelLabel) {
                        excelLabel.style.opacity = '0.8';
                        excelLabel.style.cursor = 'not-allowed';
                        excelLabel.style.position = 'relative';

                        const overlay = document.createElement('div');
                        overlay.style.cssText = `
                            pointer-events: none;
                        `;
                        excelLabel.appendChild(overlay);

                        const warningText = document.createElement('small');
                        warningText.className = 'text-warning d-block mt-1';
                        warningText.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>{{ trans('plugins/ecommerce::products.export.excel_disabled_warning', ['count' => number_format($totalItems)]) }}';
                        excelLabel.appendChild(warningText);
                    }

                    const wrapper = excelRadio.closest('.form-check-group');
                    if (wrapper) {
                        wrapper.style.display = 'flex';
                        wrapper.style.alignItems = 'flex-start';
                    }

                    csvRadio.checked = true;
                    csvRadio.dispatchEvent(new Event('change', { bubbles: true }));

                    const streamingCheckbox = document.querySelector('input[name="use_streaming"]');
                    const chunkedCheckbox = document.querySelector('input[name="use_chunked_export"]');
                    const memoryCheckbox = document.querySelector('input[name="optimize_memory"]');

                    if (streamingCheckbox) streamingCheckbox.checked = true;
                    if (chunkedCheckbox) chunkedCheckbox.checked = true;
                    if (memoryCheckbox) memoryCheckbox.checked = true;
                }
            @endif



            form.addEventListener('submit', function(e) {
                const submitButton = form.querySelector('button[type="submit"]');

                progressIndicator.style.display = 'block';
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ trans('plugins/ecommerce::products.export.starting_export') }}';

                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += Math.random() * 10;
                    if (progress > 90) progress = 90;

                    progressFill.style.width = progress + '%';
                    progressText.textContent = progress < 30 ? '{{ trans('plugins/ecommerce::products.export.preparing_export') }}' :
                                             progress < 60 ? '{{ trans('plugins/ecommerce::products.export.processing_data') }}' :
                                             '{{ trans('plugins/ecommerce::products.export.finalizing_export') }}';

                    const processedItems = Math.floor((progress / 100) * totalItems);
                    progressItems.textContent = `${processedItems.toLocaleString()} {{ trans('plugins/ecommerce::products.export.items_processed_suffix') }}`;

                    const estimatedTime = Math.max(0, Math.floor((100 - progress) * 2));
                    progressTime.textContent = `{{ trans('plugins/ecommerce::products.export.estimated_time_prefix') }} ${estimatedTime}{{ trans('plugins/ecommerce::products.export.seconds_remaining') }}`;
                }, 1000);

                window.addEventListener('beforeunload', () => {
                    clearInterval(progressInterval);
                });

                setTimeout(() => {
                    progressFill.style.width = '100%';
                    progressText.textContent = '{{ trans('plugins/ecommerce::products.export.export_completed') }}';
                    progressItems.textContent = `${totalItems.toLocaleString()} {{ trans('plugins/ecommerce::products.export.items_processed_suffix') }}`;
                    progressTime.textContent = '{{ trans('plugins/ecommerce::products.export.download_starting') }}';
                    clearInterval(progressInterval);
                }, 10000);
            });

            const originalRestore = window.restoreFormValues;
            window.restoreFormValues = function() {
                if (originalRestore) {
                    originalRestore();
                }

                @if($isLargeExport)
                    const csvRadio = document.querySelector('input[name="format"][value="csv"]');
                    const streamingCheckbox = document.querySelector('input[name="use_streaming"]');
                    const chunkedCheckbox = document.querySelector('input[name="use_chunked_export"]');
                    const memoryCheckbox = document.querySelector('input[name="optimize_memory"]');

                    if (csvRadio) {
                        csvRadio.checked = true;
                    }

                    if (streamingCheckbox) {
                        streamingCheckbox.checked = true;
                        streamingCheckbox.value = '1';
                    }
                    
                    if (chunkedCheckbox) {
                        chunkedCheckbox.checked = true;
                        chunkedCheckbox.value = '1';
                    }

                    if (memoryCheckbox) {
                        memoryCheckbox.checked = true;
                        memoryCheckbox.value = '1';
                    }
                @endif
            };

            const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                tooltipElements.forEach(el => new bootstrap.Tooltip(el));
            }
        });
    </script>
@endpush
@stop