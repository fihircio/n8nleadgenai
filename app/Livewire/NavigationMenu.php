<?php

namespace App\Livewire;

use Laravel\Jetstream\Http\Livewire\NavigationMenu as JetstreamNavigationMenu;

class NavigationMenu extends JetstreamNavigationMenu
{
    public function render()
    {
        return view('components.navigation-menu');
    }
}
