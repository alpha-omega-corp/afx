<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Repeater extends Component
{
    /**
     * @param  array  $items     the rows the form opens on
     * @param  string $title     the label above the rows
     * @param  string $idName    the field carrying each row's record id
     * @param  bool   $queue     whether removing a row queues the record for deletion
     * @param  array  $defaults  the row a fresh "Add" button creates
     */
    public function __construct(
        public array $items,
        public string $title,
        public string $idName = 'ids[]',
        public bool $queue = true,
        public array $defaults = [],
    )
    {
        //
    }

    /**
     * Everything the Alpine component needs that is not the rows themselves.
     *
     * `queue` is the difference between the two editors that use this. In the
     * menu editor, taking a dish out of a section deletes it. On the card of
     * the day, taking a dish off the card only unflags it — the dish stays on
     * the carte — so nothing may be queued for deletion there.
     */
    public function options(): array
    {
        return [
            'queue' => $this->queue,
            'defaults' => (object) $this->defaults,
        ];
    }

    public function render(): View|Closure|string
    {
        return view('components.forms.repeater');
    }
}
