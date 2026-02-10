@extends('master')

@section('title', 'Ticket Types')
@section('pageTitle', 'Ticket Types')
@section('pageDescription', 'Manage Ticket Types for Event: ' . $event->title)

@section('content')
    <div class="table-card">
        <div class="table-card-header">
            <h2>Ticket Types for "{{ $event->title }}"</h2>
            <a class="btn btn-warning btn-sm" href="{{ route('admin.events.index') }}">
                Back to Events
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTicketTypeModal">
                <i class="fas fa-plus me-2"></i>Create Ticket Type
            </button>
        </div>

        <div class="table-card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Min / Max per Order</th>
                        <th>Valid From / To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ticketTypes as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->name }}</td>
                            <td>{{ $ticket->price }}</td>
                            <td>{{ $ticket->min_per_order }} / {{ $ticket->max_per_order }}</td>
                            <td>{{ $ticket->valid_from ?? '-' }} / {{ $ticket->valid_to ?? '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-edit" onclick="editTicket({{ $ticket->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-delete" onclick="deleteTicket({{ $event->id }}, {{ $ticket->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No Ticket Types Yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modals')
    <!-- Create Modal -->
    @include('admin.ticket-types.create-modal')

    <!-- Edit Modal -->
    @include('admin.ticket-types.edit-modal')
@endsection

@section('scripts')
    <script>
        let currentTicketId = null;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Load ticket data for editing
        function editTicket(ticketId) {
            currentTicketId = ticketId;

            $.get(`/admin/events/{{ $event->id }}/ticket-type/${ticketId}/edit`, function (ticket) {
                $('#editTicketName').val(ticket.name);
                $('#editTicketPrice').val(ticket.price);
                $('#editTicketMin').val(ticket.min_per_order);
                $('#editTicketMax').val(ticket.max_per_order);
                $('#editTicketFrom').val(ticket.valid_from);
                $('#editTicketTo').val(ticket.valid_to);

                $('#editTicketTypeModal').modal('show');
            });
        }

        // Create ticket type via AJAX
        $('#createTicketTypeForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: `/admin/events/{{ $event->id }}/ticket-type`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'TicketType Created',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $('#createTicketTypeModal').modal('hide');
                    location.reload();
                },
                error: (xhr) => {
                    alert(xhr.responseJSON?.message || 'Error creating ticket type');
                }
            });
        });

        // Update ticket type via AJAX
        $('#editTicketTypeForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.append('_method', 'PUT');

            $.ajax({
                url: `/admin/events/{{ $event->id }}/ticket-type/${currentTicketId}`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'TicketType Updated',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $('#editTicketTypeModal').modal('hide');
                    location.reload();
                },
                error: (xhr) => {
                    alert(xhr.responseJSON?.message || 'Error updating ticket type');
                }
            });
        });


        // Delete ticket type via AJAX
        function deleteTicket(eventId, ticketId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {

                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: `/admin/events/${eventId}/ticket-type/${ticketId}`,
                    method: 'DELETE',
                    success: function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            timer: 1200,
                            showConfirmButton: false
                        });

                        setTimeout(() => location.reload(), 1300);
                    },
                    error: function () {
                        Swal.fire('Error', 'Delete failed', 'error');
                    }
                });
            });
        }

        // Reset modals on close
        $('.modal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });
    </script>
@endsection