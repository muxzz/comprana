<?php

namespace App\Livewire;

use Livewire\Component;

class CartProduct extends Component
{
    public $product;

    public $cart;

    public $cant;

    public function mount($cant)
    {
        $this->cant = $cant;
    }

    public function edit()
    {
        $this->cart->products()->updateExistingPivot($this->product->id, ['cant' => $this->cant]);
        $this->dispatch('notification', 'Se actualizo la cantidad el producto');
        $this->dispatch('refresh');

    }

    public function delete()
    {
        $this->cart->products()->detach($this->product->id);
        $this->dispatch('notification', 'Se Elimino el producto');
        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.cart-product');
    }
}
