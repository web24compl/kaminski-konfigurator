<?php

declare(strict_types=1);

namespace App\Nova\Dashboards;

use Configurator\QAndATree\QAndATree;
use Laravel\Nova\Dashboards\Main as Dashboard;

class QAndA extends Dashboard
{
    public function cards()
    {
        return [
            new QAndATree(),
        ];
    }

    public function label()
    {
        return __('qAndA');
    }
}
