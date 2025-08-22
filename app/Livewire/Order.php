<?php

namespace App\Livewire;

use App\Models\Order as ModelsOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Order extends Component
{
    #[Locked]
    public $user;

    #[Validate('required|min:10|max:10')]
    public $phone = '';

    #[Validate('required|min:5|max:255')]
    public $address = '';

    public $cart;

    public function mount()
    {
        $this->user = Auth::user();
        $this->phone = $this->user->phone;
        $this->address = $this->user->address;
    }

    public function contacts()
    {
        $this->validate();
        $order = ModelsOrder::create([
            'user_id' => $this->user->id,
            'cart_id' => $this->cart->id,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        $this->redirectRoute('orders.pay', [
            'id' => $this->cart->id,
            'order' => $order,
        ]);
    }

    public function render()
    {
        return view('livewire.order');
    }
}
