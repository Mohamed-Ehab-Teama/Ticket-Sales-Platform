@extends('master')

@section('title', 'Inventory')
@section('pageTitle', 'Inventory')
@section('pageDescription', 'Admin can manage inventory here!')

@section('content')
    <!-- Table Card -->
    <div class="table-card">
        <div class="table-card-header">
            <h2 class="table-card-title">All Inventory Records</h2>
        </div>
        <div class="table-card-body">

            <!-- Filter -->
            <form method="GET" class="mb-3 d-flex gap-2" id="InventoryFilterForm">
                <input type="text" name="ticket_name" placeholder="Ticket Type Name" value="{{ request('ticket_name') }}"
                    class="form-control">
                <select name="event_id" class="form-control">
                    <option value="">All Events</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                            {{ $event->title }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" form="InventoryFilterForm" class="btn btn-primary"><i
                        class="fas fa-search me-2"></i>Filter</button>
            </form>

            <table id="inventoryTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Event</th>
                        <th>Ticket Type</th>
                        <th>Date</th>
                        <th>Timeslot</th>
                        <th>Total Quantity</th>
                        <th>Sold Quantity</th>
                        <th>Available</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories as $inventory)
                        <tr>
                            <td>{{ $inventory->id }}</td>
                            <td>{{ $inventory->ticketType->event->title ?? '-' }}</td>
                            <td>{{ $inventory->ticketType->name ?? '-' }}</td>
                            <td>{{ $inventory->timeSlot->date->date ?? '-' }}</td>
                            <td>{{ $inventory->timeSlot->start_time ?? '-' }} - {{ $inventory->timeSlot->end_time ?? '-' }}</td>

                            {{-- <td>{{ $inventory->total_quantity }}</td> --}}
                            <td>
                                <input type="number" class="form-control total-quantity-input"
                                    value="{{ $inventory->total_quantity }}" min="{{ $inventory->sold_quantity }}"
                                    data-inventory-id="{{ $inventory->id }}">
                            </td>

                            <td>{{ $inventory->sold_quantity }}</td>
                            <td>{{ ($inventory->total_quantity ?? 0) - ($inventory->sold_quantity ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <h5>
                                    No inventory records found right now! :(
                                </h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $inventories->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        /* ---------------------------------------------------
             | CSRF SETUP
             --------------------------------------------------- */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            // When admin changes the total quantity
            $('.total-quantity-input').on('change', function () {
                let input = $(this);
                let inventoryId = input.data('inventory-id');
                let newQuantity = input.val();

                // Basic validation
                if (newQuantity < 0) {
                    Swal.fire('Error', 'Total quantity cannot be negative', 'error');
                    input.val(input.prop('defaultValue'));
                    return;
                }

                Swal.fire({
                    title: 'Updating...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: `/admin/inventories/${inventoryId}/update-quantity`,
                    method: 'PATCH',
                    data: {
                        total_quantity: newQuantity
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: response.message,
                            timer: 1200,
                            showConfirmButton: false
                        });
                        input.prop('defaultValue', newQuantity); // update default value
                    },
                    error: function (xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Update failed', 'error');
                        input.val(input.prop('defaultValue')); // revert to old value
                    }
                });
            });
        });
    </script>
@endsection