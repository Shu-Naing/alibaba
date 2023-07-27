<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DeleteModal extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $id;
    public $deletedataid;
    public $route;

    public function __construct($id, $deletedataid, $route)
    {
        $this->id = $id;
        $this->deletedataid = $deletedataid;
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.delete-modal');
    }
}
