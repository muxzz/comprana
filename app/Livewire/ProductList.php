<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    #[Url()]
    public $search = '';

    #[Url()]
    public $section = '';

    #[Url()]
    public $order = 'asc';

    #[On('search')]
    public function updateSearch(string $value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="flex my-40 items-center justify-center space-x-2">
            <div class="w-4 h-4 rounded-full animate-pulse dark:bg-violet-400"></div>
            <div class="w-4 h-4 rounded-full animate-pulse dark:bg-violet-400"></div>
            <div class="w-4 h-4 rounded-full animate-pulse dark:bg-violet-400"></div>
        </div>
        HTML;
    }

    #[Computed()]
    public function products()
    {
        $query = Product::select('id', 'name', 'price', 'stock', 'section_id')->search($this->search)
            ->with(['image:product_id,name']);

        if ($this->search != '') {
            $this->order = $this->order === 'asc' || $this->order === 'desc' ? $this->order : 'asc';
            $query->search($this->search)->orderBy('price', $this->order);
        }

        if ($this->section === '') {
            $query->with('section:id,name')->orderBy('id', 'desc');
        } else {
            $query->whereHas('section', function ($query) {
                $query->where('name', $this->section);
            });
        }

        return $query->paginate(12);
    }

    public function render()
    {
        return view('livewire.product-list');
    }
}
