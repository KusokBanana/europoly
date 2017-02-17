<div class="container-fluid">
    <div class="page-content page-content-popup">
        <div class="page-content-fixed-header">
            <!-- BEGIN BREADCRUMBS -->
            <ul class="page-breadcrumb">
                <li>
                    <a href="/">Dashboard</a>
                </li>
                <li>
                    <a href="/staff">Staff</a>
                </li>
                <li><?= $this->manager['first_name'] . ' ' . $this->manager['last_name'] ?></li>
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
            <div class="profile">
                <div class="tabbable-line tabbable-full-width">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab"> Overview </a>
                        </li>
                        <li>
                            <a href="#tab_1_3" data-toggle="tab"> Edit Account </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1_1">
                            <div class="row">
                                <div class="col-md-3">
                                    <ul class="list-unstyled profile-nav">
                                        <li>
                                            <img src="<?= $this->manager['avatar_url'] != null ? '/avatars/' . $this->manager['avatar_url'] : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image' ?>" class="img-responsive pic-bordered"
                                                 alt=""/>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <!--end col-md-8-->
										<div class="col-md-4">
											<ul class="list-unstyled profile-nav">
												<li>
													<h3 class="font-green sbold uppercase"><?= $this->manager['first_name'] . ' ' . $this->manager['last_name'] ?></h3>
													<h5 class="font-green sbold uppercase">Sales Manager</h5>
													<i class="fa fa-calendar"></i> Works since <?= $this->manager['employment_date'] ?><br/><br/>
													<i class="fa fa-envelope-o"></i><?= $this->manager['email'] ?><br/><br/>
													<i class="fa fa-phone"></i><?= $this->manager['mobile_number'] ?><br/><br/>
													<i class="fa fa-calendar"></i><?= $this->manager['date_of_birth'] ?><br/><br/>
													<i class="fa fa-info"></i> <?= $this->manager['notes'] ?><br/><br/>
												</li>
											</ul>
										</div>
                                        <div class="col-md-4">
                                            <div class="portlet sale-summary">
                                                <div class="portlet-title">
                                                    <div class="caption font-red sbold"> Salary</div>
                                                </div>
                                                <div class="portlet-body">
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <span class="sale-info"> Salary
                                                                <i class="fa fa-img-up"></i>
                                                            </span>
                                                            <span class="sale-num"> <?= $this->manager['salary'] ?> €</span>
                                                        </li>
                                                        <li>
                                                            <span class="sale-info"> Comission Rate
                                                                <i class="fa fa-img-up"></i>
                                                            </span>
                                                            <span class="sale-num"> <?= $this->manager['salary_bonus_rate'] ?> %</span>
                                                        </li>
                                                        <li>
                                                            <span class="sale-info"> THIS MONTH COMISSION
                                                                <i class="fa fa-img-down"></i>
                                                            </span>
                                                            <span class="sale-num"> ? €</span>
                                                        </li>
                                                        <li>
                                                            <span class="sale-info"> Total
                                                                <i class="fa fa-img-down"></i>
                                                            </span>
                                                            <span class="sale-num"> ? €</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="portlet sale-summary">
                                                <div class="portlet-title">
                                                    <div class="caption font-red sbold"> Clients</div>
                                                </div>
                                                <div class="portlet-body">
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <span class="sale-info"> Dealers
                                                                <i class="fa fa-img-up"></i>
                                                            </span>
                                                            <span class="sale-num"> ?</span>
                                                        </li>
                                                        <li>
                                                            <span class="sale-info"> Comission Agents
                                                                <i class="fa fa-img-up"></i>
                                                            </span>
                                                            <span class="sale-num"> ?</span>
                                                        </li>
                                                        <li>
                                                            <span class="sale-info"> End-Customers
                                                                <i class="fa fa-img-down"></i>
                                                            </span>
                                                            <span class="sale-num"> ?</span>
                                                        </li>
                                                        <li>
                                                            <span class="sale-info"> Active This Month
                                                                <i class="fa fa-img-down"></i>
                                                            </span>
                                                            <span class="sale-num"> ? </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end col-md-4-->
                                    </div>
                                    <!--end row-->
                                </div>
                                <div class="col-md-12">
                                    <div class="tabbable-line tabbable-custom-profile">
                                        <ul class="nav nav-tabs col-md-6" style="padding:10px">
                                            <li class="active">
                                                <a href="#tab_1_11" data-toggle="tab"> Clients </a>
                                            </li>
                                            <li>
                                                <a href="#tab_1_22" data-toggle="tab"> Orders </a>
                                            </li>
                                            <li>
                                                <a href="#tab_1_33" data-toggle="tab"> Projects </a>
                                            </li>
                                            <li>
                                                <a href="#tab_1_44" data-toggle="tab"> Salary </a>
                                            </li>
                                        </ul>
                                        <div class="actions pull-right" style="padding:10px">
                                            <div class="btn-group">
                                                <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_newClient"> Add New Client
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                            <div class="btn-group">
                                                <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_newOrder"> Add New Order
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                            <div class="btn-group ">
                                                <button class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Export
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
                                                        <a href="javascript:;">
                                                            <i class="fa fa-print"></i> Print </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;">
                                                            <i class="fa fa-file-pdf-o"></i> Save as PDF </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;">
                                                            <i class="fa fa-file-excel-o"></i> Export to Excel </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_1_11">
                                                <br/>
                                                <br/>
                                                <div class="portlet-body">
                                                    <table id="table_clients" class="table table-striped table-bordered table-advance table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>Id</th>
                                                            <th><i class="fa fa-briefcase"></i> Name</th>
                                                            <th>Category</th>
                                                            <th><i class="fa fa-calendar"></i> Last Order</th>
                                                            <th>This Year Turnover</th>
                                                            <th>This Year Profit</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="6" class="dataTables_empty">Loading data from server...</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!--tab-pane-->
                                            <div class="tab-pane" id="tab_1_22">
                                                <br/>
                                                <br/>
                                                <table id="table_orders" class="table table-striped table-bordered table-hover table-advance table-checkable order-column">
                                                    <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Name</th>
                                                        <th>Received</th>
                                                        <th>Status</th>
                                                        <th>Special expenses</th>
                                                        <th>Total price</th>
                                                        <th>Downpayment rate</th>
                                                        <th>Manager bonus</th>
                                                        <th>Commission rate</th>
                                                        <th>Commission Agent</th>
                                                        <th> Client </th>
                                                        <th>Total commission</th>
                                                        <th>Commission status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="11" class="dataTables_empty">Loading data from server...</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <style>
                                                    #table_orders_wrapper {
                                                        overflow-x: auto;
                                                    }
                                                </style>
                                            </div>
                                            <div class="tab-pane" id="tab_1_33">
                                                <br/>
                                                <br/>
                                                <div class="portlet-body">
                                                    <table class="table table-striped table-bordered table-advance table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>
                                                                Project Name
                                                            </th>
                                                            <th>
                                                                Some more information
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <a href="#"> Hillton Moscow</a>
                                                            </td>
                                                            <td>
                                                                20'000 ₽
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <a href="#"> Hillton Moscow</a>
                                                            </td>
                                                            <td>
                                                                20'000 ₽
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <a href="#"> Hillton Moscow</a>
                                                            </td>
                                                            <td>
                                                                20'000 ₽
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <a href="#"> Hillton Moscow</a>
                                                            </td>
                                                            <td>
                                                                20'000 ₽
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <a href="#"> Hillton Moscow</a>
                                                            </td>
                                                            <td>
                                                                20'000 ₽
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_1_44">
                                                <br/>
                                                <br/>
                                                <div class="portlet-body">
                                                    <table class="table table-striped table-bordered table-advance table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>
                                                                <i class="fa fa-calendar"></i> Time <br/>Period
                                                            </th>
                                                            <th>
                                                                Total<br/> Salary
                                                            </th>
                                                            <th>
                                                                Approximate <br/>Payment Date
                                                            </th>
                                                            <th>
                                                                Already<br/> Issued
                                                            </th>
                                                            <th>
                                                                Due To Pay
                                                            </th>
                                                            <th>
                                                                Fact<br/> Payment Date
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
                                                                1/2 Feb 2016
                                                            </td>
                                                            <td>
                                                                20'000 ₽
                                                            </td>
                                                            <td> 02/25/2016</td>
                                                            <td> 0 ₽</td>
                                                            <td> 20,000 ₽</td>
                                                            <td> N/A</td>
                                                            <td><span class="label label-warning">Salary</span></td>
                                                            <td>
                                                                <button type="button" class="btn blue btn-sm btn-outline sbold uppercase"><i class="fa fa-share"></i> Issue</button>
                                                            </td>
                                                        </tr>
                                                        <tr>
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
                                                            <td><span class="label label-success">Bonus</span></td>
                                                            <td>
                                                                <button type="button" class="btn blue btn-sm btn-outline sbold uppercase"><i class="fa fa-share"></i> Issue</button>
                                                            </td>
                                                        </tr>
                                                        <tr>
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
                                                            <td><span class="label label-warning">Salary</span></td>
                                                            <td>
                                                                <button type="button" class="btn blue btn-sm btn-outline sbold uppercase"><i class="fa fa-share"></i> Issue</button>
                                                            </td>
                                                        </tr>
                                                        <tr>
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
                                            </div>
                                            <!--tab-pane-->
                                        </div>
                                    </div>								
                                </div>
								
                            </div>
                        </div>
                        <!--tab_1_2-->
                        <div class="tab-pane" id="tab_1_3">
                            <div class="row profile-account">
                                <div class="col-md-3">
                                    <ul class="ver-inline-menu tabbable margin-bottom-10">
                                        <li class="active">
                                            <a data-toggle="tab" href="#tab_1-1">
                                                <i class="fa fa-cog"></i> Personal info </a>
                                            <span class="after"> </span>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#tab_4-4">
                                                <i class="fa fa-eye"></i> Salary Settings </a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#tab_2-2">
                                                <i class="fa fa-picture-o"></i> Avatar </a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#tab_3-3">
                                                <i class="fa fa-lock"></i> Account</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-9">
                                    <div class="tab-content">
                                        <div id="tab_1-1" class="tab-pane active">
                                            <form method="post" action="/sales_manager/update_personal_info">
                                                <input name="user_id" type="hidden" value="<?= $this->manager['user_id'] ?>"/>
                                                <div class="form-group">
                                                    <label class="control-label">First Name</label>
                                                    <input id="input_first_name" name="first_name" type="text" placeholder="Ivan" class="form-control" value="<?= $this->manager['first_name'] ?>" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Last Name</label>
                                                    <input id="input_last_name" name="last_name" type="text" placeholder="Kolyvan" class="form-control" value="<?= $this->manager['last_name'] ?>" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Date of Birth</label>
                                                    <input id="input_date_of_birth" name="date_of_birth" type="date" class="form-control" value="<?= $this->manager['date_of_birth'] ?>"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Position</label>
                                                    <input id="input_position" name="position" type="text" class="form-control" value="<?= $this->manager['position'] ?>"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Work Phone</label>
                                                    <input id="input_work_phone" name="work_phone" type="text" placeholder="+7 495 515 81 77" class="form-control" value="<?= $this->manager['work_phone'] ?>"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Mobile Number</label>
                                                    <input id="input_mobile_number" name="mobile_number" type="text" placeholder="+7 903 515 81 77" class="form-control" value="<?= $this->manager['mobile_number'] ?>"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Email</label>
                                                    <input id="input_email" name="email" type="text" placeholder="savadyan2007@gmail.com" class="form-control" value="<?= $this->manager['email'] ?>"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Employment date</label>
                                                    <input id="input_employment_date" name="employment_date" type="date" class="form-control" value="<?= $this->manager['employment_date'] ?>"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Notes</label>
                                                    <textarea id="input_notes" name="notes" class="form-control" rows="3" placeholder="He's working perfect! Add more Bonus next month"><?= $this->manager['notes'] ?></textarea>
                                                </div>
                                                <div class="margiv-top-10">
                                                    <button type="submit" class="btn green"> Save Changes</button>
                                                    <button type="reset" class="btn default"> Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="tab_2-2" class="tab-pane">
                                            <form method="post" action="/sales_manager/update_avatar" enctype="multipart/form-data">
                                                <input name="user_id" type="hidden" value="<?= $this->manager['user_id'] ?>"/>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="control-label"> Profile picture </label>
                                                            <div class="fileinput-new thumbnail" style="width: 100%; overflow: hidden">
                                                                <img src="<?= $this->manager['avatar_url'] != null ? '/avatars/' . $this->manager['avatar_url'] : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image' ?>"
                                                                     class="img-responsive pic-bordered" style="max-height: 300px" alt=""/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="control-label"> Upload new picture </label>
                                                            <input type="file" name="fileToUpload"><br>
                                                            <div class="margiv-top-10">
                                                                <button type="submit" name="action" value="update" class="btn green"> Update avatar</button>
                                                                <button type="submit" name="action" value="delete" class="btn default"> Remove avatar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="tab_3-3" class="tab-pane">
                                            <form method="post" action="/sales_manager/update_account">
                                                <input name="user_id" type="hidden" value="<?= $this->manager['user_id'] ?>"/>
                                                <div class="form-group">
                                                    <label class="control-label">Login</label>
                                                    <?php if ($this->access): ?>
                                                        <input id="input_login" name="login" class="form-control"
                                                                              value="<?= $this->manager['login'] ?>" required/>
                                                    <?php else: ?>
                                                        <?= $this->manager['login'] ?>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ($this->access): ?>
                                                    <div class="form-group">
                                                        <label class="control-label">New Password</label>
                                                        <input id="input_password" name="password" type="password" class="form-control" required/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Re-type New Password</label>
                                                        <input id="input_retype_password" type="password" class="form-control" required/>
                                                    </div>
                                                    <div class="margin-top-10">
                                                        <button type="submit" class="btn green"> Save Changes</button>
                                                        <button type="reset" class="btn default"> Cancel</button>
                                                    </div>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                        <div id="tab_4-4" class="tab-pane">
                                            <form method="post" action="/sales_manager/update_salary_settings">
                                                <input name="user_id" type="hidden" value="<?= $this->manager['user_id'] ?>"/>
                                                <div class="form-group">
                                                    <label class="control-label">Salary, &euro;</label>
                                                    <?php if ($this->access): ?>
                                                        <input id="input_salary" name="salary"
                                                               class="form-control" value="<?= $this->manager['salary'] ?>"
                                                               step="0.01" min="0"/>
                                                    <?php else: ?>
                                                        <?= $this->manager['salary'] ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Bonus Rate, %</label>
                                                    <?php if ($this->access): ?>
                                                        <input id="input_manager_bonus_rate" name="manager_bonus_rate" type="number"
                                                               class="form-control" placeholder="1,4" value="<?= $this->manager['salary_bonus_rate'] ?>" step="0.01" min="0" max="100"/>
                                                    <?php else: ?>
                                                        <?= $this->manager['salary_bonus_rate'] ?>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ($this->access): ?>
                                                    <div class="margin-top-10">
                                                        <button type="submit" class="btn green"> Save Changes</button>
                                                        <button type="reset" class="btn default"> Cancel</button>
                                                    </div>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-md-9-->
                            </div>
                        </div>
                        <!--end tab-pane-->
                    </div>
                </div>
            </div>
            <!-- END PAGE BASE CONTENT -->
        </div>

        <!-- BEGIN FOOTER -->
        <p class="copyright-v2">2016 © Evropoly.
        </p>
        <a href="#index" class="go2top">
            <i class="icon-arrow-up"></i>
        </a>
        <!-- END FOOTER -->
    </div>
</div>

<?php
require_once 'modals/new_client.php';
require_once 'modals/new_order.php';
?>

<script>
    var password = $("#input_password");
    var confirm_password = $("#input_retype_password");
    function validatePassword() {
        if (password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords Don't Match");
        } else {
            confirm_password.setCustomValidity('');
        }
    }
    if (password.length) {
        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    }

    $(document).ready(function () {
        var $table_clients = $("#table_clients");
        $table_clients.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/sales_manager/dt_clients',
                data: {
                    'user_id': <?= $this->manager["user_id"] ?>
                }
            },
            dom: '<t>ip',
            columnDefs: [{
                targets: [1, 2, 3, 4, 5],
                searchable: false,
                orderable: false
            }, {
                targets: [0],
                visible: false,
                searchable: false
            }]
        });
        $table_clients.find('tbody').on('click', 'tr', function () {
            var data = $table_clients.DataTable().row(this).data();
            window.location.href = "/client?id=" + data[0];
        });

        var $table_orders = $("#table_orders");
        $table_orders.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/sales_manager/dt_orders',
                data: {
                    'user_id': <?= $this->manager["user_id"] ?>
                }
            },
            dom: '<t>ip',
            columnDefs: [{
                targets: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                searchable: false,
                orderable: false
            }, {
                targets: [0],
                visible: false,
                searchable: false
            }]
        });
        $table_orders.find('tbody').on('click', 'tr', function () {
            var data = $table_orders.DataTable().row(this).data();
            window.location.href = "/order?id=" + $(data[0]).text();
        });

        $("#modal_new_client_manager_id").val(<?= $this->manager["user_id"] ?>);
        $("#modal_new_order_sales_manager").val(<?= $this->manager["user_id"] ?>);
        $("#modal_new_order_sales_manager_2").val(<?= $this->manager["user_id"] ?>);
    });
</script>