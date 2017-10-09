<div class="modal fade" id="modal_shipment_to_customer_modal_amount" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/order/ship_to_customer?actionId=2">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Change amount</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="order_item_ids" class="order_item_ids" value="">

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity Main</th>
                                <th>Quantity</th>
                                <th>Number of Packs</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                    <div>
                        <span class="text-danger" style="display: none;">
                            Incorrect amount value!
                        </span>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="form-actions right">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                        <button class="btn green submit">Add</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>

    $(document).ready(function() {
        $('body').on('click', '#modal_shipment_to_customer_modal_amount .submit', function() {
            var tr = $(this).closest('form').find('table tbody tr');

            var hasError = false;
            $.each(tr, function() {
                var amount = +$(this).find('.amount').val();
                var totalAmount = +$(this).find('.total').val();
                if (amount > totalAmount) {
                    hasError = true;
                }
            });

            if (hasError) {
                $(this).closest('.modal').find('.text-danger').show();
                return false;
            } else {
                $(this).closest('.modal').find('.text-danger').hide();
            }
        })
    })

</script>