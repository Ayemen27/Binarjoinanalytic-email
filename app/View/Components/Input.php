<?php

namespace App\View\Components;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;


class Input extends Component
{
    public string $name;
    public string $label;
    public string $type;
    public bool $showErrors;
    public string $value;
    public $id;
    public $xClass;

    public function __construct(
        string $name,
        string $label = '',
        string $type = 'text',
        bool $showErrors = true,
        string $value = '',
        string $xClass = '',
        string $id = ''
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->showErrors = $showErrors;
        $this->value = $value;
        $this->xClass = $xClass;
        $this->id = $id;
    }

    public function render()
    {
        return view('components.input');
    }
}
