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
    <link href="https://fonts.googleapis.com/css?family=Oswald:400,300,700" rel="stylesheet" type="text/css"/>
    <!-- END LAYOUT FIRST STYLES -->
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css"/>
    <link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="/assets/layouts/layout6/css/layout.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/layouts/layout6/css/custom.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/pages/css/profile-2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/jquery-editable-select-master/dist/jquery-editable-select.min.css" rel="stylesheet">

    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>

    <script src="/assets/global/plugins/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/select2/js/select2.full.js" type="text/javascript"></script>

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
                    <img src="/assets/layouts/layout6/img/logo1.png" alt="Logo"> </a>
                <!-- END LOGO -->
				<a id="menu-toggler">
                    <img src="/assets/layouts/layout6/img/sidebar-toggle-light.png" alt="menu" style="margin: 23px;">
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
                            <img src="<?= '/../avatars/' . ($_SESSION['avatar'] ? $_SESSION['avatar'] : 'user.png')
                                ?>" alt=""></button>
                        <ul class="dropdown-menu-v2" role="menu">
                            <li>
                                <?php
                                $userRole = $_SESSION['user_role'];
                                $lk_url = '#';
                                if (ROLE_SALES_MANAGER == $userRole || $userRole == ROLE_OPERATING_MANAGER) {
                                    $lk_url = '/sales_manager?id=' . $_SESSION['user_id'];
                                } else {
                                    $lk_url = '/support?id=' . $_SESSION['user_id'];
                                }
                                ?>
                                <a href="<?= $lk_url ?>">
                                    <i class="icon-user"></i> My Profile
                                </a>
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
<?php $isLogVisible = (isset($this->logs)); ?>
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<div class="container-fluid">
    <div class="page-content page-content-popup">
        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
        <?php if ($isLogVisible): ?>
            <button type="button" class="quick-sidebar-toggler logging-block" data-id="logging" data-toggle="collapse">
                <span class="sr-only">Toggle Logging</span>
                <i class="icon-notebook"></i>
            </button>
        <?php endif; ?>
        <button type="button" class="quick-sidebar-toggler documents-block" data-id="docs" data-toggle="collapse">
            <span class="sr-only">Toggle Documents</span>
            <i class="icon-doc"></i>
            <div class="quick-sidebar-notification">
                <span class="badge badge-danger"><?= (isset($this->documents) && count($this->documents))
                        ? count($this->documents) : '' ?></span>
            </div>
        </button>
        <button type="button" class="quick-sidebar-toggler" data-id="chat" id="messages-btn-sidebar-open" data-toggle="collapse">
            <span class="sr-only">Toggle Messenger</span>
            <i class="icon-logout"></i>
            <div class="quick-sidebar-notification">
                <span class="badge badge-danger chat-quick-messages-count"></span>
            </div>
        </button>
        <!-- END QUICK SIDEBAR TOGGLER -->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/' . $content_view; ?>
        <p class="copyright-v2">2016 - <?= date('Y') ?> © Evropoly.
        </p>
        <a href="#index" class="go2top">
            <i class="icon-arrow-up"></i>
        </a>
        <!-- END FOOTER -->
    </div>
</div>
<!-- BEGIN QUICK SIDEBAR -->
<?php if ($isLogVisible): ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/templates/logging.php' ?>
<?php endif; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/templates/documents.php' ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/templates/chat.php' ?>
<!-- END QUICK SIDEBAR -->
<!-- END CONTAINER -->
<!--[if lt IE 9]>
<script src="/assets/global/plugins/respond.min.js"></script>
<script src="/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/assets/layouts/layout6/scripts/layout.min.js" type="text/javascript"></script>
<script src="/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-sortable/jquery-ui.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
<script src="/assets/global/plugins/jquery-editable-select-master/dist/jquery-editable-select.min.js"
        type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
<script>
    $(document).ready(function () {
         $("#menu-toggler").click(function(){
             var sidebar = $('#sidebar');
             var isOpened = sidebar.is(':visible');
             var mainBlock = $(".page-fixed-main-content");

             if (isOpened) {
                 sidebar.hide();
                 mainBlock.css({'margin-left':'0px'});
            } else {
                 sidebar.show();
                 mainBlock.css({'margin-left':'255px'});
            }

             $.ajax({
                 url: '/login/hidden_sidebar',
                 type: "GET",
                 data: {
                     visible: !isOpened
                 }
             });
             resizeTopScroll();
         });

         $(window).on('resize', resizeTopScroll);

         function resizeTopScroll()
         {
             var topScroll = $('.top-scroll');
             if (topScroll.length) {
                 $.each(topScroll, function() {
                     var scroll = $(this);
                     var fake = scroll.find('.fake');
                     var tableWrapper = scroll.next('div');
                     scroll.width(tableWrapper.width());
                     fake.width(tableWrapper.find('table').width());
                 })
             }
         }


        addTopScroll();

        function addTopScroll()
        {
            var table = $('table.dataTable');
            function addScroll(e)
            {
                if (e.currentTarget !== undefined) {
                    var currentTab = $($(e.currentTarget).attr('href'));
                    var table = currentTab.find('table');
                } else {
                    table = e;
                }

                if (table.attr('data-top-scroll'))
                    return false;

                var tableScrollable = table.closest('.table-scrollable');
                if (tableScrollable.length)
                    var tableWrapper = tableScrollable;
                else {
                    var tableResponsive = table.closest('.table-responsive');

                    if (tableResponsive.length) {
                        tableWrapper = tableResponsive;
                    }
                }

                if (tableWrapper == undefined || !tableWrapper.length)
                    return false;

                var topScrollCode = '<div class="top-scroll"><div class="fake"></div></div>';
                tableWrapper.before(topScrollCode);

                var topScroll = tableWrapper.prev('.top-scroll');
                var fake = topScroll.find('.fake');

                topScroll.width(tableWrapper.width());
                fake.width(table.width());

                topScroll.on('scroll', function(e){
                    tableWrapper.scrollLeft($(this).scrollLeft());
                });
                tableWrapper.on('scroll', function(e){
                    topScroll.scrollLeft($(this).scrollLeft());

                    // for fixed table header
                    var fixedHeader = $('.fixed-table-head');
                    if (fixedHeader.length) {
                        fixedHeader.css('left', -$(this).scrollLeft() + 'px');
                    }

                });
                table.attr('data-top-scroll', true);
            }

            var tab = table.closest('.tab-pane');
            if ($('body').find('.tab-pane').length) {
                $.each($('body').find('.tab-pane'), function() {
                    if ($(this).find('table.dataTable').length) {
                        if ($(this).is('.active')) {
                            addScroll($(this).find('table'));
                        } else {
                            var tabId = $(this).attr('id');
                            var selector = 'a[href="#'+tabId+'"]';
                            $(selector).on('shown.bs.tab', addScroll);
                        }
                    }
                });
            } else if($('body').find('.table-scrollable').length) {
                addScroll($('body').find('.table-scrollable').find('table'));
            }
        }

        $('.print-btn').on('click', function(e) {
            e.preventDefault();
            var btn = $(this);
            $.ajax({
                url: btn.attr('href'),
                success: function(data) {
                    if (data) {
                        location.href = data;
                    }
                }
            })
        });

        var selects = $('.select2-select');
        if (selects !== undefined && selects.length) {
            $.each(selects, function() {
                $(this).select2();
                $(this).next('.select2').css('width', '100%');
            });

        }

});

</script>
</body>

</html>