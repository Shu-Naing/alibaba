<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormSelect extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $flabel;
    public $fname;
    public $foptions;
    public $frequired;
    public $fdisable;
    public $fvalue;
    public function __construct($flabel,$fname,$foptions,$frequired,$fdisable,$fvalue)
    {
        $this->flabel = $flabel;
        $this->fname = $fname;
        $this->foptions = $foptions;
        $this->frequired = $frequired;
        $this->fdisable = $fdisable;
        $this->fvalue = $fvalue;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-select');
    }
}
