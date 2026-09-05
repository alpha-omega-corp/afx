<?php

namespace App\View\Components\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * A single admin surface: what it is on the left, what you can do to it
 * on the right. Every admin screen is a stack of these.
 *
 * The title is optional — on a screen with one panel the page heading has
 * already named it, and repeating the word is noise.
 */
class Panel extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public bool $padding = true,
    ) {}

    public function render(): View
    {
        return view('components.admin.panel');
    }
}
