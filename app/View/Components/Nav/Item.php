<?php

namespace App\View\Components\Nav;

use Illuminate\View\Component;

class Item extends Component
{
    public ?string $href;

    public string $label;

    public ?string $icon;

    public bool $active = false;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, ?string $icon = null, ?string $href = null)
    {
        $routeName = app('router')->getRoutes()->match(
            app('request')->create($href)
        )->getName();
        $this->active = request()->routeIs($routeName);

        $this->href = $href;
        $this->label = $label;
        $this->icon = ($this->active ? 'fa-solid' : 'fa-regular').' fa-'.$icon;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.nav.item');
    }
}
