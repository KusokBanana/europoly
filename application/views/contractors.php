<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li>Contractors</li>
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
<div class="page-fixed-main-content" <?= $this->isSidebarClosed() ? 'style="margin-left:0"' : '' ?>>
    <!-- BEGIN PAGE BASE CONTENT -->

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
                                    <span class="caption-subject bold uppercase"> Contractors</span>
                                </div>
                            </div>
                            <div class="tabbable-line tabbable-custom-profile">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab"> Clients </a>
                                    </li>
                                    <li>
                                        <a href="#tab_2" data-toggle="tab"> Suppliers </a>
                                    </li>
                                    <li>
                                        <a href="#tab_3" data-toggle="tab"> Customs </a>
                                    </li>
                                    <li>
                                        <a href="#tab_4" data-toggle="tab"> Transportation Companies </a>
                                    </li>
                                    <li>
                                        <a href="#tab_5" data-toggle="tab"> Other </a>
                                    </li>
                                </ul>
                                <div class="tab-content" style="padding: 10px;">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="portlet-body">
                                            <?php
                                            $table_data = [
                                                'buttons' => [
                                                    '<a class="btn sbold green" 
                                                        href="client?id=new">
                                                            Add Client <i class="fa fa-plus"></i>
                                                    </a>'
                                                ],
                                                'table_id' => "table_clients",
                                                'ajax' => [
                                                    'url' => "/clients/dt_clients"
                                                ],
                                                'column_names' => $this->clients_column_names,
                                                'click_url' => "client?id=",
                                                'originalColumns' => $this->originalColumns,
                                                'selectSearch' => $this->selects,
                                                'filterSearchValues' => $this->rows,
                                            ];
                                            include 'application/views/templates/table.php'
                                            ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_2">
                                        <div class="portlet-body">
                                            <?php
                                            $table_data = [
                                                'buttons' => [
                                '<button class="btn sbold green" data-toggle="modal" 
                                data-type="Supplier" data-href-add="suppliers"
                                data-target="#modal_newContractor">Add Supplier <i class="fa fa-plus"></i></button>'
                                                ],
                                                'table_id' => "table_suppliers",
                                                'ajax' => [
                                                    'url' => "/contractors/dt_suppliers"
                                                ],
                                                'column_names' => $this->column_names,
                                                'hidden_by_default' => "[]",
                                                'click_url' => "javascript:;"
                                            ];
                                            include 'application/views/templates/table.php'
                                            ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_3">
                                        <div class="portlet-body">
                                            <?php
                                            $table_data = [
                                                'buttons' => [
                                '<button class="btn sbold green" data-toggle="modal" 
                                data-target="#modal_newContractor" data-href-add="customs"
                                data-type="Custom">Add Custom <i class="fa fa-plus"></i></button>'
                                                ],
                                                'table_id' => "table_customs",
                                                'ajax' => [
                                                    'url' => "/contractors/dt_customs"
                                                ],
                                                'column_names' => $this->column_names,
                                                'hidden_by_default' => "[]",
                                                'click_url' => "javascript:;"
                                            ];
                                            include 'application/views/templates/table.php'
                                            ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_4">
                                        <div class="portlet-body">
                                            <?php
                                            $table_data = [
                                                'buttons' => [
                        '<button class="btn sbold green" data-toggle="modal" 
                        data-target="#modal_newContractor" data-href-add="transportation_companies"
                        data-type="Transportations Company">
                            Add Transportations Company <i class="fa fa-plus"></i>
                        </button>'
                                                ],
                                                'table_id' => "table_transportation_companies",
                                                'ajax' => [
                                                    'url' => "/contractors/dt_transportation"
                                                ],
                                                'column_names' => $this->column_names,
                                                'hidden_by_default' => "[]",
                                                'click_url' => "javascript:;"
                                            ];
                                            include 'application/views/templates/table.php'
                                            ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_5">
                                        <div class="portlet-body">
                                            <?php
                                            $table_data = [
                                                'buttons' => [
                                '<button class="btn sbold green" data-toggle="modal" 
                                data-target="#modal_newContractor" data-href-add="other"
                                data-type="Other">Add Other <i class="fa fa-plus"></i></button>'
                                                ],
                                                'table_id' => "table_other",
                                                'ajax' => [
                                                    'url' => "/contractors/dt_other"
                                                ],
                                                'column_names' => $this->column_names,
                                                'hidden_by_default' => "[]",
                                                'click_url' => "javascript:;"
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

<?php
require_once 'modals/new_contractor.php';
?>
<script>
    $(document).ready(function() {
        $('body').on('click', 'button[data-href-add]', function() {
            var modal = $($(this).attr('data-target')),
                type = $(this).attr('data-type'),
                addHref = $(this).attr('data-href-add');
            var href = modal.find('form').attr('action');
            var array = href.split('type=');
            href = array[0] + 'type=' + addHref;
            modal.find('form').attr('action', href);
            modal.find('form').find('.target-name').text(type);
        })
    })
</script>

<script>
    var managers = <?= $this->toJsList($this->managers, "user_id") ?>;
    var commission_agents = <?= $this->toJsList($this->commission_agents, "client_id") ?>;

    var $tables_clients = $("#table_clients");
    $tables_clients.on('draw.dt', function () {
        $('.x-type').editable({
            type: "select",
            inputclass: 'form-control input-medium',
            source: [
                {"value": "End Customer", "text": "End Customer"},
                {"value": "Comission Agent", "text": "Comission Agent"}, // TODO Commission or Comission
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
