<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\API\ReviewRequest;
use Botble\Ecommerce\Http\Resources\API\ReviewResource;
use Botble\Ecommerce\Models\Review;
use Botble\Ecommerce\Traits\CheckReviewConditionForApiTrait;
use Botble\Media\Facades\RvMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ReviewController extends BaseApiController
{
    use CheckReviewConditionForApiTrait;

    /**
     * Get list of reviews for the current user
     *
     * @group Reviews
     * @param Request $request
     * @return JsonResponse
     *
     * @authenticated
     */
    public function index(Request $request)
    {
        abort_unless(EcommerceHelper::isReviewEnabled(), 404);

        $user = $request->user();

        $reviews = Review::query()
            ->where('customer_id', $user->id)
            ->with('product')
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return $this
            ->httpResponse()
            ->setData(ReviewResource::collection($reviews))
            ->toApiResponse();
    }

    /**
     * Create a new review
     *
     * @group Reviews
     * @param ReviewRequest $request
     *
     * @bodyParam product_id integer required The ID of the product to review. Example: 1
     * @bodyParam star integer required The rating from 1 to 5 stars. Example: 5
     * @bodyParam comment string required The review comment. Example: This is a great product! I highly recommend it.
     * @bodyParam images array Array of images for the review (optional). No-example
     *
     * @authenticated
     */
    public function store(ReviewRequest $request)
    {
        abort_unless(EcommerceHelper::isReviewEnabled(), 404);

        $productId = $request->input('product_id');
        $check = $this->checkReviewCondition($productId, $request);

        if (Arr::get($check, 'error')) {
            $message = Arr::get($check, 'message', __('Oops! Something Went Wrong.'));

            return response()->json([
                'message' => $message,
                'errors' => [
                    'product_id' => [$message],
                ],
            ], 422);
        }

        $results = [];
        if ($request->hasFile('images')) {
            $images = (array) $request->file('images', []);
            foreach ($images as $image) {
                $result = RvMedia::handleUpload($image, 0, 'reviews');
                if ($result['error']) {
                    return $this
                        ->httpResponse()
                        ->setError()
                        ->setMessage($result['message'])
                        ->toApiResponse();
                }

                $results[] = $result;
            }
        }

        $review = Review::query()->create([
            ...$request->validated(),
            'customer_id' => $request->user()->id,
            'images' => $results ? collect($results)->pluck('data.url')->values()->all() : null,
            'status' => get_ecommerce_setting(
                'review_need_to_be_approved',
                false
            ) ? BaseStatusEnum::PENDING : BaseStatusEnum::PUBLISHED,
        ]);

        return $this
            ->httpResponse()
            ->setData(new ReviewResource($review))
            ->setMessage(__('Added review successfully!'))
            ->toApiResponse();
    }

    /**
     * Delete a review
     *
     * @group Reviews
     * @param int|string $id
     * @param Request $request
     * @return JsonResponse
     *
     * @authenticated
     */
    public function destroy(int|string $id, Request $request)
    {
        abort_unless(EcommerceHelper::isReviewEnabled(), 404);

        $review = Review::query()->findOrFail($id);

        // Check if the review belongs to the authenticated user
        if ($review->customer_id != $request->user()->id) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(403)
                ->setMessage(__('You do not have permission to delete this review.'))
                ->toApiResponse();
        }

        $review->delete();

        return $this
            ->httpResponse()
            ->setMessage(__('Deleted review successfully!'))
            ->toApiResponse();
    }
}
