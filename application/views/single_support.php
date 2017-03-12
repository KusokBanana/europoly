<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li>
            <a href="/staff">Staff</a>
        </li>
        <li><?= $this->support['first_name'] . ' ' . $this->support['last_name'] ?></li>
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
            <?php if ($this->access['d']): ?>
                <br>
                <a href="/support/delete_user?id=<?= $this->support['user_id']; ?>"
                   class="text-right btn btn-danger" data-placement="top" data-popout="true"
                   data-singleton="true" data-toggle="confirmation" data-title="Are you sure to delete this user?">
                    Удалить пользователя
                </a>
            <?php endif; ?>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                    <div class="row">
                        <div class="col-md-3">
                            <ul class="list-unstyled profile-nav">
                                <li>
                                    <img src="<?= $this->support['avatar_url'] != null ? '/avatars/' . $this->support['avatar_url'] : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image' ?>" class="img-responsive pic-bordered"
                                         alt=""/>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled profile-nav">
                                        <li>
                                            <h3 class="font-green sbold uppercase"><?= $this->support['first_name'] . ' ' . $this->support['last_name'] ?></h3>
                                            <h5 class="font-green sbold uppercase">Warehouse</h5>
                                            <i class="fa fa-calendar"></i> Works since <?= $this->support['employment_date'] ?><br/><br/>
                                            <i class="fa fa-envelope-o"></i><?= $this->support['email'] ?><br/><br/>
                                            <i class="fa fa-phone"></i><?= $this->support['mobile_number'] ?><br/><br/>
                                            <i class="fa fa-calendar"></i><?= $this->support['date_of_birth'] ?><br/><br/>
                                            <i class="fa fa-info"></i> <?= $this->support['notes'] ?><br/><br/>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
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
                                                    <span class="sale-num"> <?= $this->support['salary'] ?> €</span>
                                                </li>
                                                <li>
                                                            <span class="sale-info"> Payed till
                                                                <i class="fa fa-img-up"></i>
                                                            </span>
                                                    <span class="sale-num"> ?March 2016?</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end row-->
                        </div>
                    </div>
<!--                    TODO delete -->
                    <!--<div class="row">
                        <div class="col-md-12">
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
                                    <td><span class="label label-warning">Advance</span></td>
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
                                    <td><span class="label label-success">Salary</span></td>
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
                                    <td><span class="label label-warning">Advance</span></td>
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

                    </div>-->
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
                                    <form method="post" action="/support/update_personal_info">
                                        <input name="user_id" type="hidden" value="<?= $this->support['user_id'] ?>"/>
                                        <div class="form-group">
                                            <label class="control-label">First Name</label>
                                            <input id="input_first_name" name="first_name" type="text" placeholder="Ivan" class="form-control" value="<?= $this->support['first_name'] ?>" required/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Last Name</label>
                                            <input id="input_last_name" name="last_name" type="text" placeholder="Kolyvan" class="form-control" value="<?= $this->support['last_name'] ?>" required/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Date of Birth</label>
                                            <input id="input_date_of_birth" name="date_of_birth" type="date" class="form-control" value="<?= $this->support['date_of_birth'] ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Position</label>
                                            <input id="input_position" name="position" type="text" class="form-control" value="<?= $this->support['position'] ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Work Phone</label>
                                            <input id="input_work_phone" name="work_phone" type="text" placeholder="+7 495 515 81 77" class="form-control" value="<?= $this->support['work_phone'] ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Mobile Number</label>
                                            <input id="input_mobile_number" name="mobile_number" type="text" placeholder="+7 903 515 81 77" class="form-control" value="<?= $this->support['mobile_number'] ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Email</label>
                                            <input id="input_email" name="email" type="text" placeholder="savadyan2007@gmail.com" class="form-control" value="<?= $this->support['email'] ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Employment date</label>
                                            <input id="input_employment_date" name="employment_date" type="date" class="form-control" value="<?= $this->support['employment_date'] ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Notes</label>
                                            <textarea id="input_notes" name="notes" class="form-control" rows="3" placeholder="He's working perfect! Add more Bonus next month"><?= $this->support['notes'] ?></textarea>
                                        </div>
                                        <?php if ($_SESSION['user_role'] == ROLE_ADMIN): ?>
                                            <div class="form-group">
                                                <label class="control-label">User Role</label>
                                                <select name="role_id" id="role_id" class="form-control">
                                                    <?php
                                                    if (!empty($this->roles)) {
                                                        foreach ($this->roles as $role) {
                                                            $selected = ($role['id'] == $this->support['role_id']) ?
                                                                ' selected ' : '';
                                                            echo '<option value="'.$role['id'].'"' . $selected . '>'.
                                                                $role['name'].'</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        <?php endif; ?>
                                        <div class="margiv-top-10">
                                            <button type="submit" class="btn green"> Save Changes</button>
                                            <button class="btn default" onclick="cancelPersonalInfoChanges(event)"> Cancel</button>
                                        </div>
                                    </form>
                                    <script>
                                        function cancelPersonalInfoChanges(event) {
                                            event.stopPropagation();
                                            event.preventDefault();
                                            $("#input_first_name").val("<?= $this->support['first_name'] ?>");
                                            $("#input_last_name").val("<?= $this->support['last_name'] ?>");
                                            $("#input_mobile_number").val("<?= $this->support['mobile_number'] ?>");
                                            $("#input_email").val("<?= $this->support['email'] ?>");
                                            $("#input_notes").val("<?= $this->support['notes'] ?>");
                                            return false;
                                        }
                                    </script>
                                </div>
                                <div id="tab_2-2" class="tab-pane">
                                    <form method="post" action="/support/update_avatar" enctype="multipart/form-data">
                                        <input name="user_id" type="hidden" value="<?= $this->support['user_id'] ?>"/>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="control-label"> Profile picture </label>
                                                    <div class="fileinput-new thumbnail" style="width: 100%; overflow: hidden">
                                                        <img src="<?= $this->support['avatar_url'] != null ? '/avatars/' . $this->support['avatar_url'] : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image' ?>"
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
                                    <form method="post" action="/support/update_account">
                                        <input name="user_id" type="hidden" value="<?= $this->support['user_id'] ?>"/>
                                        <div class="form-group">
                                            <label class="control-label">Login</label>
                                            <?php if ($this->access['ch']): ?>
                                                <input id="input_login" name="login"
                                                       class="form-control" value="<?= $this->support['login'] ?>"
                                                       required/>
                                            <?php else: ?>
                                                <?= $this->support['login'] ?>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($this->access['ch']): ?>
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
                                                <button class="btn default" onclick="cancelAccountChanges(event);"> Cancel</button>
                                            </div>
                                        <?php endif; ?>
                                    </form>
                                    <script>
                                        function cancelAccountChanges(event) {
                                            event.stopPropagation();
                                            event.preventDefault();
                                            $("#input_login").val("<?= $this->support['login'] ?>");
                                            $("#input_password").val("");
                                            $("#input_retype_password").val("");
                                            return false;
                                        }

                                        var password = document.getElementById("input_password");
                                        var confirm_password = document.getElementById("input_retype_password");
                                        function validatePassword() {
                                            if (password.value != confirm_password.value) {
                                                confirm_password.setCustomValidity("Passwords Don't Match");
                                            } else {
                                                confirm_password.setCustomValidity('');
                                            }
                                        }
                                        password.onchange = validatePassword;
                                        confirm_password.onkeyup = validatePassword;
                                    </script>
                                </div>
                                <div id="tab_4-4" class="tab-pane">
                                    <form method="post" action="/support/update_salary_settings">
                                        <input name="user_id" type="hidden" value="<?= $this->support['user_id'] ?>"/>
                                        <div class="form-group">
                                            <label class="control-label">Salary, &euro;</label>
                                            <?php if ($this->access['ch']): ?>
                                                <input id="input_salary" name="salary" class="form-control"
                                                       value="<?= $this->support['salary'] ?>"/>
                                            <?php else: ?>
                                                <?= $this->support['salary'] ?>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($this->access['ch']): ?>
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
