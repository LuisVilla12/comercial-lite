<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DashboardCard extends Component
{
    public function __construct(
        public string $href,
        public string $bg,
        public string $title,
        public string $desc,
        public string $iconBg
    ) {}
    public function render()
    {
        return view('components.dashboard-card');
    }
}

