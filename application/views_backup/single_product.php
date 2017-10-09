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
            <div style="float:right" class="action btn-group ">
                <?php
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
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
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
                    <div class="clearfix">
                        <?php
                        foreach ($this->photos as $photo) {
                            echo <<<END
                            <div id="photo_{$photo['photo_id']}">
                                <a href="{$photo['path']}" class="fancybox-button" data-rel="fancybox-button" style="position:relative">
                                    <img class="img-responsive" src="{$photo['path']}" alt="" style="float: left; padding:10px; max-height: 300px">
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
        <div class="col-md-12">
            <div class="portlet blue-hoki box">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-cogs"></i>Product Details
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div>
                        <?php
                        foreach ($this->columns as $name => $column) {

                            $label = $column['label'];
                            $isSelect = $column['isSelect'] ? 1 : '';
                            $formName = $column['table'];
                            $blockHeader = isset($column['blockHeader']) && $column['blockHeader']
                                ? $column['blockHeader'] : false;

                            if ($blockHeader)
                                echo '</div><div class="col-md-3"><h2>'.$blockHeader.'</h2><br>';

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
                            echo '<a href="javascript:;" id="editable-'.$name.'" class=\'x-editable\' data-pk="'.
                                $this->product["product_id"].'" data-name="'.$formName.'" data-value="'.$value.'"'.
                                ' data-sourceName="'.$name.'" data-isSelect="'.$isSelect.'" '.
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
        </div>
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
            var url = $(this).attr('data-url');
            var pk = $(this).attr('data-pk');
            var name = $(this).attr('data-name');
            if (source && source.length && isSelect) {
                $(this).editable({
                    type: "select",
                    url: url,
                    inputclass: 'form-control input-medium',
                    source: source
                });
                $(this).on('shown', function(e, editable) {
                    var parent = editable.input.$input.parent();
//                    parent.closest('form').attr('action', url);
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
                        var value = $(this).find('input').val();
                        if (!value) {
                            return false;
                        }
                        $.ajax({
                            url: url,
                            data: {
                                name: name,
                                value: value,
                                pk: pk
                            },
                            type: 'POST',
                            async: false
                        });
                        window.location.href = '';
                        return false;
                    })
                });
            } else {
                $(this).editable({
                    type: 'text',
                    inputclass: 'form-control input-medium'
                });
//                $(this).on('shown', function(e, editable) {
//                    var input = editable.input.$input;
//                    var form = input.closest('form');
//                    form.on('submit', function() {
//                        var value = $(this).find('input').val();
//                        if (!value) {
//                            return false;
//                        }
//                        $.ajax({
//                            url: url,
//                            data: {
//                                name: name,
//                                value: value,
//                                pk: pk
//                            },
//                            type: 'POST',
//                            async: true,
//                            success: function() {
//                                window.location.href = '';
//                            }
//                        });
////                        window.location.href = '';
//                        return false;
//                    })
//                })
            }
        });
    });
</script>