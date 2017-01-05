<div class="modal fade" id="modal_newProductWarehouse" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <form method="POST" action="/warehouse/add_product">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Put product to <?= $this->warehouse['name'] ?> </h4>
                </div>
                <div class="modal-body">
                    <?php
                    $table_data = [
                        'buttons' => [
                            'Select product in the table above:'
                        ],
                        'table_id' => "table_product_warehouse",
                        'ajax' => [
                            'url' => "/catalogue/dt"
                        ],
                        'column_names' => $this->full_product_column_names,
                        'hidden_by_default' => $this->full_product_hidden_columns,
                        'click_url' => "#"
                    ];
                    include 'application/views/templates/table.php'
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input name="amount" class="form-control" type="number" step="0.01">
                            </div>
                            <div class="form-group">
                                <label>Buy price</label>
                                <input name="buy_price" class="form-control" type="number" step="0.01">
                            </div>
                            <div class="form-group">
                                <label>Buy + Transport + Taxes</label>
                                <input name="buy_and_taxes" class="form-control" type="number" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sell price</label>
                                <input name="sell_price" class="form-control" type="number" step="0.01">
                            </div>
                            <div class="form-group">
                                <label>Dealer price</label>
                                <input name="dealer_price" class="form-control" type="number" step="0.01">
                            </div>
                            <div class="form-group">
                                <label>Total price</label>
                                <input name="total_price" class="form-control" type="number" step="0.01">
                            </div>
                        </div>
                        <input id="field_product_id" type="hidden" name="product_id">
                        <input type="hidden" name="warehouse_id" value="<?= $this->warehouse['warehouse_id'] ?>">
                    </div>
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
    $table_product_warehouse = $('#table_product_warehouse');
    $table_product_warehouse.find('tbody').on('click', 'tr', function () {
        console.log("click!");
        if ($table_product_warehouse.find(".selected").length == 1) {
            var pwid = $table_product_warehouse.DataTable().rows('.selected').data()[0][0];
            $("#field_product_id").val(pwid);
            $("#modal_pw_create").removeAttr('disabled');
        } else {
            $("#modal_pw_create").attr('disabled', 'disabled');
        }
    });
</script>