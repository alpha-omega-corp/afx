<?php

namespace App\View\Components\Forms;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Date extends Component
{
    private static int $sequence = 0;

    public string $id;

    public function __construct(
        public string $name,
        public string $label,
        public ?string $value = null,
        public bool $required = true,
        // A day or a clock time: the same floating-label field either way, and
        // the browser picks the right picker for it.
        public string $type = 'date',
    ) {
        $this->id = $type . '-' . preg_replace('/[^a-z0-9]+/i', '-', $name) . '-' . ++self::$sequence;
    }

    public function render(): View
    {
        return view('components.forms.date');
    }
}
