<div class="modal fade" id="modal_newOrderItem" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <form method="POST" action="/suppliers_order/add_order_item">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">New supplier order items</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="field_order_id" name="order_id" value="<?= $this->order['order_id'] ?>">
                    <input type="hidden" id="field_product_ids" name="product_ids">
                    <?php
                    $commonData = [
                        'click_url' => "/product?id=",
                        'method' => "POST",
                        'buttons' => [
                            'Select product in the table above:'
                        ],
                        'ajax' => [
                            'url' => "/catalogue/dt?table=".$this->modal_catalogue['table_name']."&page=order"
                        ]
                    ];
                    $table_data = array_merge($this->modal_catalogue, $commonData);
                    include 'application/views/templates/table.php'
                    ?>
                </div>
                <div class="modal-footer">
                    <div class="form-actions right">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                        <button id="modal_pw_create" type="submit" class="btn green" disabled>Create</button>
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
        var tableId = '#<?= $this->modal_catalogue['table_name'] ?>';
        var $table_order_item_product = $(tableId);
        $table_order_item_product.find('tbody').on('click', 'tr', function () {
            if ($table_order_item_product.find(".selected").length > 0) {
                var pwids = Array.from($table_order_item_product.DataTable().rows('.selected').data().map(function(product) {
                    return parseInt(product[0]);
                }));
                $("#field_product_ids").val(JSON.stringify(pwids));
                $("#modal_pw_create").removeAttr('disabled');
            } else {
                $("#modal_pw_create").attr('disabled', 'disabled');
            }
        });
    })
</script>