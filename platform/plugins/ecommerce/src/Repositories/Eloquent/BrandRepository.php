<?php

namespace Botble\Ecommerce\Repositories\Eloquent;

use Botble\Ecommerce\Repositories\Interfaces\BrandInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Collection;

class BrandRepository extends RepositoriesAbstract implements BrandInterface
{
    public function getAll(array $condition = []): Collection
    {
        $data = $this->model
            ->where($condition)
            ->latest('is_featured')
            ->oldest('name');

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
