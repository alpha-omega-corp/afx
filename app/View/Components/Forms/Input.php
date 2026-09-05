<?php

namespace App\View\Components\Forms;

use App\Enums\Icon;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    /** Distinguishes inputs that share a name across modals on one page. */
    private static int $sequence = 0;

    public string $id;

    public function __construct(
        public string $name,
        public string $label,
        public string $type = 'text',
        public bool $required = false,
        public ?string $value = null,
        public ?Icon $icon = null,
        public ?string $model = null,
        public ?string $index = null,
    ) {
        $this->id = 'input-' . preg_replace('/[^a-z0-9]+/i', '-', $index ?? $name) . '-' . ++self::$sequence;
    }

    public function render(): View
    {
        return view('components.forms.input');
    }
}
