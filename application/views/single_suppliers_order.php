        <!-- BEGIN CONTAINER -->
        <div class="container-fluid">
            <div class="page-content page-content-popup">
                <div class="page-content-fixed-header">
                    <!-- BEGIN BREADCRUMBS -->
                    <ul class="page-breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li>Admonter</li>
                        <li>Order #<?= $_GET['id'] ?></li>
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
                        <div class="row invoice-logo">
                            <div class="col-xs-6 invoice-logo-space">
                                <img src="../assets/pages/media/invoice/adminter.png" class="img-responsive" alt="" /> </div>
                            <div class="col-xs-6">
                                <p> <?= $this->title ?>
                                </p>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-xs-2">
                                <h3>Order status:</h3>
                                <ul class="list-unstyled">
                                    <li> <span class="label label-sm label-success"><?= $this->status ?></span> </li>
                                </ul>
                            </div>
                            <div class="col-xs-2">
                                <h3>Supplier:</h3>
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="javascript:;" class="x-editable x-supplier_id"
                                           data-pk="<?= $this->order['order_id'] ?>" data-name="supplier_id"
                                           data-value="<?= $this->order['supplier_id'] ?>"
                                           data-url="/suppliers_order/change_field" data-original-title="Choose Supplier">
                                            <?= $this->supplier['name'] ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-xs-4">
                                <h3>Production Date:</h3>
                                <ul class="list-unstyled">
                                    <li>
                                        <strong>01.07.2017</strong></li> <?php // TODO Max (prod date) ?>
                                </ul>
                            </div>
                            <div class="col-xs-4 invoice-block">
                                <h3>Edit order:</h3>
                                <ul class="list-unstyled">
                                    <li>
                                        <a class="btn btn-md blue hidden-print margin-bottom-5"
                                           href="javascript:;" data-toggle="modal" data-target="#modal_newOrderItem">
											<i class="fa fa-plus"></i> Add item
                                        </a>
										<a class="btn btn-md blue margin-bottom-5" onclick="javascript:window.print();"> Print
											<i class="fa fa-print"></i>
										</a>
                                        <a class="btn btn-md blue margin-bottom-5"
                                            href="javascript:;"
                                            onclick="return confirm('Are you sure to send order to Supplier?')">
                                            Send Order to Supplier
										</a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-xs-12" style="overflow-x: scroll;">
                                <table class="table table-striped table-hover" id="table_order_items">
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
                                            <th> Client </th>
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
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="portlet red box">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-cogs"></i> Payments </div>
                                        <div class="actions">
                                            <form action="payment?id=new" method="POST" id="payment-form">
                                                <input type="hidden" name="Order[category]" value="Supplier">
                                                <input type="hidden" name="Order[contractor_id]"
                                                       value="<?= $this->order['supplier_id'] ?>">
                                                <input type="hidden" name="Order[order_id]"
                                                       value="<?= $this->order['order_id'] ?>">
                                            </form>
                                            <a href="javascript:void;" onclick="$('#payment-form').submit()"
                                               class="btn btn-default btn-sm">
                                                <i class="fa fa-plus"></i> Add new </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered table-striped" id="table-payments">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Payment ID</th>
                                                    <th>Date</th>
                                                    <th>Legal entity</th>
                                                    <th>Category</th>
                                                    <th>Contractor</th>
                                                    <th>Order</th>
                                                    <th>Transfer Type</th>
                                                    <th>Currency</th>
                                                    <th>Sum</th>
                                                    <th>Direction</th>
                                                    <th>Purpose of Payment</th>
                                                    <th>Responsible Person</th>
                                                    <th>Status</th>
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
        <?php require_once 'modals/new_supplier_order_item.php'; ?>
        <!-- END CONTAINER -->
        <script>
            var clients = <?= $this->toJsList($this->clients, "client_id") ?>;
            var item_statuses = <?= json_encode($this->statusList); ?>;
            var suppliers = <?= json_encode($this->supplier['list']); ?>;
            $(document).ready(function () {
                var $table_order_items = $("#table_order_items");
                $table_order_items.DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/suppliers_order/dt_order_items',
                        data: {
                            'order_id': <?= $this->order["order_id"] ?>
                        }
                    },
                    dom: '<t>ip',
                    columnDefs: [{
                        targets: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
                        searchable: false,
                        orderable: false
                    }, {
                        targets: [0],
                        visible: false,
                        searchable: false
                    }]
                });
                $table_order_items.on('draw.dt', function () {
                    $('.x-amount, .x-number_of_packs, .x-manager_bonus_rate').editable({
                        type: "number",
                        min: 0,
                        step: 0.01,
                        inputclass: 'form-control input-medium',
                        success: function () {
                            location.reload();
                        }
                    });
//PHP: if ($this->client['type'] != 'Dealer' || $_SESSION["user_role"] == 'admin'):
                    $('.x-discount_rate').editable({
                        type: "number",
                        min: 0,
                        <?php if ($_SESSION["user_role"] != 'admin'): ?>
                        max: 10,
                        <?php endif; ?>
                        step: 0.01,
                        inputclass: 'form-control input-medium',
                        success: function () {
                            location.reload();
                        }
                    });
//PHP endif
                    $('.x-item_status').editable({
                        type: "select",
                        inputclass: 'form-control input-medium',
                        source: item_statuses,
                        success: function () {
                            location.reload();
                        }
                    });
                    $('.x-supplier_id').editable({
                        type: "select",
                        inputclass: 'form-control input-medium',
                        source: suppliers,
                        success: function () {
                            location.reload();
                        }
                    });
                });

                $('#editable-special_expenses, #editable-downpayment_rate, #editable-manager_bonus_rate').editable({
                    type: "number",
                    min: 0,
                    step: 0.01,
                    inputclass: 'form-control input-medium',
                    success: function () {
                        location.reload();
                    }
                });
                $('#editable-commission_rate').editable({
                    type: "number",
                    min: 0,
                    <?php if ($_SESSION["user_role"] != 'admin'): ?>
                    max: 10,
                    <?php endif ?>
                    step: 0.01,
                    inputclass: 'form-control input-medium',
                    success: function () {
                        location.reload();
                    }
                });
                $('#editable-expected_date_of_issue').editable({
                    type: "date",
                    inputclass: 'form-control input-medium',
                    success: function () {
                        location.reload();
                    }
                });
                $('#editable-email, #editable-city, #editable-mobile_number').editable({
                    type: "text",
                    inputclass: 'form-control input-medium',
                    success: function () {
                        location.reload();
                    }
                });
//                $('#editable-manager').editable({
//                    type: "select",
//                    inputclass: 'form-control input-medium',
//                    source: managers,
//                    success: function () {
//                        location.reload();
//                    }
//                });
//                $('#editable-commission_agent').editable({
//                    type: "select",
//                    inputclass: 'form-control input-medium',
//                    source: commission_agents,
//                    success: function () {
//                        location.reload();
//                    }
//                });
                $('#editable-client').editable({
                    type: "select",
                    inputclass: 'form-control input-medium',
                    source: clients,
                    success: function () {
                        location.reload();
                    }
                });
                $('#editable-comment').editable({
                    type: "textarea",
                    inputclass: 'form-control input-medium',
                    success: function () {
                        location.reload();
                    }
                });

                var $table_payments = $("#table-payments");
                $table_payments.DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/accountant/dt_order_payments',
                        data: {
                            'order_id': <?= $this->order["order_id"] ?>,
                            'type': 'Supplier'
                        }
                    },
                    dom: '<t>ip',
                    columnDefs: [{
                        targets: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
                        searchable: false,
                        orderable: false
                    }, {
                        targets: [0],
                        visible: false,
                        searchable: false
                    }]
                });

            });
        </script>