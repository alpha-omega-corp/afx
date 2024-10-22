<?php

namespace App\View\Components\Modal;

use App\Enums\Action;
use App\Enums\Icon;
use App\Helpers\ModalHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Enums\Modal as ModalEnum;
use App\Enums\Action as ActionEnum;

class Open extends Component
{
    public mixed $id;
    public string $color;

    public function __construct(
        public ModalEnum $name,
        public ActionEnum $action,
        public ?string $title = null,
        public ?Icon $icon = null,
        public mixed $iterator = null
    )
    {
        $this->id = ModalHelper::getId($name, $action, $iterator);

        $this->color = match ($action) {
            ActionEnum::CREATE => 'primary',
            ActionEnum::UPDATE => 'info',
            ActionEnum::DELETE => 'danger',
            ActionEnum::READ => 'secondary',
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.modal.open');
    }


}
