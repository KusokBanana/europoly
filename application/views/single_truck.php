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
                    <a class="btn btn-md blue margin-15" href="javascript:;"
                       data-toggle="modal" data-target="#modal_newTruckItem">
                        <i class="fa fa-plus"></i> Add item
                    </a>
                    <a class="btn btn-md blue hidden-print margin-15" onclick="javascript:window.print();">
                        <i class="fa fa-print"></i> Print
                    </a>
                    <a data-href="/truck/put_to_the_warehouse?truck_id=<?= $this->order['id'] ?>"
                       data-toggle="confirmation" data-singleton="true" data-popout="true"
                       class="btn btn-md blue print-btn-truck">Put to the warehouse</a>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <div>
                    Weight Sum: <?= $this->sums['weight'] ?>
                </div>
                <br>
                <div>
                    # of packs Sum: <?= $this->sums['number_of_packs'] ?>
                </div>
                <br>
                <div>
                    Total price Sum: <?= $this->sums['totalPrice'] ?>
                </div>
            </div>
        </div>
        <div class="row" >
            <div class="col-xs-12 table-scrollable"">
                <table class="table table-striped table-hover" id="table_truck_items">
                    <thead>
                    <tr>
                        <?php
                        $column_name_ids = [];
                        if (!empty($this->column_names)) {
                            foreach ($this->column_names as $key => $column_name) {
                                echo '<th>' . $column_name . '</th>';
                                if ($key)
                                    $column_name_ids[] = $key;
                            }
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="12" class="dataTables_empty">Loading data from server...</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'modals/new_truck_item.php'; ?>
<!-- END CONTAINER -->
<script>
    $(document).ready(function () {
        var item_statuses = <?= json_encode($this->statusList); ?>;
        var transports = <?= json_encode($this->delivery['list']) ?>;
        var customs = <?= json_encode($this->customs['list']) ?>;
        var $column_name_ids = <?= json_encode($column_name_ids); ?>;
        var $table_order_items = $("#table_truck_items");
        $table_order_items.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/truck/dt_order_items',
                data: {
                    'order_id': <?= $this->order["id"] ?>
                }
            },
            dom: '<t>ip',
            columnDefs: [{
                targets: $column_name_ids,
                searchable: false,
                orderable: false
            }, {
                targets: [0],
                visible: false,
                searchable: false
            }]
        });
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
        });
        $table_order_items.find('tbody').on('click', 'tr td', function (e) {
            var target = e.target;
            var link = $(target).find('a').not('.table-confirm-btn, .x-editable, .reserve-product-btn');
            if (link.length) {
                window.location.href = link.attr('href');
            }
        });
            $('.print-btn-truck').confirmation({
                singleton: true,
                popout: true,
                onConfirm: function () {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('data-href'),
                        success: function(data) {
                            if (data) {
                                location.href = data;
                            }
                        }
                    })
                }
            });
    });
</script>
