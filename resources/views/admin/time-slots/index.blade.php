@extends('master')

@section('title', 'Time Slots')
@section('pageTitle', 'Time Slots')
@section('pageDescription', 'Admin can manage Event Time Slots here')

@section('content')
    <div class="table-card">
        <div class="table-card-header">
            <h2 class="table-card-title">
                "{{ $event->title }}" — {{ $date->date }} (Time Slots)
            </h2>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTimeSlotModal">
                <i class="fas fa-plus me-2"></i>Create Time Slot
            </button>
        </div>

        <div class="table-card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($timeSlots as $slot)
                        <tr>
                            <td>{{ $slot->id }}</td>
                            <td>{{ $slot->start_time }}</td>
                            <td>{{ $slot->end_time }}</td>
                            <td>{{ $slot->capacity }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-icon btn-edit"
                                        onclick="editTimeSlot({{ $event->id }}, {{ $date->id }}, {{ $slot->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button class="btn btn-sm btn-icon btn-delete"
                                        onclick="deleteTimeSlot({{ $event->id }}, {{ $date->id }}, {{ $slot->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No time slots yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $timeSlots->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('admin.time-slots.create-modal')
    @include('admin.time-slots.edit-modal')
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            let currentEventId = null;
            let currentDateId = null;
            let currentSlotId = null;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            /* CREATE */
            $('#createTimeSlotForm').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                Swal.fire({ title: 'Creating...', didOpen: () => Swal.showLoading() });

                $.ajax({
                    url: `/admin/events/{{ $event->id }}/dates/{{ $date->id }}/time-slots`,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: () => {
                        Swal.fire('Created!', '', 'success');
                        $('#createTimeSlotModal').modal('hide');
                        location.reload();
                    },

                    error: () => Swal.fire('Error', 'Creation failed', 'error')
                });
            });

            /* EDIT */
            window.editTimeSlot = function (eventId, dateId, slotId) {
                currentEventId = eventId;
                currentDateId = dateId;
                currentSlotId = slotId;

                Swal.fire({ title: 'Loading...', didOpen: () => Swal.showLoading() });

                $.get(`/admin/events/${eventId}/dates/${dateId}/time-slots/${slotId}/edit`, function (slot) {
                    Swal.close();

                    $('#editStartTime').val(slot.start_time);
                    $('#editEndTime').val(slot.end_time);
                    $('#editCapacity').val(slot.capacity);

                    $('#editTimeSlotModal').modal('show');
                });
            };

            /* UPDATE */
            $('#editTimeSlotForm').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                console.log(formData);
                formData.append('_method', 'PUT');

                Swal.fire({ title: 'Updating...', didOpen: () => Swal.showLoading() });

                $.ajax({
                    url: `/admin/events/${currentEventId}/dates/${currentDateId}/time-slots/${currentSlotId}`,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: () => {
                        Swal.fire('Updated!', '', 'success');
                        $('#editTimeSlotModal').modal('hide');
                        location.reload();
                    },

                    error: () => Swal.fire('Error', 'Update failed', 'error')
                });
            });

            /* DELETE */
            window.deleteTimeSlot = function (eventId, dateId, slotId) {
                Swal.fire({
                    title: 'Delete this time slot?',
                    icon: 'warning',
                    showCancelButton: true
                }).then(result => {

                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: `/admin/events/${eventId}/dates/${dateId}/time-slots/${slotId}`,
                        method: 'DELETE',

                        success: () => {
                            Swal.fire('Deleted!', '', 'success');
                            location.reload();
                        },

                        error: () => Swal.fire('Error', 'Delete failed', 'error')
                    });
                });
            };

        });
    </script>
@endsection