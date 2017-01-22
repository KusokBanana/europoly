<!-- BEGIN CONTAINER -->
<div class="container-fluid">
    <div class="page-content page-content-popup">
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
        <div class="page-fixed-main-content">
            <!-- BEGIN PAGE BASE CONTENT -->
            <div class="invoice">
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
                                <ul class="list-unstyled">
                                    <li>
                                                <span class="label label-sm label-success">
                                                    Price: <?= $this->delivery['price'] ?>
                                                </span>
                                    </li>
                                    <li>
                                        <a href="javascript:void;" class="x-editable x-transportation_company_id"
                                           data-pk="<?= $this->order['id'] ?>" data-name="transportation_company_id"
                                           data-value="<?= $this->order['transportation_company_id'] ?>"
                                           data-url="/truck/change_field"
                                           data-original-title="Select Transportation Company">
                                            <?= $this->delivery['name'] ?>
                                        </a>
                                    </li>
                                </ul>
                        </div>
                        <div class="col-xs-3">
                            <h3>Customs</h3>
                            <ul class="list-unstyled">
                                <li>
                                                <span class="label label-sm label-success">
                                                    Price: <?= $this->customs['price'] ?>
                                                </span>
                                </li>
                                <li>
                                    <a href="javascript:void;" class="x-editable x-custom_id"
                                       data-pk="<?= $this->order['id'] ?>" data-name="custom_id"
                                       data-value="<?= $this->order['custom_id'] ?>"
                                       data-url="/truck/change_field"
                                       data-original-title="Select Custom">
                                        <?= $this->customs['name'] ?>
                                    </a>
                                </li>
                            </ul>

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
                            <a href="truck/put_to_the_warehouse?truck_id=<?= $this->order['id'] ?>"
                               onclick="return confirm('Are you sure to put to the warehouse?')"
                               class="btn btn-md blue">Put to the warehouse</a>
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
                    <div class="col-xs-12" style="overflow-x: scroll;">
                        <table class="table table-striped table-hover" id="table_truck_items">
                            <thead>
                            <tr>
                                <th> ID </th>
                                <th> Product </th>
                                <th> Quantity </th>
                                <th class="hidden-xs"> # of packs </th>
                                <th class="hidden-xs"> Price/unit </th>
                                <th class="hidden-xs"> Total price </th>
                                <th> Status </th>
                                <th> Weight </th>
                                <th> Downpayment rate </th>
                                <th> Client's expected date of issue </th>
                                <th> Manager </th>
                                <th> Managers order id </th>
                                <th> Suppliers order id </th>
                                <th> Client </th>
                                <th> Import VAT </th>
                                <th> Import Brokers Price </th>
                                <th> Import Tax </th>
                                <th> Delivery Price </th>
                                <th> Actions</th>
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
            <!-- END PAGE BASE CONTENT -->
        </div>
        <!-- BEGIN FOOTER -->

        <a href="#index" class="go2top">
            <i class="icon-arrow-up"></i>
        </a>
        <!-- END FOOTER -->
    </div>
</div>

<?php require_once 'modals/new_truck_item.php'; ?>
<!-- END CONTAINER -->
<script>

    var item_statuses = <?= json_encode($this->statusList); ?>;
    var transports = <?= json_encode($this->delivery['list']) ?>;
    var customs = <?= json_encode($this->customs['list']) ?>;

    $(document).ready(function () {
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
                targets: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
                searchable: false,
                orderable: false
            }, {
                targets: [0],
                visible: false,
                searchable: false
            }]
        });
        $table_order_items.on('draw.dt', function () {
            $('.x-amount, .x-number_of_packs, .x-import_brokers_price, .x-import_VAT, ' +
                '.x-import_tax, .x-delivery_price').editable({
                type: "number",
                min: 0,
                step: 0.01,
                inputclass: 'form-control input-medium',
                success: function () {
                    location.reload();
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
    });
</script>
