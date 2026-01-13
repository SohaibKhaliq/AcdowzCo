<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Ecommerce\Http\Requests\API\CreateAddressRequest;
use Botble\Ecommerce\Http\Requests\API\UpdateAddressRequest;
use Botble\Ecommerce\Http\Resources\API\AddressResource;
use Botble\Ecommerce\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends BaseApiController
{
    /**
     * Get list of address by customer
     *
     * @group Address
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $addresses = Address::query()
            ->where('customer_id', $request->user()->id)
            ->latest('is_default')
            ->latest()
            ->paginate(10);

        return $this
            ->httpResponse()
            ->setData(AddressResource::collection($addresses))
            ->toApiResponse();
    }

    /**
     * Create new address for customer
     *
     * @group Address
     * @authenticated
     *
     * @bodyParam name string required The name of the address owner. Example: John Doe
     * @bodyParam email string required The email address. Example: john.doe@example.com
     * @bodyParam phone string required The phone number. Example: 0123456789
     * @bodyParam country string The country name or country code. Example: United States or US
     * @bodyParam state string The state/province name. Example: California
     * @bodyParam city string The city name. Example: Los Angeles
     * @bodyParam address string The street address. Example: 123 Main St
     * @bodyParam is_default boolean Set as default address. Example: true
     * @bodyParam zip_code string The postal/zip code. Example: 90001
     *
     * @response {
     *  "error": false,
     *  "data": {
     *      "id": 1,
     *      "name": "John Doe",
     *      "phone": "0123456789",
     *      "email": "john.doe@example.com",
     *      "country": "United States",
     *      "state": "California",
     *      "city": "Los Angeles",
     *      "address": "123 Main St",
     *      "zip_code": "90001",
     *      "is_default": true
     *  },
     *  "message": null
     * }
     *
     * @return JsonResponse
     */
    public function store(CreateAddressRequest $request)
    {
        $isFirstAddress = ! Address::query()
            ->where('customer_id', $request->user()->id)
            ->exists();

        $address = Address::query()->create(array_merge($request->validated(), [
            'customer_id' => $request->user()->id,
            'is_default' => $isFirstAddress ? true : $request->input('is_default', false),
        ]));

        if (! $isFirstAddress) {
            $this->handleDefaultAddress($address, $request);
        }

        return $this
            ->httpResponse()
            ->setData(new AddressResource($address))
            ->toApiResponse();
    }

    /**
     * Update an address
     *
     * @group Address
     *
     * @authenticated
     *
     * @urlParam id integer required The ID of the address. Example: 1
     *
     * @bodyParam name string required The name of the address owner. Example: John Doe
     * @bodyParam email string required The email address. Example: john.doe@example.com
     * @bodyParam phone string required The phone number. Example: 0123456789
     * @bodyParam country string The country name or country code. Example: United States or US
     * @bodyParam state string required The state/province name. Example: California
     * @bodyParam city string required The city name. Example: Los Angeles
     * @bodyParam address string required The street address. Example: 123 Main St
     * @bodyParam is_default boolean Set as default address. Example: true
     * @bodyParam zip_code string The postal/zip code. Example: 90001
     *
     * @response {
     *  "error": false,
     *  "data": {
     *      "id": 1,
     *      "name": "John Doe",
     *      "phone": "0123456789",
     *      "email": "john.doe@example.com",
     *      "country": "United States",
     *      "state": "California",
     *      "city": "Los Angeles",
     *      "address": "123 Main St",
     *      "zip_code": "90001",
     *      "is_default": true
     *  },
     *  "message": null
     * }
     *
     * @return JsonResponse
     */
    public function update(UpdateAddressRequest $request, int|string $id)
    {
        $address = Address::query()->findOrFail($id);
        $address->update($request->validated()) ;

        $this->handleDefaultAddress($address, $request);

        return $this
            ->httpResponse()
            ->setData(new AddressResource($address))
            ->toApiResponse();
    }

    /**
     * Delete an address
     *
     * @group Address
     *
     * @authenticated
     *
     * @urlParam id integer required The ID of the address. Example: 1
     *
     * @response {
     *  "error": false,
     *  "data": null,
     *  "message": "Address deleted successfully"
     * }
     *
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $address = Address::query()
            ->where('customer_id', request()->user()->id)
            ->findOrFail($id);

        $wasDefault = $address->is_default;

        $address->delete();

        if ($wasDefault) {
            $latestAddress = Address::query()
                ->where('customer_id', request()->user()->id)
                ->latest()
                ->first();

            if ($latestAddress) {
                $latestAddress->update(['is_default' => true]);
            }
        }

        return $this
            ->httpResponse()
            ->setMessage('Address deleted successfully')
            ->toApiResponse();
    }

    /**
     * Get address details
     *
     * @group Address
     * @authenticated
     *
     * @urlParam id integer required The ID of the address. Example: 1
     *
     * @response {
     *  "error": false,
     *  "data": {
     *      "id": 1,
     *      "name": "John Doe",
     *      "phone": "0123456789",
     *      "email": "john.doe@example.com",
     *      "country": "United States",
     *      "state": "California",
     *      "city": "Los Angeles",
     *      "address": "123 Main St",
     *      "zip_code": "90001",
     *      "is_default": true
     *  },
     *  "message": null
     * }
     *
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        $address = Address::query()
            ->where('customer_id', request()->user()->id)
            ->findOrFail($id);

        return $this
            ->httpResponse()
            ->setData(new AddressResource($address))
            ->toApiResponse();
    }

    private function handleDefaultAddress(Address $address, Request $request): void
    {
        if ($request->input('is_default')) {
            Address::query()
                ->where('customer_id', $request->user()->id)
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }
    }
}
