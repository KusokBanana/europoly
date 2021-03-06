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
<div class="page-fixed-main-content" <?= $this->isSidebarClosed() ? 'style="margin-left:0"' : '' ?>>
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
                    <ul class="nav nav-tabs filter-tabs">
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
                                                    <a class="tab-filter"
                                                       data-filter-name="category_id"
                                                       data-filter-value="<?= $id ?>"
                                                       tabindex="-1"
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
                                           data-filter-value="<?= $id ?>"
                                            <?= $id === 0 ? 'aria-expanded="true"' : '' ?>
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
                            $buttons = ['<button class="btn sbold green" data-toggle="modal" data-target="#modal_newProduct">
                                            Add New Product <i class="fa fa-plus"></i>
                                        </button>'];
                            if (!$this->access['ch'])
                                $buttons = [];
                            $commonData = [
                                'click_url' => "/product?id=",
                                'method' => "POST",
                                'buttons' => $buttons,
                                'ajax' => [
                                    'url' => "/brand/dt",
                                    'data' => [
                                        'brand_id' => $this->brand["brand_id"]
                                    ]
                                ]
                            ];
                            $table_data = array_merge($this->brandsTable, $commonData);
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

<?php
if ($this->access):
    require_once 'modals/new_product.php';
endif;
?>
