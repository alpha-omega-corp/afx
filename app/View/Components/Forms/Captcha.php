<?php

namespace App\View\Components\Forms;

use App\Support\Captcha as Challenge;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Captcha extends Component
{
    private static int $sequence = 0;

    public string $question;
    public string $id;
    public string $trapId;

    public function __construct()
    {
        $this->question = Challenge::question();

        $n = ++self::$sequence;
        $this->id = "captcha-answer-$n";
        $this->trapId = "captcha-trap-$n";
    }

    public function render(): View
    {
        return view('components.forms.captcha');
    }
}
