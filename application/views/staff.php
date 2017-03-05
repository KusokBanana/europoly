<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li>Staff</li>
    </ul>
    <!-- END BREADCRUMBS -->
    <div class="content-header-menu">
        <div class="page-toolbar">
            <div style="margin:10px" id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height blue"
                 data-placement="top" data-original-title="Change dashboard date range">
                <i class="icon-calendar"></i>&nbsp;
                <span class="thin uppercase hidden-xs"></span>&nbsp;
                <i class="fa fa-angle-down"></i>
            </div>
        </div>
        <!-- BEGIN MENU TOGGLER -->
        <button type="button" class="menu-toggler responsive-toggler"
                data-toggle="collapse" data-target=".navbar-collapse">
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
        <div class="col-md-7">
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup" data-value="7500">0</span>
                            <small class="font-green-sharp">€</small>
                        </h3>
                        <small>TOTAL SALARY</small>
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

        <div class="col-md-5">
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-blue-sharp">
                            <span data-counter="counterup" data-value="3700"></span>
                            <small class="font-blue-sharp">€</small>
                        </h3>
                        <small>TOTAL BONUS</small>
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
    </div>
    <div class="row">
        <div class="col-md-7 col-sm-7">
            <div class="portlet light bordered">
                <div class="portlet-title" style="margin-bottom: 0;">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart font-green"></i>
                        <span class="caption-subject font-green bold uppercase">Sales Managers</span>
                    </div>
                    <div class="actions">
                        <button class="btn sbold green" data-toggle="modal" data-target="#modal_newUser">New Member <i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="portlet-body" style="padding: 0;">
                    <div class="table-scrollable table-scrollable-borderless" style="margin: 0 !important;">
                        <table id="table_managers" class="table table-hover table-light" style="overflow:hidden; margin: 0 !important;">
                            <thead>
                            <tr class="uppercase">
                                <th> Id</th>
                                <th> MEMBER</th>
                                <th> Turnover</th>
                                <th> ORDERS</th>
                                <th> ISSUED</th>
                                <th> PROFIT</th>
                                <th> PROFIT RATE</th>
                            </tr>
                            </thead>
                            <tbody>
                            <td colspan="6" class="dataTables_empty">Loading data from server...</td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-sm-5">
            <div class="portlet light bordered">
                <div class="portlet-title" style="margin-bottom: 0;">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart font-green"></i>
                        <span class="caption-subject font-green bold uppercase">Support Staff</span>
                    </div>
                </div>
                <div class="portlet-body" style="padding: 0;">
                    <div class="table-scrollable table-scrollable-borderless" style="margin: 0 !important;">
                        <table id="table_support" class="table table-hover table-light" style="overflow:hidden; margin: 0 !important;">
                            <thead>
                            <tr class="uppercase">
                                <th> Id</th>
                                <th> Member</th>
                                <th> Position</th>
                                <th> Salary</th>
                            </tr>
                            </thead>
                            <tbody>
                            <td colspan="3" class="dataTables_empty">Loading data from server...</td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row col-md-12">
        <table class="table table-striped table-bordered table-advance table-hover">
            <thead>
            <tr>
                <th>
                    Whom
                </th>
                <th>
                    <i class="fa fa-calendar"></i> Time Period
                </th>
                <th>
                    Total Salary
                </th>
                <th>
                    Approximate Payment Date
                </th>
                <th>
                    Already Issued
                </th>
                <th>
                    Due To Pay
                </th>
                <th>
                    Fact Payment Date
                </th>
                <th>
                    Category
                </th>
                <th>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <a href="javascript;"> Sergey</a>
                </td>
                <td>
                    1/2 Feb 2016
                </td>
                <td>
                    20'000 ₽
                </td>
                <td> 02/25/2016</td>
                <td> 0 ₽</td>
                <td> 20,000 ₽</td>
                <td> N/A</td>
                <td><span class="label label-warning">Advance</span></td>
                <td>
                    <button type="button" class="btn blue btn-sm btn-outline sbold uppercase"><i class="fa fa-share"></i> Issue</button>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="javascript;"> Elena</a>
                </td>
                <td>
                    2/2 Jan 2016
                </td>
                <td>
                    20'000 ₽
                </td>
                <td> 02/10/2016</td>
                <td> 20,000 ₽</td>
                <td> 0 ₽</td>
                <td> 02/10/2016</td>
                <td><span class="label label-success">Salary</span></td>
                <td>
                    <button type="button" class="btn blue btn-sm btn-outline sbold uppercase"><i class="fa fa-share"></i> Issue</button>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="javascript;"> Admont</a>
                </td>
                <td>
                    1/2 Jan 2016
                </td>
                <td>
                    20'000 ₽
                </td>
                <td> 01/25/2016</td>
                <td> 20,000 ₽</td>
                <td> 0 ₽</td>
                <td> 01/25/2016</td>
                <td><span class="label label-warning">Advance</span></td>
                <td>
                    <button type="button" class="btn blue btn-sm btn-outline sbold uppercase"><i class="fa fa-share"></i> Issue</button>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="javascript;"> Matvey</a>
                </td>
                <td>
                    2/2 Dec 2015
                </td>
                <td>
                    20'000 ₽
                </td>
                <td> 12/30/2015</td>
                <td> 20,000 ₽</td>
                <td> 0 ₽</td>
                <td> 12/30/2015</td>
                <td><span class="label label-danger">Premium</span></td>
                <td>
                    <button type="button" class="btn blue btn-sm btn-outline sbold uppercase"><i class="fa fa-share"></i> Issue</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- END PAGE BASE CONTENT -->
</div>


<?php
require_once "modals/new_user.php"
?>

<script>
    $(document).ready(function () {
        var $table_managers = $("#table_managers");
        $table_managers.DataTable({
            processing: true,
            serverSide: true,
            ajax: '/staff/dt_managers',
            dom: '<t>ip',
            columnDefs: [{
                targets: [1, 2, 3, 4, 5, 6],
                searchable: false,
                orderable: false
            }, {
                targets: [0],
                visible: false,
                searchable: false
            }]
        });
        $table_managers.find('tbody').on('click', 'tr td:not(:first-child)', function () {
            var data = table.row($(this).closest('tr')).data();
            window.location.href = "/sales_manager?id=" + data[0];
        });
        var $table_support = $("#table_support");
        $table_support.DataTable({
            processing: true,
            serverSide: true,
            ajax: '/staff/dt_support',
            dom: '<t>ip',
            columnDefs: [{
                targets: [1, 2, 3],
                searchable: false,
                orderable: false
            }, {
                targets: [0],
                visible: false,
                searchable: false
            }]
        });
        $table_support.find('tbody').on('click', 'tr td:not(:first-child)', function () {
            var data = table.row($(this).closest('tr')).data();
            window.location.href = "/support?id=" + data[0];
        });
    });
</script>