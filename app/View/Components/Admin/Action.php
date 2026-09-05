<?php

namespace App\View\Components\Admin;

use App\Enums\Action as ActionEnum;
use App\Enums\Icon;
use App\Enums\Modal as ModalEnum;
use App\Helpers\ModalHelper;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * A labelled button that opens the modal for one action on one record.
 * The intent (and therefore the colour) is derived from the action, so
 * a destructive action can never accidentally look like a benign one.
 */
class Action extends Component
{
    public string $id;
    public string $intent;
    public Icon $icon;

    public function __construct(
        public ModalEnum $name,
        public ActionEnum $action,
        public ?string $label = null,
        ?Icon $icon = null,
        public mixed $iterator = null,
        public bool $compact = false,
    ) {
        $this->id = ModalHelper::getId($name, $action, $iterator);

        $this->icon = $icon ?? match ($action) {
            ActionEnum::CREATE => Icon::CREATE,
            ActionEnum::UPDATE => Icon::EDIT,
            ActionEnum::DELETE => Icon::DELETE,
            ActionEnum::READ => Icon::READ,
        };

        $this->intent = match ($action) {
            ActionEnum::CREATE => 'primary',
            ActionEnum::UPDATE => 'neutral',
            ActionEnum::DELETE => 'danger',
            ActionEnum::READ => 'quiet',
        };

        $this->label ??= match ($action) {
            ActionEnum::CREATE => __('admin.action.create'),
            ActionEnum::UPDATE => __('admin.action.edit'),
            ActionEnum::DELETE => __('admin.action.delete'),
            ActionEnum::READ => __('admin.action.read'),
        };
    }

    public function render(): View
    {
        return view('components.admin.action');
    }
}
