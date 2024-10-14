<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Admin extends Component
{
    public function __construct(public \App\Models\Page $page)
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.admin');
    }
}
