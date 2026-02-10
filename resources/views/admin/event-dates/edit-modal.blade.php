<div class="modal fade" id="EditDateModal" tabindex="-1" aria-labelledby="EditDateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditDateModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Event
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEventDateForm">
                    <input type="hidden" id="editEventDateId">
                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label for="editDate" class="form-label required">Event Date</label>
                            <input type="date" class="form-control" id="editDate" name="date" required>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editEventDateForm" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Event Date
                </button>
            </div>
        </div>
    </div>
</div>