<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ButtonGroup extends Component
{
    public $buttons;

    /**
     * Create a new component instance.
     *
     * @param  array  $buttons
     * @return void
     */
    public function __construct($buttons)
    {
        $this->buttons = $buttons;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button-group');
    }
}
