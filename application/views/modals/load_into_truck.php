<?php
$products = [];
$columnNames = [];
if (isset($_POST['table_data']) && $post = $_POST['table_data']) {
    $columnNames = $post['column_names'];
    $products = $post['products'];
    $isError = false;
    $errorMessage = '';
    if (empty($products)) {
        $isError = true;
        $errorMessage = 'Choose at least 1 item';
    }
}
else {
    die();
}
?>
<div class="modal-dialog <?= !$isError ? 'modal-full' : '' ?>">
    <div class="modal-content">
        <form method="POST" action="/truck/add_order_in_truck">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Truck</h4>
            </div>
            <div class="modal-body">
                <?php if (!$isError): ?>
                    <input type="hidden" id="field_order_id" name="order_id" value="">
                    <input type="hidden" id="field_product_ids" name="product_ids">
                    <?php
                        $table_data = [
                            'buttons' => [],
                            'table_id' => "new_truck",
                            'ajax' => [
                                'url' => "/suppliers_orders/dt_suppliers_to_truck",
                                'data' => json_encode($products)
                            ],
                            'column_names' => $columnNames,
                            'hidden_by_default' => "[]",
                            'click_url' => "javascript:;"
                        ];
                        include '../templates/table.php';
                        foreach ($products as $key => $product) {
                            echo "<input type='hidden' name='truck_products[" . $key .
                                "]' value='".$product."'>";
                        }
                    ?>
                    <div class="form-group">
                        <label for="truck_id">Add to TRUCK:</label>
                        <select id="truck_id" name="truck_id" class="form-control" required>
                            <option selected value="0">New Truck</option>
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
                        <button type="submit" class="btn green">Load</button>
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
        url: 'truck/get_active_trucks',
        success: function (data) {
            var ids = JSON.parse(data);
            var truckSelect = $('#truck_id');
            var options = '<option selected value="0">New Truck</option>';
            ids.forEach(function(item) {
                if (item) {
                    options += '<option value="' + item +'">'+item+'</option>'
                }
            });
            truckSelect.html(options);
        }
    })
</script>
<!---->
<!--<script>-->
<!--    var $table_order_item_product = $('#table_order_item_product');-->
<!--    $table_order_item_product.find('tbody').on('click', 'tr', function () {-->
<!--        if ($table_order_item_product.find(".selected").length > 0) {-->
<!--            var pwids = Array.from($("#table_order_item_product").DataTable().rows('.selected').data().map(function(product) {-->
<!--                return parseInt(product[0]);-->
<!--            }));-->
<!--            $("#field_product_ids").val(JSON.stringify(pwids));-->
<!--            $("#modal_pw_create").removeAttr('disabled');-->
<!--        } else {-->
<!--            $("#modal_pw_create").attr('disabled', 'disabled');-->
<!--        }-->
<!--    });-->
<!--</script>-->
