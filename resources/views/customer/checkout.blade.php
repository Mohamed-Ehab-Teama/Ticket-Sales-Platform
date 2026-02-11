@extends('master')

@section('title', 'Checkout')

@section('content')
    <div class="container mt-4">
        <h2>Checkout</h2>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="customer_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="customer_email" class="form-control" required>
            </div>

            <h4>Cart Summary</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Ticket</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart->items as $item)
                        <tr>
                            <td>{{ $item->inventory->ticketType->event->title }}</td>
                            <td>{{ $item->inventory->ticketType->name }}</td>
                            <td>{{ $item->inventory->timeSlot->date->date }}</td>
                            <td>{{ $item->inventory->timeSlot->start_time }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h4 class="mt-4">
                Total: {{ number_format($cart->items->sum(fn($i) => $i->price * $i->quantity), 2) }} USD
            </h4>

            <button type="submit" class="btn btn-primary mt-3">Pay with PayPal</button>
            
            {{-- <button class="btn btn-primary mt-5">
                <a href="{{ route('paypal.create') }}" style="text-decoration: none; color: white;">
                    Pay with PayPal
                </a>
            </button> --}}
        </form>
    </div>
@endsection