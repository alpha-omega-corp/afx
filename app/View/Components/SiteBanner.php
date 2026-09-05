<?php

namespace App\View\Components;

use App\Support\SiteStatus;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Tells a guest the auberge is closed before they read anything else.
 * Renders nothing at all while it is open.
 */
class SiteBanner extends Component
{
    public function __construct(
        public SiteStatus $status,
    ) {}

    public function shouldRender(): bool
    {
        return $this->status->isClosed();
    }

    public function render(): View
    {
        return view('components.site-banner');
    }
}
