<?php

namespace App\View\Components\Modal;

use App\Enums\Action as ActionEnum;
use App\Enums\Modal as ModalEnum;
use App\Helpers\ModalHelper;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Index extends Component
{
    public string $id;

    public function __construct(
        public string $title,
        public ModalEnum $name,
        public ActionEnum $action,
        public ?string $route = null,
        public mixed $iterator = null,
        public bool $padding = true,
    ) {
        $this->id = ModalHelper::getId($name, $action, $iterator);
    }

    public function render(): View
    {
        return view('components.modal.index');
    }
}
