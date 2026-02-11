@extends('master')
@section('title', 'My Cart')
@section('pageTitle', 'Cart')

@section('content')

    <div class="container">
        <h2>Your Cart</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Ticket</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart->items as $item)
                    <tr>
                        <td>{{ $item->inventory->ticketType->event->title }}</td>
                        <td>{{ $item->inventory->ticketType->name }}</td>
                        <td>{{ $item->inventory->timeSlot->date->date }}</td>
                        <td>{{ $item->inventory->timeSlot->start_time }}</td>
                        <td>{{ $item->price }}</td>
                        <td>
                            <input type="number" value="{{ $item->quantity }}" min="1" class="form-control quantity-input"
                                data-id="{{ $item->id }}">
                        </td>
                        <td>{{ number_format($item->price * $item->quantity, 2, ".", "") }}</td>
                        <td>
                            <button class="btn btn-danger remove-item" data-id="{{ $item->id }}">
                                Remove
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4 class="mt-2">
            Total:
            {{ $cart->items->sum(fn($i) => $i->price * $i->quantity) }}
        </h4>

        <button class="btn btn-primary mt-5">
            <a href="{{ route('checkout.index') }}" style="text-decoration: none; color: white;">
                Proceed To Checkout
            </a>
        </button>

    </div>

@endsection


@section('scripts')
    <script>

        $('.quantity-input').change(function () {
            let itemId = $(this).data('id');
            let quantity = $(this).val();

            $.post("{{ route('cart.update') }}", {
                _token: "{{ csrf_token() }}",
                item_id: itemId,
                quantity: quantity
            }, function () {
                location.reload();
            });
        });

        $('.remove-item').click(function () {
            let itemId = $(this).data('id');

            $.ajax({
                url: '/cart/remove/' + itemId,
                type: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function () {
                    location.reload();
                }
            });
        });

    </script>
@endsection