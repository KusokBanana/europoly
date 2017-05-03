<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li>Managers' Orders</li>
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
                        <span class="caption-subject bold uppercase font-dark">Managers' Orders</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"> Items view </a>
                            </li>
                            <li>
                                <a href="#tab_1_2" data-toggle="tab"> Orders view </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="tab_1_1">
                                <div class="portlet-body">
                                    <?php
                                    $buttons = [];
                                    if ($this->access['ch'])
                                        $buttons = ['<button class="btn sbold green" data-toggle="modal" 
                                                            data-target="#modal_newOrder">
                                                        Add New Order <i class="fa fa-plus"></i>
                                                    </button>'];
                                    $table_data = [
                                        'buttons' => $buttons,
                                        'table_id' => $this->tableNames[0],
                                        'ajax' => [
                                            'url' => "/managers_orders/dt_managers_orders"
                                        ],
                                        'column_names' => $this->column_names,
                                        'click_url' => "javascript:;",
                                        'method' => "POST",
                                        'selectSearch' => $this->selects,
                                        'filterSearchValues' => $this->rows,
                                        'originalColumns' => $this->originalColumns
                                    ];
                                    include 'application/views/templates/table.php'
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab_1_2">
                                <div class="portlet-body">
                                    <?php
                                    $table_data = [
                                        'buttons' => [],
                                        'table_id' => $this->tableNames[1],
                                        'ajax' => [
                                            'url' => "/managers_orders/dt_managers_orders_reduced"
                                        ],
                                        'column_names' => $this->column_names_reduced,
                                        'method' => "POST",
                                        'click_url' => "javascript:;",
                                        'originalColumns' => $this->originalColumnsReduced
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
<?php require_once 'modals/new_order.php'; ?>