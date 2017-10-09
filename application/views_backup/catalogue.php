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
                    <ul class="nav nav-tabs filter-tabs">
                        <?php if (!empty($this->tabs)):
                            $href = false;
                            foreach ($this->tabs as $key => $tab):
                                $tab_id = 'tab_' . $key ;
                                if (!$href) $href = $tab_id;
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
                                                    <a class="tab-filter"
                                                       data-filter-name="category_id"
                                                       data-filter-value="<?= $id ?>"
                                                       tabindex="-1"
                                                       href="#<?= $tab_id ?>"
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
                                        <a class="tab-filter"
                                           data-filter-name="category_id"
                                           href="#<?= $tab_id ?>"
                                           data-filter-value="<?= $id ?>"
                                           <?= $id === 0 ? 'aria-expanded="true"' : '' ?>
                                           data-toggle="tab"> <?= $tab['name'] ?> </a>
                                    </li>
                                    <?php endif;
                            endforeach; ?>
                        <?php endif; ?>
                    </ul>
                    <div class="tab-content" style="padding: 10px;">
                        <div class="tab-pane fade active in" id="<?= $href ?>">
                            <?php
                            $buttons = [
                                '<button class="btn sbold green" data-toggle="modal" 
data-target="#modal_newProduct">Add New Product <i class="fa fa-plus"></i></button>',
                                '<button class="btn sbold blue new-similar-product-btn" data-toggle="modal" 
data-target="#modal_newProduct">Add Similar Product <i class="fa fa-plus"></i></button>'
                            ];
                            if (!$this->access['ch'])
                                $buttons = [];

                            $commonData = [
                                'click_url' => "/product?id=",
                                'method' => "POST",
                                'buttons' => $buttons,
                                'ajax' => [
                                    'url' => "/catalogue/dt"
                                ],
                                'serverSide' => false
                            ];
                            $table_data = array_merge($this->productsTable, $commonData);
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

                                var inputs = $('#modal_newProduct').find('.form-group input, .form-group select');
                                $.each(inputs, function() {
                                    var name = $(this).attr('name');
                                    switch (this.tagName) {
                                        case 'INPUT':
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
                                            break;
                                        case 'SELECT':
                                            if (product[name] !== undefined) {
                                                $(this).val(parseInt(product[name]))
                                            }
                                            break;
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
</div>
