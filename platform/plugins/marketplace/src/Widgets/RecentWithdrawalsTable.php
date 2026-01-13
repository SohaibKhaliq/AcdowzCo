<?php

namespace Botble\Marketplace\Widgets;

use Botble\Base\Widgets\Table;
use Botble\Marketplace\Tables\RecentWithdrawalsTable as BaseRecentWithdrawalsTable;

class RecentWithdrawalsTable extends Table
{
    protected string $table = BaseRecentWithdrawalsTable::class;

    protected string $route = 'marketplace.reports.recent-withdrawals';

    public function getLabel(): string
    {
        return trans('plugins/marketplace::marketplace.reports.recent_withdrawals');
    }
}
