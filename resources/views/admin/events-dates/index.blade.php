@extends('master')

@section('title', 'Events')
@section('pageTitle', 'Events')
@section('pageDescription', 'Admin can manage Events Here!')


@section('content')
    <!-- Stats Row -->
    {{-- <div class="row stats-row">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value">24</div>
                <div class="stat-label">Total Events</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div class="stat-value">18</div>
                <div class="stat-label">Active Events</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">3</div>
                <div class="stat-label">Draft Events</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="stat-value">1,247</div>
                <div class="stat-label">Tickets Sold</div>
            </div>
        </div>
    </div> --}}

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-card-header">
            <h2 class="table-card-title">All Events</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEventModal">
                <i class="fas fa-plus me-2"></i>Create Event
            </button>
        </div>
        <div class="table-card-body">
            <table id="eventsTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Event Title</th>
                        <th>Event Location</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        <tr>
                            <td> {{ $event->id }} </td>
                            <td>
                                <strong> {{ $event->title }} </strong><br>
                                <small class="text-muted">{{ $event->description }}</small>
                            </td>
                            <td> {{ $event->location }} </td>
                            <td>
                                <span class="badge badge-active"> {{ $event->dates()->date }} </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-icon btn-edit" data-bs-toggle="modal"
                                        data-bs-target="#editEventModal" onclick="editEvent({{ $event->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-delete" onclick="deleteEvent({{ $event->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="20" class="text-center">
                                <h5> No @yield('pageTitle') Found Right Now! :( </h5>
                            </td>
                        </tr>
                    @endforelse


                </tbody>
            </table>
        </div>
    </div>
@endsection


@section('modals')
    <!-- Create Event Modal -->
    @include('admin.events.create-modal')


    <!-- Edit Event Modal -->
    @include('admin.events.edit-modal')

@endsection


@section('scripts')
    <script>
        /**
    * Admin Events Management Script
    * --------------------------------
    * - Create Event
    * - Edit Event
    * - Delete Event
    * - Uses AJAX + SweetAlert
    */

        $(document).ready(function () {

            /* ---------------------------------------------------
             | CSRF SETUP
             --------------------------------------------------- */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            /* ---------------------------------------------------
             | SIDEBAR TOGGLE (MOBILE)
             --------------------------------------------------- */
            $('#sidebarToggle').on('click', function () {
                $('.sidebar').toggleClass('show');
            });

            /* ---------------------------------------------------
             | CREATE EVENT
             --------------------------------------------------- */
            $('#createEventForm').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                Swal.fire({
                    title: 'Creating Event...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: '/admin/events',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Event Created',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $('#createEventModal').modal('hide');
                        $('#createEventForm')[0].reset();

                        setTimeout(() => location.reload(), 1600);
                    },

                    error: function (xhr) {
                        Swal.fire(
                            'Error',
                            xhr.responseJSON?.message || 'Something went wrong',
                            'error'
                        );
                    }
                });
            });

            /* ---------------------------------------------------
             | EDIT EVENT (LOAD DATA)
             --------------------------------------------------- */
            window.editEvent = function (eventId) {

                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.get(`/admin/events/${eventId}/edit`, function (event) {

                    Swal.close();

                    // $('#editEventModal').modal('show');
                    currentEventId = event.id;

                    // $('#editEventId').val(event.id);
                    $('#editEventTitle').val(event.title);
                    $('#editEventDescription').val(event.description);
                    $('#editEventLocation').val(event.location);
                    $('#editEventStatus').val(event.status);
                })
                    .fail(function () {
                        Swal.fire('Error', 'Unable to load event data', 'error');
                    });
            };

            /* ---------------------------------------------------
             | UPDATE EVENT
             --------------------------------------------------- */
            $('#editEventForm').on('submit', function (e) {
                e.preventDefault();

                let eventId = currentEventId;
                // let eventId = $('#editEventId').val();
                let formData = new FormData(this);
                formData.append('_method', 'PUT');

                Swal.fire({
                    title: 'Updating Event...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: `/admin/events/${eventId}`,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Event Updated',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $('#editEventModal').modal('hide');

                        setTimeout(() => location.reload(), 1600);
                    },

                    error: function (xhr) {
                        Swal.fire(
                            'Error',
                            xhr.responseJSON?.message || 'Update failed',
                            'error'
                        );
                    }
                });
            });

            /* ---------------------------------------------------
             | DELETE EVENT
             --------------------------------------------------- */
            window.deleteEvent = function (eventId) {

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
                        url: `/admin/events/${eventId}`,
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
            };

            /* ---------------------------------------------------
             | RESET MODALS ON CLOSE
             --------------------------------------------------- */
            $('.modal').on('hidden.bs.modal', function () {
                $(this).find('form').trigger('reset');
            });

        });

    </script>
@endsection