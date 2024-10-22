<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Page;

class Admin extends Component
{
    public function __construct(
        public Page $page
    ) {}

    public function render(): View
    {
        return view('components.admin');
    }
}
