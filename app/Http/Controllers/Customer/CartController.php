<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = CartService::getUserCart(Auth::user())
            ->load(
                'items.inventory.ticketType.event',
                'items.inventory.timeSlot.date'
            );

        return view('customer.cart.index', compact('cart'));
    }


    public function add(Request $request)
    {
        $request->validate([
            'inventory_id'  => 'required|exists:inventories,id',
            'quantity'      => 'required|integer|min:1'
        ]);

        try {
            CartService::add(
                Auth::user(),
                $request->inventory_id,
                $request->quantity
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function update(Request $request)
    {
        $request->validate([
            'item_id'   => 'required|exists:cart_items,id',
            'quantity'  => 'required|integer|min:1'
        ]);

        $item = CartItem::where('id', $request->item_id)
            ->whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))
            ->firstOrFail();

        try {
            CartService::update($item, $request->quantity);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    
    public function remove(CartItem $item)
    {
        abort_if($item->cart->user_id !== Auth::id(), 403);

        CartService::remove($item);

        return response()->json(['success' => true]);
    }
}
