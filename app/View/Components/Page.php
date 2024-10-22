<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Page extends Component
{
    public function __construct(
        public string $image,
        public bool $isLarge = false
    ) {}

    public function render(): View
    {
        return view('components.page');
    }
}
