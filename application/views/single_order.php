<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li>
            <a href="javascript:;">Sales</a>
        </li>
        <li><?= $this->title ?></li>
    </ul>
    <!-- END BREADCRUMBS -->

    <div class="content-header-menu">
        <!-- BEGIN MENU TOGGLER -->
        <button type="button" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="toggle-icon">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </span>
        </button>
        <!-- END MENU TOGGLER -->
    </div>
</div>
<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <?php include 'application/views/templates/sidebar.php' ?>
    <!-- END SIDEBAR -->
</div>
<div class="page-fixed-main-content" <?= $this->isSidebarClosed() ? 'style="margin-left:0"' : '' ?>>
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12">
            <!-- Begin: life time stats -->
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase"><?= $this->title ?>
                            <span class="hidden-xs">| <?= $this->order['start_date'] ?>
                                <span class="label label-warning" style="white-space: normal;">
                                    <?= $this->order_status ?>
                                </span>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="portlet green-meadow box">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>Order Details
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Order Date & Time:</div>
                                    <div class="col-md-7 value">
                                        <?php if ($this->user->role_id == ROLE_ADMIN || $this->user->role_id == ROLE_SALES_MANAGER): ?>
                                            <a href="#"
                                               class="x-editable editable editable-click editable-empty"
                                               id="editable-start_date-visible"><?= $this->order['start_date'] ?></a>
                                            <input type="text" name="start_date" id="editable-start_date"
                                                   style="display: none;"
                                                   data-pk="<?= $this->order['order_id'] ?>"
                                                   value="<?= $this->order['start_date'] ?>">
                                            <div class="editable-buttons">
                                                <button type="submit" class="btn blue editable-submit start_date-btn"
                                                        style="display: none;">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button type="button" class="btn default editable-cancel start_date-btn"
                                                        style="display: none;">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <?= $this->order['start_date'] ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row static-info">
                                    <div class="col-md-5 name"> Order Status:</div>
                                    <div class="col-md-7 value">
                                        <span class="label label-warning"><?= $this->order_status ?></span>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Evropoly Contractor:</div>
                                    <div class="col-md-7 value">
                                        <a href="javascript:;" id="editable-legal_entity" class='x-editable'
                                           data-pk="<?= $this->order['order_id'] ?>"
                                           data-name="legal_entity_id" data-value="<?= $this->order['legal_entity_id'] ?>"
                                           data-url='/order/change_field' data-original-title='Choose Contractor:'>
                                            <?= $this->legalEntityName ?></a>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Order ID: </div>
                                    <div class="col-md-7 value">
                                        <a href="javascript:;" id="editable-visible_order_id" class='x-editable'
                                           data-pk="<?= $this->order['order_id'] ?>"
                                           data-name="visible_order_id" data-value="<?= $this->order['visible_order_id'] ?>"
                                           data-url='/order/change_field' data-original-title='Enter Visible ID:'>
                                            <?= $this->order['visible_order_id'] !== null ? $this->order['visible_order_id']
                                                : ' '?></a>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Client's expected date of issue:</div>
                                    <div class="col-md-7 value">
                                        <a href="javascript:;" id="editable-expected_date_of_issue" class='x-editable' data-pk="<?= $this->order['order_id'] ?>" data-name="expected_date_of_issue"
                                           data-value="<?= $this->order['expected_date_of_issue'] ?>"
                                           data-url='/order/change_field' data-original-title='Enter Expected Date of Issue'> <?= $this->order['expected_date_of_issue'] ?> </a>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Total Price:</div>
                                    <div class="col-md-7 value"><?= round($this->order['total_price'], 2) ?> €</div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Total Downpayment:</div>
                                    <div class="col-md-7 value">
                                        <span><?= $this->order['total_downpayment'] != null ?
                                                round($this->order['total_downpayment'], 2) . ' &euro;' : '' ?></span>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Downpayment Rate:</div>
                                    <div class="col-md-7 value">
                                        <span><?= $this->order['downpayment_rate'] != null ?
                                                round($this->order['downpayment_rate'], 2) . ' %' : '' ?></span>
                                    </div>
                                </div>
                                <br/>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Manager:</div>
                                    <div class="col-md-7 value">
                                        <a href="javascript:;" id="editable-manager" class='x-editable'
                                           data-pk="<?= $this->order['order_id'] ?>" data-name="sales_manager_id"
                                           data-value="<?= $this->order['sales_manager_id'] ?>"
                                           data-url='/order/change_field' data-original-title='Enter Sales Manager'>
                                            <?= $this->sales_manager['first_name'] . ' ' . $this->sales_manager['last_name'] ?>
                                            <a href="/sales_manager?id=<?= $this->sales_manager['user_id'] ?>">
                                                <i class="glyphicon glyphicon-link"></i></a>
                                        </a>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Manager Bonus:</div>
                                    <div class="col-md-7 value"><?= round($this->order['manager_bonus'], 2) ?> €</div>
                                </div>
                                <br/>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Comment:</div>
                                    <div class="col-md-7 value">
                                        <a href="javascript:;" id="editable-comment" class='x-editable' data-pk="<?= $this->order['order_id'] ?>" data-name="comment" data-value="<?= $this->order['comment'] ?>"
                                           data-url='/order/change_field' data-original-title='Enter Comment'> <?= $this->order['comment'] ?> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="portlet blue-hoki box">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>Customer Information
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Customer Name:</div>
                                    <div class="col-md-7 value">
                                        <a href="javascript:;" id="editable-client" class='x-editable'
                                           data-pk="<?= $this->order['order_id'] ?>" data-name="client_id"
                                           data-value="<?= $this->order['client_id'] ?>"
                                           data-url='/order/change_field'
                                           data-original-title='Choose Client:'><?= $this->client['final_name'] ?></a>
                                        <?php if (!is_null($this->order['client_id']) && $this->order['client_id']): ?>
                                            <a href="/client?id=<?= $this->order['client_id'] ?>">
                                                <i class="glyphicon glyphicon-link"></i></a>
                                        <?php endif; ?>
                                        <a href="/client?id=new">
                                            <i class="glyphicon glyphicon-plus"></i></a>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Customer Type:</div>
                                    <div class="col-md-7 value">
                                        <span><?= $this->client['type'] ?></span>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Email:</div>
                                    <div class="col-md-7 value">
                                        <?= $this->client['email']; ?>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> City:</div>
                                    <div class="col-md-7 value">
                                        <a href="javascript:;" id="editable-city" class='x-editable' data-pk="<?= $this->order['order_id'] ?>" data-name="city" data-value="<?= $this->order['city'] ?>"
                                           data-url='/order/change_field' data-original-title='Enter City'> <?= $this->order['city'] ?> </a>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Phone Number:</div>
                                    <div class="col-md-7 value">
                                        <?= $this->client['mobile_number']; ?>
                                    </div>
                                </div>
                                <br/>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Commission Agent:</div>
                                    <div class="col-md-7 value">
                                        <a href="javascript:;" id="editable-commission_agent" class='x-editable'
                                           data-pk="<?= $this->order['order_id'] ?>" data-name="commission_agent_id"
                                           data-value="<?= $this->order['commission_agent_id'] ?>"
                                           data-url='/order/change_field' data-original-title='Enter Commission Agent'>
                                            <?= $this->commission_agent['final_name'] != null ?
                                                $this->commission_agent['final_name'] .
                                                '<a href="/order/delete_commission_agent?order_id=' .
                                                $this->order["order_id"] .
                                                '"><span class="glyphicon glyphicon-trash"></span></a>' : '' ?>
                                        </a>
                                        <?php if (!is_null($this->order['commission_agent_id']) &&
                                            $this->order['commission_agent_id']): ?>
                                            <a href="/client?id=<?= $this->order['commission_agent_id'] ?>">
                                                <i class="glyphicon glyphicon-link"></i></a>
                                        <?php endif; ?>
                                        <a href="/client?id=new">
                                            <i class="glyphicon glyphicon-plus"></i></a>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Total Commission:</div>
                                    <div class="col-md-7 value">
                                        <span> <?= round($this->order['total_commission'], 2) . ' &euro;' ?> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="portlet grey-cascade box">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>Items
                                </div>
                                <div class="actions">
                                    <a class="btn btn-default btn-sm" id="joinProduct"
                                       data-placement="left" data-popout="true" data-singleton="true"
                                       data-toggle="confirmation"
                                       data-title="Are you sure to join this items?">
                                        <i class="fa fa-columns" aria-hidden="true"></i> Join Products </a>
                                    <a class="btn btn-default btn-sm" id="splitProduct">
                                        <i class="fa fa-columns" aria-hidden="true"></i> Split Product </a>
                                    <a class="btn btn-default btn-sm" id="shipToCustomer">
                                        <i class="fa fa-ship" aria-hidden="true"></i> Ship to customer </a>
                                    <a href="javascript:;" class="btn btn-default btn-sm"
                                       data-placement="left" data-popout="true" data-singleton="true"
                                       data-toggle="confirmation"
                                       data-title="Are you sure to send to logist this items?"  id="sendToLogist">
                                        <span class="glyphicon glyphicon-pencil"></span> Send to Logist </a>
                                    <button class="btn btn-default btn-sm"
                                       data-toggle="modal" data-target="#modal_newOrderItem">
                                        <i class="fa fa-plus"></i> Add new </button>
                                </div>
                            </div>
                            <div class="portlet-body">
	                            <?php
	                            $commonData = [
		                            'click_url' => "javascript:;",
		                            'method' => "POST",
		                            'serverSide' => false,
                                    'ajax' => ['url' => '/order/dt_order_items?order_id=' . $this->order['order_id']]
	                            ];

	                            $table_data = array_merge($this->itemsTable, $commonData);
	                            include 'application/views/templates/table.php'
	                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="portlet blue-hoki box">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i> Payments </div>
                                <div class="actions">
                                    <script>
                                        $(document).ready(function() {
                                            var newPayment = function(e) {
                                                e.preventDefault();

                                                var paymentType = $(this).attr('data-type');
                                                var contractor_id, category;
                                                if (paymentType === 'client') {
                                                    contractor_id = '<?= $this->client["client_id"] ?>';
                                                    category = '<?= PAYMENT_CATEGORY_CLIENT ?>';
                                                } else {
                                                    contractor_id = '<?= $this->order["commission_agent_id"] ?>';
                                                    category = '<?= PAYMENT_CATEGORY_COMMISSION_AGENT ?>';
                                                }

                                                var mess = false;
                                                if (!parseInt(contractor_id)) {
                                                    mess = (paymentType === 'client') ? 'Client' : 'Commission Agent';
                                                }

                                                if (mess) {
                                                    $('#notificationModal').modal().find('.modal-body')
                                                        .text('Please fill in ' + mess);
                                                    return false;
                                                }

                                                $('#paymentFormCategory').val(category);
                                                $('#paymentContractorId').val(contractor_id);
                                                $('#payment-form').submit()

                                            };

                                            $('.new-payment-btn').on('click', newPayment);
                                        })
                                    </script>
                                    <form action="payment?id=new" method="POST" id="payment-form">
                                        <input type="hidden" name="Similar[category]" id="paymentFormCategory">
                                        <input type="hidden" name="Similar[contractor_id]" id="paymentContractorId">
                                        <input type="hidden" name="Similar[order_id]"
                                               value="<?= $this->order['order_id'] ?>">
                                        <?php if ($this->order['legal_entity_id'] &&
                                            $this->order['legal_entity_id'] !== null): ?>
                                        <input type="hidden" name="Similar[legal_entity_id]"
                                               value="<?= $this->order['legal_entity_id'] ?>">
                                        <input type="hidden" name="Similar[sum_in_eur]"
                                               value="<?= round(
                                                       round($this->order['total_price'], 2) -
                                                       round($this->order['total_downpayment'], 2),
                                                       2); ?>">
                                        <?php endif; ?>
                                    </form>
                                    <?php if ($this->user->role_id != ROLE_SALES_MANAGER): ?>
                                        <a href="#" data-type="client"
                                           class="btn btn-default btn-sm new-payment-btn">
                                            <i class="fa fa-plus"></i> Add new Client Payment</a>
                                        <a href="#" data-type="commission"
                                           class="btn btn-default btn-sm new-payment-btn">
                                            <i class="fa fa-plus"></i> Add new Commission Payment</a>
                                    <?php endif; ?>
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
	                                        'type' => PAYMENT_CATEGORY_CLIENT,
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
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="portlet green box">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i> Delivery Notes </div>
                            </div>
                            <div class="portlet-body">
	                            <?php
	                            $commonData = [
		                            'click_url' => "javascript:;",
		                            'method' => "POST",
		                            'serverSide' => false,
		                            'ajax' => [
			                            'url' => '/delivery_notes/dt_for_order',
			                            'data' => [
				                            'order_id' => $this->order["order_id"],
			                            ]
		                            ]
	                            ];
	                            $table_data = array_merge($this->deliveryNotesTable, $commonData);
	                            include 'application/views/templates/table.php'
	                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="well">
                            <div class="row static-info align-reverse">
                                <div class="col-md-8 name"> Total Price:</div>
                                <div class="col-md-3 value"> €<?= round($this->order['total_price'], 2) ?></div>
                            </div>
                            <div class="row static-info align-reverse">
                                <div class="col-md-8 name"> Total Expences:</div>
                                <div class="col-md-3 value"> € -</div>
                            </div>
                            <div class="row static-info align-reverse">
                                <div class="col-md-8 name"> Total Profit:</div>
                                <div class="col-md-3 value"> € -</div>
                            </div>
                            <div class="row static-info align-reverse">
                                <div class="col-md-8 name"> Total Comission:</div>
                                <div class="col-md-3 value"> €<?= round($this->order['total_commission'], 2) ?></div>
                            </div>
                            <div class="row static-info align-reverse">
                                <div class="col-md-8 name"> Manager Bonus:</div>
                                <div class="col-md-3 value"> €<?= round($this->order['manager_bonus'], 2) ?> </div>
                            </div>
                            <div class="row static-info align-reverse">
                                <div class="col-md-8 name"> Downpayment:</div>
                                <div class="col-md-3 value"> €<?= $this->order['total_downpayment'] != null ? round($this->order['total_downpayment'], 2)  : '' ?></div>
                            </div>
                            <div class="row static-info align-reverse">
                                <div class="col-md-8 name"> Downpayment rate:</div>
                                <div class="col-md-3 value"> <?= $this->order['downpayment_rate'] != null ?
                                        round($this->order['downpayment_rate'], 2) . ' %' : '' ?> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
        </div>
    </div>
</div>

<?php
require_once 'modals/new_order_item.php';
require_once 'modals/cancel_order.php';
require_once 'modals/shipment_to_customer_modal_amount.php';
?>

<script>
    $(document).ready(function () {
        var managers = <?= $this->toJsList($this->managers, "user_id") ?>;
        var commission_agents = <?= $this->toJsList($this->commission_agents, "client_id") ?>;
        var clients = <?= json_encode($this->clients) ?>;
        var item_statuses = <?= json_encode($this->statusList); ?>;
        var legalEntities = <?= json_encode($this->legalEntities); ?>;
        <?php if ($this->order['downpayment_rate'] == 100): ?>
        item_statuses[6] = undefined;
        <?php endif; ?>
        var ordersTableName = "<?= $this->itemsTable['table_name'] ?>";
        var $table_order_items = $("#" + ordersTableName);

        $table_order_items.on('draw.dt', function () {
            $('.table-confirm-btn').confirmation({
                rootSelector: '.table-confirm-btn'
            });
            $('body').on('click', '.logist-issue-deny', function(e) {
                e.preventDefault();

                $('#notificationModal').modal().find('.modal-body')
                    .text('Please fill in Client\'s expected date of issue before sending request to logist');
            });
            $('.x-amount, .x-number_of_packs').editable({
                type: "number",
                min: 0,
                step: 0.001,
                inputclass: 'form-control input-medium',
                success: function () {
                    location.reload();
                }
            });

            $('.x-manager_bonus_rate, .x-manager_bonus').editable({
                type: "number",
                min: 0,
                step: 0.01,
                inputclass: 'form-control input-small',
                success: function () {
                    location.reload();
                }
            });

            $('.x-sell-price, .x-commission_agent_bonus').editable({
                type: "number",
                min: 0,
                step: 0.01,
                inputclass: 'form-control input-medium',
                success: function () {
                    location.reload();
                },
                validate: function (value) {
                    <?php if ($_SESSION['perm'] < ADMIN_PERM): ?>
                        var itemId = $(this).attr('data-pk');
                        var name = $(this).attr('data-name');
                        var result = true;
                            $.ajax({
                                url: '/order/validate_item_field',
                                type: "GET",
                                data: {
                                    item_id: itemId,
                                    name: name,
                                    value: value
                                },
                                async: false,
                                success: function(data) {
                                    result = data;
                                }
                            });
                        return result ? '' : 'Invalid Value';
                    <?php endif; ?>
                }
            });

            $('.x-commission_rate').editable({
                type: "number",
                min: 0,
                <?php if ($_SESSION["perm"] < ADMIN_PERM): ?>
                max: <?= MANAGER_MAX_COMMISSION_RATE_INPUT ?>,
                <?php endif; ?>
                step: 0.01,
                inputclass: 'form-control input-medium',
                success: function () {
                    location.reload();
                }
            });
            $('.x-discount_rate').editable({
                type: "number",
                min: 0,
                <?php if ($_SESSION["perm"] < ADMIN_PERM): ?>
                max: <?= MANAGER_MAX_DISCOUNT_RATE_INPUT ?>,
                <?php endif; ?>
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

            $table_order_items.find('tbody').on('click', 'tr td:first-child', function (e) {
                var ids = $table_order_items.attr('data-selected');
                var docsBtns = $('div[data-id="docs"]').find('.list-items').find('.print-btn');
                const delimiter = '&items=';
                $.each(docsBtns, function() {
                    var href = $(this).attr('href');
                    var hrefArray = href.split(delimiter);
                    hrefArray[1] = delimiter + ids;
                    $(this).attr('href', hrefArray.join(''));
                })
            });
        });

        $table_order_items.on('click', '.return-item-btn', function() {
            var itemId = $(this).attr('data-item_id');
            $("#modal_return").modal('show');
            $('#item_id_return_input').val(itemId);
        });

        $table_order_items.find('tbody').on('click', 'tr td:not(:first-child)', function (e) {
            var target = e.target;
            var link = $(target).find('a').not('.table-confirm-btn, .x-editable, .reserve-product-btn');
            if (link.length) {
                window.location.href = link.attr('href');
            }
        });

        <?php if ($_SESSION["perm"] >= SALES_MANAGER_PERM): ?>
            $('#editable-visible_order_id').editable({
                type: "text",
                inputclass: 'form-control input-medium',
                success: function () {
                    location.reload();
                }
            });
        <?php endif; ?>

        $('#editable-downpayment_rate, #editable-manager_bonus_rate').editable({
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
            <?php if ($_SESSION["user_role"] != ROLE_ADMIN): ?>
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
        $('#editable-manager').editable({
            type: "select2",
            inputclass: 'form-control input-medium',
            source: managers,
            success: function () {
                location.reload();
            }
        });
        $('#editable-commission_agent').editable({
            type: "select2",
            inputclass: 'form-control input-medium',
            source: commission_agents,
            success: function () {
                location.reload();
            }
        });
        $('#editable-client').editable({
            type: "select2",
            inputclass: 'form-control input-medium',
            source: clients,
            success: function () {
                location.reload();
            }
        });
        $('#editable-legal_entity').editable({
            type: "select",
            inputclass: 'form-control input-medium',
            source: legalEntities,
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
        <?php if ($this->user->role_id == ROLE_ADMIN): ?>
            var editableDate = $('#editable-start_date'),
                editableDateVisible = $('#editable-start_date-visible'),
                btns = $('.start_date-btn');
            editableDate.datetimepicker({
                format: 'Y-m-d H:i:s',
                onChangeDateTime: function(current_time,$input){

                }
            });
        editableDateVisible.click(function(e) {
                e.preventDefault();
                $(this).hide();
                editableDate.show().trigger('focusin');
                btns.show();
            });
            btns.click(function () {
                if ($(this).hasClass('editable-submit')) {
                    $.ajax({
                        url: '/order/change_field',
                        method: 'POST',
                        data: {
                            name: editableDate.attr('name'),
                            pk: editableDate.attr('data-pk'),
                            value: editableDate.val()
                        }
                    });
                    editableDateVisible.text(editableDate.val());
                } else {
                    editableDate.val(editableDateVisible.text())
                }
                editableDateVisible.show();
                editableDate.hide();
                btns.hide();
            });
        <?php endif; ?>

//        var table_payments = "<?//= $this->paymentsTable['table_name']; ?>//";
//        var $table_payments = $("#" + table_payments);

        function getSelected() {
            var selected = $table_order_items.attr('data-selected');
            if (selected === undefined || !selected) {
                $('#notificationModal').modal().find('.modal-body')
                    .text('Please select at least one item');
                return false;
            }
            return selected;
        }

        $('#sendToLogist').on('click', function(e) {
            e.preventDefault();

            function sendToLogist() {

                var selected = getSelected;
                if (!selected())
                    return false;

                $.ajax({
                    url: '/order/send_to_logist',
                    type: "GET",
                    data: {
                        order_item_ids: selected
                    },
                    success: function(data) {
                        if (data) {
                            data = JSON.parse(data);
                            if (data.success === 0) {
                                $('#notificationModal').modal().find('.modal-body').text(data.message);
                                return false;
                            }
                        }
                        location.href = '';
                    }
                })
            }

            $(this).confirmation().on('confirmed.bs.confirmation', sendToLogist);

        });
        $('#shipToCustomer').on('click', function(e) {
            e.preventDefault();
            var selected = getSelected;
            if (!selected())
                return false;

            $.ajax({
                url: '/order/ship_to_customer',
                type: "GET",
                data: {
                    order_item_ids: selected,
                    actionId: 1
                },
                success: function(data) {
                    if (data) {
                        data = JSON.parse(data);
                        if (data.success === 0) {
                            $('#notificationModal').modal().find('.modal-body').text(data.message);
                            return false;
                        }
                        var modal = $('#modal_shipment_to_customer_modal_amount');
                        var tbody = modal.find('table tbody').empty();

                        var tr = '';
                        if (data.length) {
                            $.each(data, function() {
                                var td = '<tr><td>' + this.name + '</td>';
                                td += '<td><input type="hidden" class="total" name="ship[' +
                                    this.item_id + '][total_amount]" ' + 'value="' + this.amount + '" />' +
                                    this.amount_string + '</td>';
                                td += '<td>' + '<input type="text" class="amount" value="' +
                                    this.amount + '" ' + 'name="ship[' + this.item_id + '][amount]" ' +
                                    'max="'+this.amount+'" min="0" /></td>';
                                td += '<td>' + this.number_of_packs_string + '</td></tr>';
                                tr += td;
                            });
                            tbody.append(tr);
                            modal.find('.order_item_ids').val(selected);
                            modal.modal();
                        }
                    }
                }
            })

        });

        $('#splitProduct').on('click', function() {

            var selected = getSelected();
            var count = (selected.split(',').length);
            if (count !== 1) {
                $('#notificationModal').modal().find('.modal-body')
                    .text('Need to choose only one item!');
                return false;
            }

            $.ajax({
                url: '/order/split',
                data: {
                    order_item_id: selected,
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
                        modal.find('.modal-title').text('Split Products');
                        modal.find('#splitSubmit').text('Split');
                        modal.find('form').attr('action', '/order/split?action_id=2');
                        var table = modal.find('table');

                        var tr = table.find('tbody tr').attr('data-item_id', data.item_id).attr('data-amount', data.amount);
                        var td = '<td><input type="hidden" name="item_id" value="' +
                            data.item_id + '" />' + data.name + '</td>';
                        td += '<td>' + data.amount + '</td>';
                        td += '<td><input type="text" name="amount_1" class="form-control amount_1"' +
                            ' value="' + (data.amount / 2) + '" /></td>';
                        td += '<td><input type="text" name="amount_2" readonly ' +
                            'class="form-control amount_2" value="' + (data.amount / 2) + '" /></td>';
                        tr.empty().append(td);
                        modal.modal();
                    }
                }
            })

        });

        $('#joinProduct').on('click', function() {
            var selected = getSelected();
            if (selected === undefined) {
                $('#notificationModal').modal().find('.modal-body')
                    .text('Need to choose only two items!');
                return false;
            }
            var count = selected.split(',').length;
            if (count !== 2) {
                $('#notificationModal').modal().find('.modal-body')
                    .text('Need to choose only two items!');
                return false;
            } else {
                $(this).confirmation().on('confirmed.bs.confirmation', function() {
                    $.ajax({
                        url: '/order/join',
                        data: {
                            order_item_ids: selected,
                            action_id: 1
                        },
                        success: function(data) {
                            if (data) {
                                data = JSON.parse(data);
                                if (data.success === 0) {
                                    $('#notificationModal').modal().find('.modal-body').text(data.message);
                                    return false;
                                }
                            }
                            location.href = '';
                        }
                    })
                });
            }
        });

    });
</script>
<style>
    .table-responsive {
        overflow-y: hidden;
    }
</style>
<?php
include_once 'application/views/modals/reserve.php';
include_once 'application/views/modals/return.php';
include_once 'application/views/modals/split.php';
?>
<!-- TODO переместить в нормальные скрипты и стили -->
<script>
    var DateFormatter;!function(){"use strict";var e,t,a,n,r,o,i;o=864e5,i=3600,e=function(e,t){return"string"==typeof e&&"string"==typeof t&&e.toLowerCase()===t.toLowerCase()},t=function(e,a,n){var r=e.toString();return n=n||"0",r.length<a?t(n+r,a):r},a=function(e){var t,n;for(e=e||{},t=1;t<arguments.length;t++)if(n=arguments[t])for(var r in n)n.hasOwnProperty(r)&&("object"==typeof n[r]?a(e[r],n[r]):e[r]=n[r]);return e},n=function(e,t){for(var a=0;a<t.length;a++)if(t[a].toLowerCase()===e.toLowerCase())return a;return-1},r={dateSettings:{days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],daysShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],months:["January","February","March","April","May","June","July","August","September","October","November","December"],monthsShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],meridiem:["AM","PM"],ordinal:function(e){var t=e%10,a={1:"st",2:"nd",3:"rd"};return 1!==Math.floor(e%100/10)&&a[t]?a[t]:"th"}},separators:/[ \-+\/\.T:@]/g,validParts:/[dDjlNSwzWFmMntLoYyaABgGhHisueTIOPZcrU]/g,intParts:/[djwNzmnyYhHgGis]/g,tzParts:/\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,tzClip:/[^-+\dA-Z]/g},DateFormatter=function(e){var t=this,n=a(r,e);t.dateSettings=n.dateSettings,t.separators=n.separators,t.validParts=n.validParts,t.intParts=n.intParts,t.tzParts=n.tzParts,t.tzClip=n.tzClip},DateFormatter.prototype={constructor:DateFormatter,getMonth:function(e){var t,a=this;return t=n(e,a.dateSettings.monthsShort)+1,0===t&&(t=n(e,a.dateSettings.months)+1),t},parseDate:function(t,a){var n,r,o,i,s,d,u,l,f,c,m=this,h=!1,g=!1,p=m.dateSettings,y={date:null,year:null,month:null,day:null,hour:0,min:0,sec:0};if(!t)return null;if(t instanceof Date)return t;if("U"===a)return o=parseInt(t),o?new Date(1e3*o):t;switch(typeof t){case"number":return new Date(t);case"string":break;default:return null}if(n=a.match(m.validParts),!n||0===n.length)throw new Error("Invalid date format definition.");for(r=t.replace(m.separators,"\x00").split("\x00"),o=0;o<r.length;o++)switch(i=r[o],s=parseInt(i),n[o]){case"y":case"Y":if(!s)return null;f=i.length,y.year=2===f?parseInt((70>s?"20":"19")+i):s,h=!0;break;case"m":case"n":case"M":case"F":if(isNaN(s)){if(d=m.getMonth(i),!(d>0))return null;y.month=d}else{if(!(s>=1&&12>=s))return null;y.month=s}h=!0;break;case"d":case"j":if(!(s>=1&&31>=s))return null;y.day=s,h=!0;break;case"g":case"h":if(u=n.indexOf("a")>-1?n.indexOf("a"):n.indexOf("A")>-1?n.indexOf("A"):-1,c=r[u],-1!==u)l=e(c,p.meridiem[0])?0:e(c,p.meridiem[1])?12:-1,s>=1&&12>=s&&-1!==l?y.hour=s%12===0?l:s+l:s>=0&&23>=s&&(y.hour=s);else{if(!(s>=0&&23>=s))return null;y.hour=s}g=!0;break;case"G":case"H":if(!(s>=0&&23>=s))return null;y.hour=s,g=!0;break;case"i":if(!(s>=0&&59>=s))return null;y.min=s,g=!0;break;case"s":if(!(s>=0&&59>=s))return null;y.sec=s,g=!0}if(h===!0&&y.year&&y.month&&y.day)y.date=new Date(y.year,y.month-1,y.day,y.hour,y.min,y.sec,0);else{if(g!==!0)return null;y.date=new Date(0,0,0,y.hour,y.min,y.sec,0)}return y.date},guessDate:function(e,t){if("string"!=typeof e)return e;var a,n,r,o,i,s,d=this,u=e.replace(d.separators,"\x00").split("\x00"),l=/^[djmn]/g,f=t.match(d.validParts),c=new Date,m=0;if(!l.test(f[0]))return e;for(r=0;r<u.length;r++){if(m=2,i=u[r],s=parseInt(i.substr(0,2)),isNaN(s))return null;switch(r){case 0:"m"===f[0]||"n"===f[0]?c.setMonth(s-1):c.setDate(s);break;case 1:"m"===f[0]||"n"===f[0]?c.setDate(s):c.setMonth(s-1);break;case 2:if(n=c.getFullYear(),a=i.length,m=4>a?a:4,n=parseInt(4>a?n.toString().substr(0,4-a)+i:i.substr(0,4)),!n)return null;c.setFullYear(n);break;case 3:c.setHours(s);break;case 4:c.setMinutes(s);break;case 5:c.setSeconds(s)}o=i.substr(m),o.length>0&&u.splice(r+1,0,o)}return c},parseFormat:function(e,a){var n,r=this,s=r.dateSettings,d=/\\?(.?)/gi,u=function(e,t){return n[e]?n[e]():t};return n={d:function(){return t(n.j(),2)},D:function(){return s.daysShort[n.w()]},j:function(){return a.getDate()},l:function(){return s.days[n.w()]},N:function(){return n.w()||7},w:function(){return a.getDay()},z:function(){var e=new Date(n.Y(),n.n()-1,n.j()),t=new Date(n.Y(),0,1);return Math.round((e-t)/o)},W:function(){var e=new Date(n.Y(),n.n()-1,n.j()-n.N()+3),a=new Date(e.getFullYear(),0,4);return t(1+Math.round((e-a)/o/7),2)},F:function(){return s.months[a.getMonth()]},m:function(){return t(n.n(),2)},M:function(){return s.monthsShort[a.getMonth()]},n:function(){return a.getMonth()+1},t:function(){return new Date(n.Y(),n.n(),0).getDate()},L:function(){var e=n.Y();return e%4===0&&e%100!==0||e%400===0?1:0},o:function(){var e=n.n(),t=n.W(),a=n.Y();return a+(12===e&&9>t?1:1===e&&t>9?-1:0)},Y:function(){return a.getFullYear()},y:function(){return n.Y().toString().slice(-2)},a:function(){return n.A().toLowerCase()},A:function(){var e=n.G()<12?0:1;return s.meridiem[e]},B:function(){var e=a.getUTCHours()*i,n=60*a.getUTCMinutes(),r=a.getUTCSeconds();return t(Math.floor((e+n+r+i)/86.4)%1e3,3)},g:function(){return n.G()%12||12},G:function(){return a.getHours()},h:function(){return t(n.g(),2)},H:function(){return t(n.G(),2)},i:function(){return t(a.getMinutes(),2)},s:function(){return t(a.getSeconds(),2)},u:function(){return t(1e3*a.getMilliseconds(),6)},e:function(){var e=/\((.*)\)/.exec(String(a))[1];return e||"Coordinated Universal Time"},I:function(){var e=new Date(n.Y(),0),t=Date.UTC(n.Y(),0),a=new Date(n.Y(),6),r=Date.UTC(n.Y(),6);return e-t!==a-r?1:0},O:function(){var e=a.getTimezoneOffset(),n=Math.abs(e);return(e>0?"-":"+")+t(100*Math.floor(n/60)+n%60,4)},P:function(){var e=n.O();return e.substr(0,3)+":"+e.substr(3,2)},T:function(){var e=(String(a).match(r.tzParts)||[""]).pop().replace(r.tzClip,"");return e||"UTC"},Z:function(){return 60*-a.getTimezoneOffset()},c:function(){return"Y-m-d\\TH:i:sP".replace(d,u)},r:function(){return"D, d M Y H:i:s O".replace(d,u)},U:function(){return a.getTime()/1e3||0}},u(e,e)},formatDate:function(e,t){var a,n,r,o,i,s=this,d="",u="\\";if("string"==typeof e&&(e=s.parseDate(e,t),!e))return null;if(e instanceof Date){for(r=t.length,a=0;r>a;a++)i=t.charAt(a),"S"!==i&&i!==u&&(a>0&&t.charAt(a-1)===u?d+=i:(o=s.parseFormat(i,e),a!==r-1&&s.intParts.test(i)&&"S"===t.charAt(a+1)&&(n=parseInt(o)||0,o+=s.dateSettings.ordinal(n)),d+=o));return d}return""}}}(),function(e){"function"==typeof define&&define.amd?define(["jquery","jquery-mousewheel"],e):"object"==typeof exports?module.exports=e:e(jQuery)}(function(e){"use strict";function t(e,t,a){this.date=e,this.desc=t,this.style=a}var a={i18n:{ar:{months:["كانون الثاني","شباط","آذار","نيسان","مايو","حزيران","تموز","آب","أيلول","تشرين الأول","تشرين الثاني","كانون الأول"],dayOfWeekShort:["ن","ث","ع","خ","ج","س","ح"],dayOfWeek:["الأحد","الاثنين","الثلاثاء","الأربعاء","الخميس","الجمعة","السبت","الأحد"]},ro:{months:["Ianuarie","Februarie","Martie","Aprilie","Mai","Iunie","Iulie","August","Septembrie","Octombrie","Noiembrie","Decembrie"],dayOfWeekShort:["Du","Lu","Ma","Mi","Jo","Vi","Sâ"],dayOfWeek:["Duminică","Luni","Marţi","Miercuri","Joi","Vineri","Sâmbătă"]},id:{months:["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],dayOfWeekShort:["Min","Sen","Sel","Rab","Kam","Jum","Sab"],dayOfWeek:["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"]},is:{months:["Janúar","Febrúar","Mars","Apríl","Maí","Júní","Júlí","Ágúst","September","Október","Nóvember","Desember"],dayOfWeekShort:["Sun","Mán","Þrið","Mið","Fim","Fös","Lau"],dayOfWeek:["Sunnudagur","Mánudagur","Þriðjudagur","Miðvikudagur","Fimmtudagur","Föstudagur","Laugardagur"]},bg:{months:["Януари","Февруари","Март","Април","Май","Юни","Юли","Август","Септември","Октомври","Ноември","Декември"],dayOfWeekShort:["Нд","Пн","Вт","Ср","Чт","Пт","Сб"],dayOfWeek:["Неделя","Понеделник","Вторник","Сряда","Четвъртък","Петък","Събота"]},fa:{months:["فروردین","اردیبهشت","خرداد","تیر","مرداد","شهریور","مهر","آبان","آذر","دی","بهمن","اسفند"],dayOfWeekShort:["یکشنبه","دوشنبه","سه شنبه","چهارشنبه","پنجشنبه","جمعه","شنبه"],dayOfWeek:["یک‌شنبه","دوشنبه","سه‌شنبه","چهارشنبه","پنج‌شنبه","جمعه","شنبه","یک‌شنبه"]},ru:{months:["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],dayOfWeekShort:["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],dayOfWeek:["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота"]},uk:{months:["Січень","Лютий","Березень","Квітень","Травень","Червень","Липень","Серпень","Вересень","Жовтень","Листопад","Грудень"],dayOfWeekShort:["Ндл","Пнд","Втр","Срд","Чтв","Птн","Сбт"],dayOfWeek:["Неділя","Понеділок","Вівторок","Середа","Четвер","П'ятниця","Субота"]},en:{months:["January","February","March","April","May","June","July","August","September","October","November","December"],dayOfWeekShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],dayOfWeek:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]},el:{months:["Ιανουάριος","Φεβρουάριος","Μάρτιος","Απρίλιος","Μάιος","Ιούνιος","Ιούλιος","Αύγουστος","Σεπτέμβριος","Οκτώβριος","Νοέμβριος","Δεκέμβριος"],dayOfWeekShort:["Κυρ","Δευ","Τρι","Τετ","Πεμ","Παρ","Σαβ"],dayOfWeek:["Κυριακή","Δευτέρα","Τρίτη","Τετάρτη","Πέμπτη","Παρασκευή","Σάββατο"]},de:{months:["Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"],dayOfWeekShort:["So","Mo","Di","Mi","Do","Fr","Sa"],dayOfWeek:["Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag"]},nl:{months:["januari","februari","maart","april","mei","juni","juli","augustus","september","oktober","november","december"],dayOfWeekShort:["zo","ma","di","wo","do","vr","za"],dayOfWeek:["zondag","maandag","dinsdag","woensdag","donderdag","vrijdag","zaterdag"]},tr:{months:["Ocak","Şubat","Mart","Nisan","Mayıs","Haziran","Temmuz","Ağustos","Eylül","Ekim","Kasım","Aralık"],dayOfWeekShort:["Paz","Pts","Sal","Çar","Per","Cum","Cts"],dayOfWeek:["Pazar","Pazartesi","Salı","Çarşamba","Perşembe","Cuma","Cumartesi"]},fr:{months:["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"],dayOfWeekShort:["Dim","Lun","Mar","Mer","Jeu","Ven","Sam"],dayOfWeek:["dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi"]},es:{months:["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],dayOfWeekShort:["Dom","Lun","Mar","Mié","Jue","Vie","Sáb"],dayOfWeek:["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"]},th:{months:["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"],dayOfWeekShort:["อา.","จ.","อ.","พ.","พฤ.","ศ.","ส."],dayOfWeek:["อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัส","ศุกร์","เสาร์","อาทิตย์"]},pl:{months:["styczeń","luty","marzec","kwiecień","maj","czerwiec","lipiec","sierpień","wrzesień","październik","listopad","grudzień"],dayOfWeekShort:["nd","pn","wt","śr","cz","pt","sb"],dayOfWeek:["niedziela","poniedziałek","wtorek","środa","czwartek","piątek","sobota"]},pt:{months:["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],dayOfWeekShort:["Dom","Seg","Ter","Qua","Qui","Sex","Sab"],dayOfWeek:["Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado"]},ch:{months:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],dayOfWeekShort:["日","一","二","三","四","五","六"]},se:{months:["Januari","Februari","Mars","April","Maj","Juni","Juli","Augusti","September","Oktober","November","December"],dayOfWeekShort:["Sön","Mån","Tis","Ons","Tor","Fre","Lör"]},km:{months:["មករា​","កុម្ភៈ","មិនា​","មេសា​","ឧសភា​","មិថុនា​","កក្កដា​","សីហា​","កញ្ញា​","តុលា​","វិច្ឋិកា​","ធ្នូ​"],dayOfWeekShort:["អាទិ​","ចន្ទ​","អង្គារ​","ពុធ​","ព្រហ​​","សុក្រ​","សៅរ៍"],dayOfWeek:["អាទិត្យ​","ចន្ទ​","អង្គារ​","ពុធ​","ព្រហស្បតិ៍​","សុក្រ​","សៅរ៍"]},kr:{months:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],dayOfWeekShort:["일","월","화","수","목","금","토"],dayOfWeek:["일요일","월요일","화요일","수요일","목요일","금요일","토요일"]},it:{months:["Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre"],dayOfWeekShort:["Dom","Lun","Mar","Mer","Gio","Ven","Sab"],dayOfWeek:["Domenica","Lunedì","Martedì","Mercoledì","Giovedì","Venerdì","Sabato"]},da:{months:["January","Februar","Marts","April","Maj","Juni","July","August","September","Oktober","November","December"],dayOfWeekShort:["Søn","Man","Tir","Ons","Tor","Fre","Lør"],dayOfWeek:["søndag","mandag","tirsdag","onsdag","torsdag","fredag","lørdag"]},no:{months:["Januar","Februar","Mars","April","Mai","Juni","Juli","August","September","Oktober","November","Desember"],dayOfWeekShort:["Søn","Man","Tir","Ons","Tor","Fre","Lør"],dayOfWeek:["Søndag","Mandag","Tirsdag","Onsdag","Torsdag","Fredag","Lørdag"]},ja:{months:["1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月"],dayOfWeekShort:["日","月","火","水","木","金","土"],dayOfWeek:["日曜","月曜","火曜","水曜","木曜","金曜","土曜"]},vi:{months:["Tháng 1","Tháng 2","Tháng 3","Tháng 4","Tháng 5","Tháng 6","Tháng 7","Tháng 8","Tháng 9","Tháng 10","Tháng 11","Tháng 12"],dayOfWeekShort:["CN","T2","T3","T4","T5","T6","T7"],dayOfWeek:["Chủ nhật","Thứ hai","Thứ ba","Thứ tư","Thứ năm","Thứ sáu","Thứ bảy"]},sl:{months:["Januar","Februar","Marec","April","Maj","Junij","Julij","Avgust","September","Oktober","November","December"],dayOfWeekShort:["Ned","Pon","Tor","Sre","Čet","Pet","Sob"],dayOfWeek:["Nedelja","Ponedeljek","Torek","Sreda","Četrtek","Petek","Sobota"]},cs:{months:["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"],dayOfWeekShort:["Ne","Po","Út","St","Čt","Pá","So"]},hu:{months:["Január","Február","Március","Április","Május","Június","Július","Augusztus","Szeptember","Október","November","December"],dayOfWeekShort:["Va","Hé","Ke","Sze","Cs","Pé","Szo"],dayOfWeek:["vasárnap","hétfő","kedd","szerda","csütörtök","péntek","szombat"]},az:{months:["Yanvar","Fevral","Mart","Aprel","May","Iyun","Iyul","Avqust","Sentyabr","Oktyabr","Noyabr","Dekabr"],dayOfWeekShort:["B","Be","Ça","Ç","Ca","C","Ş"],dayOfWeek:["Bazar","Bazar ertəsi","Çərşənbə axşamı","Çərşənbə","Cümə axşamı","Cümə","Şənbə"]},bs:{months:["Januar","Februar","Mart","April","Maj","Jun","Jul","Avgust","Septembar","Oktobar","Novembar","Decembar"],dayOfWeekShort:["Ned","Pon","Uto","Sri","Čet","Pet","Sub"],dayOfWeek:["Nedjelja","Ponedjeljak","Utorak","Srijeda","Četvrtak","Petak","Subota"]},ca:{months:["Gener","Febrer","Març","Abril","Maig","Juny","Juliol","Agost","Setembre","Octubre","Novembre","Desembre"],dayOfWeekShort:["Dg","Dl","Dt","Dc","Dj","Dv","Ds"],dayOfWeek:["Diumenge","Dilluns","Dimarts","Dimecres","Dijous","Divendres","Dissabte"]},"en-GB":{months:["January","February","March","April","May","June","July","August","September","October","November","December"],dayOfWeekShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],dayOfWeek:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]},et:{months:["Jaanuar","Veebruar","Märts","Aprill","Mai","Juuni","Juuli","August","September","Oktoober","November","Detsember"],dayOfWeekShort:["P","E","T","K","N","R","L"],dayOfWeek:["Pühapäev","Esmaspäev","Teisipäev","Kolmapäev","Neljapäev","Reede","Laupäev"]},eu:{months:["Urtarrila","Otsaila","Martxoa","Apirila","Maiatza","Ekaina","Uztaila","Abuztua","Iraila","Urria","Azaroa","Abendua"],dayOfWeekShort:["Ig.","Al.","Ar.","Az.","Og.","Or.","La."],dayOfWeek:["Igandea","Astelehena","Asteartea","Asteazkena","Osteguna","Ostirala","Larunbata"]},fi:{months:["Tammikuu","Helmikuu","Maaliskuu","Huhtikuu","Toukokuu","Kesäkuu","Heinäkuu","Elokuu","Syyskuu","Lokakuu","Marraskuu","Joulukuu"],dayOfWeekShort:["Su","Ma","Ti","Ke","To","Pe","La"],dayOfWeek:["sunnuntai","maanantai","tiistai","keskiviikko","torstai","perjantai","lauantai"]},gl:{months:["Xan","Feb","Maz","Abr","Mai","Xun","Xul","Ago","Set","Out","Nov","Dec"],dayOfWeekShort:["Dom","Lun","Mar","Mer","Xov","Ven","Sab"],dayOfWeek:["Domingo","Luns","Martes","Mércores","Xoves","Venres","Sábado"]},hr:{months:["Siječanj","Veljača","Ožujak","Travanj","Svibanj","Lipanj","Srpanj","Kolovoz","Rujan","Listopad","Studeni","Prosinac"],dayOfWeekShort:["Ned","Pon","Uto","Sri","Čet","Pet","Sub"],dayOfWeek:["Nedjelja","Ponedjeljak","Utorak","Srijeda","Četvrtak","Petak","Subota"]},ko:{months:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],dayOfWeekShort:["일","월","화","수","목","금","토"],dayOfWeek:["일요일","월요일","화요일","수요일","목요일","금요일","토요일"]},lt:{months:["Sausio","Vasario","Kovo","Balandžio","Gegužės","Birželio","Liepos","Rugpjūčio","Rugsėjo","Spalio","Lapkričio","Gruodžio"],dayOfWeekShort:["Sek","Pir","Ant","Tre","Ket","Pen","Šeš"],dayOfWeek:["Sekmadienis","Pirmadienis","Antradienis","Trečiadienis","Ketvirtadienis","Penktadienis","Šeštadienis"]},lv:{months:["Janvāris","Februāris","Marts","Aprīlis ","Maijs","Jūnijs","Jūlijs","Augusts","Septembris","Oktobris","Novembris","Decembris"],dayOfWeekShort:["Sv","Pr","Ot","Tr","Ct","Pk","St"],dayOfWeek:["Svētdiena","Pirmdiena","Otrdiena","Trešdiena","Ceturtdiena","Piektdiena","Sestdiena"]},mk:{months:["јануари","февруари","март","април","мај","јуни","јули","август","септември","октомври","ноември","декември"],dayOfWeekShort:["нед","пон","вто","сре","чет","пет","саб"],dayOfWeek:["Недела","Понеделник","Вторник","Среда","Четврток","Петок","Сабота"]},mn:{months:["1-р сар","2-р сар","3-р сар","4-р сар","5-р сар","6-р сар","7-р сар","8-р сар","9-р сар","10-р сар","11-р сар","12-р сар"],dayOfWeekShort:["Дав","Мяг","Лха","Пүр","Бсн","Бям","Ням"],dayOfWeek:["Даваа","Мягмар","Лхагва","Пүрэв","Баасан","Бямба","Ням"]},"pt-BR":{months:["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],dayOfWeekShort:["Dom","Seg","Ter","Qua","Qui","Sex","Sáb"],dayOfWeek:["Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado"]},sk:{months:["Január","Február","Marec","Apríl","Máj","Jún","Júl","August","September","Október","November","December"],dayOfWeekShort:["Ne","Po","Ut","St","Št","Pi","So"],dayOfWeek:["Nedeľa","Pondelok","Utorok","Streda","Štvrtok","Piatok","Sobota"]},sq:{months:["Janar","Shkurt","Mars","Prill","Maj","Qershor","Korrik","Gusht","Shtator","Tetor","Nëntor","Dhjetor"],dayOfWeekShort:["Die","Hën","Mar","Mër","Enj","Pre","Shtu"],dayOfWeek:["E Diel","E Hënë","E Martē","E Mërkurë","E Enjte","E Premte","E Shtunë"]},"sr-YU":{months:["Januar","Februar","Mart","April","Maj","Jun","Jul","Avgust","Septembar","Oktobar","Novembar","Decembar"],dayOfWeekShort:["Ned","Pon","Uto","Sre","čet","Pet","Sub"],dayOfWeek:["Nedelja","Ponedeljak","Utorak","Sreda","Četvrtak","Petak","Subota"]},sr:{months:["јануар","фебруар","март","април","мај","јун","јул","август","септембар","октобар","новембар","децембар"],dayOfWeekShort:["нед","пон","уто","сре","чет","пет","суб"],dayOfWeek:["Недеља","Понедељак","Уторак","Среда","Четвртак","Петак","Субота"]},sv:{months:["Januari","Februari","Mars","April","Maj","Juni","Juli","Augusti","September","Oktober","November","December"],dayOfWeekShort:["Sön","Mån","Tis","Ons","Tor","Fre","Lör"],dayOfWeek:["Söndag","Måndag","Tisdag","Onsdag","Torsdag","Fredag","Lördag"]},"zh-TW":{months:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],dayOfWeekShort:["日","一","二","三","四","五","六"],dayOfWeek:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"]},zh:{months:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],dayOfWeekShort:["日","一","二","三","四","五","六"],dayOfWeek:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"]},he:{months:["ינואר","פברואר","מרץ","אפריל","מאי","יוני","יולי","אוגוסט","ספטמבר","אוקטובר","נובמבר","דצמבר"],dayOfWeekShort:["א'","ב'","ג'","ד'","ה'","ו'","שבת"],dayOfWeek:["ראשון","שני","שלישי","רביעי","חמישי","שישי","שבת","ראשון"]},hy:{months:["Հունվար","Փետրվար","Մարտ","Ապրիլ","Մայիս","Հունիս","Հուլիս","Օգոստոս","Սեպտեմբեր","Հոկտեմբեր","Նոյեմբեր","Դեկտեմբեր"],dayOfWeekShort:["Կի","Երկ","Երք","Չոր","Հնգ","Ուրբ","Շբթ"],dayOfWeek:["Կիրակի","Երկուշաբթի","Երեքշաբթի","Չորեքշաբթի","Հինգշաբթի","Ուրբաթ","Շաբաթ"]},kg:{months:["Үчтүн айы","Бирдин айы","Жалган Куран","Чын Куран","Бугу","Кулжа","Теке","Баш Оона","Аяк Оона","Тогуздун айы","Жетинин айы","Бештин айы"],dayOfWeekShort:["Жек","Дүй","Шей","Шар","Бей","Жум","Ише"],dayOfWeek:["Жекшемб","Дүйшөмб","Шейшемб","Шаршемб","Бейшемби","Жума","Ишенб"]},rm:{months:["Schaner","Favrer","Mars","Avrigl","Matg","Zercladur","Fanadur","Avust","Settember","October","November","December"],dayOfWeekShort:["Du","Gli","Ma","Me","Gie","Ve","So"],dayOfWeek:["Dumengia","Glindesdi","Mardi","Mesemna","Gievgia","Venderdi","Sonda"]},ka:{months:["იანვარი","თებერვალი","მარტი","აპრილი","მაისი","ივნისი","ივლისი","აგვისტო","სექტემბერი","ოქტომბერი","ნოემბერი","დეკემბერი"],dayOfWeekShort:["კვ","ორშ","სამშ","ოთხ","ხუთ","პარ","შაბ"],dayOfWeek:["კვირა","ორშაბათი","სამშაბათი","ოთხშაბათი","ხუთშაბათი","პარასკევი","შაბათი"]}},ownerDocument:document,contentWindow:window,value:"",rtl:!1,format:"Y/m/d H:i",formatTime:"H:i",formatDate:"Y/m/d",startDate:!1,step:60,monthChangeSpinner:!0,closeOnDateSelect:!1,closeOnTimeSelect:!0,closeOnWithoutClick:!0,closeOnInputClick:!0,timepicker:!0,datepicker:!0,weeks:!1,defaultTime:!1,defaultDate:!1,minDate:!1,maxDate:!1,minTime:!1,maxTime:!1,disabledMinTime:!1,disabledMaxTime:!1,allowTimes:[],opened:!1,initTime:!0,inline:!1,theme:"",onSelectDate:function(){},onSelectTime:function(){},onChangeMonth:function(){},onGetWeekOfYear:function(){},onChangeYear:function(){},onChangeDateTime:function(){},onShow:function(){},onClose:function(){},onGenerate:function(){},withoutCopyright:!0,inverseButton:!1,hours12:!1,next:"xdsoft_next",prev:"xdsoft_prev",dayOfWeekStart:0,parentID:"body",timeHeightInTimePicker:25,timepickerScrollbar:!0,todayButton:!0,prevButton:!0,nextButton:!0,defaultSelect:!0,scrollMonth:!0,scrollTime:!0,scrollInput:!0,lazyInit:!1,mask:!1,validateOnBlur:!0,allowBlank:!0,yearStart:1950,yearEnd:2050,monthStart:0,monthEnd:11,style:"",id:"",fixed:!1,roundTime:"round",className:"",weekends:[],highlightedDates:[],highlightedPeriods:[],allowDates:[],allowDateRe:null,disabledDates:[],disabledWeekDays:[],yearOffset:0,beforeShowDay:null,enterLikeTab:!0,showApplyButton:!1},n=null,r="en",o="en",i={meridiem:["AM","PM"]},s=function(){var t=a.i18n[o],r={days:t.dayOfWeek,daysShort:t.dayOfWeekShort,months:t.months,monthsShort:e.map(t.months,function(e){return e.substring(0,3)})};"function"==typeof DateFormatter&&(n=new DateFormatter({dateSettings:e.extend({},i,r)}))};e.datetimepicker={setLocale:function(e){var t=a.i18n[e]?e:r;o!=t&&(o=t,s())},setDateFormatter:function(e){n=e},RFC_2822:"D, d M Y H:i:s O",ATOM:"Y-m-dTH:i:sP",ISO_8601:"Y-m-dTH:i:sO",RFC_822:"D, d M y H:i:s O",RFC_850:"l, d-M-y H:i:s T",RFC_1036:"D, d M y H:i:s O",RFC_1123:"D, d M Y H:i:s O",RSS:"D, d M Y H:i:s O",W3C:"Y-m-dTH:i:sP"},s(),window.getComputedStyle||(window.getComputedStyle=function(e){return this.el=e,this.getPropertyValue=function(t){var a=/(\-([a-z]){1})/g;return"float"===t&&(t="styleFloat"),a.test(t)&&(t=t.replace(a,function(e,t,a){return a.toUpperCase()})),e.currentStyle[t]||null},this}),Array.prototype.indexOf||(Array.prototype.indexOf=function(e,t){var a,n;for(a=t||0,n=this.length;n>a;a+=1)if(this[a]===e)return a;return-1}),Date.prototype.countDaysInMonth=function(){return new Date(this.getFullYear(),this.getMonth()+1,0).getDate()},e.fn.xdsoftScroller=function(t,a){return this.each(function(){var n,r,o,i,s,d=e(this),u=function(e){var t,a={x:0,y:0};return"touchstart"===e.type||"touchmove"===e.type||"touchend"===e.type||"touchcancel"===e.type?(t=e.originalEvent.touches[0]||e.originalEvent.changedTouches[0],a.x=t.clientX,a.y=t.clientY):("mousedown"===e.type||"mouseup"===e.type||"mousemove"===e.type||"mouseover"===e.type||"mouseout"===e.type||"mouseenter"===e.type||"mouseleave"===e.type)&&(a.x=e.clientX,a.y=e.clientY),a},l=100,f=!1,c=0,m=0,h=0,g=!1,p=0,y=function(){};return"hide"===a?void d.find(".xdsoft_scrollbar").hide():(e(this).hasClass("xdsoft_scroller_box")||(n=d.children().eq(0),r=d[0].clientHeight,o=n[0].offsetHeight,i=e('<div class="xdsoft_scrollbar"></div>'),s=e('<div class="xdsoft_scroller"></div>'),i.append(s),d.addClass("xdsoft_scroller_box").append(i),y=function(e){var t=u(e).y-c+p;0>t&&(t=0),t+s[0].offsetHeight>h&&(t=h-s[0].offsetHeight),d.trigger("scroll_element.xdsoft_scroller",[l?t/l:0])},s.on("touchstart.xdsoft_scroller mousedown.xdsoft_scroller",function(n){r||d.trigger("resize_scroll.xdsoft_scroller",[a]),c=u(n).y,p=parseInt(s.css("margin-top"),10),h=i[0].offsetHeight,"mousedown"===n.type||"touchstart"===n.type?(t.ownerDocument&&e(t.ownerDocument.body).addClass("xdsoft_noselect"),e([t.ownerDocument.body,t.contentWindow]).on("touchend mouseup.xdsoft_scroller",function o(){e([t.ownerDocument.body,t.contentWindow]).off("touchend mouseup.xdsoft_scroller",o).off("mousemove.xdsoft_scroller",y).removeClass("xdsoft_noselect")}),e(t.ownerDocument.body).on("mousemove.xdsoft_scroller",y)):(g=!0,n.stopPropagation(),n.preventDefault())}).on("touchmove",function(e){g&&(e.preventDefault(),y(e))}).on("touchend touchcancel",function(){g=!1,p=0}),d.on("scroll_element.xdsoft_scroller",function(e,t){r||d.trigger("resize_scroll.xdsoft_scroller",[t,!0]),t=t>1?1:0>t||isNaN(t)?0:t,s.css("margin-top",l*t),setTimeout(function(){n.css("marginTop",-parseInt((n[0].offsetHeight-r)*t,10))},10)}).on("resize_scroll.xdsoft_scroller",function(e,t,a){var u,f;r=d[0].clientHeight,o=n[0].offsetHeight,u=r/o,f=u*i[0].offsetHeight,u>1?s.hide():(s.show(),s.css("height",parseInt(f>10?f:10,10)),l=i[0].offsetHeight-s[0].offsetHeight,a!==!0&&d.trigger("scroll_element.xdsoft_scroller",[t||Math.abs(parseInt(n.css("marginTop"),10))/(o-r)]))}),d.on("mousewheel",function(e){var t=Math.abs(parseInt(n.css("marginTop"),10));return t-=20*e.deltaY,0>t&&(t=0),d.trigger("scroll_element.xdsoft_scroller",[t/(o-r)]),e.stopPropagation(),!1}),d.on("touchstart",function(e){f=u(e),m=Math.abs(parseInt(n.css("marginTop"),10))}),d.on("touchmove",function(e){if(f){e.preventDefault();var t=u(e);d.trigger("scroll_element.xdsoft_scroller",[(m-(t.y-f.y))/(o-r)])}}),d.on("touchend touchcancel",function(){f=!1,m=0})),void d.trigger("resize_scroll.xdsoft_scroller",[a]))})},e.fn.datetimepicker=function(r,i){var s,d,u=this,l=48,f=57,c=96,m=105,h=17,g=46,p=13,y=27,v=8,D=37,b=38,k=39,x=40,S=9,T=116,w=65,O=67,M=86,W=90,_=89,C=!1,F=e.isPlainObject(r)||!r?e.extend(!0,{},a,r):e.extend(!0,{},a),P=0,A=function(e){e.on("open.xdsoft focusin.xdsoft mousedown.xdsoft touchstart",function t(){e.is(":disabled")||e.data("xdsoft_datetimepicker")||(clearTimeout(P),P=setTimeout(function(){e.data("xdsoft_datetimepicker")||s(e),e.off("open.xdsoft focusin.xdsoft mousedown.xdsoft touchstart",t).trigger("open.xdsoft")},100))})};return s=function(a){function i(){var e,t=!1;return F.startDate?t=j.strToDate(F.startDate):(t=F.value||(a&&a.val&&a.val()?a.val():""),t?t=j.strToDateTime(t):F.defaultDate&&(t=j.strToDateTime(F.defaultDate),F.defaultTime&&(e=j.strtotime(F.defaultTime),t.setHours(e.getHours()),t.setMinutes(e.getMinutes())))),t&&j.isValidDate(t)?J.data("changed",!0):t="",t||0}function s(t){var n=function(e,t){var a=e.replace(/([\[\]\/\{\}\(\)\-\.\+]{1})/g,"\\$1").replace(/_/g,"{digit+}").replace(/([0-9]{1})/g,"{digit$1}").replace(/\{digit([0-9]{1})\}/g,"[0-$1_]{1}").replace(/\{digit[\+]\}/g,"[0-9_]{1}");return new RegExp(a).test(t)},r=function(e){try{if(t.ownerDocument.selection&&t.ownerDocument.selection.createRange){var a=t.ownerDocument.selection.createRange();return a.getBookmark().charCodeAt(2)-2}if(e.setSelectionRange)return e.selectionStart}catch(n){return 0}},o=function(e,a){if(e="string"==typeof e||e instanceof String?t.ownerDocument.getElementById(e):e,!e)return!1;if(e.createTextRange){var n=e.createTextRange();return n.collapse(!0),n.moveEnd("character",a),n.moveStart("character",a),n.select(),!0}return e.setSelectionRange?(e.setSelectionRange(a,a),!0):!1};t.mask&&a.off("keydown.xdsoft"),t.mask===!0&&(t.mask="undefined"!=typeof moment?t.format.replace(/Y{4}/g,"9999").replace(/Y{2}/g,"99").replace(/M{2}/g,"19").replace(/D{2}/g,"39").replace(/H{2}/g,"29").replace(/m{2}/g,"59").replace(/s{2}/g,"59"):t.format.replace(/Y/g,"9999").replace(/F/g,"9999").replace(/m/g,"19").replace(/d/g,"39").replace(/H/g,"29").replace(/i/g,"59").replace(/s/g,"59")),"string"===e.type(t.mask)&&(n(t.mask,a.val())||(a.val(t.mask.replace(/[0-9]/g,"_")),o(a[0],0)),a.on("keydown.xdsoft",function(i){var s,d,u=this.value,F=i.which;if(F>=l&&f>=F||F>=c&&m>=F||F===v||F===g){for(s=r(this),d=F!==v&&F!==g?String.fromCharCode(F>=c&&m>=F?F-l:F):"_",F!==v&&F!==g||!s||(s-=1,d="_");/[^0-9_]/.test(t.mask.substr(s,1))&&s<t.mask.length&&s>0;)s+=F===v||F===g?-1:1;if(u=u.substr(0,s)+d+u.substr(s+1),""===e.trim(u))u=t.mask.replace(/[0-9]/g,"_");else if(s===t.mask.length)return i.preventDefault(),!1;for(s+=F===v||F===g?0:1;/[^0-9_]/.test(t.mask.substr(s,1))&&s<t.mask.length&&s>0;)s+=F===v||F===g?-1:1;n(t.mask,u)?(this.value=u,o(this,s)):""===e.trim(u)?this.value=t.mask.replace(/[0-9]/g,"_"):a.trigger("error_input.xdsoft")}else if(-1!==[w,O,M,W,_].indexOf(F)&&C||-1!==[y,b,x,D,k,T,h,S,p].indexOf(F))return!0;return i.preventDefault(),!1}))}var d,u,P,A,Y,j,H,J=e('<div class="xdsoft_datetimepicker xdsoft_noselect"></div>'),z=e('<div class="xdsoft_copyright"><a target="_blank" href="http://xdsoft.net/jqplugins/datetimepicker/">xdsoft.net</a></div>'),I=e('<div class="xdsoft_datepicker active"></div>'),N=e('<div class="xdsoft_monthpicker"><button type="button" class="xdsoft_prev"></button><button type="button" class="xdsoft_today_button"></button><div class="xdsoft_label xdsoft_month"><span></span><i></i></div><div class="xdsoft_label xdsoft_year"><span></span><i></i></div><button type="button" class="xdsoft_next"></button></div>'),L=e('<div class="xdsoft_calendar"></div>'),E=e('<div class="xdsoft_timepicker active"><button type="button" class="xdsoft_prev"></button><div class="xdsoft_time_box"></div><button type="button" class="xdsoft_next"></button></div>'),R=E.find(".xdsoft_time_box").eq(0),B=e('<div class="xdsoft_time_variant"></div>'),V=e('<button type="button" class="xdsoft_save_selected blue-gradient-button">Save Selected</button>'),G=e('<div class="xdsoft_select xdsoft_monthselect"><div></div></div>'),U=e('<div class="xdsoft_select xdsoft_yearselect"><div></div></div>'),q=!1,X=0;F.id&&J.attr("id",F.id),F.style&&J.attr("style",F.style),F.weeks&&J.addClass("xdsoft_showweeks"),F.rtl&&J.addClass("xdsoft_rtl"),J.addClass("xdsoft_"+F.theme),J.addClass(F.className),N.find(".xdsoft_month span").after(G),N.find(".xdsoft_year span").after(U),N.find(".xdsoft_month,.xdsoft_year").on("touchstart mousedown.xdsoft",function(t){var a,n,r=e(this).find(".xdsoft_select").eq(0),o=0,i=0,s=r.is(":visible");for(N.find(".xdsoft_select").hide(),j.currentTime&&(o=j.currentTime[e(this).hasClass("xdsoft_month")?"getMonth":"getFullYear"]()),r[s?"hide":"show"](),a=r.find("div.xdsoft_option"),n=0;n<a.length&&a.eq(n).data("value")!==o;n+=1)i+=a[0].offsetHeight;return r.xdsoftScroller(F,i/(r.children()[0].offsetHeight-r[0].clientHeight)),t.stopPropagation(),!1}),N.find(".xdsoft_select").xdsoftScroller(F).on("touchstart mousedown.xdsoft",function(e){e.stopPropagation(),e.preventDefault()}).on("touchstart mousedown.xdsoft",".xdsoft_option",function(){(void 0===j.currentTime||null===j.currentTime)&&(j.currentTime=j.now());var t=j.currentTime.getFullYear();j&&j.currentTime&&j.currentTime[e(this).parent().parent().hasClass("xdsoft_monthselect")?"setMonth":"setFullYear"](e(this).data("value")),e(this).parent().parent().hide(),J.trigger("xchange.xdsoft"),F.onChangeMonth&&e.isFunction(F.onChangeMonth)&&F.onChangeMonth.call(J,j.currentTime,J.data("input")),t!==j.currentTime.getFullYear()&&e.isFunction(F.onChangeYear)&&F.onChangeYear.call(J,j.currentTime,J.data("input"))}),J.getValue=function(){return j.getCurrentTime()},J.setOptions=function(r){var o={};F=e.extend(!0,{},F,r),
    r.allowTimes&&e.isArray(r.allowTimes)&&r.allowTimes.length&&(F.allowTimes=e.extend(!0,[],r.allowTimes)),r.weekends&&e.isArray(r.weekends)&&r.weekends.length&&(F.weekends=e.extend(!0,[],r.weekends)),r.allowDates&&e.isArray(r.allowDates)&&r.allowDates.length&&(F.allowDates=e.extend(!0,[],r.allowDates)),r.allowDateRe&&"[object String]"===Object.prototype.toString.call(r.allowDateRe)&&(F.allowDateRe=new RegExp(r.allowDateRe)),r.highlightedDates&&e.isArray(r.highlightedDates)&&r.highlightedDates.length&&(e.each(r.highlightedDates,function(a,r){var i,s=e.map(r.split(","),e.trim),d=new t(n.parseDate(s[0],F.formatDate),s[1],s[2]),u=n.formatDate(d.date,F.formatDate);void 0!==o[u]?(i=o[u].desc,i&&i.length&&d.desc&&d.desc.length&&(o[u].desc=i+"\n"+d.desc)):o[u]=d}),F.highlightedDates=e.extend(!0,[],o)),r.highlightedPeriods&&e.isArray(r.highlightedPeriods)&&r.highlightedPeriods.length&&(o=e.extend(!0,[],F.highlightedDates),e.each(r.highlightedPeriods,function(a,r){var i,s,d,u,l,f,c;if(e.isArray(r))i=r[0],s=r[1],d=r[2],c=r[3];else{var m=e.map(r.split(","),e.trim);i=n.parseDate(m[0],F.formatDate),s=n.parseDate(m[1],F.formatDate),d=m[2],c=m[3]}for(;s>=i;)u=new t(i,d,c),l=n.formatDate(i,F.formatDate),i.setDate(i.getDate()+1),void 0!==o[l]?(f=o[l].desc,f&&f.length&&u.desc&&u.desc.length&&(o[l].desc=f+"\n"+u.desc)):o[l]=u}),F.highlightedDates=e.extend(!0,[],o)),r.disabledDates&&e.isArray(r.disabledDates)&&r.disabledDates.length&&(F.disabledDates=e.extend(!0,[],r.disabledDates)),r.disabledWeekDays&&e.isArray(r.disabledWeekDays)&&r.disabledWeekDays.length&&(F.disabledWeekDays=e.extend(!0,[],r.disabledWeekDays)),!F.open&&!F.opened||F.inline||a.trigger("open.xdsoft"),F.inline&&(q=!0,J.addClass("xdsoft_inline"),a.after(J).hide()),F.inverseButton&&(F.next="xdsoft_prev",F.prev="xdsoft_next"),F.datepicker?I.addClass("active"):I.removeClass("active"),F.timepicker?E.addClass("active"):E.removeClass("active"),F.value&&(j.setCurrentTime(F.value),a&&a.val&&a.val(j.str)),F.dayOfWeekStart=isNaN(F.dayOfWeekStart)?0:parseInt(F.dayOfWeekStart,10)%7,F.timepickerScrollbar||R.xdsoftScroller(F,"hide"),F.minDate&&/^[\+\-](.*)$/.test(F.minDate)&&(F.minDate=n.formatDate(j.strToDateTime(F.minDate),F.formatDate)),F.maxDate&&/^[\+\-](.*)$/.test(F.maxDate)&&(F.maxDate=n.formatDate(j.strToDateTime(F.maxDate),F.formatDate)),V.toggle(F.showApplyButton),N.find(".xdsoft_today_button").css("visibility",F.todayButton?"visible":"hidden"),N.find("."+F.prev).css("visibility",F.prevButton?"visible":"hidden"),N.find("."+F.next).css("visibility",F.nextButton?"visible":"hidden"),s(F),F.validateOnBlur&&a.off("blur.xdsoft").on("blur.xdsoft",function(){if(F.allowBlank&&(!e.trim(e(this).val()).length||"string"==typeof F.mask&&e.trim(e(this).val())===F.mask.replace(/[0-9]/g,"_")))e(this).val(null),J.data("xdsoft_datetime").empty();else{var t=n.parseDate(e(this).val(),F.format);if(t)e(this).val(n.formatDate(t,F.format));else{var a=+[e(this).val()[0],e(this).val()[1]].join(""),r=+[e(this).val()[2],e(this).val()[3]].join("");e(this).val(!F.datepicker&&F.timepicker&&a>=0&&24>a&&r>=0&&60>r?[a,r].map(function(e){return e>9?e:"0"+e}).join(":"):n.formatDate(j.now(),F.format))}J.data("xdsoft_datetime").setCurrentTime(e(this).val())}J.trigger("changedatetime.xdsoft"),J.trigger("close.xdsoft")}),F.dayOfWeekStartPrev=0===F.dayOfWeekStart?6:F.dayOfWeekStart-1,J.trigger("xchange.xdsoft").trigger("afterOpen.xdsoft")},J.data("options",F).on("touchstart mousedown.xdsoft",function(e){return e.stopPropagation(),e.preventDefault(),U.hide(),G.hide(),!1}),R.append(B),R.xdsoftScroller(F),J.on("afterOpen.xdsoft",function(){R.xdsoftScroller(F)}),J.append(I).append(E),F.withoutCopyright!==!0&&J.append(z),I.append(N).append(L).append(V),e(F.parentID).append(J),d=function(){var t=this;t.now=function(e){var a,n,r=new Date;return!e&&F.defaultDate&&(a=t.strToDateTime(F.defaultDate),r.setFullYear(a.getFullYear()),r.setMonth(a.getMonth()),r.setDate(a.getDate())),F.yearOffset&&r.setFullYear(r.getFullYear()+F.yearOffset),!e&&F.defaultTime&&(n=t.strtotime(F.defaultTime),r.setHours(n.getHours()),r.setMinutes(n.getMinutes())),r},t.isValidDate=function(e){return"[object Date]"!==Object.prototype.toString.call(e)?!1:!isNaN(e.getTime())},t.setCurrentTime=function(e,a){t.currentTime="string"==typeof e?t.strToDateTime(e):t.isValidDate(e)?e:e||a||!F.allowBlank?t.now():null,J.trigger("xchange.xdsoft")},t.empty=function(){t.currentTime=null},t.getCurrentTime=function(){return t.currentTime},t.nextMonth=function(){(void 0===t.currentTime||null===t.currentTime)&&(t.currentTime=t.now());var a,n=t.currentTime.getMonth()+1;return 12===n&&(t.currentTime.setFullYear(t.currentTime.getFullYear()+1),n=0),a=t.currentTime.getFullYear(),t.currentTime.setDate(Math.min(new Date(t.currentTime.getFullYear(),n+1,0).getDate(),t.currentTime.getDate())),t.currentTime.setMonth(n),F.onChangeMonth&&e.isFunction(F.onChangeMonth)&&F.onChangeMonth.call(J,j.currentTime,J.data("input")),a!==t.currentTime.getFullYear()&&e.isFunction(F.onChangeYear)&&F.onChangeYear.call(J,j.currentTime,J.data("input")),J.trigger("xchange.xdsoft"),n},t.prevMonth=function(){(void 0===t.currentTime||null===t.currentTime)&&(t.currentTime=t.now());var a=t.currentTime.getMonth()-1;return-1===a&&(t.currentTime.setFullYear(t.currentTime.getFullYear()-1),a=11),t.currentTime.setDate(Math.min(new Date(t.currentTime.getFullYear(),a+1,0).getDate(),t.currentTime.getDate())),t.currentTime.setMonth(a),F.onChangeMonth&&e.isFunction(F.onChangeMonth)&&F.onChangeMonth.call(J,j.currentTime,J.data("input")),J.trigger("xchange.xdsoft"),a},t.getWeekOfYear=function(t){if(F.onGetWeekOfYear&&e.isFunction(F.onGetWeekOfYear)){var a=F.onGetWeekOfYear.call(J,t);if("undefined"!=typeof a)return a}var n=new Date(t.getFullYear(),0,1);return 4!=n.getDay()&&n.setMonth(0,1+(4-n.getDay()+7)%7),Math.ceil(((t-n)/864e5+n.getDay()+1)/7)},t.strToDateTime=function(e){var a,r,o=[];return e&&e instanceof Date&&t.isValidDate(e)?e:(o=/^(\+|\-)(.*)$/.exec(e),o&&(o[2]=n.parseDate(o[2],F.formatDate)),o&&o[2]?(a=o[2].getTime()-6e4*o[2].getTimezoneOffset(),r=new Date(t.now(!0).getTime()+parseInt(o[1]+"1",10)*a)):r=e?n.parseDate(e,F.format):t.now(),t.isValidDate(r)||(r=t.now()),r)},t.strToDate=function(e){if(e&&e instanceof Date&&t.isValidDate(e))return e;var a=e?n.parseDate(e,F.formatDate):t.now(!0);return t.isValidDate(a)||(a=t.now(!0)),a},t.strtotime=function(e){if(e&&e instanceof Date&&t.isValidDate(e))return e;var a=e?n.parseDate(e,F.formatTime):t.now(!0);return t.isValidDate(a)||(a=t.now(!0)),a},t.str=function(){return n.formatDate(t.currentTime,F.format)},t.currentTime=this.now()},j=new d,V.on("touchend click",function(e){e.preventDefault(),J.data("changed",!0),j.setCurrentTime(i()),a.val(j.str()),J.trigger("close.xdsoft")}),N.find(".xdsoft_today_button").on("touchend mousedown.xdsoft",function(){J.data("changed",!0),j.setCurrentTime(0,!0),J.trigger("afterOpen.xdsoft")}).on("dblclick.xdsoft",function(){var e,t,n=j.getCurrentTime();n=new Date(n.getFullYear(),n.getMonth(),n.getDate()),e=j.strToDate(F.minDate),e=new Date(e.getFullYear(),e.getMonth(),e.getDate()),e>n||(t=j.strToDate(F.maxDate),t=new Date(t.getFullYear(),t.getMonth(),t.getDate()),n>t||(a.val(j.str()),a.trigger("change"),J.trigger("close.xdsoft")))}),N.find(".xdsoft_prev,.xdsoft_next").on("touchend mousedown.xdsoft",function(){var t=e(this),a=0,n=!1;!function r(e){t.hasClass(F.next)?j.nextMonth():t.hasClass(F.prev)&&j.prevMonth(),F.monthChangeSpinner&&(n||(a=setTimeout(r,e||100)))}(500),e([F.ownerDocument.body,F.contentWindow]).on("touchend mouseup.xdsoft",function o(){clearTimeout(a),n=!0,e([F.ownerDocument.body,F.contentWindow]).off("touchend mouseup.xdsoft",o)})}),E.find(".xdsoft_prev,.xdsoft_next").on("touchend mousedown.xdsoft",function(){var t=e(this),a=0,n=!1,r=110;!function o(e){var i=R[0].clientHeight,s=B[0].offsetHeight,d=Math.abs(parseInt(B.css("marginTop"),10));t.hasClass(F.next)&&s-i-F.timeHeightInTimePicker>=d?B.css("marginTop","-"+(d+F.timeHeightInTimePicker)+"px"):t.hasClass(F.prev)&&d-F.timeHeightInTimePicker>=0&&B.css("marginTop","-"+(d-F.timeHeightInTimePicker)+"px"),R.trigger("scroll_element.xdsoft_scroller",[Math.abs(parseInt(B[0].style.marginTop,10)/(s-i))]),r=r>10?10:r-10,n||(a=setTimeout(o,e||r))}(500),e([F.ownerDocument.body,F.contentWindow]).on("touchend mouseup.xdsoft",function i(){clearTimeout(a),n=!0,e([F.ownerDocument.body,F.contentWindow]).off("touchend mouseup.xdsoft",i)})}),u=0,J.on("xchange.xdsoft",function(t){clearTimeout(u),u=setTimeout(function(){if(void 0===j.currentTime||null===j.currentTime){if(F.allowBlank)return;j.currentTime=j.now()}for(var t,i,s,d,u,l,f,c,m,h,g="",p=new Date(j.currentTime.getFullYear(),j.currentTime.getMonth(),1,12,0,0),y=0,v=j.now(),D=!1,b=!1,k=[],x=!0,S="",T="";p.getDay()!==F.dayOfWeekStart;)p.setDate(p.getDate()-1);for(g+="<table><thead><tr>",F.weeks&&(g+="<th></th>"),t=0;7>t;t+=1)g+="<th>"+F.i18n[o].dayOfWeekShort[(t+F.dayOfWeekStart)%7]+"</th>";for(g+="</tr></thead>",g+="<tbody>",F.maxDate!==!1&&(D=j.strToDate(F.maxDate),D=new Date(D.getFullYear(),D.getMonth(),D.getDate(),23,59,59,999)),F.minDate!==!1&&(b=j.strToDate(F.minDate),b=new Date(b.getFullYear(),b.getMonth(),b.getDate()));y<j.currentTime.countDaysInMonth()||p.getDay()!==F.dayOfWeekStart||j.currentTime.getMonth()===p.getMonth();)k=[],y+=1,s=p.getDay(),d=p.getDate(),u=p.getFullYear(),l=p.getMonth(),f=j.getWeekOfYear(p),h="",k.push("xdsoft_date"),c=F.beforeShowDay&&e.isFunction(F.beforeShowDay.call)?F.beforeShowDay.call(J,p):null,F.allowDateRe&&"[object RegExp]"===Object.prototype.toString.call(F.allowDateRe)?F.allowDateRe.test(n.formatDate(p,F.formatDate))||k.push("xdsoft_disabled"):F.allowDates&&F.allowDates.length>0?-1===F.allowDates.indexOf(n.formatDate(p,F.formatDate))&&k.push("xdsoft_disabled"):D!==!1&&p>D||b!==!1&&b>p||c&&c[0]===!1?k.push("xdsoft_disabled"):-1!==F.disabledDates.indexOf(n.formatDate(p,F.formatDate))?k.push("xdsoft_disabled"):-1!==F.disabledWeekDays.indexOf(s)?k.push("xdsoft_disabled"):a.is("[readonly]")&&k.push("xdsoft_disabled"),c&&""!==c[1]&&k.push(c[1]),j.currentTime.getMonth()!==l&&k.push("xdsoft_other_month"),(F.defaultSelect||J.data("changed"))&&n.formatDate(j.currentTime,F.formatDate)===n.formatDate(p,F.formatDate)&&k.push("xdsoft_current"),n.formatDate(v,F.formatDate)===n.formatDate(p,F.formatDate)&&k.push("xdsoft_today"),(0===p.getDay()||6===p.getDay()||-1!==F.weekends.indexOf(n.formatDate(p,F.formatDate)))&&k.push("xdsoft_weekend"),void 0!==F.highlightedDates[n.formatDate(p,F.formatDate)]&&(i=F.highlightedDates[n.formatDate(p,F.formatDate)],k.push(void 0===i.style?"xdsoft_highlighted_default":i.style),h=void 0===i.desc?"":i.desc),F.beforeShowDay&&e.isFunction(F.beforeShowDay)&&k.push(F.beforeShowDay(p)),x&&(g+="<tr>",x=!1,F.weeks&&(g+="<th>"+f+"</th>")),g+='<td data-date="'+d+'" data-month="'+l+'" data-year="'+u+'" class="xdsoft_date xdsoft_day_of_week'+p.getDay()+" "+k.join(" ")+'" title="'+h+'"><div>'+d+"</div></td>",p.getDay()===F.dayOfWeekStartPrev&&(g+="</tr>",x=!0),p.setDate(d+1);if(g+="</tbody></table>",L.html(g),N.find(".xdsoft_label span").eq(0).text(F.i18n[o].months[j.currentTime.getMonth()]),N.find(".xdsoft_label span").eq(1).text(j.currentTime.getFullYear()),S="",T="",l="",m=function(t,r){var o,i,s=j.now(),d=F.allowTimes&&e.isArray(F.allowTimes)&&F.allowTimes.length;s.setHours(t),t=parseInt(s.getHours(),10),s.setMinutes(r),r=parseInt(s.getMinutes(),10),o=new Date(j.currentTime),o.setHours(t),o.setMinutes(r),k=[],F.minDateTime!==!1&&F.minDateTime>o||F.maxTime!==!1&&j.strtotime(F.maxTime).getTime()<s.getTime()||F.minTime!==!1&&j.strtotime(F.minTime).getTime()>s.getTime()?k.push("xdsoft_disabled"):F.minDateTime!==!1&&F.minDateTime>o||F.disabledMinTime!==!1&&s.getTime()>j.strtotime(F.disabledMinTime).getTime()&&F.disabledMaxTime!==!1&&s.getTime()<j.strtotime(F.disabledMaxTime).getTime()?k.push("xdsoft_disabled"):a.is("[readonly]")&&k.push("xdsoft_disabled"),i=new Date(j.currentTime),i.setHours(parseInt(j.currentTime.getHours(),10)),d||i.setMinutes(Math[F.roundTime](j.currentTime.getMinutes()/F.step)*F.step),(F.initTime||F.defaultSelect||J.data("changed"))&&i.getHours()===parseInt(t,10)&&(!d&&F.step>59||i.getMinutes()===parseInt(r,10))&&(F.defaultSelect||J.data("changed")?k.push("xdsoft_current"):F.initTime&&k.push("xdsoft_init_time")),parseInt(v.getHours(),10)===parseInt(t,10)&&parseInt(v.getMinutes(),10)===parseInt(r,10)&&k.push("xdsoft_today"),S+='<div class="xdsoft_time '+k.join(" ")+'" data-hour="'+t+'" data-minute="'+r+'">'+n.formatDate(s,F.formatTime)+"</div>"},F.allowTimes&&e.isArray(F.allowTimes)&&F.allowTimes.length)for(y=0;y<F.allowTimes.length;y+=1)T=j.strtotime(F.allowTimes[y]).getHours(),l=j.strtotime(F.allowTimes[y]).getMinutes(),m(T,l);else for(y=0,t=0;y<(F.hours12?12:24);y+=1)for(t=0;60>t;t+=F.step)T=(10>y?"0":"")+y,l=(10>t?"0":"")+t,m(T,l);for(B.html(S),r="",y=0,y=parseInt(F.yearStart,10)+F.yearOffset;y<=parseInt(F.yearEnd,10)+F.yearOffset;y+=1)r+='<div class="xdsoft_option '+(j.currentTime.getFullYear()===y?"xdsoft_current":"")+'" data-value="'+y+'">'+y+"</div>";for(U.children().eq(0).html(r),y=parseInt(F.monthStart,10),r="";y<=parseInt(F.monthEnd,10);y+=1)r+='<div class="xdsoft_option '+(j.currentTime.getMonth()===y?"xdsoft_current":"")+'" data-value="'+y+'">'+F.i18n[o].months[y]+"</div>";G.children().eq(0).html(r),e(J).trigger("generate.xdsoft")},10),t.stopPropagation()}).on("afterOpen.xdsoft",function(){if(F.timepicker){var e,t,a,n;B.find(".xdsoft_current").length?e=".xdsoft_current":B.find(".xdsoft_init_time").length&&(e=".xdsoft_init_time"),e?(t=R[0].clientHeight,a=B[0].offsetHeight,n=B.find(e).index()*F.timeHeightInTimePicker+1,n>a-t&&(n=a-t),R.trigger("scroll_element.xdsoft_scroller",[parseInt(n,10)/(a-t)])):R.trigger("scroll_element.xdsoft_scroller",[0])}}),P=0,L.on("touchend click.xdsoft","td",function(t){t.stopPropagation(),P+=1;var n=e(this),r=j.currentTime;return(void 0===r||null===r)&&(j.currentTime=j.now(),r=j.currentTime),n.hasClass("xdsoft_disabled")?!1:(r.setDate(1),r.setFullYear(n.data("year")),r.setMonth(n.data("month")),r.setDate(n.data("date")),J.trigger("select.xdsoft",[r]),a.val(j.str()),F.onSelectDate&&e.isFunction(F.onSelectDate)&&F.onSelectDate.call(J,j.currentTime,J.data("input"),t),J.data("changed",!0),J.trigger("xchange.xdsoft"),J.trigger("changedatetime.xdsoft"),(P>1||F.closeOnDateSelect===!0||F.closeOnDateSelect===!1&&!F.timepicker)&&!F.inline&&J.trigger("close.xdsoft"),void setTimeout(function(){P=0},200))}),B.on("touchend click.xdsoft","div",function(t){t.stopPropagation();var a=e(this),n=j.currentTime;return(void 0===n||null===n)&&(j.currentTime=j.now(),n=j.currentTime),a.hasClass("xdsoft_disabled")?!1:(n.setHours(a.data("hour")),n.setMinutes(a.data("minute")),J.trigger("select.xdsoft",[n]),J.data("input").val(j.str()),F.onSelectTime&&e.isFunction(F.onSelectTime)&&F.onSelectTime.call(J,j.currentTime,J.data("input"),t),J.data("changed",!0),J.trigger("xchange.xdsoft"),J.trigger("changedatetime.xdsoft"),void(F.inline!==!0&&F.closeOnTimeSelect===!0&&J.trigger("close.xdsoft")))}),I.on("mousewheel.xdsoft",function(e){return F.scrollMonth?(e.deltaY<0?j.nextMonth():j.prevMonth(),!1):!0}),a.on("mousewheel.xdsoft",function(e){return F.scrollInput?!F.datepicker&&F.timepicker?(A=B.find(".xdsoft_current").length?B.find(".xdsoft_current").eq(0).index():0,A+e.deltaY>=0&&A+e.deltaY<B.children().length&&(A+=e.deltaY),B.children().eq(A).length&&B.children().eq(A).trigger("mousedown"),!1):F.datepicker&&!F.timepicker?(I.trigger(e,[e.deltaY,e.deltaX,e.deltaY]),a.val&&a.val(j.str()),J.trigger("changedatetime.xdsoft"),!1):void 0:!0}),J.on("changedatetime.xdsoft",function(t){if(F.onChangeDateTime&&e.isFunction(F.onChangeDateTime)){var a=J.data("input");F.onChangeDateTime.call(J,j.currentTime,a,t),delete F.value,a.trigger("change")}}).on("generate.xdsoft",function(){F.onGenerate&&e.isFunction(F.onGenerate)&&F.onGenerate.call(J,j.currentTime,J.data("input")),q&&(J.trigger("afterOpen.xdsoft"),q=!1)}).on("click.xdsoft",function(e){e.stopPropagation()}),A=0,H=function(e,t){do if(e=e.parentNode,t(e)===!1)break;while("HTML"!==e.nodeName)},Y=function(){var t,a,n,r,o,i,s,d,u,l,f,c,m;if(d=J.data("input"),t=d.offset(),a=d[0],l="top",n=t.top+a.offsetHeight-1,r=t.left,o="absolute",u=e(F.contentWindow).width(),c=e(F.contentWindow).height(),m=e(F.contentWindow).scrollTop(),F.ownerDocument.documentElement.clientWidth-t.left<I.parent().outerWidth(!0)){var h=I.parent().outerWidth(!0)-a.offsetWidth;r-=h}"rtl"===d.parent().css("direction")&&(r-=J.outerWidth()-d.outerWidth()),F.fixed?(n-=m,r-=e(F.contentWindow).scrollLeft(),o="fixed"):(s=!1,H(a,function(e){return"fixed"===F.contentWindow.getComputedStyle(e).getPropertyValue("position")?(s=!0,!1):void 0}),s?(o="fixed",n+J.outerHeight()>c+m?(l="bottom",n=c+m-t.top):n-=m):n+a.offsetHeight>c+m&&(n=t.top-a.offsetHeight+1),0>n&&(n=0),r+a.offsetWidth>u&&(r=u-a.offsetWidth)),i=J[0],H(i,function(e){var t;return t=F.contentWindow.getComputedStyle(e).getPropertyValue("position"),"relative"===t&&u>=e.offsetWidth?(r-=(u-e.offsetWidth)/2,!1):void 0}),f={position:o,left:r,top:"",bottom:""},f[l]=n,J.css(f)},J.on("open.xdsoft",function(t){var a=!0;F.onShow&&e.isFunction(F.onShow)&&(a=F.onShow.call(J,j.currentTime,J.data("input"),t)),a!==!1&&(J.show(),Y(),e(F.contentWindow).off("resize.xdsoft",Y).on("resize.xdsoft",Y),F.closeOnWithoutClick&&e([F.ownerDocument.body,F.contentWindow]).on("touchstart mousedown.xdsoft",function n(){J.trigger("close.xdsoft"),e([F.ownerDocument.body,F.contentWindow]).off("touchstart mousedown.xdsoft",n)}))}).on("close.xdsoft",function(t){var a=!0;N.find(".xdsoft_month,.xdsoft_year").find(".xdsoft_select").hide(),F.onClose&&e.isFunction(F.onClose)&&(a=F.onClose.call(J,j.currentTime,J.data("input"),t)),a===!1||F.opened||F.inline||J.hide(),t.stopPropagation()}).on("toggle.xdsoft",function(){J.trigger(J.is(":visible")?"close.xdsoft":"open.xdsoft")}).data("input",a),X=0,J.data("xdsoft_datetime",j),J.setOptions(F),j.setCurrentTime(i()),a.data("xdsoft_datetimepicker",J).on("open.xdsoft focusin.xdsoft mousedown.xdsoft touchstart",function(){a.is(":disabled")||a.data("xdsoft_datetimepicker").is(":visible")&&F.closeOnInputClick||(clearTimeout(X),X=setTimeout(function(){a.is(":disabled")||(q=!0,j.setCurrentTime(i(),!0),F.mask&&s(F),J.trigger("open.xdsoft"))},100))}).on("keydown.xdsoft",function(t){var a,n=t.which;return-1!==[p].indexOf(n)&&F.enterLikeTab?(a=e("input:visible,textarea:visible,button:visible,a:visible"),J.trigger("close.xdsoft"),a.eq(a.index(this)+1).focus(),!1):-1!==[S].indexOf(n)?(J.trigger("close.xdsoft"),!0):void 0}).on("blur.xdsoft",function(){J.trigger("close.xdsoft")})},d=function(t){var a=t.data("xdsoft_datetimepicker");a&&(a.data("xdsoft_datetime",null),a.remove(),t.data("xdsoft_datetimepicker",null).off(".xdsoft"),e(F.contentWindow).off("resize.xdsoft"),e([F.contentWindow,F.ownerDocument.body]).off("mousedown.xdsoft touchstart"),t.unmousewheel&&t.unmousewheel())},e(F.ownerDocument).off("keydown.xdsoftctrl keyup.xdsoftctrl").on("keydown.xdsoftctrl",function(e){e.keyCode===h&&(C=!0)}).on("keyup.xdsoftctrl",function(e){e.keyCode===h&&(C=!1)}),this.each(function(){var t,a=e(this).data("xdsoft_datetimepicker");if(a){if("string"===e.type(r))switch(r){case"show":e(this).select().focus(),a.trigger("open.xdsoft");break;case"hide":a.trigger("close.xdsoft");break;case"toggle":a.trigger("toggle.xdsoft");break;case"destroy":d(e(this));break;case"reset":this.value=this.defaultValue,this.value&&a.data("xdsoft_datetime").isValidDate(n.parseDate(this.value,F.format))||a.data("changed",!1),a.data("xdsoft_datetime").setCurrentTime(this.value);break;case"validate":t=a.data("input"),t.trigger("blur.xdsoft");break;default:a[r]&&e.isFunction(a[r])&&(u=a[r](i))}else a.setOptions(r);return 0}"string"!==e.type(r)&&(!F.lazyInit||F.open||F.inline?s(e(this)):A(e(this)))}),u},e.fn.datetimepicker.defaults=a}),function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?module.exports=e:e(jQuery)}(function(e){function t(t){var i=t||window.event,s=d.call(arguments,1),u=0,f=0,c=0,m=0,h=0,g=0;if(t=e.event.fix(i),t.type="mousewheel","detail"in i&&(c=-1*i.detail),"wheelDelta"in i&&(c=i.wheelDelta),"wheelDeltaY"in i&&(c=i.wheelDeltaY),"wheelDeltaX"in i&&(f=-1*i.wheelDeltaX),"axis"in i&&i.axis===i.HORIZONTAL_AXIS&&(f=-1*c,c=0),u=0===c?f:c,"deltaY"in i&&(c=-1*i.deltaY,u=c),"deltaX"in i&&(f=i.deltaX,0===c&&(u=-1*f)),0!==c||0!==f){if(1===i.deltaMode){var p=e.data(this,"mousewheel-line-height");u*=p,c*=p,f*=p}else if(2===i.deltaMode){var y=e.data(this,"mousewheel-page-height");u*=y,c*=y,f*=y}if(m=Math.max(Math.abs(c),Math.abs(f)),(!o||o>m)&&(o=m,n(i,m)&&(o/=40)),n(i,m)&&(u/=40,f/=40,c/=40),u=Math[u>=1?"floor":"ceil"](u/o),f=Math[f>=1?"floor":"ceil"](f/o),c=Math[c>=1?"floor":"ceil"](c/o),l.settings.normalizeOffset&&this.getBoundingClientRect){var v=this.getBoundingClientRect();h=t.clientX-v.left,g=t.clientY-v.top}return t.deltaX=f,t.deltaY=c,t.deltaFactor=o,t.offsetX=h,t.offsetY=g,t.deltaMode=0,s.unshift(t,u,f,c),r&&clearTimeout(r),r=setTimeout(a,200),(e.event.dispatch||e.event.handle).apply(this,s)}}function a(){o=null}function n(e,t){return l.settings.adjustOldDeltas&&"mousewheel"===e.type&&t%120===0}var r,o,i=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],s="onwheel"in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],d=Array.prototype.slice;if(e.event.fixHooks)for(var u=i.length;u;)e.event.fixHooks[i[--u]]=e.event.mouseHooks;var l=e.event.special.mousewheel={version:"3.1.12",setup:function(){if(this.addEventListener)for(var a=s.length;a;)this.addEventListener(s[--a],t,!1);else this.onmousewheel=t;e.data(this,"mousewheel-line-height",l.getLineHeight(this)),e.data(this,"mousewheel-page-height",l.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var a=s.length;a;)this.removeEventListener(s[--a],t,!1);else this.onmousewheel=null;e.removeData(this,"mousewheel-line-height"),e.removeData(this,"mousewheel-page-height")},getLineHeight:function(t){var a=e(t),n=a["offsetParent"in e.fn?"offsetParent":"parent"]();return n.length||(n=e("body")),parseInt(n.css("fontSize"),10)||parseInt(a.css("fontSize"),10)||16},getPageHeight:function(t){return e(t).height()},settings:{adjustOldDeltas:!0,normalizeOffset:!0}};e.fn.extend({mousewheel:function(e){return e?this.bind("mousewheel",e):this.trigger("mousewheel")},unmousewheel:function(e){return this.unbind("mousewheel",e)}})});
</script>

<style>
    .xdsoft_datetimepicker{box-shadow:0 5px 15px -5px rgba(0,0,0,0.506);background:#fff;border-bottom:1px solid #bbb;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc;color:#333;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;padding:8px;padding-left:0;padding-top:2px;position:absolute;z-index:9999;-moz-box-sizing:border-box;box-sizing:border-box;display:none}.xdsoft_datetimepicker.xdsoft_rtl{padding:8px 0 8px 8px}.xdsoft_datetimepicker iframe{position:absolute;left:0;top:0;width:75px;height:210px;background:transparent;border:0}.xdsoft_datetimepicker button{border:none !important}.xdsoft_noselect{-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;-o-user-select:none;user-select:none}.xdsoft_noselect::selection{background:transparent}.xdsoft_noselect::-moz-selection{background:transparent}.xdsoft_datetimepicker.xdsoft_inline{display:inline-block;position:static;box-shadow:none}.xdsoft_datetimepicker *{-moz-box-sizing:border-box;box-sizing:border-box;padding:0;margin:0}.xdsoft_datetimepicker .xdsoft_datepicker,.xdsoft_datetimepicker .xdsoft_timepicker{display:none}.xdsoft_datetimepicker .xdsoft_datepicker.active,.xdsoft_datetimepicker .xdsoft_timepicker.active{display:block}.xdsoft_datetimepicker .xdsoft_datepicker{width:224px;float:left;margin-left:8px}.xdsoft_datetimepicker.xdsoft_rtl .xdsoft_datepicker{float:right;margin-right:8px;margin-left:0}.xdsoft_datetimepicker.xdsoft_showweeks .xdsoft_datepicker{width:256px}.xdsoft_datetimepicker .xdsoft_timepicker{width:58px;float:left;text-align:center;margin-left:8px;margin-top:0}.xdsoft_datetimepicker.xdsoft_rtl .xdsoft_timepicker{float:right;margin-right:8px;margin-left:0}.xdsoft_datetimepicker .xdsoft_datepicker.active+.xdsoft_timepicker{margin-top:8px;margin-bottom:3px}.xdsoft_datetimepicker .xdsoft_monthpicker{position:relative;text-align:center}.xdsoft_datetimepicker .xdsoft_label i,.xdsoft_datetimepicker .xdsoft_prev,.xdsoft_datetimepicker .xdsoft_next,.xdsoft_datetimepicker .xdsoft_today_button{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAAAeCAYAAADaW7vzAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6Q0NBRjI1NjM0M0UwMTFFNDk4NkFGMzJFQkQzQjEwRUIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6Q0NBRjI1NjQ0M0UwMTFFNDk4NkFGMzJFQkQzQjEwRUIiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpDQ0FGMjU2MTQzRTAxMUU0OTg2QUYzMkVCRDNCMTBFQiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpDQ0FGMjU2MjQzRTAxMUU0OTg2QUYzMkVCRDNCMTBFQiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PoNEP54AAAIOSURBVHja7Jq9TsMwEMcxrZD4WpBYeKUCe+kTMCACHZh4BFfHO/AAIHZGFhYkBBsSEqxsLCAgXKhbXYOTxh9pfJVP+qutnZ5s/5Lz2Y5I03QhWji2GIcgAokWgfCxNvcOCCGKqiSqhUp0laHOne05vdEyGMfkdxJDVjgwDlEQgYQBgx+ULJaWSXXS6r/ER5FBVR8VfGftTKcITNs+a1XpcFoExREIDF14AVIFxgQUS+h520cdud6wNkC0UBw6BCO/HoCYwBhD8QCkQ/x1mwDyD4plh4D6DDV0TAGyo4HcawLIBBSLDkHeH0Mg2yVP3l4TQMZQDDsEOl/MgHQqhMNuE0D+oBh0CIr8MAKyazBH9WyBuKxDWgbXfjNf32TZ1KWm/Ap1oSk/R53UtQ5xTh3LUlMmT8gt6g51Q9p+SobxgJQ/qmsfZhWywGFSl0yBjCLJCMgXail3b7+rumdVJ2YRss4cN+r6qAHDkPWjPjdJCF4n9RmAD/V9A/Wp4NQassDjwlB6XBiCxcJQWmZZb8THFilfy/lfrTvLghq2TqTHrRMTKNJ0sIhdo15RT+RpyWwFdY96UZ/LdQKBGjcXpcc1AlSFEfLmouD+1knuxBDUVrvOBmoOC/rEcN7OQxKVeJTCiAdUzUJhA2Oez9QTkp72OTVcxDcXY8iKNkxGAJXmJCOQwOa6dhyXsOa6XwEGAKdeb5ET3rQdAAAAAElFTkSuQmCC)}.xdsoft_datetimepicker .xdsoft_label i{opacity:.5;background-position:-92px -19px;display:inline-block;width:9px;height:20px;vertical-align:middle}.xdsoft_datetimepicker .xdsoft_prev{float:left;background-position:-20px 0}.xdsoft_datetimepicker .xdsoft_today_button{float:left;background-position:-70px 0;margin-left:5px}.xdsoft_datetimepicker .xdsoft_next{float:right;background-position:0 0}.xdsoft_datetimepicker .xdsoft_next,.xdsoft_datetimepicker .xdsoft_prev,.xdsoft_datetimepicker .xdsoft_today_button{background-color:transparent;background-repeat:no-repeat;border:0 none;cursor:pointer;display:block;height:30px;opacity:.5;-ms-filter:"alpha(opacity=50)";outline:medium none;overflow:hidden;padding:0;position:relative;text-indent:100%;white-space:nowrap;width:20px;min-width:0}.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_prev,.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_next{float:none;background-position:-40px -15px;height:15px;width:30px;display:block;margin-left:14px;margin-top:7px}.xdsoft_datetimepicker.xdsoft_rtl .xdsoft_timepicker .xdsoft_prev,.xdsoft_datetimepicker.xdsoft_rtl .xdsoft_timepicker .xdsoft_next{float:none;margin-left:0;margin-right:14px}.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_prev{background-position:-40px 0;margin-bottom:7px;margin-top:0}.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box{height:151px;overflow:hidden;border-bottom:1px solid #ddd}.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box>div>div{background:#f5f5f5;border-top:1px solid #ddd;color:#666;font-size:12px;text-align:center;border-collapse:collapse;cursor:pointer;border-bottom-width:0;height:25px;line-height:25px}.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box>div>div:first-child{border-top-width:0}.xdsoft_datetimepicker .xdsoft_today_button:hover,.xdsoft_datetimepicker .xdsoft_next:hover,.xdsoft_datetimepicker .xdsoft_prev:hover{opacity:1;-ms-filter:"alpha(opacity=100)"}.xdsoft_datetimepicker .xdsoft_label{display:inline;position:relative;z-index:9999;margin:0;padding:5px 3px;font-size:14px;line-height:20px;font-weight:bold;background-color:#fff;float:left;width:182px;text-align:center;cursor:pointer}.xdsoft_datetimepicker .xdsoft_label:hover>span{text-decoration:underline}.xdsoft_datetimepicker .xdsoft_label:hover i{opacity:1.0}.xdsoft_datetimepicker .xdsoft_label>.xdsoft_select{border:1px solid #ccc;position:absolute;right:0;top:30px;z-index:101;display:none;background:#fff;max-height:160px;overflow-y:hidden}.xdsoft_datetimepicker .xdsoft_label>.xdsoft_select.xdsoft_monthselect{right:-7px}.xdsoft_datetimepicker .xdsoft_label>.xdsoft_select.xdsoft_yearselect{right:2px}.xdsoft_datetimepicker .xdsoft_label>.xdsoft_select>div>.xdsoft_option:hover{color:#fff;background:#ff8000}.xdsoft_datetimepicker .xdsoft_label>.xdsoft_select>div>.xdsoft_option{padding:2px 10px 2px 5px;text-decoration:none !important}.xdsoft_datetimepicker .xdsoft_label>.xdsoft_select>div>.xdsoft_option.xdsoft_current{background:#3af;box-shadow:#178fe5 0 1px 3px 0 inset;color:#fff;font-weight:700}.xdsoft_datetimepicker .xdsoft_month{width:100px;text-align:right}.xdsoft_datetimepicker .xdsoft_calendar{clear:both}.xdsoft_datetimepicker .xdsoft_year{width:48px;margin-left:5px}.xdsoft_datetimepicker .xdsoft_calendar table{border-collapse:collapse;width:100%}.xdsoft_datetimepicker .xdsoft_calendar td>div{padding-right:5px}.xdsoft_datetimepicker .xdsoft_calendar th{height:25px}.xdsoft_datetimepicker .xdsoft_calendar td,.xdsoft_datetimepicker .xdsoft_calendar th{width:14.2857142%;background:#f5f5f5;border:1px solid #ddd;color:#666;font-size:12px;text-align:right;vertical-align:middle;padding:0;border-collapse:collapse;cursor:pointer;height:25px}.xdsoft_datetimepicker.xdsoft_showweeks .xdsoft_calendar td,.xdsoft_datetimepicker.xdsoft_showweeks .xdsoft_calendar th{width:12.5%}.xdsoft_datetimepicker .xdsoft_calendar th{background:#f1f1f1}.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_today{color:#3af}.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_highlighted_default{background:#ffe9d2;box-shadow:#ffb871 0 1px 4px 0 inset;color:#000}.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_highlighted_mint{background:#c1ffc9;box-shadow:#00dd1c 0 1px 4px 0 inset;color:#000}.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_default,.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_current,.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box>div>div.xdsoft_current{background:#3af;box-shadow:#178fe5 0 1px 3px 0 inset;color:#fff;font-weight:700}.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_other_month,.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_disabled,.xdsoft_datetimepicker .xdsoft_time_box>div>div.xdsoft_disabled{opacity:.5;-ms-filter:"alpha(opacity=50)";cursor:default}.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_other_month.xdsoft_disabled{opacity:.2;-ms-filter:"alpha(opacity=20)"}.xdsoft_datetimepicker .xdsoft_calendar td:hover,.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box>div>div:hover{color:#fff !important;background:#ff8000 !important;box-shadow:none !important}.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_current.xdsoft_disabled:hover,.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box>div>div.xdsoft_current.xdsoft_disabled:hover{background:#3af !important;box-shadow:#178fe5 0 1px 3px 0 inset !important;color:#fff !important}.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_disabled:hover,.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box>div>div.xdsoft_disabled:hover{color:inherit !important;background:inherit !important;box-shadow:inherit !important}.xdsoft_datetimepicker .xdsoft_calendar th{font-weight:700;text-align:center;color:#999;cursor:default}.xdsoft_datetimepicker .xdsoft_copyright{color:#ccc !important;font-size:10px;clear:both;float:none;margin-left:8px}.xdsoft_datetimepicker .xdsoft_copyright a{color:#eee !important}.xdsoft_datetimepicker .xdsoft_copyright a:hover{color:#aaa !important}.xdsoft_time_box{position:relative;border:1px solid #ccc}.xdsoft_scrollbar>.xdsoft_scroller{background:#ccc !important;height:20px;border-radius:3px}.xdsoft_scrollbar{position:absolute;width:7px;right:0;top:0;bottom:0;cursor:pointer}.xdsoft_datetimepicker.xdsoft_rtl .xdsoft_scrollbar{left:0;right:auto}.xdsoft_scroller_box{position:relative}.xdsoft_datetimepicker.xdsoft_dark{box-shadow:0 5px 15px -5px rgba(255,255,255,0.506);background:#000;border-bottom:1px solid #444;border-left:1px solid #333;border-right:1px solid #333;border-top:1px solid #333;color:#ccc}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_timepicker .xdsoft_time_box{border-bottom:1px solid #222}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_timepicker .xdsoft_time_box>div>div{background:#0a0a0a;border-top:1px solid #222;color:#999}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_label{background-color:#000}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_label>.xdsoft_select{border:1px solid #333;background:#000}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_label>.xdsoft_select>div>.xdsoft_option:hover{color:#000;background:#007fff}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_label>.xdsoft_select>div>.xdsoft_option.xdsoft_current{background:#c50;box-shadow:#b03e00 0 1px 3px 0 inset;color:#000}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_label i,.xdsoft_datetimepicker.xdsoft_dark .xdsoft_prev,.xdsoft_datetimepicker.xdsoft_dark .xdsoft_next,.xdsoft_datetimepicker.xdsoft_dark .xdsoft_today_button{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAAAeCAYAAADaW7vzAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QUExQUUzOTA0M0UyMTFFNDlBM0FFQTJENTExRDVBODYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QUExQUUzOTE0M0UyMTFFNDlBM0FFQTJENTExRDVBODYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpBQTFBRTM4RTQzRTIxMUU0OUEzQUVBMkQ1MTFENUE4NiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpBQTFBRTM4RjQzRTIxMUU0OUEzQUVBMkQ1MTFENUE4NiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pp0VxGEAAAIASURBVHja7JrNSgMxEMebtgh+3MSLr1T1Xn2CHoSKB08+QmR8Bx9A8e7RixdB9CKCoNdexIugxFlJa7rNZneTbLIpM/CnNLsdMvNjM8l0mRCiQ9Ye61IKCAgZAUnH+mU3MMZaHYChBnJUDzWOFZdVfc5+ZFLbrWDeXPwbxIqrLLfaeS0hEBVGIRQCEiZoHQwtlGSByCCdYBl8g8egTTAWoKQMRBRBcZxYlhzhKegqMOageErsCHVkk3hXIFooDgHB1KkHIHVgzKB4ADJQ/A1jAFmAYhkQqA5TOBtocrKrgXwQA8gcFIuAIO8sQSA7hidvPwaQGZSaAYHOUWJABhWWw2EMIH9QagQERU4SArJXo0ZZL18uvaxejXt/Em8xjVBXmvFr1KVm/AJ10tRe2XnraNqaJvKE3KHuUbfK1E+VHB0q40/y3sdQSxY4FHWeKJCunP8UyDdqJZenT3ntVV5jIYCAh20vT7ioP8tpf6E2lfEMwERe+whV1MHjwZB7PBiCxcGQWwKZKD62lfGNnP/1poFAA60T7rF1UgcKd2id3KDeUS+oLWV8DfWAepOfq00CgQabi9zjcgJVYVD7PVzQUAUGAQkbNJTBICDhgwYTjDYD6XeW08ZKh+A4pYkzenOxXUbvZcWz7E8ykRMnIHGX1XPl+1m2vPYpL+2qdb8CDAARlKFEz/ZVkAAAAABJRU5ErkJggg==)}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_calendar td,.xdsoft_datetimepicker.xdsoft_dark .xdsoft_calendar th{background:#0a0a0a;border:1px solid #222;color:#999}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_calendar th{background:#0e0e0e}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_calendar td.xdsoft_today{color:#c50}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_calendar td.xdsoft_highlighted_default{background:#ffe9d2;box-shadow:#ffb871 0 1px 4px 0 inset;color:#000}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_calendar td.xdsoft_highlighted_mint{background:#c1ffc9;box-shadow:#00dd1c 0 1px 4px 0 inset;color:#000}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_calendar td.xdsoft_default,.xdsoft_datetimepicker.xdsoft_dark .xdsoft_calendar td.xdsoft_current,.xdsoft_datetimepicker.xdsoft_dark .xdsoft_timepicker .xdsoft_time_box>div>div.xdsoft_current{background:#c50;box-shadow:#b03e00 0 1px 3px 0 inset;color:#000}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_calendar td:hover,.xdsoft_datetimepicker.xdsoft_dark .xdsoft_timepicker .xdsoft_time_box>div>div:hover{color:#000 !important;background:#007fff !important}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_calendar th{color:#666}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_copyright{color:#333 !important}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_copyright a{color:#111 !important}.xdsoft_datetimepicker.xdsoft_dark .xdsoft_copyright a:hover{color:#555 !important}.xdsoft_dark .xdsoft_time_box{border:1px solid #333}.xdsoft_dark .xdsoft_scrollbar>.xdsoft_scroller{background:#333 !important}.xdsoft_datetimepicker .xdsoft_save_selected{display:block;border:1px solid #ddd !important;margin-top:5px;width:100%;color:#454551;font-size:13px}.xdsoft_datetimepicker .blue-gradient-button{font-family:"museo-sans","Book Antiqua",sans-serif;font-size:12px;font-weight:300;color:#82878c;height:28px;position:relative;padding:4px 17px 4px 33px;border:1px solid #d7d8da;background:-moz-linear-gradient(top,#fff 0,#f4f8fa 73%);background:-webkit-gradient(linear,left top,left bottom,color-stop(0,#fff),color-stop(73%,#f4f8fa));background:-webkit-linear-gradient(top,#fff 0,#f4f8fa 73%);background:-o-linear-gradient(top,#fff 0,#f4f8fa 73%);background:-ms-linear-gradient(top,#fff 0,#f4f8fa 73%);background:linear-gradient(to bottom,#fff 0,#f4f8fa 73%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fff',endColorstr='#f4f8fa',GradientType=0)}.xdsoft_datetimepicker .blue-gradient-button:hover,.xdsoft_datetimepicker .blue-gradient-button:focus,.xdsoft_datetimepicker .blue-gradient-button:hover span,.xdsoft_datetimepicker .blue-gradient-button:focus span{color:#454551;background:-moz-linear-gradient(top,#f4f8fa 0,#FFF 73%);background:-webkit-gradient(linear,left top,left bottom,color-stop(0,#f4f8fa),color-stop(73%,#FFF));background:-webkit-linear-gradient(top,#f4f8fa 0,#FFF 73%);background:-o-linear-gradient(top,#f4f8fa 0,#FFF 73%);background:-ms-linear-gradient(top,#f4f8fa 0,#FFF 73%);background:linear-gradient(to bottom,#f4f8fa 0,#FFF 73%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f4f8fa',endColorstr='#FFF',GradientType=0)}
</style>