<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Enums\ProductLicenseCodeStatusEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductLicenseCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Controller for managing license codes for digital products.
 *
 * This controller handles CRUD operations for product license codes and ensures that:
 * - Only digital products can have license codes
 * - License codes feature is globally enabled
 * - The product has license code generation enabled
 * - Both main products and variations can have their own license codes
 * - Proper validation for different license code types (auto_generate vs pick_from_list)
 *
 * Note: License codes can be managed at both the main product level and individual
 * variation level. When a customer purchases a specific variation, the license code
 * is assigned from that variation's pool if available, otherwise from the main product.
 *
 * Breadcrumb structure:
 * Dashboard > Products > Edit Product Name > License Code Management
 */
class ProductLicenseCodeController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::products.name'), route('products.index'));
    }

    /**
     * Add product-specific breadcrumb items for license code management.
     *
     * @param Product $product
     * @return void
     */
    protected function addProductLicenseCodeBreadcrumb(Product $product): void
    {
        if ($product->is_variation) {
            // For variations, show the main product first, then the variation
            $mainProduct = $product->variationInfo?->configurableProduct;
            if ($mainProduct) {
                $this->breadcrumb()
                    ->add(
                        trans('core/base::forms.edit_item', ['name' => $mainProduct->name]),
                        route('products.edit', $mainProduct->id)
                    )
                    ->add(
                        trans('plugins/ecommerce::products.license_codes.variation_breadcrumb', ['name' => $product->name]),
                        route('products.edit', $product->id)
                    )
                    ->add(trans('plugins/ecommerce::products.license_codes.management'));
            } else {
                // Fallback if main product not found
                $this->breadcrumb()
                    ->add(
                        trans('core/base::forms.edit_item', ['name' => $product->name]),
                        route('products.edit', $product->id)
                    )
                    ->add(trans('plugins/ecommerce::products.license_codes.management'));
            }
        } else {
            // For main products
            $this->breadcrumb()
                ->add(
                    trans('core/base::forms.edit_item', ['name' => $product->name]),
                    route('products.edit', $product->id)
                )
                ->add(trans('plugins/ecommerce::products.license_codes.management'));
        }
    }

    public function index(Product $product)
    {
        $this->validateProductLicenseCodeAccess($product);

        // Add specific breadcrumb items for this product
        $this->addProductLicenseCodeBreadcrumb($product);

        $this->pageTitle(trans('plugins/ecommerce::products.license_codes.title') . ' - ' . $product->name, false);

        $licenseCodes = $product->licenseCodes()
            ->with(['assignedOrderProduct.order'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Check for low stock warning
        $availableCount = $product->availableLicenseCodes()->count();
        $showLowStockWarning = $product->license_code_type === 'pick_from_list' && $availableCount <= 5 && $availableCount > 0;
        $showOutOfStockWarning = $product->license_code_type === 'pick_from_list' && $availableCount === 0;

        return view('plugins/ecommerce::products.license-codes.index', compact(
            'product',
            'licenseCodes',
            'availableCount',
            'showLowStockWarning',
            'showOutOfStockWarning'
        ));
    }

    public function store(Product $product, Request $request): BaseHttpResponse
    {
        $this->validateProductLicenseCodeAccess($product);

        // Additional validation for manual license code creation
        if ($product->license_code_type === 'auto_generate') {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/ecommerce::products.license_codes.errors.cannot_add_codes_auto_generate'));
        }

        $request->validate([
            'license_code' => 'required|string|max:255|unique:ec_product_license_codes,license_code',
        ]);

        $product->licenseCodes()->create([
            'license_code' => $request->input('license_code'),
            'status' => ProductLicenseCodeStatusEnum::AVAILABLE,
        ]);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/ecommerce::products.license_codes.created_successfully'));
    }

    public function update(Product $product, ProductLicenseCode $licenseCode, Request $request): BaseHttpResponse
    {
        $this->validateProductLicenseCodeAccess($product);

        if ($licenseCode->product_id !== $product->id) {
            abort(404);
        }

        if ($licenseCode->isUsed()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/ecommerce::products.license_codes.cannot_edit_used_code'));
        }

        $request->validate([
            'license_code' => 'required|string|max:255|unique:ec_product_license_codes,license_code,' . $licenseCode->id,
        ]);

        $licenseCode->update([
            'license_code' => $request->input('license_code'),
        ]);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/ecommerce::products.license_codes.updated_successfully'));
    }

    public function destroy(Product $product, ProductLicenseCode $licenseCode): BaseHttpResponse
    {
        $this->validateProductLicenseCodeAccess($product);

        if ($licenseCode->product_id !== $product->id) {
            abort(404);
        }

        if ($licenseCode->isUsed()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/ecommerce::products.license_codes.cannot_delete_used_code'));
        }

        $licenseCode->delete();

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/ecommerce::products.license_codes.deleted_successfully'));
    }

    public function bulkGenerate(Product $product, Request $request): BaseHttpResponse
    {
        $this->validateProductLicenseCodeAccess($product);

        // Additional validation for bulk generation
        if ($product->license_code_type === 'auto_generate') {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/ecommerce::products.license_codes.errors.cannot_generate_codes_auto_generate'));
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
            'format' => 'required|string|in:uuid,alphanumeric,numeric,custom',
            'pattern' => 'required_if:format,custom|string|max:50',
        ]);

        $quantity = $request->input('quantity');
        $format = $request->input('format');
        $pattern = $request->input('pattern');

        $generatedCodes = [];
        $duplicateCount = 0;
        $generatedCodesInBatch = []; // Track codes generated in this batch
        $maxAttempts = $quantity * 10; // Prevent infinite loops
        $attempts = 0;

        while (count($generatedCodes) < $quantity && $attempts < $maxAttempts) {
            $attempts++;
            $code = $this->generateLicenseCode($format, $pattern);

            // Check for duplicates in database
            if (ProductLicenseCode::where('license_code', $code)->exists()) {
                $duplicateCount++;

                continue;
            }

            // Check for duplicates within this batch
            if (in_array($code, $generatedCodesInBatch)) {
                $duplicateCount++;

                continue;
            }

            // Add to batch tracking
            $generatedCodesInBatch[] = $code;

            $generatedCodes[] = [
                'product_id' => $product->id,
                'license_code' => $code,
                'status' => ProductLicenseCodeStatusEnum::AVAILABLE,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (! empty($generatedCodes)) {
            ProductLicenseCode::insert($generatedCodes);
        }

        $actualGenerated = count($generatedCodes);
        $message = trans('plugins/ecommerce::products.license_codes.generated_successfully', [
            'count' => $actualGenerated,
        ]);

        if ($duplicateCount > 0) {
            $message .= ' ' . trans('plugins/ecommerce::products.license_codes.duplicates_skipped', [
                'count' => $duplicateCount,
            ]);
        }

        // Warn if we couldn't generate the requested quantity
        if ($actualGenerated < $quantity) {
            $message .= ' ' . trans('plugins/ecommerce::products.license_codes.generation_incomplete', [
                'requested' => $quantity,
                'generated' => $actualGenerated,
            ]);
        }

        return $this
            ->httpResponse()
            ->setMessage($message);
    }

    private function generateLicenseCode(string $format, ?string $pattern = null): string
    {
        return match ($format) {
            'uuid' => Str::uuid()->toString(),
            'alphanumeric' => $this->generateAlphanumeric(12),
            'numeric' => $this->generateNumeric(12),
            'custom' => $this->generateCustomPattern($pattern),
            default => Str::uuid()->toString(),
        };
    }

    private function generateAlphanumeric(int $length): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = '';

        // Add some entropy from current microseconds to reduce duplicates
        $microtime = (int) (microtime(true) * 1000000);
        $entropy = base_convert($microtime, 10, 36);
        $entropy = strtoupper(substr($entropy, -3)); // Take last 3 characters

        $result .= $entropy;

        // Fill remaining length with random characters
        for ($i = strlen($result); $i < $length; $i++) {
            $result .= $chars[random_int(0, strlen($chars) - 1)];
        }

        // If result is longer than desired length, truncate
        if (strlen($result) > $length) {
            $result = substr($result, 0, $length);
        }

        return $result;
    }

    private function generateNumeric(int $length): string
    {
        // Add timestamp to make numeric codes more unique
        $timestamp = substr((string) time(), -6); // Last 6 digits of timestamp
        $result = $timestamp;

        // Fill remaining length with random digits
        for ($i = strlen($timestamp); $i < $length; $i++) {
            $result .= random_int(0, 9);
        }

        // If result is longer than desired length, truncate
        if (strlen($result) > $length) {
            $result = substr($result, 0, $length);
        }

        return $result;
    }

    private function generateCustomPattern(?string $pattern): string
    {
        if (! $pattern) {
            return Str::uuid()->toString();
        }

        return preg_replace_callback('/[#Aa]/', function ($matches) {
            return match ($matches[0]) {
                '#' => random_int(0, 9),
                'A' => chr(65 + random_int(0, 25)),
                'a' => chr(97 + random_int(0, 25)),
                default => $matches[0],
            };
        }, $pattern);
    }

    /**
     * Validate that the product can access license code management.
     *
     * This method ensures that:
     * 1. License codes feature is globally enabled
     * 2. The product is a digital product
     * 3. The product has license code generation enabled
     * 4. The product exists and is not a variation
     *
     * @param Product $product
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    private function validateProductLicenseCodeAccess(Product $product): void
    {
        // Check if license codes feature is globally enabled
        if (! EcommerceHelper::isEnabledLicenseCodesForDigitalProducts()) {
            abort(404, trans('plugins/ecommerce::products.license_codes.errors.feature_not_enabled'));
        }

        // Check if digital products are supported
        if (! EcommerceHelper::isEnabledSupportDigitalProducts()) {
            abort(404, trans('plugins/ecommerce::products.license_codes.errors.digital_products_not_enabled'));
        }

        // Check if the product exists
        if (! $product) {
            abort(404, trans('plugins/ecommerce::products.license_codes.errors.product_not_found'));
        }

        // Check if the product is a digital product
        if (! $product->isTypeDigital()) {
            abort(404, trans('plugins/ecommerce::products.license_codes.errors.not_digital_product'));
        }

        // Check if the product has license code generation enabled
        if (! $product->generate_license_code) {
            abort(404, trans('plugins/ecommerce::products.license_codes.errors.license_codes_not_enabled_for_product'));
        }
    }

    /**
     * Check if a product can access license code management (without throwing exceptions).
     * This method can be used in views or other places where you need to check access without aborting.
     *
     * @param Product $product
     * @return bool
     */
    public static function canAccessLicenseCodeManagement(Product $product): bool
    {
        return EcommerceHelper::isEnabledLicenseCodesForDigitalProducts()
            && EcommerceHelper::isEnabledSupportDigitalProducts()
            && $product
            && $product->isTypeDigital()
            && $product->generate_license_code;
    }
}
