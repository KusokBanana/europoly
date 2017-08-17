<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li>
            Brands
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
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-briefcase font-dark"></i>
                        <span class="caption-subject bold uppercase"> Brands</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php
                    $buttons = ['<button class="btn sbold green" data-toggle="modal" data-target="#modal_newBrand"> Add New Brand <i class="fa fa-plus"></i></button>'];
                    if (!$this->access)
                        $buttons = [];

                    $commonData = [
                        'click_url' => "/brand?id=",
                        'buttons' => $buttons,
                        'method' => 'POST',
                        'ajax' => [
                            'url' => "/brands/dt"
                        ]
                    ];
                    $table_data = array_merge($this->brandsTable, $commonData);
                    
                    include 'application/views/templates/table.php'
                    ?>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>

    </div>

    <!-- END PAGE BASE CONTENT -->
</div>

<?php
require_once 'modals/new_brand.php';
?>

<script>
    $(document).ready(function() {

        var $table = $('.portlet-body').find('table').DataTable();
        $table.on('draw.dt', function () {
            $('.table-confirm-btn').confirmation({
                rootSelector: '.table-confirm-btn'
            });
        })
    })
</script>
