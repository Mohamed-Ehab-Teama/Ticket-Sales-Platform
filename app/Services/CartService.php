<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryReservation;
use Illuminate\Container\Attributes\Auth;

class CartService
{
    // public function viewCart()
    // {
    //     $cart = Auth::user()
    //         ->cart()
    //         ->with('items.inventory.ticketType', 'items.inventory.timeSlot')
    //         ->first();
    // }





    public static function addToCart($user, Inventory $inventory, int $qty)
    {
        if ($qty <= 0) throw new \Exception('Quantity must be greater than zero');


        return DB::transaction(function () use ($user, $inventory, $qty) {
            // 🔒 Lock inventory row
            $inventory = Inventory::where('id', $inventory->id)
                ->lockForUpdate()
                ->first();

            if ($inventory->available_quantity < $qty) {
                throw new \Exception('Not enough tickets available');
            }

            $cart = Cart::firstOrCreate([
                'user_id' => $user->id
            ]);


            /** 🔒 Lock cart item row */
            $item = CartItem::where([
                'cart_id'      => $cart->id,
                'inventory_id' => $inventory->id,
            ])
                ->lockForUpdate()
                ->first();

            if ($item) {
                $item->quantity += $qty;
                $item->save();
            } else {
                $item = CartItem::create([
                    'cart_id'      => $cart->id,
                    'inventory_id' => $inventory->id,
                    'quantity'     => $qty,
                    'price'        => $inventory->ticketType->price,
                ]);
            }


            // Create / update reservation
            InventoryReservation::updateOrCreate(
                [
                    'inventory_id' => $inventory->id,
                    'cart_id' => $cart->id,
                ],
                [
                    'quantity' => $item->quantity,
                    'expires_at' => now()->addMinutes(15),
                ]
            );

            return $item;
        });
    }




    // public function RemoveItem()
    // {
    //     DB::transaction(function () use ($item) {
    //         InventoryReservation::where([
    //             'cart_id' => $item->cart_id,
    //             'inventory_id' => $item->inventory_id,
    //         ])->delete();

    //         $item->delete();
    //     });
    // }
}
