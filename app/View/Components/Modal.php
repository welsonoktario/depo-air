<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    /**
     * The modal type.
     *
     * @var string
     */
    public $title;

    /**
     * The modal id.
     *
     * @var string
     */
    public $id;

    /**
     * Create the component instance.
     *
     * @param  string  $title
     * @param  string  $id
     * @return void
     */
    public function __construct($title, $id)
    {
        $this->title = $title;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
