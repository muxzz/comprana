<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class CartProductsList extends Component
{
    public $cart;

    public function placeholder()
    {
        return <<<'HTML'
        <div class="flex items-center justify-center my-40 space-x-2">
            <div class="w-4 h-4 rounded-full animate-pulse dark:bg-violet-400"></div>
            <div class="w-4 h-4 rounded-full animate-pulse dark:bg-violet-400"></div>
            <div class="w-4 h-4 rounded-full animate-pulse dark:bg-violet-400"></div>
        </div>
        HTML;
    }

    #[On('refresh')]
    public function render()
    {
        $this->cart->products()->with('image')->get();

        return view('livewire.cart-products-list');
    }
}
