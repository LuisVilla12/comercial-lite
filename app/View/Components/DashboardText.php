<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DashboardText extends Component
{
    public $title;
    public $desc;

    public function render()
    {
        return view('components.dashboard-text');
    }
}
