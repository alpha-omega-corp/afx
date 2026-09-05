<?php

namespace App\View\Components\Admin;

use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * One row of the Pages screen: what the guest sees at the top of that page,
 * and the single action that changes it.
 */
class PageRow extends Component
{
    public function __construct(
        public Page $page,
    ) {}

    public function render(): View
    {
        return view('components.admin.page-row');
    }
}
