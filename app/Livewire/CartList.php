<?php

namespace App\Livewire;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CartList extends Component
{
    public $carts;

    public $user_id;

    public $cart_id = '';

    #[Validate('required|min:2|max:25|unique:carts')]
    public $name = '';

    public function mount()
    {
        $this->user_id = Auth::user()->id;
    }

    public function active_cart(string $cart_id)
    {
        Cart::where('active', true)->where('user_id', $this->user_id)->update(['active' => false]);
        Cart::where('id', $cart_id)->where('user_id', $this->user_id)->update(['active' => true]);
    }

    public function void_cart(string $cart_id)
    {
        $cart = Cart::find($cart_id);
        $this->authorize('author', $cart);
        $cart->products()->detach();
        $this->dispatch('notification', 'Se vació el carrito');
    }

    public function edit_cart()
    {
        $this->validate();
        Cart::where('id', $this->cart_id)->update(['name' => $this->name]);

        $this->dispatch('notification', 'Se edito el carrito');
        $this->dispatch('close-edit');
        $this->reset('name');
    }

    public function render()
    {
        $this->carts = Cart::where('user_id', $this->user_id)->withCount('products')
            ->orderBy('active', 'desc')->get();

        return view('livewire.cart-list');
    }
}
