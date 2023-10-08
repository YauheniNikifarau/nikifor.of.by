<?php

namespace App\MoonShine;

use App\Models\BudgetAction;
use MoonShine\Dashboard\DashboardBlock;
use MoonShine\Dashboard\DashboardScreen;
use MoonShine\Metrics\ValueMetric;

class Dashboard extends DashboardScreen
{
	public function blocks(): array
	{
		return [DashboardBlock::make([
            ValueMetric::make('$ в нычке')->value(BudgetAction::calculate_sum_in_usd()),
        ])];
	}
}
