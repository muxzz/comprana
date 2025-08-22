<?php

namespace App\Http\Controllers;

use App\Models\Cart;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('carts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        $this->authorize('author', $cart);

        return view('carts.show', [
            'cart' => $cart,
        ]);
    }
}
