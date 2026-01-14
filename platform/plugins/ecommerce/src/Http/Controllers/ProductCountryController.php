<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\Product;
use Botble\Location\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCountryController extends BaseController
{
    public function index(Product $product)
    {
        $this->checkPermission('product.country.assign');

        PageTitle::setTitle(trans('plugins/ecommerce::product-countries.manage', ['name' => $product->name]));

        $assignedCountries = $product->countries()->pluck('countries.id')->toArray();
        $availableCountries = Country::query()
            ->where('status', 'published')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('plugins/ecommerce::product-countries.index', compact('product', 'assignedCountries', 'availableCountries'));
    }

    public function store(Product $product, Request $request, BaseHttpResponse $response)
    {
        $this->checkPermission('product.country.assign');

        $request->validate([
            'countries' => 'nullable|array',
            'countries.*' => 'exists:countries,id',
        ]);

        DB::beginTransaction();

        try {
            $product->countries()->sync($request->input('countries', []));

            DB::commit();

            return $response
                ->setMessage(trans('plugins/ecommerce::product-countries.updated_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();

            return $response
                ->setError()
                ->setMessage($e->getMessage());
        }
    }

    public function bulkAssign(Request $request, BaseHttpResponse $response)
    {
        $this->checkPermission('product.country.assign');

        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:ec_products,id',
            'countries' => 'required|array',
            'countries.*' => 'exists:countries,id',
            'action' => 'required|in:assign,remove',
        ]);

        DB::beginTransaction();

        try {
            $products = Product::query()->whereIn('id', $request->input('product_ids'))->get();
            $countries = $request->input('countries');
            $action = $request->input('action');

            foreach ($products as $product) {
                if ($action === 'assign') {
                    $existingCountries = $product->countries()->pluck('countries.id')->toArray();
                    $newCountries = array_unique(array_merge($existingCountries, $countries));
                    $product->countries()->sync($newCountries);
                } else {
                    $product->countries()->detach($countries);
                }
            }

            DB::commit();

            return $response
                ->setMessage(trans('plugins/ecommerce::product-countries.bulk_updated_successfully', [
                    'count' => count($products),
                ]));
        } catch (\Exception $e) {
            DB::rollBack();

            return $response
                ->setError()
                ->setMessage($e->getMessage());
        }
    }

    protected function checkPermission(string $permission): void
    {
        if (!$this->hasPermission($permission)) {
            abort(403);
        }
    }
}
