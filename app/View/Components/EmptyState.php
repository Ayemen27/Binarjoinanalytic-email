<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EmptyState extends Component
{

    public $title;
    public $type;



    /**
     * Create a new component instance.
     */
    public function __construct(string $title,   $type = "")
    {
        $this->title       = $title;
        $this->type       = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.empty');
    }
}
