<!-- Add License Code Modal -->
<div class="modal fade" id="add-license-code-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="add-license-code-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('plugins/ecommerce::products.license_codes.add') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <x-core::form.label for="license_code" :value="trans('plugins/ecommerce::products.license_codes.code')" />
                        <x-core::form.text-input name="license_code" id="license_code" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ trans('core/base::forms.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ trans('core/base::forms.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit License Code Modal -->
<div class="modal fade" id="edit-license-code-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit-license-code-form">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-license-code-id" name="id">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('plugins/ecommerce::products.license_codes.edit') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <x-core::form.label for="edit-license-code" :value="trans('plugins/ecommerce::products.license_codes.code')" />
                        <x-core::form.text-input name="license_code" id="edit-license-code" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ trans('core/base::forms.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ trans('core/base::forms.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Generate Modal -->
<div class="modal fade" id="bulk-generate-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="bulk-generate-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('plugins/ecommerce::products.license_codes.generate_modal.title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <x-core::form.label for="quantity" :value="trans('plugins/ecommerce::products.license_codes.generate_modal.quantity')" />
                        <x-core::form.text-input type="number" name="quantity" id="quantity" value="1" min="1" max="100" required />
                    </div>
                    <div class="mb-3">
                        <x-core::form.label for="license-code-format" :value="trans('plugins/ecommerce::products.license_codes.generate_modal.format')" />
                        <x-core::form.select name="format" id="license-code-format" required>
                            <option value="uuid">UUID (e.g., 550e8400-e29b-41d4-a716-446655440000)</option>
                            <option value="alphanumeric">Alphanumeric (e.g., ABC123DEF456)</option>
                            <option value="numeric">Numeric (e.g., 123456789012)</option>
                            <option value="custom">Custom Pattern</option>
                        </x-core::form.select>
                    </div>
                    <div class="mb-3" id="custom-pattern-group" style="display: none;">
                        <x-core::form.label for="pattern" :value="trans('plugins/ecommerce::products.license_codes.generate_modal.custom_pattern')" />
                        <x-core::form.text-input name="pattern" id="pattern" placeholder="e.g., PROD-####-####" />
                        <small class="form-text text-muted">
                            {{ trans('plugins/ecommerce::products.license_codes.generate_modal.pattern_help') }}
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ trans('core/base::forms.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ trans('plugins/ecommerce::products.license_codes.generate_modal.generate') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
