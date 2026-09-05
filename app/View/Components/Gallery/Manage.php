<?php

namespace App\View\Components\Gallery;

use App\Models\Gallery;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Manage extends Component
{
    public function __construct(
        public Gallery $gallery,
    ) {}

    public function render(): View
    {
        return view('components.gallery.manage');
    }
}
