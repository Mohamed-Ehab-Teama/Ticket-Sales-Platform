<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEventModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Event
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEventForm">
                    <input type="hidden" id="editEventId">
                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label for="editEventTitle" class="form-label required">Event Title</label>
                            <input type="text" class="form-control" id="editEventTitle" name="title" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="editEventDescription" class="form-label required">Description</label>
                            <textarea class="form-control" id="editEventDescription" required name="description"></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="editEventLocation" class="form-label required">Location</label>
                            <input type="text" class="form-control" id="editEventLocation" name="location" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="editEventStatus" class="form-label required">Status</label>
                            <select class="form-select" id="editEventStatus" name="status" required>
                                <option value="" selected hidden disabled>Select Status</option>
                                {{-- <option value="active" @selected($event->status == 'active')> --}}
                                <option value="active">
                                    Active
                                </option>
                                {{-- <option value="draft" @selected($event->status == 'draft')> --}}
                                <option value="draft">
                                    Draft
                                </option>
                                {{-- <option value="archived" @selected($event->status == 'archived')> --}}
                                <option value="archived">
                                    Archived
                                </option>
                            </select>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editEventForm" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Event
                </button>
            </div>
        </div>
    </div>
</div>