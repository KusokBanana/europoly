<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="#">Dashboard</a>
        </li>
        <li>Shipment</li>
        <li><?= $this->title ?></li>
    </ul>
    <!-- END BREADCRUMBS -->
</div>
<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <?php include 'application/views/templates/sidebar.php' ?>
    <!-- END SIDEBAR -->
</div>
<div class="page-fixed-main-content" <?= $this->isSidebarClosed() ? 'style="margin-left:0"' : '' ?>>
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="invoice">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-body font-dark sbold uppercase"><?= $this->title ?></span>
                </div>
            </div>
        </div>
        <div class="row">
            <form action="/truck/change_truck_select" id="truck-select-form" method="POST">
                <div class="col-xs-2">
                    <h3>Truck status:</h3>
                    <ul class="list-unstyled">
                        <li> <span class="label label-sm label-success"><?= $this->status ?></span> </li>
                    </ul>
                </div>
                <div class="col-xs-3">
                    <input type="hidden" name="pk" value="<?= $this->order['id'] ?>">
                    <h3>Delivery</h3>
                    <div>
                        <span class="label label-sm label-success">
                            Price: <?= $this->delivery['price'] ?>
                        </span>
                    </div>
                    <br>
                    <div>
                        <span>Delivery Service: </span>
                        <a href="javascript:void;" class="x-editable x-transportation_company_id"
                           data-pk="<?= $this->order['id'] ?>" data-name="transportation_company_id"
                           data-value="<?= $this->order['transportation_company_id'] ?>"
                           data-url="/truck/change_field"
                           data-original-title="Select Transportation Company">
                            <?= $this->delivery['name'] ?>
                        </a>
                    </div>
                    <br>
                    <div>
                        <span>Dispatch Date: </span>
                        <a href="javascript:;" id="editable-dispatch_date" class='x-editable'
                           data-pk="<?= $this->order['id'] ?>" data-name="dispatch_date"
                           data-value="<?= $this->order['dispatch_date'] ?>"
                           data-url='/truck/change_field'
                           data-original-title='Enter Dispatch Date'>
                            <?= $this->order['dispatch_date'] ?>
                        </a>
                    </div>
                    <br>
                    <div>
                        <span>Delivery Date: </span>
                        <a href="javascript:;" id="editable-delivery_date" class='x-editable'
                           data-pk="<?= $this->order['id'] ?>" data-name="delivery_date"
                           data-value="<?= $this->order['delivery_date'] ?>"
                           data-url='/truck/change_field'
                           data-original-title='Enter Delivery Date'>
                            <?= $this->order['delivery_date'] ?>
                        </a>
                    </div>
                </div>
                <div class="col-xs-3">
                    <h3>Customs</h3>
                    <div>
                        <span class="label label-sm label-success">
                            Price: <?= $this->customs['price'] ?>
                        </span>
                    </div>
                    <br>
                    <div>
                        <span>Customs Service: </span>
                        <a href="javascript:void;" class="x-editable x-custom_id"
                           data-pk="<?= $this->order['id'] ?>" data-name="custom_id"
                           data-value="<?= $this->order['custom_id'] ?>"
                           data-url="/truck/change_field"
                           data-original-title="Select Custom">
                            <?= $this->customs['name'] ?>
                        </a>
                    </div>
                </div>
                <div class="col-xs-4">
                    <h3> Items in this Truck
                    </h3>
                    <a class="btn btn-md blue margin-15" href="#"
                       data-toggle="modal" data-target="#modal_newTruckItem">
                        <i class="fa fa-plus"></i> Add item
                    </a>
                    <a class="btn btn-md blue hidden-print margin-15" onclick="javascript:window.print();">
                        <i class="fa fa-print"></i> Print
                    </a>
                    <button data-href="/truck/put_to_the_warehouse?truck_id=<?= $this->order['id'] ?>"
                       class="btn btn-md blue put-to-warehouse">Put to the warehouse</button>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <div>
                    Quantity: <?= round($this->sums['amount'], 3) ?>
                </div>
                <br>
                <div>
                    # of packs Sum: <?= round($this->sums['number_of_packs'], 3) ?>
                </div>
                <br>
                <div>
                    Purchase Value Sum: <?= round($this->sums['purchase_value'], 2) ?>
                </div>
                <br>
                <div>
                    Weight Sum: <?= round($this->sums['weight'], 3) ?>
                </div>
            </div>
        </div>
        <div class="row" >
	        <?php
	        $commonData = [
		        'click_url' => "javascript:;",
		        'method' => "POST",
		        'serverSide' => false,
		        'ajax' => [
			        'url' => '/truck/dt_order_items',
			        'data' => [
				        'truck_id' => $this->order["id"],
			        ]
		        ]
	        ];
	        $table_data = array_merge($this->itemsTable, $commonData);
	        include 'application/views/templates/table.php'
	        ?>
        </div>
    </div>


<div class="modal fade" id="modal_choose_warehouse" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Choose Warehouse</h4>
                </div>
                <div class="modal-body">
                    <label for="warehouse_id">Warehouse</label>
                    <select name="warehouse_id" id="warehouse_id" class="form-control">
                        <?php foreach ($this->warehouses as $warehouse): ?>
                            <option value="<?= $warehouse['value'] ?>"><?= $warehouse['text'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <div class="form-actions right">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn default btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php require_once 'modals/new_truck_item.php'; ?>
<?php require_once 'modals/split.php'; ?>
<!-- END CONTAINER -->
<script>
    $(document).ready(function () {
        var item_statuses = <?= json_encode($this->statusList); ?>;
        var transports = <?= json_encode($this->delivery['list']) ?>;
        var customs = <?= json_encode($this->customs['list']) ?>;
        var warehouses = <?= json_encode($this->warehouses); ?>;
        var table_order_items = "<?= $this->itemsTable['table_name'] ?>";
        var $table_order_items = $("#" + table_order_items);

        $table_order_items.on('draw.dt', function () {
            $('.table-confirm-btn').confirmation({
                rootSelector: '.table-confirm-btn'
            });
            $('.x-import_brokers_price, .x-import_VAT, ' +
                '.x-import_tax, .x-delivery_price').editable({
                type: "number",
                min: 0,
                step: 0.01,
                inputclass: 'form-control input-medium',
                success: function () {
                    location.reload();
                }
            });
            $('.x-amount, .x-number_of_packs').editable({
                type: "number",
                min: 0,
                step: 0.01,
                inputclass: 'form-control input-medium',
                success: function () {
                    location.reload();
                },
                validate: function(value) {
                    if (parseFloat(value) > parseFloat($(this).attr('data-value')))
                        return 'Invalid Value';
                }
            });
            $('.x-item_status').editable({
                type: "select",
                inputclass: 'form-control input-medium',
                source: item_statuses,
                success: function () {
                    location.reload();
                }
            });
            $('.x-transportation_company_id').editable({
                type: "select",
                inputclass: 'form-control input-medium',
                source: transports,
                success: function () {
                    location.reload();
                }
            });
            $('.x-custom_id').editable({
                type: "select",
                inputclass: 'form-control input-medium',
                source: customs,
                success: function () {
                    location.reload();
                }
            });


//            $('.put-to-warehouse').confirmation({
//                singleton: true,
//                popout: true,
//                onConfirm: function (e) {
//                    var btn = $(this);
//
//                    $('#modal_choose_warehouse').modal().find('form').attr('action', btn.attr('data-href'));
//                    return false;
//
////                $.ajax({
////                    url: btn.attr('data-href'),
////                    success: function() {
////                        location.href = '';
////                    }
////                })
//                }
//            });

        });

        $('body').on('click', '.put-to-warehouse', function(e) {
            e.preventDefault();
            var href = $(this).attr('data-href');

            $.ajax({
                url: href,
                type: "GET",
                data: {
                    action_id: 1
                },
                success: function(data) {
                    if (data) {
                        data = JSON.parse(data);
                        if (data.success === 0) {
                            $('#notificationModal').modal().find('.modal-body').text(data.message);
                            return false;
                        }

                        var modal = $('#modal_order_split');
                        modal.attr('data-type', 'truck').find('.modal-title').text('Put to the warehouse');
                        modal.find('#splitSubmit').text('Put');
                        modal.find('form').attr('action', '/truck/put_to_the_warehouse?action_id=2');
                        var tbody = modal.find('table tbody').empty();

                        modal.find('.select-block').empty().append('<label for="warehouse_id_choose">Warehouse</label>'+
                            '<select name="warehouse_id" id="warehouse_id_choose" class="form-control"></select>');
                        $.each(warehouses, function() {
                            var option = '<option value="' + this.value + '">' + this.text + '</option>';
                            $('#warehouse_id_choose').append(option);
                        });

                        $.each(data, function() {
                            var tr = '<tr data-item_id="'+this.item_id+'" data-amount="'+this.amount+'">';
                            var td = '<td>' + this.name + '</td>';
                            td += '<td>' + this.amount + '</td>';
                            td += '<td><input type="text" name="amounts['+this.item_id+']" ' +
                                'class="form-control amount_1" ' +
                                'value="' + (this.amount) + '" /></td>';
                            td += '<td><input type="text" readonly ' +
                                'class="form-control amount_2" value="' + (0) + '" /></td>';
                            tr += td + '</tr>';
                            tbody.append(tr);
                        });

                        modal.modal();
                    }
                }
            })

        });

        $table_order_items.find('tbody').on('click', 'tr td', function (e) {
            var target = e.target;
            var link = $(target).find('a').not('.table-confirm-btn, .x-editable, .reserve-product-btn');
            if (link.length) {
                window.location.href = link.attr('href');
            }
        });

        $('#editable-dispatch_date').editable({
            type: "date",
            inputclass: 'form-control input-medium',
            success: function () {
                location.reload();
            }
        });
        $('#editable-delivery_date').editable({
            type: "date",
            inputclass: 'form-control input-medium',
            success: function () {
                location.reload();
            }
        });

    });
</script>
