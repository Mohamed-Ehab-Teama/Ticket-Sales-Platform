<div class="modal fade" id="createTimeSlotModal">
    <div class="modal-dialog">
        <form id="createTimeSlotForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Create Time Slot</h5>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Start Time</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>End Time</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Capacity</label>
                        <input type="number" name="capacity" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>
