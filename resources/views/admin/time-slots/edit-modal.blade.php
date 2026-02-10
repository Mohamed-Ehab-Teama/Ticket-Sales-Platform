<div class="modal fade" id="editTimeSlotModal">
    <div class="modal-dialog">
        <form id="editTimeSlotForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Time Slot</h5>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Start Time</label>
                        <input type="time" id="editStartTime" name="start_time" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>End Time</label>
                        <input type="time" id="editEndTime" name="end_time" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Capacity</label>
                        <input type="number" id="editCapacity" name="capacity" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
