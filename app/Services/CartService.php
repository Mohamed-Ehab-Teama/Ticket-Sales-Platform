<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryReservation;
use Exception;
use Illuminate\Container\Attributes\Auth;

class CartService
{
    const RESERVATION_MINUTES = 15;

    public static function getUserCart($user)
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }


    public static function add($user, $inventoryId, $quantity)
    {
        return DB::transaction(function () use ($user, $inventoryId, $quantity) {

            self::cleanupExpired($inventoryId);

            $inventory = Inventory::lockForUpdate()->findOrFail($inventoryId);

            $reserved = InventoryReservation::where('inventory_id', $inventoryId)
                ->where('expires_at', '>', now())
                ->sum('quantity');

            $available = $inventory->total_quantity
                - $inventory->sold_quantity
                - $reserved;

            if ($quantity > $available) {
                throw new Exception("Only $available tickets available.");
            }

            $cart = self::getUserCart($user);

            // reservation
            InventoryReservation::create([
                'inventory_id' => $inventoryId,
                'cart_id'      => $cart->id,
                'quantity'     => $quantity,
                'expires_at'   => now()->addMinutes(self::RESERVATION_MINUTES),
            ]);

            $item = CartItem::where('cart_id', $cart->id)
                ->where('inventory_id', $inventoryId)
                ->first();

            if ($item) {
                $item->increment('quantity', $quantity);
            } else {
                $item = CartItem::create([
                    'cart_id' => $cart->id,
                    'inventory_id' => $inventoryId,
                    'quantity' => $quantity,
                    'price' => $inventory->ticketType->price,
                ]);
            }

            return $item;
        });
    }


    public static function update($cartItem, $newQuantity)
    {
        return DB::transaction(function () use ($cartItem, $newQuantity) {

            $inventory = Inventory::lockForUpdate()->findOrFail($cartItem->inventory_id);

            self::cleanupExpired($inventory->id);

            $reserved = InventoryReservation::where('inventory_id', $inventory->id)
                ->where('expires_at', '>', now())
                ->sum('quantity');

            $available = $inventory->total_quantity
                - $inventory->sold_quantity
                - $reserved
                + $cartItem->quantity;

            if ($newQuantity > $available) {
                throw new Exception("Only $available tickets available.");
            }

            $difference = $newQuantity - $cartItem->quantity;

            if ($difference > 0) {
                InventoryReservation::create([
                    'inventory_id' => $inventory->id,
                    'cart_id'      => $cartItem->cart_id,
                    'quantity'     => $difference,
                    'expires_at'   => now()->addMinutes(self::RESERVATION_MINUTES),
                ]);
            }

            $cartItem->update(['quantity' => $newQuantity]);

            return $cartItem;
        });
    }


    public static function remove($cartItem)
    {
        return DB::transaction(function () use ($cartItem) {

            InventoryReservation::where('inventory_id', $cartItem->inventory_id)
                ->where('cart_id', $cartItem->cart_id)
                ->delete();

            $cartItem->delete();
        });
    }

    
    public static function cleanupExpired($inventoryId)
    {
        InventoryReservation::where('inventory_id', $inventoryId)
            ->where('expires_at', '<', now())
            ->delete();
    }
}
