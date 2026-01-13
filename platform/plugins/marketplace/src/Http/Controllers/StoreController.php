<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Supports\Breadcrumb;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Forms\PayoutInformationForm;
use Botble\Marketplace\Forms\StoreForm;
use Botble\Marketplace\Forms\TaxInformationForm;
use Botble\Marketplace\Http\Requests\PayoutInformationSettingRequest;
use Botble\Marketplace\Http\Requests\StoreRequest;
use Botble\Marketplace\Http\Requests\TaxInformationSettingRequest;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Tables\StoreTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/marketplace::store.name'), route('marketplace.store.index'));
    }

    public function index(StoreTable $table)
    {
        $this->pageTitle(trans('plugins/marketplace::store.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/marketplace::store.create'));

        return view('plugins/marketplace::stores.form', [
            'store' => new Store(),
            'form' => StoreForm::create()
                ->setUrl(route('marketplace.store.create'))
                ->renderForm(),
        ]);
    }

    public function store(StoreRequest $request)
    {
        $form = StoreForm::create()
            ->setRequest($request);

        $form->save();

        $store = $form->getModel();

        if ($request->has('social_links')) {
            if ($socialLinks = $request->input('social_links', [])) {
                $socials = array_keys(MarketplaceHelper::getAllowedSocialLinks());
                $socialLinks = collect($socialLinks)->only($socials)->filter();
                MetaBox::saveMetaBoxData($store, 'social_links', $socialLinks);
            }
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('marketplace.store.index'))
            ->setNextUrl(route('marketplace.store.edit', $store->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(Store $store, Request $request)
    {
        $form = StoreForm::createFromModel($store)
            ->setUrl(route('marketplace.store.update', $store->getKey()))
            ->renderForm();

        $taxInformationForm = null;
        $payoutInformationForm = null;

        if ($store->customer->is_vendor) {
            $taxInformationForm = TaxInformationForm::createFromModel($store->customer)
                ->setUrl(route('marketplace.store.update-tax-info', $store))
                ->renderForm();

            $payoutInformationForm = PayoutInformationForm::createFromModel($store->customer)
                ->setUrl(route('marketplace.store.update-payout-info', $store))
                ->renderForm();
        }

        event(new BeforeEditContentEvent($request, $store));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $store->name]));

        return view(
            'plugins/marketplace::stores.form',
            compact('store', 'form', 'taxInformationForm', 'payoutInformationForm')
        );
    }

    public function update(Store $store, StoreRequest $request)
    {
        StoreForm::createFromModel($store)
            ->setRequest($request)
            ->save();

        if ($request->has('social_links')) {
            if ($socialLinks = $request->input('social_links', [])) {
                $socials = array_keys(MarketplaceHelper::getAllowedSocialLinks());
                $socialLinks = collect($socialLinks)->only($socials)->filter();
                MetaBox::saveMetaBoxData($store, 'social_links', $socialLinks);
            }
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('marketplace.store.index'))
            ->withUpdatedSuccessMessage();
    }

    public function updateTaxInformation(Store $store, TaxInformationSettingRequest $request)
    {
        $customer = $store->customer;

        if ($customer && $customer->id) {
            $customer->vendorInfo->update($request->validated());
        }

        event(new UpdatedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $store));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('marketplace.store.index'))
            ->withUpdatedSuccessMessage();
    }

    public function updatePayoutInformation(Store $store, PayoutInformationSettingRequest $request)
    {
        $customer = $store->customer;

        if ($customer && $customer->id) {
            $vendorInfo = $customer->vendorInfo;
            $vendorInfo->payout_payment_method = $request->input('payout_payment_method');
            $vendorInfo->bank_info = $request->input('bank_info', []);
            $vendorInfo->save();
        }

        event(new UpdatedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $store));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('marketplace.store.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Store $store)
    {
        return DeleteResourceAction::make($store);
    }

    public function verify(int|string $id, Request $request)
    {
        $store = Store::query()->findOrFail($id);

        if ($store->is_verified) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/marketplace::store.already_verified'));
        }

        $store->is_verified = true;
        $store->verified_at = Carbon::now();
        $store->verified_by = Auth::id();
        $store->verification_note = $request->input('verification_note');
        $store->save();

        // Send email notification to store owner
        if ($store->email || $store->customer->email) {
            $verifiedBy = Auth::user();

            EmailHandler::setModule(MARKETPLACE_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'store_name' => $store->name,
                    'store_phone' => $store->phone,
                    'store_address' => $store->address,
                    'store_url' => $store->url,
                    'verified_by' => $verifiedBy->name,
                    'verified_at' => $store->verified_at->format('Y-m-d H:i:s'),
                    'verification_note' => $store->verification_note ?: '',
                ])
                ->sendUsingTemplate('store-verified', $store->email ?: $store->customer->email);
        }

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/marketplace::store.verify_success', ['name' => $store->name]));
    }

    public function unverify(int|string $id, Request $request)
    {
        $store = Store::query()->findOrFail($id);

        if (! $store->is_verified) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/marketplace::store.already_unverified'));
        }

        $store->is_verified = false;
        $store->verified_at = null;
        $store->verified_by = null;
        $store->verification_note = $request->input('verification_note');
        $store->save();

        // Send email notification to store owner
        if ($store->email || $store->customer->email) {
            $unverifiedBy = Auth::user();

            EmailHandler::setModule(MARKETPLACE_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'store_name' => $store->name,
                    'store_phone' => $store->phone,
                    'store_address' => $store->address,
                    'store_url' => $store->url,
                    'unverified_by' => $unverifiedBy->name,
                    'unverified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'verification_note' => $store->verification_note ?: '',
                ])
                ->sendUsingTemplate('store-unverified', $store->email ?: $store->customer->email);
        }

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/marketplace::store.unverify_success', ['name' => $store->name]));
    }
}
