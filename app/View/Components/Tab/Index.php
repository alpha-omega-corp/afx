<?php

namespace App\View\Components\Tab;

use App\Helpers\ModalHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Index extends Component
{
    public mixed $id;

    public function __construct(
        public array $items,
        // The accessible name of the strip. A tablist without one is
        // announced as "tab list" and nothing else, and a page that renders
        // six identical FR/EN strips — one per edit dialog — gives a screen
        // reader no way to tell which set of tabs it has landed in. Optional
        // so the component keeps working wherever it is already called.
        public ?string $label = null,
    )
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.tab.index');
    }


}
