<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Rules\EmailRule;
use Botble\Base\Rules\OnOffRule;
use Botble\Ecommerce\Facades\InvoiceHelper;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class InvoiceSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'company_name_for_invoicing' => ['nullable', 'string', 'max:120'],
            'company_address_for_invoicing' => ['nullable', 'string', 'max:255'],
            'company_country_for_invoicing' => ['nullable', 'string', 'max:120'],
            'company_state_for_invoicing' => ['nullable', 'string', 'max:120'],
            'company_city_for_invoicing' => ['nullable', 'string', 'max:120'],
            'company_email_for_invoicing' => ['nullable', new EmailRule()],
            'company_phone_for_invoicing' => 'sometimes|' . BaseHelper::getPhoneValidationRule(),
            'company_tax_id_for_invoicing' => ['nullable', 'string', 'max:120'],
            'company_logo_for_invoicing' => ['nullable', 'string', 'max:255'],
            'using_custom_font_for_invoice' => $onOffRule = new OnOffRule(),
            'invoice_language_support' => ['sometimes', 'string'],
            'enable_invoice_stamp' => $onOffRule,
            'invoice_code_prefix' => ['nullable', 'string', 'max:120'],
            'disable_order_invoice_until_order_confirmed' => $onOffRule,
            'invoice_font_family' => ['nullable', Rule::in(BaseHelper::getFonts())],
            'invoice_date_format' => [Rule::in(InvoiceHelper::supportedDateFormats())],
            'invoice_processing_library' => ['nullable', Rule::in(['dompdf', 'mpdf'])],
        ];
    }
}
