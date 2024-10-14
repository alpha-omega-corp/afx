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

    )
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.tab.index');
    }


}
