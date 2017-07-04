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
                                    <span class="caption-subject bold uppercase"> Clients</span>
                                </div>
                            </div>
                            <div class="tabbable-line tabbable-custom-profile">
                                <ul class="nav nav-tabs filter-tabs">
                                    <li class="active">
                                        <a data-toggle="tab"
                                           class="tab-filter tab-filter-filter-first"
                                           data-filter-value="<?= CLIENT_TYPE_END_CUSTOMER ?>"
                                           data-filter-name="type"> End-Customers </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab"
                                           class="tab-filter"
                                           data-filter-value="<?= CLIENT_TYPE_COMISSION_AGENT ?>"
                                           data-filter-name="type"> Commission Agents </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab"
                                           class="tab-filter"
                                           data-filter-value="<?= CLIENT_TYPE_DEALER ?>"
                                           data-filter-name="type"> Dealers </a>
                                    </li>
                                </ul>
                                <div class="tab-content" style="padding: 10px;">
                                    <div class="tab-pane active">
                                        <div class="portlet-body">
                                            <?php
                                            $buttons = [
                                                '<a class="btn sbold green" id="createClientBtn"
                                                        href="client?id=new&category=">
                                                            Add Client <i class="fa fa-plus"></i>
                                                    </a>'
                                            ];
                                            if (!$this->access)
                                                $buttons = [];
                                            $table_data = [
                                                'buttons' => $buttons,
                                                'table_id' => "table_clients",
                                                'ajax' => [
                                                    'url' => "/clients/dt_all_clients"
                                                ],
                                                'column_names' => $this->column_names,
                                                'click_url' => "client?id=",
                                                'originalColumns' => $this->originalColumns,
                                                'selectSearch' => $this->selects,
                                                'filterSearchValues' => $this->rows,
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

<script>
    var managers = <?= $this->toJsList($this->managers, "user_id") ?>;
    var commission_agents = <?= $this->toJsList($this->commission_agents, "client_id") ?>;
    var $table_clients = $("#table_clients");
    <?php if ($this->access): ?>
        $table_clients.on('draw.dt', function () {
            $('.x-type').editable({
                type: "select",
                inputclass: 'form-control input-medium',
                source: [
                    {"value": "<?= CLIENT_TYPE_END_CUSTOMER ?>", "text": "<?= CLIENT_TYPE_END_CUSTOMER ?>"},
                    {"value": "<?= CLIENT_TYPE_COMISSION_AGENT ?>",
                        "text": "<?= CLIENT_TYPE_COMISSION_AGENT ?>"}, // TODO commission or comission ?
                    {"value": "<?= CLIENT_TYPE_DEALER ?>", "text": "<?= CLIENT_TYPE_DEALER ?>"}
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
    <?php endif; ?>

    $('.tab-filter').on('click', function() {

        var createBtn = $('#createClientBtn');
        if (createBtn.length) {
            var href = createBtn.attr('href');
            var explodedHref = href.split('category=');
            explodedHref[1] = $(this).attr('data-filter-value');
            href = explodedHref.join('category=');
            createBtn.attr('href', href);
        }

    });

</script>
