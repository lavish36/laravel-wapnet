<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartStoreRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $carts = $user->carts;
        return response()->json($carts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $product = Product::findOrFail($request->input('product_id'));

        $cart = Cart::firstOrCreate([
            'user_id' => $user->id,
            'is_checked_out' => false,
            'total_price' => $product->price
        ]);

        $cart->items()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $request->input('quantity'),
        ]);

        return response()->json($cart, 201);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        return response()->json($cart);
    }
  
    public function checkout($cartId)
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)
                    ->where('id', $cartId)
                    ->where('is_checked_out', false)
                    ->firstOrFail();

        $totalPrice = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $cart->update(['is_checked_out' => true, 'total_price' => $totalPrice]);

        return response()->json(['message' => 'Checkout successful'], 200);

    }

}
