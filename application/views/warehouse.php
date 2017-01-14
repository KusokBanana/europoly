<div class="container-fluid">
    <div class="page-content page-content-popup">
        <div class="page-content-fixed-header">
            <!-- BEGIN BREADCRUMBS -->
            <ul class="page-breadcrumb">
                <li>
                    <a href="/">Dashboard</a>
                </li>
                <li>Warehouse</li>
            </ul>
            <!-- END BREADCRUMBS -->

            <div class="content-header-menu">
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
            <div class="row">
                <!--end col-md-8-->
                <div class="col-md-4">
                    <div class="portlet sale-summary">
                        <div class="portlet-title">
                            <div class="caption font-red sbold"> Available Remainings</div>
                        </div>
                        <div class="portlet-body">
                            <ul class="list-unstyled">
                                <li>
                                    <span class="sale-info"> Buy Price
                                        <i class="fa fa-img-up"></i>
                                    </span>
                                    <span class="sale-num"> <?= $this->prices['buy'] ?> €</span>
                                </li>
                                <li>
                                    <span class="sale-info"> Buy Price + Transport + Taxes
                                        <i class="fa fa-img-up"></i>
                                    </span>
                                    <span class="sale-num"> <?= $this->prices['buyAndExpenses'] ?> €</span>
                                </li>
                                <li>
                                    <span class="sale-info"> Sell Price
                                        <i class="fa fa-img-down"></i>
                                    </span>
                                    <span class="sale-num"> 0 €</span>
                                </li>
                                <li>
                                    <span class="sale-info"> Dealer Price
                                        <i class="fa fa-img-down"></i>
                                    </span>
                                    <span class="sale-num"> <?= $this->prices['dealer'] ?> €</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="portlet sale-summary">
                        <div class="portlet-title">
                            <div class="caption font-red sbold"> Reserved Remainings</div>
                        </div>
                        <div class="portlet-body">
                            <ul class="list-unstyled">
                                <li>
                                    <span class="sale-info"> Buy Price
                                        <i class="fa fa-img-up"></i>
                                    </span>
                                    <span class="sale-num"> 0 €</span>
                                </li>
                                <li>
                                    <span class="sale-info"> Buy Price + Transport + Taxes
                                        <i class="fa fa-img-up"></i>
                                    </span>
                                    <span class="sale-num"> 0 €</span>
                                </li>
                                <li>
                                    <span class="sale-info"> Total Prepayment
                                        <i class="fa fa-img-down"></i>
                                    </span>
                                    <span class="sale-num"> 0 €</span>
                                </li>
                                <li>
                                    <span class="sale-info"> Total Price
                                        <i class="fa fa-img-down"></i>
                                    </span>
                                    <span class="sale-num"> 0 €</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end col-md-4-->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-settings font-dark"></i>
                                <span class="caption-subject bold uppercase"> Warehouse: <?= $this->title ?> </span>
                            </div>
                        </div>
                        <div class="tabbable-line tabbable-custom-profile">
                            <ul class="nav nav-tabs" style="padding:10px">
                                <li class="active">
                                    <a href="#tab_1_11" data-toggle="tab"> Available </a>
                                </li>
                                <li>
                                    <a href="#tab_1_22" data-toggle="tab"> Reserved </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1_11">
                                    <div class="portlet-body">
                                        <?php
                                        if ($this->title != "All") {
                                            $table_data = [
                                                'buttons' => [
                                                    '<button class="btn sbold green" data-toggle="modal" data-target="#modal_newProductWarehouse">Add New <i class="fa fa-plus"></i></button>',
                                                    '<button id="sample_1_transfer_button" class="btn green  btn-outline " data-toggle="modal" data-target="#modal_transfer" disabled >Transfer between Warehouses </button>'
                                                ],
                                                'table_id' => "table_warehouses_products",
                                                'ajax' => [
                                                    'url' => "/warehouse/dt",
                                                    'data' => "{'warehouse_id': " . $this->warehouse["warehouse_id"] . "}"
                                                ],
                                                'column_names' => [
                                                    "Id",
                                                    "Article",
                                                    "Product",
                                                    "Brand",
                                                    "Quantity",
                                                    "Units",
                                                    "Buy Price",
                                                    "Buy + Transport + Taxes",
                                                    "Sell Price",
                                                    "Dealer Price (-30%)",
                                                    "Total Price"
                                                ],
                                                'hidden_by_default' => "[]",
                                                'click_url' => "javascript:;"
                                            ];
                                        } else {
                                            $table_data = [
                                                'buttons' => [],
                                                'table_id' => "table_warehouses_products",
                                                'ajax' => [
                                                    'url' => "/warehouse/dt",
                                                    'data' => "{'warehouse_id': 0}"
                                                ],
                                                'column_names' => [
                                                    "Id",
                                                    "Article",
                                                    "Product",
                                                    "Brand",
                                                    "Warehouse",
                                                    "Quantity",
                                                    "Units",
                                                    "Buy Price",
                                                    "Buy + Transport + Taxes",
                                                    "Sell Price",
                                                    "Dealer Price (-30%)",
                                                    "Total Price"
                                                ],
                                                'hidden_by_default' => "[]",
                                                'click_url' => "javascript:;"
                                            ];
                                        }
                                        include 'application/views/templates/table.php'
                                        ?>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_1_22">
                                    <div class="portlet-body">
                                        <div class="table-toolbar">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="btn-group">
                                                        <button id="sample_editable_1_new" class="btn sbold green"> Add New
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="btn-group pull-right">
                                                        <button class="btn green  btn-outline ">Transfer between Warehouses
                                                        </button>
                                                        <button class="btn green  btn-outline dropdown-toggle" data-toggle="dropdown">Tools
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
                                            </div>
                                        </div>
                                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_2">
                                            <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes"/>
                                                </th>
                                                <th> Article</th>
                                                <th> Manager</th>
                                                <th> Order</th>
                                                <th> Supplier</th>
                                                <th> Product</th>
                                                <th> Units</th>
                                                <th> Ammount</th>
                                                <th> Price</th>
                                                <th> Total Price</th>
                                                <th> Reservation Date</th>
                                                <th> Reserved till</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="odd gradeX">
                                                <td>
                                                    <input type="checkbox" class="checkboxes" value="1"/></td>
                                                <td> 3226483629</td>
                                                <td>
                                                    <a href="javascript:;"> Sergey </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;"> 0572/04 </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;"> Admonter </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;">Relief-Brushed</a>
                                                </td>
                                                <td class="center"> m<sup>2</sup></td>
                                                <td> 250</td>
                                                <td> 50</td>
                                                <td> 12500</td>
                                                <td> 12/30/2015</td>
                                                <td> 02/05/2016</td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>
                                                    <input type="checkbox" class="checkboxes" value="1"/></td>
                                                <td> 3226483629</td>
                                                <td>
                                                    <a href="javascript:;"> Sergey </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;"> 0572/04 </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;"> Admonter </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;">Relief-Brushed</a>
                                                </td>
                                                <td class="center"> m<sup>2</sup></td>
                                                <td> 250</td>
                                                <td> 50</td>
                                                <td> 12500</td>
                                                <td> 12/30/2015</td>
                                                <td> 02/05/2016</td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>
                                                    <input type="checkbox" class="checkboxes" value="1"/></td>
                                                <td> 3226483629</td>
                                                <td>
                                                    <a href="javascript:;"> Sergey </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;"> 0572/04 </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;"> Admonter </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;">Relief-Brushed</a>
                                                </td>
                                                <td class="center"> m<sup>2</sup></td>
                                                <td> 250</td>
                                                <td> 50</td>
                                                <td> 12500</td>
                                                <td> 12/30/2015</td>
                                                <td> 02/05/2016</td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>
                                                    <input type="checkbox" class="checkboxes" value="1"/></td>
                                                <td> 3226483629</td>
                                                <td>
                                                    <a href="javascript:;"> Sergey </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;"> 0572/04 </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;"> Admonter </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;">Relief-Brushed</a>
                                                </td>
                                                <td class="center"> m<sup>2</sup></td>
                                                <td> 250</td>
                                                <td> 50</td>
                                                <td> 12500</td>
                                                <td> 12/30/2015</td>
                                                <td> 02/05/2016</td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>
                                                    <input type="checkbox" class="checkboxes" value="1"/></td>
                                                <td> 3226483629</td>
                                                <td>
                                                    <a href="javascript:;"> Sergey </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;"> 0572/04 </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;"> Admonter </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;">Relief-Brushed</a>
                                                </td>
                                                <td class="center"> m<sup>2</sup></td>
                                                <td> 250</td>
                                                <td> 50</td>
                                                <td> 12500</td>
                                                <td> 12/30/2015</td>
                                                <td> 02/05/2016</td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>
                                                    <input type="checkbox" class="checkboxes" value="1"/></td>
                                                <td> 3226483629</td>
                                                <td>
                                                    <a href="javascript:;"> Sergey </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;"> 0572/04 </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;"> Admonter </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:;">Relief-Brushed</a>
                                                </td>
                                                <td class="center"> m<sup>2</sup></td>
                                                <td> 250</td>
                                                <td> 50</td>
                                                <td> 12500</td>
                                                <td> 12/30/2015</td>
                                                <td> 02/05/2016</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PAGE BASE CONTENT -->
        </div>
        <!-- BEGIN FOOTER -->
        <p class="copyright-v2">2016 © Europoly.
        </p>
        <a href="#index" class="go2top">
            <i class="icon-arrow-up"></i>
        </a>
        <!-- END FOOTER -->
    </div>
</div>

<?php
if ($this->id != 0) {
    require_once 'modals/new_product_warehouse.php';
    require_once 'modals/transfer.php';
}
?>

<script>
    var $table_warehouses_products = $('#table_warehouses_products');
    $table_warehouses_products.find('tbody').on('click', 'tr', function () {
        console.log("click!");
        if ($table_warehouses_products.find(".selected").length == 1) {
            var pw_data = $table_warehouse_product.DataTable().rows('.selected').data()[0];
            $("#modal_transfer_product_warehouse_id").val(pw_data[0]);
            $("#modal_transfer_product_name").val($(pw_data[2]).text());
            $("#modal_transfer_amount").attr('max', pw_data[4]);
            $("#sample_1_transfer_button").removeAttr('disabled');
        } else {
            $("#sample_1_transfer_button").attr('disabled', 'disabled');
        }
    });
</script>
