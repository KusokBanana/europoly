<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title><?= $this->title ?> | Evropoly</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN LAYOUT FIRST STYLES -->
    <link href="//fonts.googleapis.com/css?family=Oswald:400,300,700" rel="stylesheet" type="text/css"/>
    <!-- END LAYOUT FIRST STYLES -->
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css"/>
    <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="assets/layouts/layout6/css/layout.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/layouts/layout6/css/custom.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/pages/css/profile-2.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/global/plugins/jquery-editable-select-master/dist/jquery-editable-select.min.css" rel="stylesheet">

    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>

    <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js" type="text/javascript"></script>
    <script src="assets/global/plugins/select2/js/select2.full.js" type="text/javascript"></script>

    <style>
        tbody tr {
            cursor: pointer;
        }
        .table-advance tr td:first-child {
            border-left-width: 0 !important;
        }
        .select2-container {
            z-index: 100000;
        }
    </style>
</head>
<!-- END HEAD -->

<body class="">
<!-- BEGIN HEADER -->
<header class="page-header">
    <nav class="navbar" role="navigation">
        <div class="container-fluid">
            <div class="havbar-header">
                <!-- BEGIN LOGO -->
                <a id="index" class="navbar-brand" href="/">
                    <img src="assets/layouts/layout6/img/logo1.png" alt="Logo"> </a>
                <!-- END LOGO -->
				<a id="menu-toggler">
                    <img src="assets/layouts/layout6/img/sidebar-toggle-light.png" alt="menu" style="margin: 23px;">
				</a>
                <!-- BEGIN TOPBAR ACTIONS -->
                <div class="topbar-actions">
                    <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
                    <form class="search-form" action="extra_search.html" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search here" name="query">
                            <span class="input-group-btn">
                                <a href="javascript:;" class="btn md-skip submit">
                                    <i class="fa fa-search"></i>
                                </a>
                            </span>
                        </div>
                    </form>
                    <!-- END HEADER SEARCH BOX -->
                    <!-- BEGIN USER PROFILE -->
                    <div class="btn-group-img btn-group">
                        <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <img src="assets/layouts/layout5/img/avatar1.jpg" alt=""></button>
                        <ul class="dropdown-menu-v2" role="menu">
                            <li>
                                <a href="#"><i class="icon-user"></i> My Profile</a>
                            </li>
                            <li>
                                <a href="/login/logout"><i class="icon-key"></i> Log Out </a>
                            </li>
                        </ul>
                    </div>
                    <!-- END USER PROFILE -->
                </div>
                <!-- END TOPBAR ACTIONS -->
            </div>
        </div>
        <!--/container-->
    </nav>
</header>
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<?php include 'application/views/' . $content_view; ?>
<!-- END CONTAINER -->
<!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="assets/layouts/layout6/scripts/layout.min.js" type="text/javascript"></script>
<script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-editable-select-master/dist/jquery-editable-select.min.js"
        type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<script>
    $(document).ready(function () {
     $("#menu-toggler").click(function(){
        var menu_shown = true;
        if ($("#sidebar").is(":visible")) {
            $("#sidebar").hide();
            $(".page-fixed-main-content").css({'margin-left':'0px'});
        } else {
            $("#sidebar").show();
            $(".page-fixed-main-content").css({'margin-left':'255px'});
        }
     });
});

</script>
</body>

</html>