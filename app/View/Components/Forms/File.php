<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class File extends Component
{
    /**
     * Five page rows render this field, all of them called "image".
     * Without this the ids collided and every row's picker drove the
     * first row's input: choosing a photograph for the hotel attached it
     * to the home page's form, and the form actually submitted carried no
     * file at all. Same guard as Forms\Input.
     */
    private static int $sequence = 0;

    public string $id;

    public function __construct(
        public string $name,
        public ?string $label = null,
        public ?string $file = null,
        public bool $multiple = false,
    ) {
        $this->id = 'file-' . preg_replace('/[^a-z0-9]+/i', '-', $name) . '-' . ++self::$sequence;
    }

    public function render(): View|Closure|string
    {
        return view('components.forms.file');
    }
}
