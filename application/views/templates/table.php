<div class="table-toolbar">
    <?php
    $buttons = $table_data['buttons'];
    $table_id = $table_data['table_id'];
    $ajax = $table_data['ajax'];
    $column_names = $table_data['column_names'];
    $hidden_by_default = $table_data['hidden_by_default'];
    $click_url = $table_data['click_url'];
    $mustHidden = 0;
    $selectSearchColumns = isset($table_data['selectSearchColumns']) ?
        (empty($table_data['selectSearchColumns']) ? $table_data['selectSearchColumns'] : []) : false;
    ?>
    <div id="<?= $table_id ?>_left_buttons" class="btn-group">
        <?php
        foreach ($buttons as $button) {
            echo $button;
        }
        ?>
    </div>
    <div id="<?= $table_id ?>_right_buttons" class="btn-group pull-right">
        <button class="btn green  btn-outline dropdown-toggle" data-toggle="dropdown">Export <i class="fa fa-angle-down"></i></button>
        <ul class="dropdown-menu pull-right">
            <li><a href="javascript:;"><i class="fa fa-print"></i> Print </a></li>
            <li><a href="javascript:;"><i class="fa fa-file-pdf-o"></i> PDF </a></li>
            <li><a href="javascript:;"><i class="fa fa-file-excel-o"></i> Excel </a></li>
        </ul>
    </div>

    <div class="btn-group pull-right">
        <button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown">Columns <i class="fa fa-angle-down"></i></button>
        <div id="<?= $table_id ?>_columns_choose" class="dropdown-menu hold-on-click dropdown-checkboxes" style="left: 0" role="menu">
            <?php
            echo '<label style="display: none;"><input type="checkbox" data-column="0">Id</label>';
            foreach ($column_names as $column_id => $column_name) {
                if ($column_name == '_category_id')
                    $mustHidden = $column_id;
                if ($column_name[0] == '_') continue;
                echo '<label><input type="checkbox" data-column="' . $column_id . '" checked>' . $column_name . '</label>';
            }
            ?>
        </div>
    </div>
</div>
<table id="<?= $table_id ?>" class="table table-striped table-bordered table-hover table-checkable order-column">
    <thead>
    <tr>
        <?php
        foreach ($column_names as $column_id => $column_name) {
            if ($column_id == 0) {
                echo '<th></th>';
            } else {
                echo '<th>' . $column_name .
                        '<br><input type="text" class="form-control" style="width: 100%" 
                            onclick="$(this).focus(); event.stopPropagation()" />
                    </th>';
            }
        }
        ?>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="<?= count($column_names) ?>" class="dataTables_empty">Loading data from server...</td>
    </tr>
    </tbody>
</table>

<style>
    .hide-text {
        text-indent: 100%;
        white-space: nowrap;
        overflow: hidden;
    }
    table .es-list {
        width: auto !important;
    }
</style>

<script>
    var tableFilter;
    $(document).ready(function () {
        var $table = $('#<?= $table_id ?>');
        var hiddenByDefault = <?= $hidden_by_default ?>;
        var cookieHiddenCols = getHiddenColumns('<?= $table_id ?>');
        var mustHidden = <?= $mustHidden ?>;
        hiddenByDefault = cookieHiddenCols ? cookieHiddenCols : hiddenByDefault;
        if (mustHidden)
            hiddenByDefault.push(mustHidden);
        <?php
        if (isset($ajax['data']) && $ajax['data'] != "") {
            echo "var ajax = { url: '" . $ajax['url'] . "', 
                                data: { products:" . json_encode($ajax['data']) . "}, 
                                type: 'GET' 
                              };";
        } else {
            echo "var ajax = '" . $ajax['url'] . "';";
        }
        echo 'var $selectSearchColumns = false;';
        if ($selectSearchColumns) {
            echo '$selectSearchColumns = ' . json_encode($selectSearchColumns) . ';';
        }
        ?>
        // DataTable
        var table = $table.DataTable({
            processing: true,
            serverSide: true,
            ajax: ajax,
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    orderable: false,
                    className: 'dt-body-center select-checkbox',
                    render: function (data, type, full, meta) {
                        return '';
                    }
                },
                {
                    "targets": hiddenByDefault,
                    "visible": false,
                    "searchable": true
                }
            ],
            order: [
                [1, 'asc']
            ],
            select: {
                style: 'os',
                selector: 'td:first-child',
                blurable: true
            },
            colReorder: true
        });

        $table.find('tbody').on('click', 'tr td:not(:first-child)', function (e) {
            var data = table.row($(this).closest('tr')).data();
            var target = e.target;
            if ($(target).hasClass('editable-click') || $(target).closest('.editable-container').length)
                return;
            window.location.href = "<?= $click_url ?>" + data[0];
        });

        table.columns().every(function () {
            var that = this;
            $(this.header()).on('keyup change', 'input', {column: that}, keyUpChangeHandler);
        });

        function keyUpChangeHandler(event) {
            if (event.data.column.search() !== this.value) {
                event.data.column.search(this.value).draw();
            }
        }

        var $inputs = $("#<?= $table_id ?>_columns_choose input");
        hiddenByDefault.forEach(function (item) {
            $($inputs[item]).removeAttr('checked');
        });
        $inputs.each(function () {
            $(this).on('change', function () {
                var column = table.column($(this).attr('data-column'));
                column.visible(!column.visible());
            });
        });

        // Save hidden columns in cookies
        var tableId = $table.attr('id');
        var columnChoose = $('#'+tableId+'_columns_choose');
        var tableCheckboxes = columnChoose.find('label').not(':first-child');
        tableCheckboxes.on('change', 'input', function() {
            var columnsId = [];
            var columns = tableCheckboxes.find('input');
            $.each(columns, function() {
                if (!$(this).is(':checked')) {
                    columnsId.push(+$(this).attr('data-column'));
                }
            });
            var cols = {
                'tableId': tableId,
                'ids': columnsId,
                'action': 'change'
            };
            cols = JSON.stringify(cols);
            $.ajax({
                url: '/clients/hidden_columns',
                type: "POST",
                data: {
                    'columnsHidden': cols
                }
            })
        });

        // Get hidden columns for current table from cookies
        function getHiddenColumns(tableId) {
            var cols = {
                'tableId': tableId,
                'action': 'get'
            },
                returnValue = false;
            cols = JSON.stringify(cols);
            var ajax = $.ajax({
                url: '/clients/hidden_columns',
                type: "POST",
                async: false,
                data: {
                    'columnsHidden': cols
                },
                success: function(data) {
                    if (data) {
                        returnValue = JSON.parse(data);
                        if (returnValue.length) {
                            var checkedCols = [];
                            $.each(returnValue, function() {
                                var checked = $('#'+tableId+'_columns_choose').find('label input[data-column="'+this+'"]');
                                if (checked.length) {
                                    var index = checked.closest('label').index();
                                    checkedCols.push(index);
                                }
                            });
                            returnValue = checkedCols;
                        }
                    }
                    else {
                        returnValue = false;
                    }
                }
            });
            return returnValue;
        }
        //    If category tabs exist on page
        var categoryTabsBlock = $('.category-tabs');
        if (categoryTabsBlock.length) {

            categoryTabsBlock.on('click', 'a[data-toggle="tab"]', function() {
                var categoryId = $(this).attr('data-category-id');
                if (categoryId == '0') {
                    location.reload();
                }
                table.columns(33).search(categoryId).draw()
            })
        }

        // add filter selects for all not numeric columns
        var data = {};
        var url = ajax;
        if (ajax instanceof Object && ajax['data'] !== "undefined") {
            data = ajax['data'];
            url = ajax['url'];
        }
        $.ajax({
            url: url,
            type: "GET",
            data: data,
            success: function (data) {
                if (data) {
                    var ajaxReq = JSON.parse(data);
                    var dataAr = ajaxReq['data'];
                    if (!dataAr.length)
                        return;
                    var cols = [];
                    var numberCols = [];
                    $.each(dataAr, function (rowId, row) {
                        $.each(row, function (colId, value) {
                            if (!colId)
                                return;

                            if (!$selectSearchColumns) {
                                if ($selectSearchColumns.length) {
                                    if ($selectSearchColumns.indexOf(colId) !== -1)
                                        return;
                                }
                            }

                            if (numberCols.length) {
                                if (numberCols.indexOf(colId) !== -1)
                                    return;
                            }

                            if (!value)
                                return;

                            //delete spaces
                            value = value.replace(/\s+/g, ' ');

                            var tagExp = /<.+\s*>.*?<\/.+>/gi;
                            var isTag = (value.search(tagExp) !== -1);
                            if (isTag) {
                                if (value[0] == '<') {
                                    value = $(value).text();
                                }
                                else {
                                    value = value.replace(tagExp, '');
                                }
                            }

                            var isNumber = !isNaN(parseFloat(value)) && isFinite(value);
                            if (isNumber) {
                                numberCols.push(colId);
                                if (cols[colId] !== undefined)
                                    delete cols[colId];
                                return;
                            }

                            if (cols[colId] === undefined) {
                                cols[colId] = [value];
                                return;
                            }
                            if (cols[colId].indexOf(value) === -1 && cols[colId].length < 50)
                                cols[colId].push(value);
                        });
                    });
                    if (cols.length) {
                        $.each(cols, function (colId, valuesArray) {
                            if (valuesArray !== undefined && valuesArray.length) {
                                var input = $(table.column(colId).header()).find('input');
                                var select = $(document.createElement('select'));
                                select.attr('class', input.attr('class'));
                                select.attr('style', input.attr('style'));
                                select.attr('onclick', input.attr('onclick'));
                                select.attr('data-col-id', colId);
                                var options = '';
                                $.each(valuesArray, function (id, val) {
                                    options += '<option value="' + val + '">' + val + '</option>';
                                });
                                if (options) {
                                    input.parent().append(select);
                                    input.remove();
                                    select.append(options);
                                    select.editableSelect();
                                    select.on('select.editable-select', function (e, li) {
                                        var value = li.text();
                                        if (table.column($(this).attr('data-col-id')).search() !== value) {
                                            table.column($(this).attr('data-col-id')).search(value).draw();
                                        }
                                    })
                                }
                            }
                        })
                    }
                }
            }
        })
    });

</script>
