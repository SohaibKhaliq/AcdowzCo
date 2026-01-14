<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Services\Location\CountryDetectionService;
use Botble\Location\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CountryController extends BaseController
{
    public function __construct(
        protected CountryDetectionService $countryDetectionService
    ) {}

    public function setCountry(Request $request, BaseHttpResponse $response)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
        ]);

        $country = Country::query()
            ->where('id', $request->input('country_id'))
            ->where('status', 'published')
            ->first();

        if (!$country) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::product-countries.country_not_available'));
        }

        $customer = Auth::guard('customer')->user();

        $this->countryDetectionService->setCountry(
            $country->id,
            $customer,
            'manual'
        );

        return $response
            ->setMessage(trans('plugins/ecommerce::product-countries.country_updated', ['country' => $country->name]))
            ->setData([
                'reload' => true,
                'country_id' => $country->id,
                'country_name' => $country->name,
            ]);
    }

    public function detect(BaseHttpResponse $response)
    {
        $customer = Auth::guard('customer')->user();
        $countryId = $this->countryDetectionService->detectCountry($customer);
        $countryName = $this->countryDetectionService->getCountryName($countryId);

        return $response->setData([
            'country_id' => $countryId,
            'country_name' => $countryName,
        ]);
    }
}
