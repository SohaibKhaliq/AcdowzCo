<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;

class SharedWishlist extends BaseModel
{
    use MassPrunable;

    protected $table = 'ec_shared_wishlists';

    protected $fillable = [
        'code',
        'product_ids',
    ];

    protected $casts = [
        'product_ids' => 'array',
    ];

    public function prunable(): Builder
    {
        return $this->where('created_at', '<=', Carbon::now()->subDays(EcommerceHelper::getSharedWishlistLifetime()));
    }
}
