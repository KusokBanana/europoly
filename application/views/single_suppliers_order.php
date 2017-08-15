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
<div class="page-fixed-main-content" <?= $this->isSidebarClosed() ? 'style="margin-left:0"' : '' ?>>
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="invoice">
        <div class="invoice-logo">
            <span class="caption-subject font-dark sbold uppercase"><?= $this->title ?>
                <span class="hidden-xs">| <?= $this->order['supplier_date_of_order'] ?> </span>
            </span>
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
                        <a href="" class="x-editable x-supplier_id"
                           data-pk="<?= $this->order['order_id'] ?>" data-name="supplier_id"
                           data-value="<?= $this->order['supplier_id'] ?>"
                           data-url="/suppliers_order/change_field" data-original-title="Choose Supplier">
                            <?= $this->supplier['name'] ?>
                        </a>
                    </li>
                    <li><br>
                        <span>Supplier Verification ID: </span>
                        <a href="" class="x-editable x-supplier_verification"
                           data-pk="<?= $this->order['order_id'] ?>" data-name="supplier_verification"
                           data-value="<?= $this->order['supplier_verification'] ?>"
                           data-url="/suppliers_order/change_field" data-original-title="Choose Supplier">
                            <?= $this->order['supplier_verification'] ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-xs-4">
                <h3>Production Date:</h3>
                <ul class="list-unstyled">
                    <li>
                        <strong> <?= $this->order['production_date'] ?></strong>
                    </li>
                </ul>
            </div>
            <div class="col-xs-4 invoice-block">
                <h3>Edit order:</h3>
                <ul class="list-unstyled">
                    <li>
                        <a class="btn btn-md blue hidden-print margin-bottom-5"
                           href="" data-toggle="modal" data-target="#modal_newOrderItem">
                            <i class="fa fa-plus"></i> Add item
                        </a>
<!--										<a class="btn btn-md blue margin-bottom-5" onclick="javascript:window.print();"> Print-->
<!--											<i class="fa fa-print"></i>-->
<!--										</a>-->
                    </li>
                </ul>
            </div>
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
        <div class="row">
	        <?php
	        $commonData = [
		        'click_url' => "javascript:;",
		        'method' => "POST",
		        'serverSide' => false,
		        'ajax' => [
			        'url' => '/suppliers_order/dt_order_items',
			        'data' => [
				        'order_id' => $this->order["order_id"]
			        ]
		        ]
	        ];
            $table_data = array_merge($this->itemsTable, $commonData);
            include 'application/views/templates/table.php'
            ?>
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
	                    <?php
	                    $commonData = [
		                    'click_url' => "javascript:;",
		                    'method' => "POST",
		                    'serverSide' => false,
		                    'ajax' => [
			                    'url' => '/accountant/dt_order_payments',
			                    'data' => [
				                    'order_id' => $this->order["order_id"],
				                    'type' => PAYMENT_CATEGORY_SUPPLIER,
			                    ]
		                    ]
	                    ];
	                    $table_data = array_merge($this->paymentsTable, $commonData);
	                    include 'application/views/templates/table.php'
	                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'modals/new_supplier_order_item.php'; ?>
<!-- END CONTAINER -->
<script>
    $(document).ready(function () {
        var clients = <?= $this->toJsList($this->clients, "client_id") ?>;
        var item_statuses = <?= json_encode($this->statusList); ?>;
        var suppliers = <?= json_encode($this->supplier['list']); ?>;
        var table_order_items = "<?= $this->itemsTable['table_name'] ?>";
        var $table_order_items = $("#" + table_order_items);

        $table_order_items.on('draw.dt', function () {
            $('.table-confirm-btn').confirmation({
                rootSelector: '.table-confirm-btn'
            });
            $('.x-amount, .x-number_of_packs, .x-manager_bonus_rate, .x-purchase_price').editable({
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
            $.each($('.x-item_status'), function() {
                var $input = $(this);
                var val = $input.attr('data-value');
                var isApply = false;
                $.each(item_statuses, function() {
                    if (this.value === val) {
                        isApply = true;
                        return false;
                    }
                });
                if (isApply) {
                    $input.editable({
                        type: "select",
                        inputclass: 'form-control input-medium',
                        source: item_statuses,
                        success: function () {
                            location.reload();
                        }
                    }).on('shown', function(e, editable) {
                        var popover = editable.input.$input.closest('.popover');
                        popover.closest('.table-scrollable').parent().append(popover);
                    });
                } else {
                    var text = $input.text();
                    $input.replaceWith(text);
                }
            });
            $('.x-supplier_id').editable({
                type: "select2",
                inputclass: 'form-control input-medium',
                source: suppliers,
                success: function () {
                    location.reload();
                }
            });
            $('.x-supplier_verification').editable({
                type: "text",
                inputclass: 'form-control input-medium',
                success: function () {
                    location.reload();
                }
            });
            $('.x-production_date').editable({
                type: "date",
                inputclass: 'form-control input-medium',
                format: 'yyyy-mm-dd',
                viewformat: 'yyyy-mm-dd',
                datepicker: {
                    weekStart: 1
                },
                success: function () {
                    location.reload();
                }
            }).on('shown', function(e, editable) {
                var popover = editable.input.$input.closest('.popover');
                popover.closest('.table-scrollable').parent().append(popover);
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

        var table_payments = "<?= $this->paymentsTable['table_name'] ?>";
        var $table_payments = $("#" + table_payments);

        $table_payments.on('draw', function() {
            var contractors = $('table').find('.change-me-contractor');
            $.each(contractors, function() {
                if ($(this).attr('data-type') && $(this).text()) {
                    var string = $(this).attr('data-type') + '.' + $(this).text();
                    var that = $(this);
                    $.ajax({
                        url: '/order/change_contractor_id_to_name',
                        data: {
                            tableAndId: string
                        },
                        type: "GET",
                        success: function(data) {
                            if (data) {
                                that.text(data);
                            }
                        }
                    })
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
        $table_payments.find('tbody').on('click', 'tr td', function (e) {
            var target = e.target;
            var link = $(target).find('a').not('.table-confirm-btn, .x-editable, .reserve-product-btn');
            if (link.length) {
                window.location.href = link.attr('href');
            }
        });

    });
</script>