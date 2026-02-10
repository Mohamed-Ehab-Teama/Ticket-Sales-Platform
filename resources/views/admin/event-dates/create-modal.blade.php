<div class="modal fade" id="createDateModal" tabindex="-1" aria-labelledby="createDateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDateModalLabel">
                    <i class="fas fa-calendar-plus me-2"></i>Create New Event Date
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createDateForm">

                    <input type="hidden" id="event_id" value="{{ $event->id }}">

                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label for="eventTitle" class="form-label required">Event Date</label>
                            <input type="date" class="form-control" id="eventTitle" name="date" required>
                        </div>


                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="createDateForm" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Create Event Date
                </button>
            </div>
        </div>
    </div>
</div>