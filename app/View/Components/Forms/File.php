<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class File extends Component
{
    public function __construct(
        public string $name,
        public ?string $label = null,
        public ?string $file = null,
        public bool $multiple = false,
    )
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.forms.file');
    }
}
