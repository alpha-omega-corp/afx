<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Carousel extends Component
{
    public function __construct(
        public string $name,
        public int $count,
    )
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.carousel');
    }
}
