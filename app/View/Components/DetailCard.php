<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DetailCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $body;
    public $title;
    public $route;

    public function __construct($body,$title,$route)
    {
        $this->body = $body;
        $this->title = $title;
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.detail-card');
    }
}
