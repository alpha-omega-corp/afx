<?php

namespace App\View\Components\Modal;

use App\Helpers\ModalHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Enums\Modal as ModalEnum;
use App\Enums\Action as ActionEnum;

class Index extends Component
{
    public mixed $id;

    public function __construct(
        public string $title,
        public ModalEnum $name,
        public ActionEnum $action,
        public ?string $route = null,
        public mixed $iterator = null,
        public ?string $func = null,

    )
    {
        $this->id = ModalHelper::getId($name, $action, $iterator);
    }

    public function render(): View|Closure|string
    {
        return view('components.modal.index');
    }


}
