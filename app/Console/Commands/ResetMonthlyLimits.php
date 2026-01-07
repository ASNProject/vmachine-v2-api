<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetMonthlyLimits extends Command
{
    protected $signature ='limit:reset';

    public function handle()
    {
        $month = now()->format('Y-m');

        $limits = CustomerItemLimits::all();

        foreach ($limits as $limit) {
            LimitPeriods::updateOrCreate(
                [
                    'uid' => $limit->uid,
                    'group_id' => $limit->group_id,
                    'period_month' => $month
                ],
                [
                    'remaining_qty' => $limit->limit_qty,
                ]
                );
        }
    }
}
