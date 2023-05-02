<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormInput extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $flabel;
    public $ftype;
    public $fname;
    public $frequired;
    public $fvalue;
    public $fdisable;

    public function __construct($flabel,$ftype,$fname,$frequired,$fvalue,$fdisable)
    {
        $this->flabel = $flabel;
        $this->ftype = $ftype;
        $this->fname = $fname;
        $this->frequired = $frequired;
        $this->fvalue = $fvalue;
        $this->fdisable = $fdisable;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-input');
    }
}
