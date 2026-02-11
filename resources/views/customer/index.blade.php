@extends('master')

@section('title', 'Book Tickets')

@section('content')
    <div class="container mt-4">
        <h2>Book Your Tickets</h2>

        <div class="mb-3">
            <label>Event</label>
            <select id="eventSelect" class="form-control">
                <option value="">Select Event</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Date</label>
            <select id="dateSelect" class="form-control" disabled>
                <option value="">Select Date</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Time Slot</label>
            <select id="timeSlotSelect" class="form-control" disabled>
                <option value="">Select Time Slot</option>
            </select>
        </div>

        <div id="ticketsContainer" class="mt-4"></div>


        {{-- <form action="{{ route('paypal.create', $order->id) }}" method="GET">
            <button class="btn btn-primary">Pay with PayPal</button>
        </form> --}}
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Load events on page load
            $.get("{{ route('book.events') }}", function (events) {
                events.forEach(event => {
                    $('#eventSelect').append(`<option value="${event.id}">${event.title}</option>`);
                });
            });

            // When event changes -> load dates
            $('#eventSelect').on('change', function () {
                let eventId = $(this).val();
                $('#dateSelect').empty().append('<option value="">Select Date</option>').prop('disabled', !eventId);
                $('#timeSlotSelect').empty().append('<option value="">Select Time Slot</option>').prop('disabled', true);
                $('#ticketsContainer').empty();

                if (eventId) {
                    $.get(`/book/events/${eventId}/dates`, function (dates) {
                        dates.forEach(date => {
                            $('#dateSelect').append(`<option value="${date.id}">${date.date}</option>`);
                        });
                    });
                }
            });

            // When date changes -> load time slots
            $('#dateSelect').on('change', function () {
                let dateId = $(this).val();
                $('#timeSlotSelect').empty().append('<option value="">Select Time Slot</option>').prop('disabled', !dateId);
                $('#ticketsContainer').empty();

                if (dateId) {
                    $.get(`/book/dates/${dateId}/timeslots`, function (slots) {
                        slots.forEach(slot => {
                            $('#timeSlotSelect').append(`<option value="${slot.id}">${slot.start_time} - ${slot.end_time}</option>`);
                        });
                    });
                }
            });

            // When timeslot changes -> load tickets
            $('#timeSlotSelect').on('change', function () {
                let slotId = $(this).val();
                $('#ticketsContainer').empty();

                if (slotId) {
                    $.get(`/book/timeslots/${slotId}/tickets`, function (tickets) {
                        if (tickets.length == 0) {
                            $('#ticketsContainer').html('<p>No tickets available for this timeslot.</p>');
                            return;
                        }

                        let html = '<table class="table table-bordered">';
                        html += '<thead><tr><th>Ticket</th><th>Price</th><th>Available</th><th>Quantity</th><th></th></tr></thead><tbody>';

                        tickets.forEach(ticket => {

                            let disabled = ticket.available == 0 ? 'disabled' : '';
                            let maxQty = Math.min(ticket.max, ticket.available);

                            html += `<tr>
                                                <td>${ticket.name}</td>
                                                <td>${ticket.price} EGP</td>
                                                <td>${ticket.available}</td>
                                                <td>
                                                    <input type="number"
                                                        min="${ticket.min}"
                                                        max="${maxQty}"
                                                        value="${ticket.min}"
                                                        class="form-control ticket-qty"
                                                        data-inventory="${ticket.inventory_id}"
                                                        ${disabled}>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary add-to-cart-btn"
                                                            data-inventory="${ticket.inventory_id}"
                                                            ${disabled}>
                                                        Add to Cart
                                                    </button>
                                                </td>
                                            </tr>
                                        `;
                        });

                        html += '</tbody></table>';
                        $('#ticketsContainer').html(html);
                    });
                }
            });
        });
    </script>


    <script>
        // ADD TO CART
        $(document).on('click', '.add-to-cart-btn', function () {

            let button = $(this);
            let inventoryId = button.data('inventory');

            let quantityInput = $(`input.ticket-qty[data-inventory="${inventoryId}"]`);
            let quantity = quantityInput.val();

            button.prop('disabled', true).text('Adding...');

            $.ajax({
                url: "{{ route('cart.add') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    inventory_id: inventoryId,
                    quantity: quantity
                },

                success: function (response) {

                    button.prop('disabled', false).text('Add to Cart');

                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart!',
                        timer: 1200,
                        showConfirmButton: false
                    });

                },

                error: function (xhr) {

                    button.prop('disabled', false).text('Add to Cart');

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message ?? 'Something went wrong'
                    });
                }
            });
        });

    </script>
@endsection