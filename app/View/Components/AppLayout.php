<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppLayout extends Component
{
    /**
     * Apline.js x-data.
     *
     * @var string
     */
    public $xData;

    /**
     * Create the component instance.
     *
     * @param  string  $xData
     * @return void
     */
    public function __construct($xData = null)
    {
        $this->xData = $xData;
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('layouts.app');
    }
}
