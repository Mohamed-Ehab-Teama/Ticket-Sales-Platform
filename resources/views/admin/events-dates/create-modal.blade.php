<div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEventModalLabel">
                    <i class="fas fa-calendar-plus me-2"></i>Create New Event
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createEventForm">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="eventTitle" class="form-label required">Event Title</label>
                            <input type="text" class="form-control" id="eventTitle" placeholder="Enter event title"
                                name="title" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="eventDescription" class="form-label required">Description</label>
                            <textarea class="form-control" id="eventDescription" placeholder="Enter event description"
                                required name="description"></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="eventLocation" class="form-label required">Location</label>
                            <input type="text" class="form-control" id="eventLocation" placeholder="Enter location"
                                name="location" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="eventCategory" class="form-label required">Category</label>
                            <select class="form-select" id="eventCategory" name="status" required>
                                <option value="" selected hidden disabled>Select category</option>
                                <option value="active"> Active </option>
                                <option value="draft"> Draft </option>
                                <option value="archived"> Archived </option>
                            </select>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="createEventForm" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Create Event
                </button>
            </div>
        </div>
    </div>
</div>