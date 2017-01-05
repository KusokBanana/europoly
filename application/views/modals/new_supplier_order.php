<?php
$products = [];
$columnNames = [];
if (isset($_POST['table_data']) && $post = $_POST['table_data']) {
    $columnNames = $post['column_names'];
    $products = $post['products'];
    $error = $post['error'];
    $isError = false;
    $errorMessage = '';
    if (empty($products)) {
        $isError = true;
        $errorMessage = 'Choose at least 1 item';
    }
    if ($error) {
        $isError = true;
        $errorMessage = $error;
    }
}
else {
    die();
}

?>
<div class="modal-dialog <?= !$isError ? 'modal-full' : '' ?>">
    <div class="modal-content">
        <form method="POST" action="/suppliers_orders/add_suppliers_order">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Supplier Order</h4>
            </div>
            <div class="modal-body">
                <?php if (!$isError): ?>
                    <input type="hidden" id="field_order_id" name="order_id" value="">
                    <input type="hidden" id="field_product_ids" name="product_ids">
                    <?php
                        $table_data = [
                            'buttons' => [],
                            'table_id' => "new_supplier_order_table",
                            'ajax' => [
                                'url' => "/managers_orders/dt_managers_orders_to_suppliers",
                                'data' => json_encode($products)
                            ],
                            'column_names' => $columnNames,
                            'hidden_by_default' => "[]",
                            'click_url' => "javascript:;"
                        ];
                        include '../templates/table.php';
                    foreach ($products as $key => $product) {
                        echo "<input type='hidden' name='suppliers_products[" . $key .
                            "]' value='".$product."'>";
                    }
                    ?>
                <div class="form-group">
                    <label for="suppliers_order_id">Add to Supplier Order:</label>
                    <select id="suppliers_order_id" name="suppliers_order_id" class="form-control" required>
                        <option selected value="0">New Order</option>
                    </select>
                </div>
                <?php else: ?>
                    <h4 class="text-danger text-center"><?= $errorMessage ?></h4>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <div class="form-actions right">
                    <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                    <?php if (!$isError): ?>
                        <button type="submit" class="btn green">Create</button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script>
    $.ajax({
        url: 'suppliers_orders/get_active_suppliers_orders',
        success: function (data) {
            var ids = JSON.parse(data);
            var suppliersSelect = $('#suppliers_order_id');
            var options = '<option selected value="0">New Order</option>';
            ids.forEach(function(item) {
                if (item) {
                    console.log(item);
                    options += '<option value="' + item['id'] +'">'+item['brand'] + ', ' + item['date'] + ', №' +
                        item['id'] + '</option>'
                }
            });
            suppliersSelect.html(options);
        }
    })
</script>

<!--<script>-->
<!--    var $table_order_item_product = $('#new_supplier_order_table');-->
<!--    $table_order_item_product.find('tbody').on('click', 'tr', function () {-->
<!--        if ($table_order_item_product.find(".selected").length > 0) {-->
<!--            var pwids = Array.from($("#new_supplier_order_table").DataTable().rows('.selected').data().map(function(product) {-->
<!--                console.log(product);-->
<!--                console.log(pwids);-->
<!--                return parseInt(product[0]);-->
<!--            }));-->
<!--            $("#field_product_ids").val(JSON.stringify(pwids));-->
<!--            $("#modal_pw_create").removeAttr('disabled');-->
<!--        } else {-->
<!--            $("#modal_pw_create").attr('disabled', 'disabled');-->
<!--        }-->
<!--    });-->
<!--</script>-->
