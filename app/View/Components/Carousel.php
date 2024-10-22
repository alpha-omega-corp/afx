<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Carousel extends Component
{
    public function __construct(
        public string $name,
        public int $count,
    ) {}

    public function render(): View
    {
        return view('components.carousel');
    }
}
