<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    #[Url()]
    public $status = '';

    #[Computed()]
    public function orders()
    {
        $query = Order::whereNotNull('payment_id')->where('user_id', Auth::user()->id)->orderBy('id', 'desc');

        if ($this->status != '') {
            $query->where('status', $this->status);
        }

        return $query->paginate(5);
    }

    public function render()
    {
        return view('livewire.order-list');
    }
}
