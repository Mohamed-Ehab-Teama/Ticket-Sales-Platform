<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with('items.inventory.ticketType.event', 'items.inventory.timeSlot.date')
            ->firstOrFail();

        return view('customer.checkout', compact('cart'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
        ]);

        $cart = Cart::where('user_id', Auth::id())->with('items.inventory')->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        try {
            $order = DB::transaction(function () use ($request, $cart) {
                // 1️⃣ Create Order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'total_amount' => $cart->items->sum(fn($i) => ($i->price ?? 0) * ($i->quantity ?? 0)),
                    'currency' => 'USD',
                    'status' => 'pending',
                ]);

                // 2️⃣ Create Order Items & Reduce Inventory sold_quantity
                foreach ($cart->items as $item) {
                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'ticket_type_id' => $item->inventory->ticket_type_id,
                        'time_slot_id' => $item->inventory->time_slot_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ]);

                    // Update inventory sold quantity
                    $item->inventory->increment('sold_quantity', $item->quantity);

                    // 3️⃣ Generate Tickets
                    for ($i = 0; $i < $item->quantity; $i++) {
                        $code = strtoupper(Str::random(10));

                        // Generate verification URL
                        $verificationUrl = route('tickets.verify', $code);

                        // Define file name
                        $fileName = "qrcodes/{$code}.svg";

                        // Generate QR Code (SVG format)
                        Storage::disk('public')->put(
                            $fileName,
                            QrCode::format('svg')
                                ->size(300)
                                ->generate($verificationUrl)
                        );

                        Ticket::create([
                            'order_item_id' => $orderItem->id,
                            'code'          => $code,
                            'qr_path'       => $fileName,
                        ]);
                        // Ticket::create([
                        //     'order_item_id' => $orderItem->id,
                        //     'code' => strtoupper(Str::random(12)), // unique ticket code
                        //     'qr_path' => '', // optionally generate QR later
                        // ]);
                    }
                }

                // 4️⃣ Clear Cart
                $cart->items()->delete();

                return $order;
            });

            // 5️⃣ Redirect to PayPal
            return redirect()->route('paypal.create', $order);
        } catch (\Exception $e) {
            // DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
