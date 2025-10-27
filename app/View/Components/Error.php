<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Error extends Component
{
    public string $name;
    public string $class;
    public string $bag;

    public function __construct(string $name,  $class = '', $bag = '')
    {
        $this->name       = $name;
        $this->class       = $class;
        $this->bag       = $bag;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.error');
    }
}
