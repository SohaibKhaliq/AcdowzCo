<?php

namespace Botble\Marketplace\Services;

use Botble\Base\Supports\Pdf;
use Botble\Ecommerce\Facades\InvoiceHelper;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Supports\TwigExtension;
use Botble\Marketplace\Enums\PayoutPaymentMethodsEnum;
use Botble\Marketplace\Enums\WithdrawalStatusEnum;
use Botble\Marketplace\Models\Withdrawal;
use Botble\Media\Facades\RvMedia;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class GeneratePayoutInvoiceService
{
    public function __construct(
        protected ?Withdrawal $withdrawal = null
    ) {
    }

    public function withdrawal(Withdrawal $withdrawal): self
    {
        $this->withdrawal = $withdrawal;

        return $this;
    }

    public function stream($fileName = 'document.pdf'): Response
    {
        return $this->generateInvoice()->stream($fileName);
    }

    public function download($fileName = 'document.pdf'): Response
    {
        return $this->generateInvoice()->download($fileName);
    }

    protected function generateInvoice(): Pdf
    {
        return (new Pdf())
            ->templatePath($this->getTemplatePath())
            ->destinationPath($this->getCustomizedTemplatePath())
            ->supportLanguage(InvoiceHelper::getLanguageSupport())
            ->paperSizeA4()
            ->data($this->getInvoiceData())
            ->twigExtensions([
                new TwigExtension(),
            ])
            ->setProcessingLibrary(get_ecommerce_setting('invoice_processing_library', 'dompdf'));
    }

    protected function getInvoiceData(): array
    {
        $country = $this->getCompanyCountry();
        $state = $this->getCompanyState();
        $city = $this->getCompanyCity();

        $logo = get_ecommerce_setting('company_logo_for_invoicing') ?: (theme_option('logo_in_invoices') ?: Theme::getLogo());

        $companyName = get_ecommerce_setting('company_name_for_invoicing') ?: get_ecommerce_setting('store_name');
        $companyAddress = get_ecommerce_setting('company_address_for_invoicing');
        $companyPhone = get_ecommerce_setting('company_phone_for_invoicing') ?: get_ecommerce_setting('store_phone');
        $companyEmail = get_ecommerce_setting('company_email_for_invoicing') ?: get_ecommerce_setting('store_email');
        $companyTaxId = get_ecommerce_setting('company_tax_id_for_invoicing') ?: get_ecommerce_setting('store_vat_number');

        if (! $companyAddress) {
            $companyAddress = implode(', ', array_filter([ // @phpstan-ignore-line
                get_ecommerce_setting('company_address_for_invoicing', get_ecommerce_setting('store_address')),
                $city,
                $state,
                $country,
            ]));
        }

        return [
            'company' => [
                'logo' => $logo ? RvMedia::getRealPath($logo) : null,
                'name' => $companyName,
                'address' => $companyAddress,
                'state' => $state,
                'city' => $city,
                'zipcode' => $this->getCompanyZipCode(),
                'phone' => $companyPhone,
                'email' => $companyEmail,
                'tax_id' => $companyTaxId,
            ],
            'withdrawal' => $this->withdrawal,
            'withdrawal_fee_percentage' => round($this->withdrawal->fee / $this->withdrawal->amount * 100, 2),
            'withdrawal_status' => $this->withdrawal->status->label(),
            'withdrawal_payment_channel' => $this->withdrawal->payment_channel->label(),
        ];
    }

    public function preview(): Response
    {
        $withdrawal = new Withdrawal([
            'amount' => 100,
            'fee' => 10,
            'status' => WithdrawalStatusEnum::COMPLETED,
            'payment_channel' => PayoutPaymentMethodsEnum::BANK_TRANSFER,
            'description' => 'This is a test withdrawal',
            'bank_info' => json_decode('{"name":"Rowena Von","number":"+16694299919","full_name":"Ashlynn Rowe","description":"Ervin Stanton"}'),
        ]);

        $customer = new Customer([
            'name' => 'John Doe',
        ]);

        $withdrawal->customer()->associate($customer);
        $withdrawal->id = 1;
        $withdrawal->created_at = Carbon::now();

        $this->withdrawal($withdrawal);

        return $this->stream();
    }

    public function getContent(): string
    {
        return (new Pdf())
            ->twigExtensions([
                new TwigExtension(),
            ])
            ->getContent($this->getTemplatePath(), $this->getCustomizedTemplatePath());
    }

    public function getTemplatePath(): string
    {
        return plugin_path('marketplace/resources/templates/payout-invoice.tpl');
    }

    public function getCustomizedTemplatePath(): string
    {
        return storage_path('app/templates/marketplace/payout-invoice.tpl');
    }

    public function getVariables(): array
    {
        return [
            'company.logo' => trans('plugins/marketplace::withdrawal.invoice.variables.company_logo'),
            'company.name' => trans('plugins/marketplace::withdrawal.invoice.variables.company_name'),
            'company.address' => trans('plugins/marketplace::withdrawal.invoice.variables.company_address'),
            'company.state' => trans('plugins/marketplace::withdrawal.invoice.variables.company_state'),
            'company.city' => trans('plugins/marketplace::withdrawal.invoice.variables.company_city'),
            'company.zipcode' => trans('plugins/marketplace::withdrawal.invoice.variables.company_zipcode'),
            'company.phone' => trans('plugins/marketplace::withdrawal.invoice.variables.company_phone'),
            'company.email' => trans('plugins/marketplace::withdrawal.invoice.variables.company_email'),
            'company.tax_id' => trans('plugins/marketplace::withdrawal.invoice.variables.company_tax_id'),
            'withdrawal.id' => trans('plugins/marketplace::withdrawal.invoice.variables.withdrawal_id'),
            'withdrawal.created_at' => trans('plugins/marketplace::withdrawal.invoice.variables.withdrawal_created_at'),
            'withdrawal.customer.name' => trans('plugins/marketplace::withdrawal.invoice.variables.withdrawal_customer_name'),
            'withdrawal.payment_channel' => trans('plugins/marketplace::withdrawal.invoice.variables.withdrawal_payment_channel'),
            'withdrawal.amount' => trans('plugins/marketplace::withdrawal.invoice.variables.withdrawal_amount'),
            'withdrawal.fee' => trans('plugins/marketplace::withdrawal.invoice.variables.withdrawal_fee'),
            'withdrawal_fee_percentage' => trans('plugins/marketplace::withdrawal.invoice.variables.withdrawal_fee_percentage'),
            'withdrawal_status' => trans('plugins/marketplace::withdrawal.invoice.variables.withdrawal_status'),
            'withdrawal.description' => trans('plugins/marketplace::withdrawal.invoice.variables.withdrawal_description'),
            'withdrawal.bank_info.name' => trans('plugins/marketplace::withdrawal.invoice.variables.withdrawal_bank_info_name'),
            'withdrawal.bank_info.number' => trans('plugins/marketplace::withdrawal.invoice.variables.withdrawal_bank_info_number'),
            'withdrawal.bank_info.full_name' => trans('plugins/marketplace::withdrawal.invoice.variables.withdrawal_bank_info_full_name'),
        ];
    }

    public function getCompanyCountry(): ?string
    {
        return get_ecommerce_setting('company_country_for_invoicing', get_ecommerce_setting('store_country'));
    }

    public function getCompanyState(): ?string
    {
        return get_ecommerce_setting('company_state_for_invoicing', get_ecommerce_setting('store_state'));
    }

    public function getCompanyCity(): ?string
    {
        return get_ecommerce_setting('company_city_for_invoicing', get_ecommerce_setting('store_city'));
    }

    public function getCompanyZipCode(): ?string
    {
        return get_ecommerce_setting('company_zipcode_for_invoicing', get_ecommerce_setting('store_zip_code'));
    }
}
