<div class="modal fade" id="modal_newProductWarehouse" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <form method="POST" action="/warehouse/add_product">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Put product to <?= isset($this->warehouse['name']) ?
                            $this->warehouse['name'] : 'Warehouse' ?> </h4>
                </div>
                <div class="modal-body">
                    <div class="form-wizard">
                        <div class="form-body">
                            <ul class="nav nav-pills nav-justified steps">
                                <li class="active">
                                    <a href="#newProductWarehouseTab1" data-toggle="tab" class="step active">
                                        <span class="number"> 1 </span>
                                        <span class="desc"><i class="fa fa-check"></i> Select Products </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#newProductWarehouseTab2" data-toggle="tab" class="step">
                                        <span class="number"> 2 </span>
                                        <span class="desc"><i class="fa fa-check"></i> Change Products </span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="alert alert-danger error-choose-products display-none">
                                    <button class="close" data-dismiss="alert"></button> Please choose at least 1 product below.
                                </div>
                                <div class="tab-pane active" id="newProductWarehouseTab1">
                                    <?php
                                    $table_data = [
                                        'buttons' => [
                                            'Select product in the table above:'
                                        ],
                                        'table_id' => "table_product_warehouse_modal",
                                        'ajax' => [
                                            'url' => "/catalogue/dt"
                                        ],
                                        'column_names' => $this->products_column_names,
                                        'hidden_by_default' => $this->products_hidden_columns,
                                        'click_url' => "#",
                                        'originalColumns' => $this->products_originalColumns,
                                        'selectSearch' => $this->catalogue_selects,
                                        'filterSearchValues' => $this->catalogue_rows
                                    ];
                                    include 'application/views/templates/table.php'
                                    ?>
                                </div>
                                <div class="tab-pane" id="newProductWarehouseTab2">
                                    <div class="portlet-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered table-striped"
                                                   id="table_newProductWarehouse_change">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Quantity</th>
                                                        <th>Units</th>
                                                        <th>Buy Price</th>
                                                        <th>Currency</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php if(!$this->id): ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Warehouse</label>
                                                    <select name="warehouse_id" class="form-control">
                                                        <?php foreach ($this->warehouses as $warehouse):
                                                            echo '<option value="'.$warehouse['value'].'">'.$warehouse['text'].'</option>';
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <input type="hidden" name="warehouse_id" value="<?= $this->warehouse['warehouse_id'] ?>">
                                    <?php endif; ?>
                                    <div class="row">

<!--                                        <div class="col-md-6">-->
<!--                                            <div class="form-group">-->
<!--                                                <label>Quantity</label>-->
<!--                                                <input name="amount" class="form-control" type="number" step="0.01">-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <div class="col-md-6">-->
<!--                                            <div class="form-group">-->
<!--                                                <label>Buy price</label>-->
<!--                                                <input name="buy_price" class="form-control" type="number" step="0.01">-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <input id="field_product_id" type="hidden" name="product_ids">-->
                                        <div class="new-product-warehouse-inputs-block"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-actions right">
                                <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                                <button id="modal_pw_create" class="btn green disabled">Create</button>
                            </div>
                        </div>
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
        $table_product_warehouse = $('#table_product_warehouse_modal');
        var modal = $('#modal_newProductWarehouse');
        modal.find('.nav-pills .step').on('show.bs.tab', function () {
            var selectorTab = $(this).attr('href');
            if (selectorTab == '#newProductWarehouseTab1') {
                $("#modal_pw_create").addClass('disabled');
            } else if (selectorTab == '#newProductWarehouseTab2') {
                if (!$table_product_warehouse.find(".selected").length) {
                    $('.error-choose-products').show();
                    return false;
                } else {
                    $('.error-choose-products').hide();
                    var tableChange = $('#table_newProductWarehouse_change');

                    if (tableChange.attr('data-products') == undefined ||
                        $table_product_warehouse.attr('data-selected') != tableChange.attr('data-products')) {

                        var tr = '';
                        var selectedRows = $table_product_warehouse.DataTable().rows('.selected').data();
                        $.each(selectedRows, function (index) {
                            var name = getColumnValue("Name", index);
                            var units = getColumnValue("Units", index);
                            var currency = getColumnValue("Currency", index);
                            var id = getColumnValue("Id", index);

                            tr += '<tr>' + '<td>' + name + '</td>';
                            tr += '<td><a href="javascript:;" class="x-editable x-new-warehouse-product-amount"' +
                                ' data-original-title="Enter Quantity" data-name="NewWarehouseProduct['+id+'][amount]" ' +
                                'data-pk="'+id+'" data-value="0">0</a></td>';
                            tr += '<td>' + units + '</td>';
                            tr += '<td><a href="javascript:;" class="x-editable x-new-warehouse-product-price"' +
                                ' data-original-title="Enter Buy Price" data-name="NewWarehouseProduct['+id+'][buy_price]" ' +
                                'data-pk="'+id+'" data-value="0">0</a></td>';
                            tr += '<td>' + currency + '</td></tr>';
                        });
                        tableChange.attr('data-products', $table_product_warehouse.attr('data-selected'))
                            .find('tbody').empty().append(tr);

                        tableChange.find('.x-editable').editable({
                            type: "number",
                            min: 0,
                            step: 0.01,
                            inputclass: 'form-control input-medium',
                            success: function () {}
                        })
                    }
                $("#modal_pw_create").removeClass('disabled');
                }
            }
        });
        $('#modal_pw_create').on('click', function() {
            var tableChange = $('#table_newProductWarehouse_change');
            var inputsBlock = $('.new-product-warehouse-inputs-block');
            inputsBlock.empty();
            $.each(tableChange.find('.x-editable'), function() {
                var input = '<input type="hidden" name="'+$(this).attr('data-name')+'" ' +
                    'value="'+$(this).editable('getValue', true)+'" />';
                inputsBlock.append(input);
            });
            modal.find('form').submit();
        });

        function getColumnValue(name, id) {
            var index =$('#table_product_warehouse_modal_columns_choose').find(':contains('+name+')')
                .closest('label').find('input').attr('data-column');
            return $table_product_warehouse.DataTable().rows('.selected').data()[id][index];
        }
    })
</script>