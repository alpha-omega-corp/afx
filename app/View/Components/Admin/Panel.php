<?php

namespace App\View\Components\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * A single admin surface: what it is on the left, what you can do to it
 * on the right. Every admin screen is a bento of these.
 *
 * The title is optional — on a screen with one panel the page heading has
 * already named it, and repeating the word is noise.
 *
 * Placement is a property of the panel rather than of the markup around it:
 * the screen says how wide this surface is, and the grid in _bento.scss does
 * the rest. A panel that says nothing stays full width, at every breakpoint.
 */
class Panel extends Component
{
    /** The bento placement classes, already assembled for the view. */
    public string $cell;

    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public bool $padding = true,
        // The tone of the surface. 'glass' is the default card; 'ember' is
        // the one heated surface of a screen and there is never more than
        // one; 'flat' is glass without the blur, for a very tall panel or a
        // panel sitting inside a modal.
        public string $tone = 'glass',
        // The bento placement. null = full width at that step.
        public ?int $md = null,
        public ?int $xl = null,
    ) {
        $this->tone = in_array($tone, ['glass', 'ember', 'flat'], true) ? $tone : 'glass';

        $this->cell = trim(
            ($md !== null ? "bento-md-{$md} " : '')
            . ($xl !== null ? "bento-xl-{$xl}" : '')
        );
    }

    public function render(): View
    {
        return view('components.admin.panel');
    }
}
