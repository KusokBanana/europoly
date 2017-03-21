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
            <a href="/brand?id=<?= $this->brand["brand_id"] ?>"><?= $this->brand["name"] ?></a>
        </li>
        <li>
            <?= $this->product["name"] ?>
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
            <div id="status_0" class="alert alert-success" style="display: <?= $this->product["status"] == 0 ? "block" : "none" ?>">
                <strong>Status: Active.</strong>
                <div style="float:right" class="action btn-group ">
                    <?php
                    if ($this->access['ch']) {
                        echo <<<END
                    <button class="btn green btn-sm dropdown-toggle" data-toggle="dropdown">Change Status
                        <i class="fa fa-angle-down"></i>
                    </button>
                    
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="javascript:;" onclick="changeProductStatus(0)"> Active </a>
                        </li>
                        <li>
                            <a href="javascript:;" onclick="changeProductStatus(1)"> Limited Edition </a>
                        </li>
                        <li>
                            <a href="javascript:;" onclick="changeProductStatus(2)"> Out of Production </a>
                        </li>
                    </ul>
END;
                    }
                    if ($this->access['d']) {
                        echo '<a class="btn red btn-sm" '.
                                'href="/product/delete?product_id='.$this->product['product_id'].'"'.
                                 'data-placement="top" data-popout="true" data-singleton="true" '.
                                 'data-toggle="confirmation" data-title="Are you sure to delete this product?">'.
                                    'Delete Product'.
                             '</a>';
                    }
                    ?>
                </div>
                <br/>
                This product is in production.
            </div>


            <div id="status_1" class="alert alert-warning" style="display: <?= $this->product["status"] == 1 ? "block" : "none" ?>">
                <strong>Status: Limited Edition.</strong>
                <div style="float:right" class="action btn-group ">
                    <?php
                    if ($this->access['ch']) {
                        echo <<<END
                    <button class="btn yellow btn-sm dropdown-toggle" data-toggle="dropdown">Change Status
                        <i class="fa fa-angle-down"></i>
                    </button>
                    
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="javascript:;" onclick="changeProductStatus(0)"> Active </a>
                        </li>
                        <li>
                            <a href="javascript:;" onclick="changeProductStatus(1)"> Limited Edition </a>
                        </li>
                        <li>
                            <a href="javascript:;" onclick="changeProductStatus(2)"> Out of Production </a>
                        </li>
                    </ul>
END;
                    }
                    ?>
                </div>
                <br/>Limited ammount is left. Contact supplier before making order
            </div>

            <div id="status_2" class="alert alert-danger" style="display: <?= $this->product["status"] == 2 ? "block" : "none" ?>">
                <strong>Status: Out of Production.</strong>
                <div style="float:right" class="action btn-group ">
                    <?php
                    if ($this->access['ch']) {
                        echo <<<END
                    <button class="btn red btn-sm dropdown-toggle" data-toggle="dropdown">Change Status
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="javascript:;" onclick="changeProductStatus(0)"> Active </a>
                        </li>
                        <li>
                            <a href="javascript:;" onclick="changeProductStatus(1)"> Limited Edition </a>
                        </li>
                        <li>
                            <a href="javascript:;" onclick="changeProductStatus(2)"> Out of Production </a>
                        </li>
                    </ul>
END;
                    }
                    ?>
                </div>
                <br/>This product is not produced any more. Warehouse remainings only
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="portlet green-meadow box">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-image"></i>Images
                    </div>
                    <div class="actions">
                        <?php
                        if ($this->access['ch']) {
                            echo '<a href="javascript:;" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_uploadImage"><i class="fa fa-pencil"></i> Add </a>';
                        }
                        ?>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <?php
                        foreach ($this->photos as $photo) {
                            echo <<<END
                            <div id="photo_{$photo['photo_id']}" class="col-md-6">
                                <a href="{$photo['path']}" class="fancybox-button" data-rel="fancybox-button" style="position:relative">
                                    <img class="img-responsive" src="{$photo['path']}" alt="" style="float: left; padding:10px;">
END;
                            if ($this->access['ch']) {
                                echo "<span class='glyphicon glyphicon-remove-circle' style='position:absolute;top:15px;right:15px;font-size:20px' onclick='showDeleteModal(event, {$photo['photo_id']})'></span>";
                            }
                            echo "</a></div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="portlet blue-hoki box">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-cogs"></i>Product Details
                    </div>
                </div>
                <div class="portlet-body">
                    <?php
                    foreach ($this->columns as $name => $column) {

                        $label = $column['label'];
                        $isSelect = $column['isSelect'] ? 1 : '';
                        $formName = $column['table'];

                        if (strpos($name, '_rus') !== false) {
                            echo ' / ';
                            $column_name_rus = str_replace('_rus', '', $name);
                            $value = $this->orEmpty($this->rus[$column_name_rus]);
                            $text = $value;
                            $formName .= '.' . $column_name_rus;
                        } else {
                            echo '<div class="row static-info">';
                            echo '<div class="col-md-4 name">' . $label . ':</div>
                                    <div class="col-md-8 value">';

                            $value = $text = $this->orEmpty($this->product[$name]);
                            if ($column['type'] == 'id') {
                                // TODO remove please
                                if (isset($this->selects[$name]))
                                    foreach ($this->selects[$name] as $select) {
                                        if (intval($select['id']) == intval($value)) {
                                            $text = $select['text'];
                                            break;
                                        }
                                    }
                            }
                            $formName .= '.' . $name;
                        }

                        $formName .= '.' . $column['type'];
                        echo '<a href="javascript:;" id="editable-$name" class=\'x-editable\' data-pk="'.
                            $this->product["product_id"].'" data-name="'.$formName.'" data-value="'.$value.'"'.
                            'data-sourceName="'.$name.'" data-isSelect="'.$isSelect.'" '.
                            'data-url=\'/product/change_field\' data-original-title=\'Enter '.$label.'\'>'.
                            $text.
                            '</a>';

                        if (!isset($this->columns[$name . '_rus'])) {
                            echo '</div></div>';
                        }

                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet grey-cascade box">
            <div class="portlet-title">
                <div class="caption ">
                    <i class="icon-settings "></i>
                    <span class="caption-subject "> Balances</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs nav-tabs-lg col-md-6">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab"> Available</a>
                        </li>
                        <li>
                            <a href="#tab_2" data-toggle="tab"> Reserved </a>
                        </li>
                        <li>
                            <a href="#tab_3" data-toggle="tab"> Current Orders </a>
                        </li>
                    </ul>
                    <div class="actions pull-right " style="margin:10px">
                        <div class="btn-group ">
                            <button class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" disabled>Transfer between warehouses
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="javascript:;"><i class="fa fa-print"></i> Print </a>
                                </li>
                                <li>
                                    <a href="javascript:;"><i class="fa fa-file-pdf-o"></i> Save as PDF </a>
                                </li>
                                <li>
                                    <a href="javascript:;"><i class="fa fa-file-excel-o"></i> Export to Excel </a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group ">
                            <button class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Export
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="javascript:;"><i class="fa fa-print"></i> Print </a>
                                </li>
                                <li>
                                    <a href="javascript:;"><i class="fa fa-file-pdf-o"></i> Save as PDF </a>
                                </li>
                                <li>
                                    <a href="javascript:;"><i class="fa fa-file-excel-o"></i> Export to Excel </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <br/>
                            <br/>
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th> Warehouse Type</th>
                                    <th> Quantity</th>
                                    <th> Total Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($this->balances as $balance) {
                                    echo <<<END
                                <tr>
                                    <td>
                                        <a href="/warehouse?id=${balance['warehouse_id']}">${balance['name']}</a>
                                    </td>
                                    <td>${balance['amount']}</td>
                                    <td>${balance['total_price']}</td>
                                </tr>
END;
                                }
                                ?>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-8">
                                </div>
                                <div class="col-md-4">
                                    <div class="well">
                                        <div class="row static-info align-reverse">
                                            <div class="col-md-7 name"> Total Quantity:</div>
                                            <div class="col-md-5 value"><?= $this->all_warehouces_balance['amount'] ?></div>
                                        </div>
                                        <div class="row static-info align-reverse">
                                            <div class="col-md-7 name"> Total Price:</div>
                                            <div class="col-md-5 value"><?= $this->all_warehouces_balance['total_price'] ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_2">

                        </div>
                        <div class="tab-pane" id="tab_3">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
    <!-- END PAGE BASE CONTENT -->
</div>

<?php
require_once "modals/upload_image.php"
?>

<div class="modal fade" id="modal_areYouSure" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Delete image</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="removeImage()">Delete image</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    var status = <?= $this->product["status"] ?>;
    var photoIdToDelete = null;

    function changeProductStatus(newStatus) {
        if (status != newStatus) {
            $.ajax({
                url: "/product/change_status",
                type: "post",
                data: {
                    "product_id": <?= $this->product["product_id"] ?>,
                    "new_status": newStatus
                },
                success: function () {
                    $("#status_" + status).hide();
                    $("#status_" + newStatus).show();
                    status = newStatus;
                }
            })
        }
    }

    function showDeleteModal(event, photo_id) {
        event.stopPropagation();
        event.preventDefault();
        photoIdToDelete = photo_id;
        $("#modal_areYouSure").modal('show');
        return false;
    }

    function removeImage() {
        $.ajax({
            url: "/product/delete_photo",
            type: "post",
            data: {
                "photo_id": photoIdToDelete
            },
            success: function () {
                $("#photo_" + photoIdToDelete).remove();
                $("#modal_areYouSure").modal('hide');
            }
        });
    }

    $(document).ready(function () {
        $(".fancybox-button").fancybox();
    });

    <?php
    if (!$this->access['ch']) {
        echo "$('.x-editable').editable('toggleDisabled');";
    }
    ?>

</script>
<script type="text/javascript">
    $(document).ready(function() {
        var $selects = <?= json_encode($this->selects) ?>;

        var editableSelects = $('.x-editable');
        $.each(editableSelects, function() {
            var sourceName = $(this).attr('data-sourceName');
            var source = ($selects[sourceName] !== undefined) ? $selects[sourceName] : false;
            var isSelect = $(this).attr('data-isSelect');
            if (source && source.length && isSelect) {
                $(this).editable({
                    type: "select",
                    inputclass: 'form-control input-medium',
                    source: source
                });
                $(this).on('shown', function(e, editable) {
                    var parent = editable.input.$input.parent();
                    var hidden = editable.input.$input;
                    var clone = editable.input.$input.clone();
                    editable.input.$input.after(clone);
                    editable.input.$input.hide();
                    var link;
                    clone.editableSelect();
                    var editableSelect = parent.find('.es-input');
                    var selectValue;
                    editableSelect.on('select.editable-select', function (e) {
                        selectValue = $(e.target).val();
                        link = $(e.target).closest('.editable-popup').prev();
                        link.attr('data-value', selectValue);
                        hidden.val(selectValue);
                    });
                    editableSelect.closest('form').on('submit', function() {
                        window.location.href = '';
                    })
                });
            } else {
                $(this).editable({
                    type: 'text',
                    inputclass: 'form-control input-medium'
                });
            }
        });
    });
</script>