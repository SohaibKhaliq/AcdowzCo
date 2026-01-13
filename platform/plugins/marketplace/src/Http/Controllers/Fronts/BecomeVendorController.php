<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Forms\Fronts\BecomeVendorForm;
use Botble\Marketplace\Http\Controllers\BaseController;
use Botble\Marketplace\Http\Requests\Fronts\BecomeVendorRequest;
use Botble\Marketplace\Models\Store;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Slug\Facades\SlugHelper;
use Botble\Slug\Models\Slug;
use Botble\Theme\Facades\Theme;
use Closure;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BecomeVendorController extends BaseController
{
    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            abort_unless(MarketplaceHelper::isVendorRegistrationEnabled(), 404);

            return $next($request);
        });

        $version = get_cms_version();

        Theme::asset()
            ->add('customer-style', 'vendor/core/plugins/ecommerce/css/customer.css', ['bootstrap-css'], version: $version);

        Theme::asset()
            ->container('footer')
            ->add('ecommerce-utilities-js', 'vendor/core/plugins/ecommerce/js/utilities.js', ['jquery'], version: $version)
            ->add('cropper-js', 'vendor/core/plugins/ecommerce/libraries/cropper.js', ['jquery'], version: $version)
            ->add('avatar-js', 'vendor/core/plugins/ecommerce/js/avatar.js', ['jquery'], version: $version);
    }

    public function index()
    {
        $customer = auth('customer')->user();

        SeoHelper::setTitle(__('Become Vendor'));

        Theme::breadcrumb()
            ->add(__('Become Vendor'), route('marketplace.vendor.become-vendor'));

        if ($customer->is_vendor) {
            $store = $customer->store;

            if (
                MarketplaceHelper::getSetting('requires_vendor_documentations_verification', true)
                && (! $store->certificate_file || ! $store->government_id_file)
            ) {
                $storeInfo = [
                    'shop_name' => $store->name,
                    'shop_url' => $store->slug,
                    'shop_phone' => $store->phone,
                ];

                $form = BecomeVendorForm::createFromArray($storeInfo)
                    ->addBefore(
                        'shop_name',
                        'missing_documentation_alert',
                        HtmlField::class,
                        HtmlFieldOption::make()
                            ->content('<div class="alert alert-warning">' . __('Missing documentations! Please upload your certificate of incorporation and government ID to continue.') . '</div>')
                    )
                    ->setUrl(route('marketplace.vendor.become-vendor.update'))
                    ->setMethod('PUT');

                return Theme::scope('marketplace.become-vendor', compact('form'), MarketplaceHelper::viewPath('become-vendor', false))
                    ->render();
            }

            if (MarketplaceHelper::getSetting('verify_vendor', 1) && ! $customer->vendor_verified_at) {
                return Theme::scope('marketplace.approving-vendor', compact('store'), MarketplaceHelper::viewPath('approving-vendor', false))
                    ->render();
            }

            return redirect()->route('marketplace.vendor.dashboard');
        }

        $form = BecomeVendorForm::create();

        return Theme::scope('marketplace.become-vendor', compact('form'), MarketplaceHelper::viewPath('become-vendor', false))
            ->render();
    }

    public function store(BecomeVendorRequest $request)
    {
        $customer = auth('customer')->user();

        abort_if($customer->is_vendor, 404);

        $existing = SlugHelper::getSlug($request->input('shop_url'), SlugHelper::getPrefix(Store::class));

        if ($existing) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Shop URL is existing. Please choose another one!'));
        }

        event(new Registered($customer));

        return $this
            ->httpResponse()
            ->setData([
                'redirect_url' => route('marketplace.vendor.dashboard'),
            ])
            ->setMessage(__('Registered successfully!'));
    }

    public function update(BecomeVendorRequest $request)
    {
        $customer = auth('customer')->user();

        abort_if($customer->is_vendor
        && (! MarketplaceHelper::getSetting('requires_vendor_documentations_verification', true)
            || ($customer->certificate_of_incorporation && $customer->government_id)), 404);

        $store = $customer->store;

        $store->name = $request->input('shop_name');
        $store->phone = $request->input('shop_phone');

        Slug::query()->updateOrCreate([
            'reference_type' => Store::class,
            'reference_id' => $store->id,
            'key' => Str::slug($request->input('shop_url')),
            'prefix' => SlugHelper::getPrefix(Store::class),
        ]);

        $storage = Storage::disk('local');

        if (! $storage->exists("marketplace/$store->slug")) {
            $storage->makeDirectory("marketplace/$store->slug");
        }

        if ($certificateFile = $request->file('certificate_file')) {
            $certificateFilePath = $storage->putFileAs("marketplace/$store->slug", $certificateFile, 'certificate.' . $certificateFile->getClientOriginalExtension());
            $store->certificate_file = $certificateFilePath;
        }

        if ($governmentIdFile = $request->file('government_id_file')) {
            $governmentIdFilePath = $storage->putFileAs("marketplace/$store->slug", $governmentIdFile, 'government_id.' . $governmentIdFile->getClientOriginalExtension());
            $store->government_id_file = $governmentIdFilePath;
        }

        $store->save();

        return $this
            ->httpResponse()
            ->setData([
                'redirect_url' => route('marketplace.vendor.become-vendor'),
            ])
            ->setMessage(__('Updated registration info successfully!'));
    }

    public function downloadCertificate()
    {
        $customer = auth('customer')->user();

        abort_if(! $customer->is_vendor || ! $customer->store, 404);

        $storage = Storage::disk('local');

        $certificate = $customer->store->certificate_file;

        if (! $storage->exists($certificate)) {
            return BaseHttpResponse::make()
                ->setError()
                ->setMessage(__('File not found!'));
        }

        return response()->file($storage->path($certificate));
    }

    public function downloadGovernmentId()
    {
        $customer = auth('customer')->user();

        abort_if(! $customer->is_vendor || ! $customer->store, 404);

        $storage = Storage::disk('local');

        $governmentId = $customer->store->government_id_file;

        if (! $storage->exists($governmentId)) {
            return BaseHttpResponse::make()
                ->setError()
                ->setMessage(__('File not found!'));
        }

        return response()->file($storage->path($governmentId));
    }
}
