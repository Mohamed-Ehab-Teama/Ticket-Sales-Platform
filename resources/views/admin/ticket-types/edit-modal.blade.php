<!-- Edit Ticket Type Modal -->
<div class="modal fade" id="editTicketTypeModal">
    <div class="modal-dialog">
        <form id="editTicketTypeForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Ticket Type</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" id="editTicketName" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Price</label>
                        <input type="number" id="editTicketPrice" name="price" step="0.01" class="form-control"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Min per Order</label>
                        <input type="number" id="editTicketMin" name="min_per_order" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Max per Order</label>
                        <input type="number" id="editTicketMax" name="max_per_order" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Valid From</label>
                        <input type="date" id="editTicketFrom" name="valid_from" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Valid To</label>
                        <input type="date" id="editTicketTo" name="valid_to" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>