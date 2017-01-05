<div class="container-fluid">
    <div class="page-content page-content-popup">
        <div class="page-content-fixed-header">
            <!-- BEGIN BREADCRUMBS -->
            <ul class="page-breadcrumb">
                <li>
                    <a href="/">Dashboard</a>
                </li>
                <li>Clients</li>
            </ul>
            <!-- END BREADCRUMBS -->


            <div class="content-header-menu">
                <div class="page-toolbar">
                    <div style="margin:10px" id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height blue" data-placement="top" data-original-title="Change dashboard date range">
                        <i class="icon-calendar"></i>&nbsp;
                        <span class="thin uppercase hidden-xs"></span>&nbsp;
                        <i class="fa fa-angle-down"></i>
                    </div>
                </div>

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
        <div class="page-fixed-main-content">
            <!-- BEGIN PAGE BASE CONTENT -->
            <div class="row">
                <div class="col-md-4">
                    <div class="dashboard-stat2 bordered">
                        <div class="display">
                            <div class="number">
                                <h3 class="font-green-sharp">
                                    <span data-counter="counterup" data-value="135">0</span>
                                </h3>
                                <small>CUSTOMERS</small>
                            </div>
                            <div class="icon">
                                <i class="icon-pie-chart"></i>
                            </div>
                        </div>
                        <div class="progress-info">
                            <div class="progress">
                                        <span style="width: 76%;" class="progress-bar progress-bar-success green-sharp">
                                            <span class="sr-only">76% progress</span>
                                        </span>
                            </div>
                            <div class="status">
                                <div class="status-title"> progress</div>
                                <div class="status-number"> 76%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="dashboard-stat2 bordered">
                        <div class="display">
                            <div class="number">
                                <h3 class="font-blue-sharp">
                                    <span data-counter="counterup" data-value="59">0</span>
                                </h3>
                                <small>COMISSION AGENTS</small>
                            </div>
                            <div class="icon">
                                <i class="icon-like"></i>
                            </div>
                        </div>
                        <div class="progress-info">
                            <div class="progress">
                                        <span style="width: 45%;" class="progress-bar progress-bar-success blue-sharp">
                                            <span class="sr-only">45% grow</span>
                                        </span>
                            </div>
                            <div class="status">
                                <div class="status-title"> grow</div>
                                <div class="status-number"> 45%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="dashboard-stat2 bordered">
                        <div class="display">
                            <div class="number">
                                <h3 class="font-red">
                                    <span data-counter="counterup" data-value="32">0</span>
                                </h3>
                                <small>Dealers</small>
                            </div>
                            <div class="icon">
                                <i class="icon-user"></i>
                            </div>
                        </div>
                        <div class="progress-info">
                            <div class="progress">
                                        <span style="width: 57%;" class="progress-bar progress-bar-success red">
                                            <span class="sr-only">56% change</span>
                                        </span>
                            </div>
                            <div class="status">
                                <div class="status-title"> change</div>
                                <div class="status-number"> 57%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN TICKET LIST CONTENT -->
                    <div class="app-ticket app-ticket-list">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-dark">
                                            <i class="icon-settings font-dark"></i>
                                            <span class="caption-subject bold uppercase"> Clients</span>
                                        </div>
                                    </div>
                                    <div class="tabbable-line tabbable-custom-profile">
                                        <ul class="nav nav-tabs">
                                            <li class="active">
                                                <a href="#tab_1_11" data-toggle="tab"> End-Customers </a>
                                            </li>
                                            <li>
                                                <a href="#tab_1_22" data-toggle="tab"> Commission Agents </a>
                                            </li>
                                            <li>
                                                <a href="#tab_1_33" data-toggle="tab"> Dealers </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" style="padding: 10px;">
                                            <div class="tab-pane active" id="tab_1_11">
                                                <div class="portlet-body">
                                                    <?php
                                                    $table_data = [
                                                        'buttons' => [
                                                            '<a class="btn sbold green" 
                                                                href="client?id=new">
                                                                    Add Client <i class="fa fa-plus"></i>
                                                            </a>'
                                                        ],
                                                        'table_id' => "table_end_customers",
                                                        'ajax' => [
                                                            'url' => "/clients/dt_end_customers"
                                                        ],
                                                        'column_names' => $this->column_names,
                                                        'hidden_by_default' => "[]",
                                                        'click_url' => "client?id="
                                                    ];
                                                    include 'application/views/templates/table.php'
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_1_22">
                                                <div class="portlet-body">
                                                    <?php
                                                    $table_data = [
                                                        'buttons' => [
                                                            '<button class="btn sbold green" data-toggle="modal" data-target="#modal_newClient">Add Client <i class="fa fa-plus"></i></button>'
                                                        ],
                                                        'table_id' => "table_commission_agents",
                                                        'ajax' => [
                                                            'url' => "/clients/dt_commission_agents"
                                                        ],
                                                        'column_names' => $this->column_names,
                                                        'hidden_by_default' => "[]",
                                                        'click_url' => "client?id="
                                                    ];
                                                    include 'application/views/templates/table.php'
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_1_33">
                                                <div class="portlet-body">
                                                    <?php
                                                    $table_data = [
                                                        'buttons' => [
                                                            '<button class="btn sbold green" data-toggle="modal" data-target="#modal_newClient">Add Client <i class="fa fa-plus"></i></button>'
                                                        ],
                                                        'table_id' => "table_dealers",
                                                        'ajax' => [
                                                            'url' => "/clients/dt_dealers"
                                                        ],
                                                        'column_names' => $this->column_names,
                                                        'hidden_by_default' => "[]",                                                                                                 'click_url' => "client?id="
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
                    <!-- END PROFILE CONTENT -->
                </div>
            </div>
            <!-- END PAGE BASE CONTENT -->
        </div>

        <!-- BEGIN FOOTER -->
        <p class="copyright-v2">2016 © Europoly.
        </p>
        <a href="#index" class="go2top">
            <i class="icon-arrow-up"></i>
        </a>
        <!-- END FOOTER -->
    </div>
</div>

<script>
    var managers = <?= $this->toJsList($this->managers, "user_id") ?>;
    var commission_agents = <?= $this->toJsList($this->commission_agents, "client_id") ?>;

    var $tables_clients = $("#table_end_customers, #table_commission_agents, #table_dealers");
    $tables_clients.on('draw.dt', function () {
        $('.x-type').editable({
            type: "select",
            inputclass: 'form-control input-medium',
            source: [
                {"value": "End Customer", "text": "End Customer"},
                {"value": "Commission Agent", "text": "Commission Agent"},
                {"value": "Dealer", "text": "Dealer"}
            ],
            success: function () {
                location.reload();
            }
        });
        $('.x-sales_manager').editable({
            type: "select",
            inputclass: 'form-control input-medium',
            source: managers
        });
        $('.x-commission_agent').editable({
            type: "select",
            inputclass: 'form-control input-medium',
            source: commission_agents
        });
    });
</script>
