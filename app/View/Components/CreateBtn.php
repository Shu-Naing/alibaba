<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CreateBtn extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $label;
    public $route;

    public function __construct($label,$route)
    {
        $this->label = $label;
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.create-btn');
    }
}
