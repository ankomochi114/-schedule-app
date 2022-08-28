<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Player;

class Header extends Component
{
    /**
     * フォロー芸人
     *
     * @var array
     */
    public $players;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->players = Player::all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.header');
    }
}
