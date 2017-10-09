<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li>Warehouse</li>
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
                    <div class="caption font-dark">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject bold uppercase"> Warehouse: <?= $this->title ?> </span>
                    </div>
                </div>
                <div class="tabbable-line tabbable-custom-profile">
                    <ul class="nav nav-tabs" style="padding:10px">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab"> Available </a>
                        </li>
                        <li>
                            <a href="#tab_1_2" data-toggle="tab"> Expects Issue </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1_1">
                            <div class="portlet-body">
                                <?php
                                $buttons[] = '<button class="btn sbold green" data-toggle="modal" 
                                    data-target="#modal_newProductWarehouse">Add New <i class="fa fa-plus"></i></button>';
                                $buttons[] =
                                    '<a class="btn dark btn-outline sbold disable assemble-btn" 
                                        data-toggle="modal" 
                                        href="#assemble-set"> Assemble Set </a>';
                                if (!$this->access['ch'])
                                    $buttons = [];
                                if ($this->access['d'])
                                    $buttons[] = '<button data-link="/warehouse/discard_products" 
                                                        class="btn sbold red discard-products-btn"
                                                        data-sel=".tab-pane.active table">
                                                            Discard Goods <i class="fa fa-minus"></i>
                                                   </button>';
                                $commonData = [
                                    'click_url' => "javascript:;",
                                    'method' => "POST",
                                    'serverSide' => false
                                ];
                                $urlId = ($this->id) ? $this->warehouse['warehouse_id'] : 0;

                                $table_data = array_merge($this->generalTable, [
                                    'buttons' => $buttons,
                                    'ajax' => [
                                        'url' => "/warehouse/dt?warehouse_id=$urlId&type=".
                                            $this->generalTable['table_name']
                                    ]
                                ], $commonData);

                                include 'application/views/templates/table.php'
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_1_2">
                            <div class="portlet-body">
                                <div class="portlet-body">
                                    <?php
//                                    if ($this->access['ch'])
//                                        $buttons[] =
//                                            '<button data-link="/warehouse/issue_products"
//                                                        class="btn sbold green issue-products-btn"
//                                                        data-sel="#table_warehouses_products_issue">Issue</button>';
                                    $buttons = [];

                                    $table_data = array_merge($this->expectsIssueTable, [
                                        'buttons' => $buttons,
                                        'ajax' => [
                                            'url' => "/warehouse/dt?warehouse_id=$urlId&type=".
                                                $this->expectsIssueTable['table_name']
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

<?php
if ($this->access['ch']) {
    require_once 'modals/new_product_warehouse.php';
}
?>
<script>
    $(document).ready(function () {
        var $tables_warehouses = $('#<?= $this->expectsIssueTable['table_name'] ?>, ' +
            '#<?= $this->generalTable['table_name'] ?>');
        var warehouses = <?= json_encode($this->warehouses); ?>;
        $tables_warehouses.on('draw.dt', function () {
            $('.x-warehouse_id').editable({
                type: "select",
                inputclass: 'form-control input-medium',
                source: warehouses
            });
            $('.x-amount, .x-number_of_packs, .x-purchase_price').editable({
                type: "number",
                min: 0,
                step: 0.01,
                inputclass: 'form-control input-medium',
                success: function () {
//                location.reload();
                }
            });

        });
        // TODO remove it
        <?php if (isset($_GET['documentPath']) && $_GET['documentPath']): ?>
            location.href = '<?= $_GET['documentPath']; ?>';
            var re = new RegExp('(\\?|&)documentPath=[^&]+','g');
            var url = location.href.replace(re,'');
            history.pushState({}, '', url);
        <?php endif; ?>

        $('.issue-products-btn, .discard-products-btn').on('click', function() {
            var btn = $(this);
            var tableSelector = btn.attr('data-sel');
            var table = $(tableSelector).DataTable();
            var selected = table.rows('.selected').data(),
                selectedCount = selected.length;
            if (selectedCount) {
                var ids = [];
                $.each(selected, function() {
                    ids.push(this[0]);
                });
                btn.confirmation({
                    singleton: true,
                    popout: true,
                    placement: 'right',
                    onConfirm: function () {
                        window.location.href = btn.attr('data-link') + '?products=' + ids.join();
                    }
                });
            } else {
                $('#modal_warehouse_error').modal('show')/*.find('.modal-body h4').text(errorMessage)*/;
            }
        });


        $('.print-btn').addClass('has-event').on('click', function(e) {
            e.preventDefault();
            var btn = $(this);

            var $table = $('#<?= $this->generalTable['table_name'] ?>');
            var selected = $table.attr('data-selected');

            if (!selected || selected === undefined) {
                $('#notificationModal').modal().find('.modal-body').text('Choose at least one item!');
                return false;
            }

            $.ajax({
                url: btn.attr('href'),
                type: 'POST',
                data: {
                    selected: selected
                },
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

<div class="modal fade" id="modal_warehouse_error" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-danger text-center">Select one of the items!</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
<?php require_once ('modals/assemble_set.php'); ?>
