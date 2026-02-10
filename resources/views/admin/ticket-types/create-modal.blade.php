<!-- Create Ticket Type Modal -->
<div class="modal fade" id="createTicketTypeModal">
    <div class="modal-dialog">
        <form id="createTicketTypeForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Create Ticket Type</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Price</label>
                        <input type="number" name="price" step="0.01" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Min per Order</label>
                        <input type="number" name="min_per_order" class="form-control" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label>Max per Order</label>
                        <input type="number" name="max_per_order" class="form-control" value="10" required>
                    </div>
                    <div class="mb-3">
                        <label>Valid From</label>
                        <input type="date" name="valid_from" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Valid To</label>
                        <input type="date" name="valid_to" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>