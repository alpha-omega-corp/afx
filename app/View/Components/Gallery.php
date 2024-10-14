<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Gallery extends Component
{
    public function __construct(
        public \App\Models\Gallery $gallery,
    )
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.gallery');
    }
}
