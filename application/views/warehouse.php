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
                        <li>
                            <a href="#tab_1_3" data-toggle="tab"> Reserved </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1_1">
                            <div class="portlet-body">
                                <?php
                                $buttons = ($this->id) ? ['<button class="btn sbold green" data-toggle="modal" data-target="#modal_newProductWarehouse">Add New <i class="fa fa-plus"></i></button>'] : [];
                                if (!$this->access['ch'])
                                    $buttons = [];
                                $urlId = ($this->id) ? $this->warehouse['warehouse_id'] : 0;
                                $table_data = [
                                    'buttons' => $buttons,
                                    'table_id' => "table_warehouses_products",
                                    'ajax' => [
                                        'url' => "/warehouse/dt?warehouse_id=$urlId",
                                    ],
                                    'column_names' => $this->column_names,
                                    'hidden_by_default' => "[]",
                                    'click_url' => "javascript:;",
                                    'selectSearch' => $this->selects,
                                    'filterSearchValues' => $this->rows,
                                    'method' => 'POST',
                                    'originalColumns' => $this->originalColumns
                                ];
                                include 'application/views/templates/table.php'
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_1_2">
                            <div class="portlet-body">
                                <div class="portlet-body">
                                    <?php
                                    $table_data = [
                                        'buttons' => $buttons,
                                        'table_id' => "table_warehouses_products_issue",
                                        'ajax' => [
                                            'url' => "/warehouse/dt?warehouse_id=$urlId&type=issue",
                                        ],
                                        'column_names' => $this->column_names,
                                        'hidden_by_default' => "[]",
                                        'click_url' => "javascript:;",
                                        'selectSearch' => $this->selects,
                                        'filterSearchValues' => $this->rows,
                                        'method' => 'POST',
                                        'originalColumns' => $this->originalColumns
                                    ];
                                    include 'application/views/templates/table.php'
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_1_3">
                            <div class="portlet-body">
                                <div class="portlet-body">
                                    <?php
                                    $table_data = [
                                        'buttons' => $buttons,
                                        'table_id' => "table_warehouses_products_reserved",
                                        'ajax' => [
                                            'url' => "/warehouse/dt?warehouse_id=$urlId&type=reserve",
                                        ],
                                        'column_names' => $this->column_names,
                                        'hidden_by_default' => "[]",
                                        'click_url' => "javascript:;",
                                        'selectSearch' => $this->selects,
                                        'filterSearchValues' => $this->rows,
                                        'method' => 'POST',
                                        'originalColumns' => $this->originalColumns
                                    ];
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
if ($this->id != 0) {
    require_once 'modals/new_product_warehouse.php';
}
?>
<script>
    $(document).ready(function () {
        var $tables_warehouses = $('#table_warehouses_products');
        var warehouses = <?= json_encode($this->warehouses); ?>;
        $tables_warehouses.on('draw.dt', function () {
            $('.x-warehouse_id').editable({
                type: "select",
                inputclass: 'form-control input-medium',
                source: warehouses
            });
        });
        <?php if (isset($_GET['documentPath']) && $_GET['documentPath']): ?>
            var path = '<?= $_GET['documentPath']; ?>';
            location.href = path;
            var re = new RegExp('(\\?|&)documentPath=[^&]+','g');
            var url = location.href.replace(re,'');
            history.pushState({}, '', url);
        <?php endif; ?>
    });
</script>
