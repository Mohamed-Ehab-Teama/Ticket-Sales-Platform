@extends('master')

@section('title', 'Event Dates')
@section('pageTitle', 'Event Dates')
@section('pageDescription', 'Admin can manage Event Dates Here!')


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
            <h2 class="table-card-title">All Event "{{ $event->title }}" Dates</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDateModal">
                <i class="fas fa-plus me-2"></i>Create Event Date
            </button>
        </div>
        <div class="table-card-body">
            <table id="eventsTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>Event ID</th>
                        <th>Event Title</th>
                        <th>Date ID</th>
                        <th>Event Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dates as $date)
                        <tr>
                            <td> {{ $date->event->id }} </td>
                            <td>
                                <strong> {{ $date->event->title }} </strong><br>
                            </td>

                            <td> {{ $date->id }} </td>
                            <td> {{ $date->date }} </td>

                            <td>
                                <div class="action-buttons">
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('admin.dates.time-slots.index', [$event, $date]) }}">
                                        Time Slots
                                    </a>

                                    <button class="btn btn-sm btn-icon btn-edit" data-bs-toggle="modal"
                                        data-bs-target="#EditDateModal" onclick="editDate({{ $event->id }}, {{ $date->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-delete"
                                        onclick="deleteDate({{ $event->id }}, {{ $date->id }})">
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

            <div class="mt-3">
                {{ $dates->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection


@section('modals')
    <!-- Create Event Modal -->
    @include('admin.event-dates.create-modal')


    <!-- Edit Event Modal -->
    @include('admin.event-dates.edit-modal')

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

            // let currentDateId = null;
            // let eventId = $('#event_id').val(); // hidden input
            let currentEventId = null;
            let currentDateId = null;

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
            $('#createDateForm').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                Swal.fire({
                    title: 'Creating Date...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: '/admin/events/{{ $event->id }}/dates',
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

                        $('#createDateModal').modal('hide');
                        $('#createDateForm')[0].reset();

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
            window.editDate = function (eventId, dateId) {
                currentEventId = eventId;
                currentDateId = dateId;

                Swal.fire({ title: 'Loading...', didOpen: () => Swal.showLoading() });

                $.get(`/admin/events/${eventId}/dates/${dateId}/edit`, function (date) {
                    Swal.close();

                    $('#editDate').val(date.date);
                    $('#EditDateModal').modal('show');
                });
            };

            /* ---------------------------------------------------
             | UPDATE EVENT
             --------------------------------------------------- */
            $('#editEventDateForm').on('submit', function (e) {
                e.preventDefault();

                let eventId = currentEventId;
                // let eventId = $('#editEventDateId').val();
                let formData = new FormData(this);
                formData.append('_method', 'PUT');

                Swal.fire({
                    title: 'Updating...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: `/admin/events/${currentEventId}/dates/${currentDateId}`,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: () => {
                        Swal.fire('Updated!', 'Date updated successfully', 'success');
                        $('#EditDateModal').modal('hide');
                        location.reload();
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
            window.deleteDate = function (eventId, dateId) {
                currentEventId = eventId;
                currentDateId = dateId;

                Swal.fire({
                    title: 'Delete this date?',
                    icon: 'warning',
                    showCancelButton: true
                }).then(result => {

                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: `/admin/events/${currentEventId}/dates/${currentDateId}`,
                        method: 'DELETE',

                        success: () => {
                            Swal.fire('Deleted!', 'Date removed', 'success');
                            location.reload();
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