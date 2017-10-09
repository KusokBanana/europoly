<div class="modal fade" id="modal_cancelOrder" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/order/cancel_order">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Cancel Order </h4>
                </div>
                <div class="modal-body">
                    <input name="order_id" value="<?= $this->order['order_id']?>" type="hidden">
                    <div class="form-group">
                        <label for="modal_transfer_product_name">Cancel Order Reason:</label>
                        <textarea id="modal_transfer_product_name" name="cancel_reason" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions right">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn green">Cancel Order</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>