<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public $title;
    public $col;
    public $icon;
    public $goTo;
    public $backTo;
    public $exclude;

    public $nav;



    public function __construct($title, string $col = 'col-12',  $goTo = null, $nav = true, $icon = null, $backTo = null,  $exclude = 10)
    {
        $this->title  = $title;
        $this->col  = $col;
        $this->goTo  = $goTo;
        $this->backTo  = $backTo;
        $this->exclude  = $exclude;
        $this->icon  = $icon;
        $this->nav  = $nav;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.breadcrumb');
    }
}