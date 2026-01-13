<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Resources\API\DigitalProductResource;
use Botble\Ecommerce\Models\OrderProduct;
use Botble\Ecommerce\Models\Product;
use Botble\Media\Facades\RvMedia;
use Botble\Media\Supports\Zipper;
use Botble\Payment\Enums\PaymentStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadController extends BaseApiController
{
    /**
     * Get list of digital products available for download
     *
     * @group Downloads
     * @param Request $request
     * @return mixed
     *
     * @authenticated
     */
    public function index(Request $request)
    {
        abort_unless(EcommerceHelper::isEnabledSupportDigitalProducts(), 404);

        $user = $request->user();

        $orderProducts = OrderProduct::query()
            ->whereHas('order', function (Builder $query) use ($user): void {
                $query
                    ->where('user_id', $user->id)
                    ->where('is_finished', 1)
                    ->when(is_plugin_active('payment'), function (Builder $query): void {
                        $query
                            ->where(function (Builder $query): void {
                                $query
                                    ->where('amount', 0)
                                    ->orWhereHas('payment', function ($query): void {
                                        $query->where('status', PaymentStatusEnum::COMPLETED);
                                    });
                            });
                    });
            })
            ->where('product_type', ProductTypeEnum::DIGITAL)->latest()
            ->with(['order', 'product', 'productFiles', 'product.productFiles'])
            ->paginate($request->integer('per_page', 10));

        return $this
            ->httpResponse()
            ->setData(DigitalProductResource::collection($orderProducts))
            ->toApiResponse();
    }

    /**
     * Download a digital product
     *
     * @group Downloads
     * @param int|string $id
     * @param Request $request
     * @return mixed
     *
     * @authenticated
     */
    public function download(int|string $id, Request $request)
    {
        abort_unless(EcommerceHelper::isEnabledSupportDigitalProducts(), 404);

        $orderProduct = OrderProduct::query()
            ->where([
                'id' => $id,
                'product_type' => ProductTypeEnum::DIGITAL,
            ])
            ->whereHas('order', function (Builder $query) use ($request): void {
                $query
                    ->when(
                        $request->user(),
                        fn (Builder $query, $user) => $query->where('user_id', $user->id)
                    )
                    ->where('is_finished', 1)
                    ->when(is_plugin_active('payment'), function (Builder $query): void {
                        $query
                            ->where(function (Builder $query): void {
                                $query
                                    ->where('amount', 0)
                                    ->orWhereHas('payment', function ($query): void {
                                        $query->where('status', PaymentStatusEnum::COMPLETED);
                                    });
                            });
                    });
            })
            ->with(['order', 'product'])
            ->first();

        abort_unless($orderProduct, 404);

        $order = $orderProduct->order;

        if ($request->user()) {
            abort_if($order->user_id != $request->user()->id, 404);
        } elseif ($hash = $request->input('hash')) {
            abort_if(! $orderProduct->download_token || ! Hash::check($orderProduct->download_token, $hash), 404);
        } else {
            abort(404);
        }

        $product = $orderProduct->product;
        $productFiles = $product->id ? $product->productFiles : $orderProduct->productFiles;

        if ($productFiles->isEmpty()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('No files found'))
                ->toApiResponse();
        }

        $externalProductFiles = $productFiles->filter(fn ($productFile) => $productFile->is_external_link);

        if ($request->input('external')) {
            if ($externalProductFiles->count()) {
                $orderProduct->increment('times_downloaded');

                if (! $orderProduct->downloaded_at) {
                    $orderProduct->downloaded_at = Carbon::now();
                    $orderProduct->save();
                }

                if ($externalProductFiles->count() == 1) {
                    $productFile = $externalProductFiles->first();

                    return $this
                        ->httpResponse()
                        ->setData(['url' => $productFile->url])
                        ->toApiResponse();
                }

                return $this
                    ->httpResponse()
                    ->setData(['files' => $externalProductFiles->map(function ($file) {
                        return [
                            'id' => $file->id,
                            'name' => $file->name,
                            'url' => $file->url,
                        ];
                    })])
                    ->toApiResponse();
            }

            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Unable to download files'))
                ->toApiResponse();
        }

        $internalProductFiles = $productFiles->filter(fn ($productFile) => ! $productFile->is_external_link);
        if ($internalProductFiles->isEmpty()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Unable to download files'))
                ->toApiResponse();
        }

        $zipName = Str::slug($orderProduct->product_name) . Str::random(5) . '-' . Carbon::now()->format(
            'Y-m-d-h-i-s'
        ) . '.zip';

        $storageDisk = Storage::disk('local');

        $fileName = $storageDisk->path($zipName);

        $zip = new Zipper();
        $zip->make($fileName);

        foreach ($internalProductFiles as $file) {
            if (Str::startsWith($file->url, Product::getDigitalProductFilesDirectory())) {
                $filePath = $storageDisk->path($file->url);

                if (File::exists($filePath)) {
                    $zip->add($filePath);
                }

                continue;
            }

            $filePath = RvMedia::getRealPath($file->url);
            if (! RvMedia::isUsingCloud()) {
                if (File::exists($filePath)) {
                    $zip->add($filePath);
                }
            } else {
                $zip->addString(
                    $file->base_name,
                    file_get_contents(str_replace('https://', 'http://', $filePath))
                );
            }
        }

        if (version_compare(phpversion(), '8.0') >= 0) {
            $zip = null;
        } else {
            $zip->close();
        }

        if (File::exists($fileName)) {
            $orderProduct->increment('times_downloaded');

            if (! $orderProduct->downloaded_at) {
                $orderProduct->downloaded_at = Carbon::now();
                $orderProduct->save();
            }

            // For API, we'll return a download URL instead of directly downloading the file
            $downloadUrl = route('api.ecommerce.download.download-file', [
                'token' => Hash::make($fileName),
                'order_id' => $orderProduct->order_id,
            ]);

            return $this
                ->httpResponse()
                ->setData(['download_url' => $downloadUrl])
                ->toApiResponse();
        }

        return $this
            ->httpResponse()
            ->setError()
            ->setMessage(__('Unable to download files'))
            ->toApiResponse();
    }

    /**
     * Download a file using a token
     *
     * @param string $token
     * @param string $order_id
     * @return mixed
     */
    public function downloadFile(string $token, string $order_id)
    {
        // Find the order product by order_id and validate token
        $orderProduct = OrderProduct::query()
            ->whereHas('order', function (Builder $query) use ($order_id): void {
                $query->where('id', $order_id);
            })
            ->where('product_type', ProductTypeEnum::DIGITAL)
            ->first();

        abort_unless($orderProduct, 404);

        // For digital products, we need to find the generated zip file
        // The token should match the hashed file path
        $storageDisk = Storage::disk('local');

        // Get all files in the storage and find the one that matches the token
        $files = $storageDisk->allFiles();
        $targetFile = null;

        foreach ($files as $file) {
            $filePath = $storageDisk->path($file);
            if (Hash::check($filePath, $token)) {
                $targetFile = $filePath;

                break;
            }
        }

        abort_unless($targetFile && File::exists($targetFile), 404);

        return response()->download($targetFile)->deleteFileAfterSend();
    }
}
