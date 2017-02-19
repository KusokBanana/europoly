<div class="container-fluid">
    <div class="page-content page-content-popup">
        <div class="page-content-fixed-header">
            <!-- BEGIN BREADCRUMBS -->
            <ul class="page-breadcrumb">
                <li>
                    <a href="/">Dashboard</a>
                </li>
                <li>
                    <a href="/catalogue">Catalogue</a>
                </li>
                <li>
                    <?= $this->brand["name"] ?>
                </li>
            </ul>
            <!-- END BREADCRUMBS -->
        </div>
        <div class="page-sidebar-wrapper">
            <!-- BEGIN SIDEBAR -->
            <?php include 'application/views/templates/sidebar.php' ?>
            <!-- END SIDEBAR -->
        </div>
        <div class="page-fixed-main-content">
            <!-- BEGIN PAGE BASE CONTENT -->
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-settings font-dark"></i>
                                <span class="caption-subject bold uppercase"> Catalogue: <?= $this->brand["name"] ?> </span>
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
                                <div class="tab-pane fade active in"
                                     id="">
                                    <?php
                                    $button = '<button class="btn sbold green" data-toggle="modal" data-target="#modal_newProduct">
                                                    Add New Product <i class="fa fa-plus"></i>
                                                </button>';
                                    if (!$this->access)
                                        $button = '';
                                    $table_data = [
                                        'buttons' => [$button],
                                        'table_id' => "table_products",
                                        'ajax' => [
                                            'url' => "/brand/dt",
                                            'data' => [
                                                'brand_id' => $this->brand["brand_id"]
                                            ]
                                        ],
                                        'column_names' => $this->full_product_column_names,
                                        'hidden_by_default' => $this->full_product_hidden_columns,
                                        'click_url' => "/product?id=",
                                        'selectSearch' => $this->selects,
                                        'filterSearchValues' => $this->rows
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

<?php
if ($this->access):
    require_once 'modals/new_product.php';
endif;
?>
