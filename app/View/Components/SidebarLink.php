<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarLink extends Component
{
    public $href;
    public $icon;
    public $title;
    public $badge;
    public $current;

    public function __construct($href, $icon, $title, $badge = null, $current = false)
    {
        $this->href = $href;
        $this->icon = $icon;
        $this->title = $title;
        $this->badge = $badge;
        $this->current = $current;
    }


    public function render(): View|Closure|string
    {
        return view('components.sidebar-link');
    }
}
