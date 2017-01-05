<div class="modal fade" id="modal_newOrder" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/sales_manager/add_order">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">New Order</h4>
                </div>
                <div class="modal-body">
                    <input id="modal_new_order_sales_manager" type="hidden" name="sales_manager_id" class="form-control" required>
                    <div class="form-group">
                        <label for="modal_new_order_sales_manager_2">Sales Manager</label>
                        <select id="modal_new_order_sales_manager_2" class="form-control" disabled>
                            <option disabled selected value></option>
                            <?php
                            foreach ($this->managers as $manager) {
                                echo "<option value='{$manager["user_id"]}'>{$manager["name"]}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="modal_new_order_client">Client</label>
                        <select id="modal_new_order_client" name="client_id" class="form-control" required>
                            <option disabled selected value></option>
                            <?php
                            foreach ($this->clients as $client) {
                                echo "<option value='{$client["client_id"]}'>{$client["name"]}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions right">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn green">Create</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
