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
                        <span class="caption-subject font-dark sbold uppercase"><?= $this->title ?></span>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="portlet green-meadow box">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>Delivery Note Details
                                </div>
                            </div>
                            <div class="portlet-body">

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="row static-info">
                                            <div class="col-md-5 name"> Delivery Note ID: </div>
                                            <div class="col-md-7 value">
                                                <?= $this->note['id'] ?>
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-5 name"> Legal Entity Evropoly:</div>
                                            <div class="col-md-7 value"><?= $this->legalEntityName ?></div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-5 name"> Manager Order ID:</div>
                                            <div class="col-md-7 value">
                                                <a href="/order?id=<?= $this->order['order_id'] ?>">
                                                    <?= $this->order['visible_order_id'] ?
                                                        $this->order['visible_order_id'] : $this->order['order_id'] ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="row static-info">
                                            <div class="col-md-5 name"> Customer Name:</div>
                                            <div class="col-md-7 value">
                                                <a href="/client?id=<?= $this->order['client_id'] ?>">
                                                    <?= $this->client['final_name'] ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-5 name"> Commission Agent:</div>
                                            <div class="col-md-7 value">
                                                <a href="/client?id=<?= $this->order['commission_agent_id'] ?>">
                                                    <?= $this->commission_agent['final_name'] ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-5 name"> Manager:</div>
                                            <div class="col-md-7 value">
                                                <?= $this->sales_manager['first_name'] . ' ' .
                                                $this->sales_manager['last_name'] ?>
                                            </div>
                                        </div>
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
                                    <a href="/delivery_notes/issue?note_id=<?= $this->note['id'] ?>"
                                       data-toggle="confirmation" data-title="Are you sure to delete the item?"
                                       class="table-confirm-btn btn btn-default btn-sm" data-placement="left" data-popout="true"
                                       data-singleton="true">
                                        <i class="fa fa-plus"></i> Issue </a>
                                    <a class="btn btn-default btn-sm"
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
                                            <td colspan="16" class="dataTables_empty">Loading data from server...</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
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
require_once 'modals/new_delivery_note_item_new.php';
?>

<script>
    $(document).ready(function () {
        var $column_name_ids = <?= json_encode($column_name_ids); ?>;

        var $table_order_items = $("#table_order_items");
        var table_order_items = $table_order_items.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/delivery_notes/get_dt_note',
                data: {
                    'note_id': <?= $this->note['id'] ?>
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

            $table_order_items.find('tbody').on('click', 'tr td:first-child', function (e) {
                var selectedRows = table_order_items.rows('.selected').data(),
                    ids = [];
                $.each(selectedRows, function() {
                    ids.push(this[0]);
                });
                ids = ids.join();
                $table_order_items.attr('data-selected', ids);
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

        $('.print-btn').addClass('has-event').on('click', function(e) {
            e.preventDefault();
            var btn = $(this);

            $.ajax({
                url: btn.attr('href'),
                type: 'POST',
                success: function(data) {
                    if (data) {
                        var isJson = true;
                        try {
                            var dataObject = JSON.parse(data);
                        } catch (e) {
                            isJson = false;
                        }

                        if (isJson) {
                            var success = dataObject.success;
                            if (!success) {
                                var message = dataObject.message;
                                $('#notificationModal').modal().find('.modal-body').text(message);
                                return false;
                            }
                        } else {
                            location.href = data;
                        }
                    }
                }
            })
        });

    });
</script>
<style>
    .table-responsive {
        overflow-y: hidden;
    }
</style>