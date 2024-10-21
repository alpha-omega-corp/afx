<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Repeater extends Component
{
    public function __construct(
        public array $items,
        public string $title,
    )
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.forms.repeater');
    }
}
