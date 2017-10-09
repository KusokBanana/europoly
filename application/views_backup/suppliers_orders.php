<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li>Suppliers' Orders</li>
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
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-social-dribbble font-dark"></i>
                        <span class="caption-subject bold uppercase font-dark">Suppliers' Orders</span>
                    </div>
                    <?php if($this->access): ?>
                        <div class="actions">
                            <button class="btn btn-default load-into-truck-modal-btn">
                                + Load into Truck
                            </button>
                        </div>
                        <div class="actions">
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal_newOrder">
                                + Add Supplier Order
                            </button>
                        </div>
                    <?php endif; ?>
                    <script>

                        $(document).ready(function() {
                            var trucks = <?= json_encode($this->trucks); ?>;

                            $('body').on('click', '.load-into-truck-modal-btn', function(e) {
                                var table = $('#table_suppliers_orders');
                                if (table.attr('data-selected') !== undefined) {
                                    var ids = table.attr('data-selected');
                                } else {
                                    $('#notificationModal').modal().find('.modal-body').text('Choose at least one item!');
                                    return false;
                                }

                                $.ajax({
                                    url: '/suppliers_orders/load_into_truck',
                                    type: "GET",
                                    data: {
                                        action_id: 1,
                                        ids: ids
                                    },
                                    success: function(data) {
                                        if (data) {
                                            data = JSON.parse(data);
                                            if (data.success === 0) {
                                                $('#notificationModal').modal().find('.modal-body').text(data.message);
                                                return false;
                                            }

                                            var modal = $('#modal_order_split');
                                            modal.attr('data-type', 'truck').find('.modal-title').text('Load into truck');
                                            modal.find('#splitSubmit').text('Load');
                                            modal.find('form').attr('action', '/suppliers_orders/load_into_truck?action_id=2');
                                            var tbody = modal.find('table tbody').empty();

                                            modal.find('.select-block').empty().append('<label for="truck_id_choose">Truck</label>'+
                                                '<select name="truck_id" id="truck_id_choose" style="width: 100%" ' +
                                                'class="js-example-data-array-selected"></select>');
                                            $('#truck_id_choose').select2({data: trucks});

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
                        })

                    </script>
                </div>
                <div class="portlet-body">
                    <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"> Orders view </a>
                            </li>
                            <li>
                                <a href="#tab_1_2" data-toggle="tab"> Items view </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="tab_1_1">
                                <div class="portlet-body">
                                    <?php
                                    $commonData = [
                                        'click_url' => "javascript:;",
                                        'method' => "POST",
                                        'serverSide' => false
                                    ];

                                    $table_data = array_merge($this->ordersTable, [
                                        'ajax' => [
                                            'url' => "/suppliers_orders/dt_suppliers_orders?type=reduced"
                                        ]
                                    ], $commonData);

                                    include 'application/views/templates/table.php'
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab_1_2">
                                <div class="portlet-body">
                                    <?php
                                    $table_data = array_merge($this->itemsTable, [
                                        'ajax' => [
                                            'url' => "/suppliers_orders/dt_suppliers_orders"
                                        ]
                                    ], $commonData);

                                    include 'application/views/templates/table.php'
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE BASE CONTENT -->
</div>

<div class="modal fade" id="modal_loadintoTruck" role="dialog" aria-hidden="true"></div>
<?php
include 'application/views/modals/new_empty_supplier_order.php';
include_once 'application/views/modals/split.php';
?>
<script>
	$('#modal_loadintoTruck').on('hidden.bs.modal', function() {
		$(this).empty();
	})
</script>

