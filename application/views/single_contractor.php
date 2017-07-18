<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li>Contractor</li>
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
                    <div class="col-md-3">
                        <div class="dashboard-stat2 bordered">
                            <div class="display">
                                <div class="number">
                                    <h3 class="font-blue-sharp">
                                        <span data-counter="counterup" data-value="135"><?= $this->balance['money'] ?> €</span>
                                    </h3>
                                    <small>Money Turnover</small>
                                </div>
                                <div class="icon">
                                    <i class="icon-pie-chart"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="dashboard-stat2 bordered">
                            <div class="display">
                                <div class="number">
                                    <h3 class="font-blue-sharp">
                                        <span data-counter="counterup" data-value="59"><?= $this->balance['goods'] ?> €</span>
                                    </h3>
                                    <small>Goods Turnover</small>
                                </div>
                                <div class="icon">
                                    <i class="icon-pie-chart"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="dashboard-stat2 bordered">
                            <div class="display">
                                <div class="number">
                                    <h3 class="font-blue-sharp">
                                        <span data-counter="counterup" data-value="59"><?= $this->balance['services'] ?> €</span>
                                    </h3>
                                    <small>Services Turnover</small>
                                </div>
                                <div class="icon">
                                    <i class="icon-pie-chart"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="dashboard-stat2 bordered">
                            <div class="display">
                                <div class="number">
                                    <h3 class="<?= ($this->balance['diff'] >= 0) ? 'font-green' : 'font-red' ?>">
                                        <span data-counter="counterup" data-value="32"><?= $this->balance['diff'] ?> €</span>
                                    </h3>
                                    <small>Balance</small>
                                </div>
                                <div class="icon">
                                    <i class="icon-pie-chart"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption font-dark">
                                    <i class="icon-settings font-dark"></i>
                                    <span class="caption-subject bold uppercase"> <?= $this->title ?> </span>
                                </div>
                            </div>
                            <div class="tabbable-line tabbable-custom-profile">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab"> Money Movement </a>
                                    </li>
                                    <li>
                                        <a href="#tab_2" data-toggle="tab"> Goods Movement </a>
                                    </li>
                                    <li>
                                        <a href="#tab_3" data-toggle="tab"> Provision of Services </a>
                                    </li>
                                </ul>
                                <div class="tab-content" style="padding: 10px;">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="portlet-body">
                                            <?php
                                            $commonData = [
                                                'click_url' => "javascript:;",
                                                'method' => "POST",
                                                'serverSide' => false
                                            ];

                                            $table_data = array_merge($this->generalTable, [
                                                'ajax' => [
                                                    'url' => "/accountant/dt_payments",
                                                    'data' => [
                                                        'contractor_id' => $this->contractor["contractor_id"],
                                                        'contractor_type' => $this->contractor["contractor_type"]
                                                    ]
                                                ]
                                            ], $commonData);

                                            include 'application/views/templates/table.php'
                                            ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_2">
                                        <div class="portlet-body">
                                            <?php
                                            if ($this->isGoods) {
                                                $commonData = [
                                                    'click_url' => "javascript:;",
                                                    'method' => "POST",
                                                    'serverSide' => false
                                                ];

                                                $table_data = array_merge($this->tableGoods, [
                                                    'ajax' => [
                                                        'url' => "/contractor/dt_contractor_goods",
                                                        'data' => [
                                                            'contractor_id' => $this->contractor["contractor_id"],
                                                            'contractor_type' => $this->contractor["contractor_type"]
                                                        ]
                                                    ]
                                                ], $commonData);
                                                include 'application/views/templates/table.php';
                                            }

                                            ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_3">
                                        <div class="portlet-body">
                                            <?php
                                            if ($this->isServices) {
                                                $buttons = [
                                                    '<a href="#new_other" class="btn btn-primary" '.
                                                    'data-toggle="modal">Add new</a>'
                                                ];

                                                $commonData = [
                                                    'click_url' => "javascript:;",
                                                    'method' => "POST",
                                                    'serverSide' => false
                                                ];

                                                $table_data = array_merge($this->tableServices, [
                                                    'buttons' => $buttons,
                                                    'ajax' => [
                                                        'url' => "/contractor/dt_contractor_services",
                                                        'data' => [
                                                            'contractor_id' => $this->contractor["contractor_id"],
                                                            'contractor_type' => $this->contractor["contractor_type"]
                                                        ]
                                                    ]
                                                ], $commonData);

                                                include 'application/views/templates/table.php';
                                            }
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

<div id="new_other" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Заголовок модального окна -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">New Services Item</h4>
            </div>
            <!-- Основное содержимое модального окна -->
            <div class="modal-body">
                <form action="/contractor/new_service" id="newOtherForm" method="post">

                    <label class="control-label" for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                    <label class="control-label" for="sum">Sum</label>
                    <input type="text" name="sum" id="sum" class="form-control" required>
                    <label class="control-label" for="currency">Currency</label>
                    <select name="currency" id="currency" class="form-control" required>
                        <option> </option>
                        <?php $currencies = ['USD', 'EUR', 'РУБ', 'GBP', 'SEK', 'AED'] ?>
                        <?php foreach ($currencies as $currency): ?>
                            <option value="<?= $currency ?>">
                                <?= $currency ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label class="control-label" for="direction">Direction</label>
                    <select name="direction" id="direction" class="form-control" required>
                        <option> </option>
                        <?php $directions = ['Income', 'Expense'] ?>
                        <?php foreach ($directions as $direction): ?>
                            <option value="<?= $direction ?>">
                                <?= $direction ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="contractor_id" value="<?= $this->contractor["contractor_id"] ?>">
                    <input type="hidden" name="contractor_type" value="<?= $this->contractor_type ?>">

                </form>
            </div>
            <!-- Футер модального окна -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" form="newOtherForm" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>