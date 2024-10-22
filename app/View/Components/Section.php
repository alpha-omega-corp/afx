<?php

namespace App\View\Components;

use App\Enums\Color;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Section extends Component
{
    public function __construct(
        public Color $color,
        public ?string $title = null,
        public bool $padding = false
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.section');
    }
}
