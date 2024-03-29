<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Dashboards\Main as Dashboard;
use Web24\Export\Export;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new Export)->export(),
        ];
    }

    public function label()
    {
        return __('mainDashboard');
    }
}
