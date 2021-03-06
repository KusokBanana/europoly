<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li>Managers' Order</li>
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
                        <span class="caption-subject bold uppercase font-dark">Requests to Logist</span>
                    </div>
                    <?php if($this->access): ?>
                        <div class="actions">
                            <a class="btn btn-default modal-new-suppliers-order-btn">
                                + Add to Supplier's Order
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="portlet-body">
                    <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"> Items view </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="tab_1_1">
                                <div class="portlet-body">
                                    <script>
                                        $('body').on('click', '.modal-new-suppliers-order-btn', function() {
                                            var table = $('#sent_to_logist');
                                            var products = table.find('tr.selected .order-item-product').length ?
                                                table.find('tr.selected .order-item-product') : [];
                                            var brandStr = '';
                                            var error = '';
                                            var ids = [];
                                            if (table.attr('data-selected') !== undefined)
                                                ids = table.attr('data-selected').split(',');

                                            var brandError = false;
                                            if (products.length) {
                                                products.each(function(i, elem) {
                                                    var brand = $(elem).closest('tr').find('.brand-cell').text();
                                                    if (i>0 && brandStr !== brand) {
                                                        brandError = true;
                                                    }
                                                    brandStr = brand;
                                                });
                                            } else {
                                                $('#notificationModal').modal().find('.modal-body').text('Choose at least one item!');
                                                return false;
                                            }
                                            if (brandError)
                                                error = 'Items must have the same brands';

                                            $.ajax({
                                                type: "POST",
                                                url: "application/views/modals/new_supplier_order.php",
                                                data: {
                                                    table_data: {
                                                        column_names: <?php echo json_encode($this->itemsTable['columns_names']) ?>,
                                                        products: ids,
                                                        error: error
                                                    }
                                                },
                                                success: function(data) {
                                                    $('#modal_newSupplierOrder').append(data).modal('show');

                                                }
                                            })
                                        });
                                    </script>
                                    <?php
                                    $commonData = [
                                        'click_url' => "javascript:;",
                                        'method' => "POST",
                                        'serverSide' => false,
                                        'ajax' => [
                                            'url' => "/sent_to_logist/dt_managers_orders"
                                        ]
                                    ];
                                    $table_data = array_merge($this->itemsTable, $commonData);
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

<div class="modal fade" id="modal_newSupplierOrder" role="dialog" aria-hidden="true"></div>
<script>
	$('#modal_newSupplierOrder').on('hidden.bs.modal', function() {
		$(this).empty();
	})
</script>