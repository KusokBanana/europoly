<div class="container-fluid">
    <div class="page-content page-content-popup">
        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
        <button type="button" class="quick-sidebar-toggler" data-toggle="collapse">
            <span class="sr-only">Toggle Documents</span>
            <i class="icon-doc"></i>
            <div class="quick-sidebar-notification">
                <span class="badge badge-danger">2</span>
            </div>
        </button>
        <button type="button" class="quick-sidebar-toggler" data-toggle="collapse">
            <span class="sr-only">Toggle Messenger</span>
            <i class="icon-logout"></i>
            <div class="quick-sidebar-notification">
                <span class="badge badge-danger">7</span>
            </div>
        </button>
        <!-- END QUICK SIDEBAR TOGGLER -->
        <div class="page-content-fixed-header">
            <!-- BEGIN BREADCRUMBS -->
            <ul class="page-breadcrumb">
                <li>
                    <a href="/">Dashboard</a>
                </li>
                <li>
                    Catalogue
                </li>
            </ul>
            <!-- END BREADCRUMBS -->
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
                            <div class="caption font-dark">
                                <i class="icon-settings font-dark"></i>
                                <span class="caption-subject bold uppercase"> Catalogue</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <ul class="nav nav-tabs category-tabs">
                                <?php if (!empty($this->tabs)):
                                    foreach ($this->tabs as $key => $tab):
                                        $tab_id = 'tab_' . $key ;
                                        if (isset($tab['items']) && !empty($tab['items'])): ?>
                                            <li class="dropdown">
                                                <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown">
                                                    <?= $tab['name'] ?>
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu" role="menu">
                                                    <?php $i = 0; ?>
                                                    <?php foreach ($tab['items'] as $item):
                                                        $id = $item['id'];
                                                        $name = $item['name'];
                                                        ?>
                                                        <li>
                                                            <?php
                                                            $tab_item_id = $tab_id . '_' . $i++;
                                                            ?>
                                                            <a href="<?= '#' . $tab_item_id ?>"
                                                               tabindex="-1"
                                                               data-category-id="<?= $id ?>"
                                                               data-toggle="tab">
                                                                <?= $name ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </li>
                                        <?php else: ?>
                                            <?php
                                            $id = ($tab['name'] != 'All') ? $tab['id'] : 0;
                                            ?>
                                            <li <?= $id === 0 ? 'class="active"' : '' ?>>
                                                <a href="<?= '#' . $tab_id ?>"
                                                   <?= $id === 0 ? 'aria-expanded="true"' : '' ?>
                                                   data-category-id="<?= $id ?>"
                                                   data-toggle="tab"> <?= $tab['name'] ?> </a>
                                            </li>
                                            <?php endif;
                                    endforeach; ?>
                                <?php endif; ?>
                            </ul>
                            <div class="tab-content" style="padding: 10px;">
                                <div class="tab-pane fade active in">
                                    <?php
                                    $buttons = [
                                        '<button class="btn sbold green" data-toggle="modal" 
data-target="#modal_newProduct">Add New Product <i class="fa fa-plus"></i></button>',
                                        '<button class="btn sbold blue new-similar-product-btn" data-toggle="modal" 
data-target="#modal_newProduct">Add Similar Product <i class="fa fa-plus"></i></button>'
                                    ];
                                    if (!$this->access)
                                        $buttons = [];
                                    $table_data = [
                                        'buttons' => $buttons,
                                        'table_id' => $this->tableName,
                                        'ajax' => [
                                            'url' => "/catalogue/dt",
                                        ],
                                        'column_names' => $this->column_names,
                                        'hidden_by_default' => $this->hidden_columns,
                                        'click_url' => "/product?id=",
                                        'selectSearch' => $this->selects,
                                        'filterSearchValues' => $this->rows,
                                        'originalColumns' => $this->originalColumns
                                    ];
                                    include 'application/views/templates/table.php'
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PAGE BASE CONTENT -->
        </div>
        <!-- BEGIN FOOTER -->
        <p class="copyright-v2">2016 © Evropoly.
        </p>
    </div>
</div>
<!-- BEGIN QUICK SIDEBAR -->
<a href="javascript:;" class="page-quick-sidebar-toggler">
    <i class="icon-docs"></i>
</a>
<div class="page-quick-sidebar-wrapper" data-close-on-body-click="false">
    <div class="page-quick-sidebar">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="javascript:;" data-target="#quick_sidebar_tab_1" data-toggle="tab"> Messages
                    <span class="badge badge-danger">2</span>
                </a>
            </li>
            <li class="">
                <a href="javascript:;" data-target="#quick_sidebar_tab_2" data-toggle="tab"> Documents
                    <span class="badge badge-danger">2</span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
                <div class="page-quick-sidebar-chat-users" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list">
                    <h3 class="list-heading">Staff</h3>
                    <ul class="media-list list-items">
                        <li class="media">
                            <div class="media-status">
                                <span class="badge badge-success">8</span>
                            </div>
                            <img class="media-object" src="../assets/layouts/layout/img/avatar3.jpg" alt="...">
                            <div class="media-body">
                                <h4 class="media-heading">Bob Nilson</h4>
                                <div class="media-heading-sub"> Sales Manager </div>
                            </div>
                        </li>
                        <li class="media">
                            <img class="media-object" src="../assets/layouts/layout/img/avatar1.jpg" alt="...">
                            <div class="media-body">
                                <h4 class="media-heading">Nick Larson</h4>
                                <div class="media-heading-sub"> Sales Manager </div>
                            </div>
                        </li>
                        <li class="media">
                            <div class="media-status">
                                <span class="badge badge-danger">3</span>
                            </div>
                            <img class="media-object" src="../assets/layouts/layout/img/avatar4.jpg" alt="...">
                            <div class="media-body">
                                <h4 class="media-heading">Deon Hubert</h4>
                                <div class="media-heading-sub"> Warehouse </div>
                            </div>
                        </li>
                        <li class="media">
                            <img class="media-object" src="../assets/layouts/layout/img/avatar2.jpg" alt="...">
                            <div class="media-body">
                                <h4 class="media-heading">Ella Wong</h4>
                                <div class="media-heading-sub"> CEO </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="page-quick-sidebar-item">
                    <div class="page-quick-sidebar-chat-user">
                        <div class="page-quick-sidebar-nav">
                            <a href="javascript:;" class="page-quick-sidebar-back-to-list">
                                <i class="icon-arrow-left"></i>Back</a>
                        </div>
                        <div class="page-quick-sidebar-chat-user-messages">
                            <div class="post out">
                                <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar3.jpg" />
                                <div class="message">
                                    <span class="arrow"></span>
                                    <a href="javascript:;" class="name">Bob Nilson</a>
                                    <span class="datetime">20:15</span>
                                    <span class="body"> When could you send me the report ? </span>
                                </div>
                            </div>
                            <div class="post in">
                                <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar2.jpg" />
                                <div class="message">
                                    <span class="arrow"></span>
                                    <a href="javascript:;" class="name">Ella Wong</a>
                                    <span class="datetime">20:15</span>
                                    <span class="body"> Its almost done. I will be sending it shortly </span>
                                </div>
                            </div>
                            <div class="post out">
                                <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar3.jpg" />
                                <div class="message">
                                    <span class="arrow"></span>
                                    <a href="javascript:;" class="name">Bob Nilson</a>
                                    <span class="datetime">20:15</span>
                                    <span class="body"> Alright. Thanks! :) </span>
                                </div>
                            </div>
                            <div class="post in">
                                <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar2.jpg" />
                                <div class="message">
                                    <span class="arrow"></span>
                                    <a href="javascript:;" class="name">Ella Wong</a>
                                    <span class="datetime">20:16</span>
                                    <span class="body"> You are most welcome. Sorry for the delay. </span>
                                </div>
                            </div>
                            <div class="post out">
                                <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar3.jpg" />
                                <div class="message">
                                    <span class="arrow"></span>
                                    <a href="javascript:;" class="name">Bob Nilson</a>
                                    <span class="datetime">20:17</span>
                                    <span class="body"> No probs. Just take your time :) </span>
                                </div>
                            </div>
                            <div class="post in">
                                <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar2.jpg" />
                                <div class="message">
                                    <span class="arrow"></span>
                                    <a href="javascript:;" class="name">Ella Wong</a>
                                    <span class="datetime">20:40</span>
                                    <span class="body"> Alright. I just emailed it to you. </span>
                                </div>
                            </div>
                            <div class="post out">
                                <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar3.jpg" />
                                <div class="message">
                                    <span class="arrow"></span>
                                    <a href="javascript:;" class="name">Bob Nilson</a>
                                    <span class="datetime">20:17</span>
                                    <span class="body"> Great! Thanks. Will check it right away. </span>
                                </div>
                            </div>
                            <div class="post in">
                                <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar2.jpg" />
                                <div class="message">
                                    <span class="arrow"></span>
                                    <a href="javascript:;" class="name">Ella Wong</a>
                                    <span class="datetime">20:40</span>
                                    <span class="body"> Please let me know if you have any comment. </span>
                                </div>
                            </div>
                            <div class="post out">
                                <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar3.jpg" />
                                <div class="message">
                                    <span class="arrow"></span>
                                    <a href="javascript:;" class="name">Bob Nilson</a>
                                    <span class="datetime">20:17</span>
                                    <span class="body"> Sure. I will check and buzz you if anything needs to be corrected. </span>
                                </div>
                            </div>
                        </div>
                        <div class="page-quick-sidebar-chat-user-form">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Type a message here...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn green">
                                        <i class="icon-paper-clip"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane page-quick-sidebar-alerts" id="quick_sidebar_tab_2">
                <div class="page-quick-sidebar-alerts-list">
                    <h3 class="list-heading">Documents</h3>
                    <ul class="feeds list-items">
                        <li>
                            <a href="javascript:;">
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-success">
                                                <i class="fa fa-bar-chart-o"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> Document name </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-success">
                                                <i class="fa fa-bar-chart-o"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> Document name </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-success">
                                                <i class="fa fa-bar-chart-o"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> Document name </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END QUICK SIDEBAR -->

<script>
    $(document).ready(function() {
        $('body').on('click', '.new-similar-product-btn', function() {
            var table = $('table').DataTable();
            var errorMessage = '',
                selected = table.rows('.selected').data(),
                selectedCount = selected.length;
            if (selectedCount) {
                if (selectedCount == 1) {
                    var productId = selected[0];
                    $.ajax({
                        url: '/catalogue/similar_product?product_id='+productId,
                        success: function(data) {
                            if (data) {
                                var product = JSON.parse(data);

                                var inputs = $('#modal_newProduct').find('.form-group input');
                                $.each(inputs, function() {
                                    var name = $(this).attr('name');
                                    if (name.indexOf('RUS') !== -1) {
                                        name = name.slice(4, -1) + '_rus';
                                    } else if (name.indexOf('_fix_') !== -1) {
                                        name = name.replace(/_fix_.+/g, '');
                                    }

                                    if (product[name] !== undefined) {
                                        var value = product[name];
                                        if (value == 'NULL')
                                            return;
                                        if ($(this).hasClass('select-editable')) {
                                            var valueOption = $(this).next('ul').find('li[value="' + value + '"]');
                                            $(this).editableSelect('select', valueOption).editableSelect('hide');
                                        } else {
                                            $(this).val(product[name]);
                                        }
                                    }
                                })
                            }
                        }
                    })

                } else {
                   errorMessage = 'Select only one of the items!'
                }
            } else {
                errorMessage = 'Select one of the items!'
            }
            if (errorMessage) {
                $('#modal_similar_error').modal('show').find('.modal-body h4').text(errorMessage);
                return false;
            }
        });
        $('#modal_newProduct').on('hide.bs.modal', function(){
            var inputs = $('#modal_newProduct').find('.form-group input');
            $.each(inputs, function() {
                if ($(this).hasClass('select-editable')) {
                    var parent = $(this).parent();
                    $(this).editableSelect('destroy');
                    parent.find('select').editableSelect().attr('placeholder', 'Did not find the desired item? - Enter new one here');
                } else {
                    $(this).val('');
                }
            })
        })

    })
</script>

<?php
require_once 'modals/new_product.php';

?>
<div class="modal fade" id="modal_similar_error" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">New Similar Product</h4>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-danger text-center">Select one of the items!</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
