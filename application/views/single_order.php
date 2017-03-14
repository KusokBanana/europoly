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
                            <span class="hidden-xs">| <?= $this->order['start_date'] ?> <span class="label label-warning" style="white-space: normal;"> <?= $this->order_status ?> </span> </span>
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
                                    <div class="col-md-7 value"><?= $this->order['start_date'] ?></div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Order Status:</div>
                                    <div class="col-md-7 value">
                                        <span class="label label-warning"><?= $this->order_status ?></span>
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
                                        <span><?= $this->order['downpayment_rate'] != null ? $this->order['downpayment_rate'] . ' %' : '' ?></span>
                                    </div>
                                </div>
                                <br/>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Manager:</div>
                                    <div class="col-md-7 value">
                                        <a href="javascript:;" id="editable-manager" class='x-editable' data-pk="<?= $this->order['order_id'] ?>" data-name="sales_manager_id" data-value="<?= $this->order['sales_manager_id'] ?>"
                                           data-url='/order/change_field' data-original-title='Enter Sales Manager'>
                                            <?= $this->sales_manager['first_name'] . ' ' . $this->sales_manager['last_name'] ?>
                                            <a href="/sales_manager?id=<?= $this->sales_manager['user_id'] ?>">
                                                <i class="glyphicon glyphicon-link"></i></a>
                                        </a>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Manager Bonus:</div>
                                    <div class="col-md-7 value"><?= $this->order['manager_bonus'] ?> €</div>
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
                                        <a href="javascript:;" id="editable-client" class='x-editable' data-pk="<?= $this->order['order_id'] ?>" data-name="client_id" data-value="<?= $this->order['client_id'] ?>"
                                           data-url='/order/change_field' data-original-title='Choose Client:'><?= $this->client['name'] ?></a>
                                        <a href="/client?id=<?= $this->order['client_id'] ?>">
                                            <i class="glyphicon glyphicon-link"></i></a>
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
                                        <a href="javascript:;" id="editable-commission_agent" class='x-editable' data-pk="<?= $this->order['order_id'] ?>" data-name="commission_agent_id" data-value="<?= $this->order['commission_agent_id'] ?>"
                                           data-url='/order/change_field' data-original-title='Enter Commission Agent'>
                                            <?= $this->commission_agent['name'] != null ? $this->commission_agent['name'] . '<a href="/order/delete_commission_agent?order_id=' . $this->order["order_id"] . '"><span class="glyphicon glyphicon-trash"></span></a>' : '' ?>
                                        </a>
                                        <a href="/client?id=<?= $this->order['commission_agent_id'] ?>">
                                            <i class="glyphicon glyphicon-link"></i></a>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Total Commission:</div>
                                    <div class="col-md-7 value">
                                        <span> <?= $this->order['total_commission'] . ' &euro;' ?> </span>
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
                                    <a href="javascript:;" class="btn btn-default btn-sm"
                                       data-toggle="modal" data-target="#modal_newOrderItem">
                                        <i class="fa fa-plus"></i> Add new </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="table_order_items" class="table table-hover table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <?php
                                            $column_name_ids = [];
                                            if (!empty($this->column_names)) {
                                                foreach ($this->column_names as $key => $column_name) {
                                                    if (!$key)
                                                        $column_name = '';
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
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="portlet blue-hoki box">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i> Payments </div>
                                <div class="actions">
                                    <form action="payment?id=new" method="POST" id="payment-form">
                                        <input type="hidden" name="Similar[category]" value="Client">
                                        <input type="hidden" name="Similar[contractor_id]"
                                               value="<?= $this->client['client_id'] ?>">
                                        <input type="hidden" name="Similar[order_id]"
                                               value="<?= $this->order['order_id'] ?>">
                                        <?php if ($this->order['legal_entity_id'] &&
                                            $this->order['legal_entity_id'] !== null): ?>
                                        <input type="hidden" name="Similar[legal_entity_id]"
                                               value="<?= $this->order['legal_entity_id'] ?>">
                                        <?php endif; ?>
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
                                            <th>Sum</th>
                                            <th>Currency</th>
                                            <th>Direction</th>
                                            <th>Currency Rate</th>
                                            <th>Sum in EURO</th>
                                            <th>Purpose of Payment</th>
                                            <th>Article of Expense</th>
                                            <th>Category of Expense</th>
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
                                <div class="col-md-3 value"> <?= $this->order['downpayment_rate'] != null ? $this->order['downpayment_rate'] . ' %' : '' ?> </div>
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
?>

<script>
    $(document).ready(function () {
        var managers = <?= $this->toJsList($this->managers, "user_id") ?>;
        var commission_agents = <?= $this->toJsList($this->commission_agents, "client_id") ?>;
        var clients = <?= $this->toJsList($this->clients, "client_id") ?>;
        var item_statuses = <?= json_encode($this->statusList); ?>;
        var $column_name_ids = <?= json_encode($column_name_ids); ?>;
        var legalEntities = <?= json_encode($this->legalEntities); ?>;
        <?php if ($this->order['downpayment_rate'] == 100): ?>
        item_statuses[6] = undefined;
        <?php endif; ?>
        var $table_order_items = $("#table_order_items");
        var table_order_items = $table_order_items.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/order/dt_order_items',
                data: {
                    'order_id': <?= $this->order["order_id"] ?>
                }
            },
            dom: '<t>ip',
            columnDefs: [
                {
                    targets: [0],
                    searchable: false,
                    className: 'dt-body-center select-checkbox',
                    render: function (data, type, full, meta) {
                        return '';
                    }
                },
                {
                    targets: $column_name_ids,
                    searchable: false,
                    orderable: false
                }
            ],
            select: {
                style: 'os',
                selector: 'td:first-child'
            }
        });
        $table_order_items.on('draw.dt', function () {
            $('.table-confirm-btn').confirmation({
                rootSelector: '.table-confirm-btn'
            });
            $('.x-amount, .x-number_of_packs, .x-manager_bonus_rate, .x-manager_bonus').editable({
                type: "number",
                min: 0,
                step: 0.01,
                inputclass: 'form-control input-medium',
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
                var selectedRows = table_order_items.rows('.selected').data(),
                    ids = [];
                $.each(selectedRows, function() {
                    ids.push(this[0]);
                });
                ids = ids.join();
                var docsBtns = $('div[data-id="docs"]').find('.list-items').find('.print-btn');
                const delimiter = '&items=';
                $.each(docsBtns, function() {
                    var href = $(this).attr('href');
                    var hrefArray = href.split(delimiter);
                    hrefArray[1] = delimiter + ids;
                    console.log(href, hrefArray);
                    $(this).attr('href', hrefArray.join(''));
                })
            });
        });

        $table_order_items.find('tbody').on('click', 'tr td:not(:first-child)', function (e) {
            var target = e.target;
            var link = $(target).find('a').not('.table-confirm-btn, .x-editable, .reserve-product-btn');
            if (link.length) {
                window.location.href = link.attr('href');
            }
        });

        <?php if ($_SESSION["perm"] >= OPERATING_MANAGER_PERM): ?>
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


        var $table_payments = $("#table-payments");
        var tablePay = $table_payments.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/accountant/dt_order_payments',
                data: {
                    'order_id': <?= $this->order["order_id"] ?>,
                    'type': 'Client'
                }
            },
            dom: '<t>ip',
            columnDefs: [{
                targets: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
                searchable: false,
                orderable: false
            }, {
                targets: [0],
                visible: false,
                searchable: false
            }]
        });
        tablePay.on('draw', function() {
            // TODO remove it
//            var contractors = $('table').find('.change-me-contractor');
//            $.each(contractors, function() {
//                if ($(this).attr('data-type') && $(this).text()) {
//                    var string = $(this).attr('data-type') + '.' + $(this).text();
//                    var that = $(this);
//                    $.ajax({
//                        url: '/order/change_contractor_id_to_name',
//                        data: {
//                            tableAndId: string
//                        },
//                        type: "GET",
//                        success: function(data) {
//                            if (data) {
//                                that.text(data);
//                            }
//                        }
//                    })
//                }
//            })
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
?>
