<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Cart $cart)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $existingItem = $cart->items()->where('product_id', $validatedData['product_id'])->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $validatedData['quantity']);
        } else {
            $cart->items()->create($validatedData);
        }

        return response()->json(['message' => 'Added to cart successfully'], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $item)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item->update($validatedData);

        return response()->json(['message' => 'Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $item)
    {
        $item->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
