<?php

namespace App\View\Components;

use Illuminate\View\Component;

class card extends Component
{
    public $title,$items;
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public function __construct($title,$items=null)
    {
        $this->title=$title;
        $this->items=$items;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card');
    }
}
