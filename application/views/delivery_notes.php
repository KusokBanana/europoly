<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li>Delivery Notes</li>
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
                        <span class="caption-subject bold uppercase font-dark">Delivery Notes</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"> Notes view </a>
                            </li>
                            <li>
                                <a href="#tab_1_2" data-toggle="tab"> Items view </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="tab_1_1">
                                <div class="portlet-body">
                                    <?php
                                    $table_data = [
                                        'buttons' => [],
                                        'table_id' => $this->tableNames[1],
                                        'ajax' => [
                                            'url' => "/delivery_notes/dt_reduced"
                                        ],
                                        'column_names' => $this->column_names_reduced,
                                        'click_url' => "javascript:;",
//                                        'selectSearch' => $this->selects,
                                        'method' => "POST",
//                                        'filterSearchValues' => $this->rows,
                                        'originalColumns' => $this->originalColumns_reduced,
                                        'serverSide' => false
                                    ];
                                    include 'application/views/templates/table.php'
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab_1_2">
                                <div class="portlet-body">
                                    <?php
                                    $buttons = [];
                                    $table_data = [
                                        'buttons' => $buttons,
                                        'table_id' => $this->tableNames[0],
                                        'ajax' => [
                                            'url' => "/delivery_notes/dt"
                                        ],
                                        'column_names' => $this->column_names,
                                        'click_url' => "javascript:;",
                                        'selectSearch' => $this->selects,
                                        'method' => "POST",
                                        'filterSearchValues' => $this->rows,
                                        'originalColumns' => $this->originalColumns,
                                        'serverSide' => false
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
