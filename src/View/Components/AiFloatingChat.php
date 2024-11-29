<?php

namespace dougkusanagi\LaravelAiChat\View\Components;

use Illuminate\View\Component;

class AiFloatingChat extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('ai-chat::components.ai-floating-chat');
    }
}
