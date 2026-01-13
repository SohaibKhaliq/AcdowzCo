<?php

namespace Botble\Ecommerce\Repositories\Eloquent;

use Botble\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class FlashSaleRepository extends RepositoriesAbstract implements FlashSaleInterface
{
    public function getAvailableFlashSales(array $with = []): Collection
    {
        /**
         * @phpstan-ignore-next-line
         */
        $data = $this->model
            ->notExpired()
            ->wherePublished()
            ->latest();

        if ($with) {
            // If products are included, filter out sold-out products
            if (in_array('products', $with)) {
                $with = [
                    'products' => function ($query) {
                        $query->wherePivot('quantity', '>', DB::raw('sold'));
                    },
                ];
            }

            $data = $data->with($with);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
