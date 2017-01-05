<div class="modal fade" id="modal_transfer" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/warehouse/transfer">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Move product to </h4>
                </div>
                <div class="modal-body">
                    <input id="modal_transfer_product_warehouse_id" name="product_warehouse_id" type="hidden">
                    <div class="form-group">
                        <label for="modal_transfer_product_name">Product:</label>
                        <input id="modal_transfer_product_name" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="modal_transfer_from_warehouse_id">From Warehouse:</label>
                        <select id="modal_transfer_from_warehouse_id" class="form-control" readonly>
                            <option value="1">Main</option>
                            <option value="2">Sales Out</option>
                            <option value="3">Samples</option>
                            <option value="4">Claimed</option>
                            <option value="5">Upcoming Delivery</option>
                            <option value="6">Expects Issue</option>
                            <option value="7">Other</option>
                        </select>
                        <script>
                            $("#modal_transfer_from_warehouse_id").val("<?= $this->id ?>");
                        </script>
                    </div>
                    <div class="form-group">
                        <label for="modal_transfer_to_warehouse_id">To Warehouse:</label>
                        <select id="modal_transfer_to_warehouse_id" name="warehouse_id" class="form-control" required>
                            <option value="1">Main</option>
                            <option value="2">Sales Out</option>
                            <option value="3">Samples</option>
                            <option value="4">Claimed</option>
                            <option value="5">Upcoming Delivery</option>
                            <option value="6">Expects Issue</option>
                            <option value="7">Other</option>
                        </select>
                        <script>
                            $("#modal_transfer_to_warehouse_id").find("option[value=<?= $this->id ?>]").remove();
                        </script>
                    </div>
                    <div class="form-group">
                        <label for="modal_transfer_amount">Quantity:</label>
                        <input id="modal_transfer_amount" name="amount" type="number" step="0.01" class="form-control" value="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions right">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn green">Move</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>