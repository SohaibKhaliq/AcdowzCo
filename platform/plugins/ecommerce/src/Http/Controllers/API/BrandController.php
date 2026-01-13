<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\API\BrandRequest;
use Botble\Ecommerce\Http\Resources\API\AvailableProductResource;
use Botble\Ecommerce\Http\Resources\API\BrandResource;
use Botble\Ecommerce\Models\Brand;
use Botble\Ecommerce\Services\Products\GetProductService;
use Botble\Slug\Facades\SlugHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends BaseApiController
{
    /**
     * Get list of brands
     *
     * @group Brands
     * @param BrandRequest $request
     * @queryParam brands string[] List of brand IDs if you need filter by brands. Example: 1,2,3
     * @queryParam page int Page number. Default: 1. No-example
     * @queryParam per_page int Number of items per page. Default: 16. No-example
     *
     * @return JsonResponse
     */
    public function index(BrandRequest $request)
    {
        $brands = Brand::query()
            ->wherePublished()
            ->oldest('order')->latest()
            ->when($request->input('brands'), function ($query, $brandIds) {
                return $query->whereIn('id', $brandIds);
            })
            ->when($request->has('is_featured'), function ($query) use ($request) {
                return $query->where('is_featured', $request->boolean('is_featured'));
            })
            ->paginate(config('ecommerce.pagination.per_page', 16));

        return $this
            ->httpResponse()
            ->setData(BrandResource::collection($brands))
            ->toApiResponse();
    }

    /**
     * Get brand details by slug
     *
     * @group Brands
     * @param string $slug Brand slug
     * @return JsonResponse
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function show(string $slug)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Brand::class));

        abort_unless($slug, 404);

        $brand = Brand::query()
            ->where('id', $slug->reference_id)
            ->firstOrFail();

        return $this
            ->httpResponse()
            ->setData(new BrandResource($brand))
            ->toApiResponse();
    }

    /**
     * Get products by brand
     *
     * @group Brands
     *
     * @param Brand $brand
     * @param Request $request
     * @return JsonResponse
     */
    public function products(Brand $brand, Request $request)
    {
        if (! EcommerceHelper::productFilterParamsValidated($request)) {
            $request = request();
        }

        $request->merge(['brands' => array_merge((array) $request->input('brands', []), [$brand->getKey()])]);

        $products = app(GetProductService::class)->getProduct(
            $request,
            null,
            $brand->getKey(),
            EcommerceHelper::withProductEagerLoadingRelations()
        );

        return $this
            ->httpResponse()
            ->setData(AvailableProductResource::collection($products))
            ->toApiResponse();
    }
}
