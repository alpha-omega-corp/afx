<?php

namespace App\View\Components\Admin;

use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * One cell of the Pages screen: what the guest sees at the top of that page,
 * and the single action that changes it.
 *
 * It carries its own bento placement for the same reason Panel does — the
 * screen composes the grid, the component never guesses its own width.
 */
class PageRow extends Component
{
    /** The bento placement classes, already assembled for the view. */
    public string $cell;

    public function __construct(
        public Page $page,
        public ?int $md = null,
        public ?int $xl = null,
    ) {
        $this->cell = trim(
            ($md !== null ? "bento-md-{$md} " : '')
            . ($xl !== null ? "bento-xl-{$xl}" : '')
        );
    }

    public function render(): View
    {
        return view('components.admin.page-row');
    }
}
