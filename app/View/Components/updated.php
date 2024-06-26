<?php

namespace App\View\Components;

use Illuminate\View\Component;

class updated extends Component
{
    public $date,$name;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($date,$name='')
    {
        $this->date=$date->diffForHumans();
        $this->name=$name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.updated');
    }
}
